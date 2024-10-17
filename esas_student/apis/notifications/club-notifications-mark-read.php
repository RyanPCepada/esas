<?php
session_start();
require_once "../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if club_id is provided
if (isset($_POST['club_id']) && isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
    $club_id = $_POST['club_id'];

    // Update the is_read field to 1 for notifications for this student and club
    $sql = "UPDATE tbl_notifications SET is_read = 1 WHERE student_id = :student_id AND club_id = :club_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Notifications marked as read.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
