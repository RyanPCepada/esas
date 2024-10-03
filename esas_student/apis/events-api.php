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
        // Check if an event_id parameter is provided for fetching a single event
        if (isset($_GET['event_id'])) {
            $event_id = $_GET['event_id'];
            $stmt = $pdo->prepare('
                SELECT e.*, 
                       c.clubName 
                FROM tbl_events e
                LEFT JOIN tbl_clubs c ON e.club_id = c.club_id
                WHERE e.event_id = ?
            ');
            $stmt->execute([$event_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Event not found']);
            }
        } elseif (isset($_GET['club_id'])) {
            // Fetch events for a specific club
            $club_id = $_GET['club_id'];
            $stmt = $pdo->prepare('
                SELECT e.*, 
                       c.clubName 
                FROM tbl_events e
                LEFT JOIN tbl_clubs c ON e.club_id = c.club_id
                WHERE e.club_id = ?
                ORDER BY e.date, e.time
            ');
            $stmt->execute([$club_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($result);
        } else {
            // Fetch all events
            $stmt = $pdo->query('
                SELECT e.*, 
                       c.clubName 
                FROM tbl_events e
                LEFT JOIN tbl_clubs c ON e.club_id = c.club_id
                ORDER BY e.date, e.time
            ');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($result);
        }
        break;

    default:
        // Invalid method
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>
