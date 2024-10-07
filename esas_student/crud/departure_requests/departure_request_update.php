<?php
require_once "../../../config.php"; // Database config file
session_start();

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    header("Location: ../departure_request_read.php?error=not_logged_in");
    exit();
}

// Retrieve student_id from the session
$student_id = $_SESSION['student_id'];

// Check if club_id and reason are posted
if (!isset($_POST['club_id'], $_POST['reason'])) {
    header("Location: ../departure_request_read.php?error=missing_data");
    exit();
}

$club_id = $_POST['club_id'];
$reason = $_POST['reason'];

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');
$dateRequested = date('Y-m-d H:i:s');

try {
    // Use the existing PDO instance from config.php
    global $pdo;

    // Check if a departure request exists for this student and club
    $checkSql = "
        SELECT departure_id 
        FROM tbl_departure_requests 
        WHERE student_id = :student_id AND club_id = :club_id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $checkStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
    $checkStmt->execute();
    $departureRequest = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$departureRequest) {
        header("Location: ../departure_request_read.php?error=request_not_found");
        exit();
    }

    // Update the reason and dateRequested for the departure request
    $updateSql = "
        UPDATE tbl_departure_requests 
        SET reason = :reason, dateRequested = :dateRequested 
        WHERE student_id = :student_id AND club_id = :club_id";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->bindParam(":reason", $reason, PDO::PARAM_STR);
    $updateStmt->bindParam(":dateRequested", $dateRequested, PDO::PARAM_STR);
    $updateStmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $updateStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

    if ($updateStmt->execute()) {
        // Redirect to the home page with the club_id in the URL
        $redirect_url = '/esas/esas_student/crud/departure_requests/departure_request_read.php?club_id=' . urlencode($club_id);
        header("Location: $redirect_url");
        exit();
    } else {
        // Redirect with an error message if the update fails
        header("Location: ../departure_request_read.php?error=update_failed");
        exit();
    }

} catch (PDOException $e) {
    // Handle database connection or query error
    header("Location: ../departure_request_read.php?error=db_error");
    exit();
} 
