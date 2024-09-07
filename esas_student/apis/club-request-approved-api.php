<?php
require_once '../../config.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all approved club requests
        $stmt = $pdo->query('
            SELECT r.request_id, r.clubName, r.description, r.activities, r.status, r.coverPhoto, r.dateRequested, 
                   s.firstName, s.lastName
            FROM tbl_club_requests r
            LEFT JOIN tbl_students s ON r.student_id = s.student_id
            WHERE r.status = "approved"
            ORDER BY r.dateRequested DESC
        ');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No approved club requests found']);
        }
        break;
        
    default:
        // Invalid method
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
