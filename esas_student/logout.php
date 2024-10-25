<?php
// Initialize the session
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Include the database configuration file
require_once '../config.php'; // Adjust the path as needed

// Log the activity in tbl_activity_logs
if (isset($_SESSION['student_id'])) {
    $activity = "You logged out of your account";
    $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id, student_id) 
               VALUES (:activity, NOW(), NULL, NULL, :student_id)";
    
    $logStmt = $pdo->prepare($logSQL);
    $logStmt->bindParam(":activity", $activity);
    $logStmt->bindParam(":student_id", $_SESSION['student_id']); // Bind the student_id from the session

    // Execute the log insertion
    $logStmt->execute();
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../esas_student/login.php");
exit;
?>
