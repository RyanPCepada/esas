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

$loggedInStudentId = $_SESSION['student_id']; // The logged-in student (current session)
$studentId = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0; // Get the selected student ID from the request

if ($studentId > 0) {
    // Fetch chat messages between the logged-in student and the selected student
    $query = "SELECT * FROM tbl_chats 
              WHERE (sender_id = :loggedInStudentId AND recipient_id = :studentId)
                 OR (sender_id = :studentId AND recipient_id = :loggedInStudentId)
              ORDER BY dateAdded ASC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'loggedInStudentId' => $loggedInStudentId,
        'studentId' => $studentId
    ]);

    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the chat messages in JSON format
    echo json_encode($chats);
} else {
    echo json_encode(['error' => 'Invalid student ID']);
}
?>
