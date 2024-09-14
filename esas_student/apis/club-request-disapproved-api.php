<?php
require_once '../../config.php';

// Start the session to access session variables
session_start();

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Check if the student_id is set in the session
        if (!isset($_SESSION['student_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized: No student_id found in session']);
            exit();
        }

        // Get the student_id from the session
        $student_id = $_SESSION['student_id'];

        // Prepare and execute the query to fetch disapproved requests for the specific student
        $stmt = $pdo->prepare('
            SELECT r.request_id, r.clubName, r.description, r.activities, r.status, r.coverPhoto, r.dateModified,
                   s.firstName, s.lastName
            FROM tbl_club_requests r
            LEFT JOIN tbl_students s ON r.student_id = s.student_id
            WHERE r.student_id = :student_id AND r.status = "disapproved"
            ORDER BY r.dateModified DESC
        ');
        $stmt->execute(['student_id' => $student_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            // Format the dateModified field
            foreach ($result as &$club) {
                $club['dateModified'] = (new DateTime($club['dateModified']))->format('F j, Y'); // Format the date
            }

            echo json_encode($result);
        } else {
            // Return an empty array with a 200 status code
            echo json_encode([]);
        }
        break;
        
    default:
        // Invalid method
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
