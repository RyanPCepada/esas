<?php
require_once '../../config.php';
session_start();

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Ensure the user is logged in and has a valid session
        if (isset($_SESSION['student_id'])) {
            $student_id = $_SESSION['student_id'];

            // Query to fetch the clubs associated with the student
            $stmt = $pdo->prepare('
                SELECT c.club_id, c.clubName, c.information, c.coverPhoto, r.dateApplied, r.dateModified,
                       GROUP_CONCAT(m.firstName ORDER BY m.firstName SEPARATOR ", ") AS moderators,
                       GROUP_CONCAT(m.profilePic ORDER BY m.firstName SEPARATOR ", ") AS profilePics
                FROM tbl_registration r
                JOIN tbl_clubs c ON r.club_id = c.club_id
                LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
                WHERE r.student_id = ? AND r.status = "active"
                GROUP BY c.club_id
            ');
            $stmt->execute([$student_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch member counts and format moderators for each club
            foreach ($result as &$club) {
                // Replace dateApplied with dateModified
                $club['dateModified'] = (new DateTime($club['dateModified']))->format('F j, Y'); // Format the date
                $stmt_count = $pdo->prepare('SELECT COUNT(*) as member_count FROM tbl_registration WHERE club_id = ?');
                $stmt_count->execute([$club['club_id']]);
                $club['membersCount'] = $stmt_count->fetch(PDO::FETCH_ASSOC)['member_count'];

                // Format the moderators
                $moderatorNames = explode(", ", $club['moderators']);
                $moderatorPics = explode(", ", $club['profilePics']);
                $club['formattedModerators'] = formatModerators($moderatorNames, $moderatorPics);
            }

            echo json_encode($result);
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

// Function to format moderators' names and profile pictures
function formatModerators($names, $pics) {
    $formatted = '';
    foreach ($names as $index => $name) {
        $pic = $pics[$index] ?? 'default.png'; // Use default image if profilePic is missing
        $formatted .= "<img src='/esas/esas_admin/images/$pic' alt='Moderator' class='moderator-pic'> $name<br>";
    }
    return $formatted;
}
?>
