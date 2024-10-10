<?php
require_once '../../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all events from all clubs
        $stmt = $pdo->query('
            SELECT e.*, 
                   c.clubName 
            FROM tbl_events e
            LEFT JOIN tbl_clubs c ON e.club_id = c.club_id
            ORDER BY e.date, e.timeStarts
        ');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
        break;

    default:
        // Invalid method
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
