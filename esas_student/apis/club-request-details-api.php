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

        // Get the request_id from the query parameters
        $request_id = $_GET['request_id'];

        // Prepare and execute the query to fetch details for the specific club request
        $stmt = $pdo->prepare('
            SELECT request_id, clubName, goal, activities, status, coverPhoto, dateRequested, dateModified
            FROM tbl_club_requests
            WHERE request_id = :request_id AND student_id = :student_id
        ');
        $stmt->execute(['request_id' => $request_id, 'student_id' => $student_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Format the dateRequested and dateModified fields
            $result['dateRequested'] = (new DateTime($result['dateRequested']))->format('F j, Y'); // Format the date
            $result['dateModified'] = (new DateTime($result['dateModified']))->format('F j, Y'); // Format the date

            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No details found for this club request']);
        }
        break;
        
    default:
        // Invalid method
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
