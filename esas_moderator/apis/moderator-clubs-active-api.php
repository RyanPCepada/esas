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
        // Ensure the moderator is logged in and has a valid session
        if (isset($_SESSION['moderator_id'])) {
            $moderator_id = $_SESSION['moderator_id'];

            // Query to fetch the clubs associated with the moderator
            $stmt = $pdo->prepare('
                SELECT c.club_id, c.clubName, c.information, c.coverPhoto, c.dateAdded, c.dateModified,
                       GROUP_CONCAT(s.firstName ORDER BY s.firstName SEPARATOR ", ") AS students,
                       GROUP_CONCAT(s.profilePic ORDER BY s.firstName SEPARATOR ", ") AS studentPics
                FROM tbl_clubs c
                LEFT JOIN tbl_registration r ON c.club_id = r.club_id
                LEFT JOIN tbl_students s ON r.student_id = s.student_id
                JOIN tbl_clubs_and_moderators cm ON cm.club_id = c.club_id
                WHERE cm.moderator_id = ? AND r.status = "active"
                GROUP BY c.club_id
            ');
            $stmt->execute([$moderator_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch member counts and format students for each club
            foreach ($result as &$club) {
                // Format the dateAdded and dateModified
                $club['dateAdded'] = (new DateTime($club['dateAdded']))->format('F j, Y');
                $club['dateModified'] = (new DateTime($club['dateModified']))->format('F j, Y');

                // Fetch member count for each club
                $stmt_count = $pdo->prepare('SELECT COUNT(*) as member_count FROM tbl_registration WHERE club_id = ?');
                $stmt_count->execute([$club['club_id']]);
                $club['membersCount'] = $stmt_count->fetch(PDO::FETCH_ASSOC)['member_count'];

                // Format the students
                $studentNames = explode(", ", $club['students']);
                $studentPics = explode(", ", $club['studentPics']);
                $club['formattedStudents'] = formatStudents($studentNames, $studentPics);
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

// Function to format students' names and profile pictures
function formatStudents($names, $pics) {
    $formatted = '';
    foreach ($names as $index => $name) {
        $pic = $pics[$index] ?? 'default.png'; // Use default image if profilePic is missing
        $formatted .= "<img src='/esas/esas_student/images/$pic' alt='Student' class='student-pic'> $name<br>";
    }
    return $formatted;
}
?>
