<?php
require_once '../../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Fetch chats for a specific club
        if (isset($_GET['club_id'])) {
            $club_id = $_GET['club_id'];

            $stmt = $pdo->prepare("
                SELECT c.message, c.dateAdded, s.firstName, s.lastName, s.profilePic, m.firstName AS modFirstName, m.lastName AS modLastName, m.profilePic AS modProfilePic
                FROM tbl_chats c
                LEFT JOIN tbl_students s ON c.student_id = s.student_id
                LEFT JOIN tbl_moderators m ON c.moderator_id = m.moderator_id
                WHERE c.club_id = :club_id
                ORDER BY c.dateAdded ASC
            ");
            $stmt->execute(['club_id' => $club_id]);
            $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($chats);
        } else {
            echo json_encode(['error' => 'club_id is required']);
        }
        break;

    case 'POST':
        // Post a new chat message
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['message'], $data['club_id'], $data['student_id'])) {
            $message = $data['message'];
            $club_id = $data['club_id'];
            $student_id = $data['student_id'];
            $moderator_id = $data['moderator_id'] ?? null; // Optional for moderators

            $stmt = $pdo->prepare("
                INSERT INTO tbl_chats (message, student_id, moderator_id, club_id, dateAdded, dateModified)
                VALUES (:message, :student_id, :moderator_id, :club_id, NOW(), NOW())
            ");
            $stmt->execute([
                'message' => $message,
                'student_id' => $student_id,
                'moderator_id' => $moderator_id,
                'club_id' => $club_id
            ]);

            echo json_encode(['success' => 'Message sent successfully']);
        } else {
            echo json_encode(['error' => 'Required fields: message, club_id, student_id']);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}
?>
