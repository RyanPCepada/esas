<?php
require_once '../../config.php';
session_start();

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json');

// Ensure the student ID is set in the session
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['error' => 'Student not logged in.']);
    exit;
}

$studentId = $_SESSION['student_id']; // Assuming you store the logged-in student ID in the session
$studentId = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0; // Get the student_id from the request

if ($studentId > 0) {
    // Fetch chat messages
    $query = "SELECT * FROM tbl_chats 
              WHERE (sender_id = :student_id AND recipient_id = :student_id)
                 OR (sender_id = :student_id AND recipient_id = :student_id)
              ORDER BY dateAdded ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['student_id' => $studentId, 'student_id' => $studentId]);

    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the chat messages in JSON format
    echo json_encode($chats);
} else {
    echo json_encode(['error' => 'Invalid student ID']);
}
?>
