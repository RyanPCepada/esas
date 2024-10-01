<?php
session_start();
require_once "../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$student_id = $_POST['student_id'];

// Update the is_read field to 1 for all notifications for this student
$sql = "UPDATE tbl_notifications SET is_read = 1 WHERE student_id = :student_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
$stmt->execute();

echo "Notifications marked as read.";
?>
