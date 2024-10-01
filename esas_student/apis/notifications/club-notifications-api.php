<?php
session_start();
require_once "../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$student_id = $_SESSION['student_id'];
$club_id = $_GET['club_id']; // Get the club ID from the request

$sql = "SELECT COUNT(*) AS unread_count FROM tbl_notifications WHERE student_id = :student_id AND club_id = :club_id AND is_read = 0";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
$stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Return the count as a JSON response
echo json_encode(['unread_count' => $result['unread_count']]);
?>
