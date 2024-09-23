<?php
session_start();
require_once "../../config.php";

// Fetch the current student's ID
$student_id = $_SESSION['student_id']; 

// Get the count of unread notifications for the student
$sql = "SELECT COUNT(*) AS unread_count FROM tbl_notifications WHERE student_id = :student_id AND is_read = 0";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Return the count as a JSON response
echo json_encode(['unread_count' => $result['unread_count']]);
?>
