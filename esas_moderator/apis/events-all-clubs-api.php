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
        // Get the current club's ID from query parameters
        $currentClubId = isset($_GET['club_id']) ? intval($_GET['club_id']) : 0;

        // Fetch only upcoming events from other clubs
        $stmt = $pdo->prepare('
            SELECT e.event_id, e.title, e.date, e.timeStarts, e.timeEnds, e.location,
                c.clubName 
            FROM tbl_events e
            LEFT JOIN tbl_clubs c ON e.club_id = c.club_id
            WHERE e.date >= CURDATE() AND e.club_id != :currentClubId
            ORDER BY e.date, e.timeStarts
        ');
        $stmt->execute(['currentClubId' => $currentClubId]);
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
