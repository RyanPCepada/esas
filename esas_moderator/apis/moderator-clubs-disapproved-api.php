<?php
require_once '../../config.php';
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Ensure the user is logged in and has a valid session
        if (isset($_SESSION['student_id'])) {
            $student_id = $_SESSION['student_id'];

            // Fetch all disapproved applications and their associated clubs
$stmt = $pdo->prepare('
SELECT r.application_id, r.club_id, c.clubName, c.information, c.coverPhoto, r.dateModified
FROM tbl_application r
JOIN tbl_clubs c ON r.club_id = c.club_id
WHERE r.student_id = ? AND r.status = "disapproved"
');
$stmt->execute([$student_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize an array to hold the full details including moderators
$results = [];

// Fetch and add moderators for each disapproved application
foreach ($applications as $application) {
$clubId = $application['club_id'];

// Format the dateModified field
$application['dateModified'] = (new DateTime($application['dateModified']))->format('F j, Y'); // Format the date

// Fetch moderators for the current club
$stmt_moderators = $pdo->prepare('
    SELECT m.firstName, m.profilePic
    FROM tbl_moderators m
    WHERE m.club_id = ?
');
$stmt_moderators->execute([$clubId]);
$moderators = $stmt_moderators->fetchAll(PDO::FETCH_ASSOC);

// Format the moderators' information
$formattedModerators = [];
foreach ($moderators as $moderator) {
    $formattedModerators[] = [
        'name' => $moderator['firstName'],
        'pic' => $moderator['profilePic'] ?: 'default.png'
    ];
}

// Add the current application's details and moderators to the results
$application['moderators'] = $formattedModerators;
$results[] = $application;
}

echo json_encode($results);

        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized. Please log in.']);
        }
        break;

    default:
        // Invalid method
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
