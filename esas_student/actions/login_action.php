<?php
require_once '../../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Retrieve the student ID and password from the form
$student_id = $_POST['student_id'];
$pass =  $_POST['password'];

// Prepare the SQL statement using the $pdo object
$selectQry = $pdo->prepare("SELECT count(student_id) as cnt, student_id, `password`
    FROM tbl_students WHERE student_id = ? AND `password` = ?");
$selectQry->execute([$student_id, $pass]);
$selectQry = $selectQry->fetch(PDO::FETCH_ASSOC);

// Check if a matching record was found
if ($selectQry['cnt'] > 0 && $selectQry['student_id'] == $student_id && $selectQry['password'] == $pass) {
    session_start();
    $_SESSION['student_id'] = $selectQry['student_id'];

    // Log the activity in tbl_activity_logs
    $activity = "You logged in to your account";
    $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id, student_id) 
               VALUES (:activity, NOW(), NULL, NULL, :student_id)";
    
    $logStmt = $pdo->prepare($logSQL);
    $logStmt->bindParam(":activity", $activity);
    $logStmt->bindParam(":student_id", $_SESSION['student_id']); // Bind the student_id from the session

    // Execute the log insertion
    $logStmt->execute();

    echo 'success';
} else {
    echo 'Invalid email/password';
}
?>
