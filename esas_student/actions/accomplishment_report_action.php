<?php
// Include config file
require_once "../../config.php";
session_start();

if (!isset($_SESSION['student_id'])) {
    echo "Student ID is not set in the session.";
    exit;
}

$student_id = $_SESSION['student_id'];

if (isset($_POST["club_id"])) {
    $club_id = $_POST["club_id"];
} else {
    echo "Club ID is required.";
    exit;
}

// Set default timezone
date_default_timezone_set('Asia/Manila');

// File upload configuration
$reportTargetDir = "/esas/esas_student/accomplishment_reports/"; // Full server path
$fileName = basename($_FILES["accReportFile"]["name"]);
$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// Check if file is a PDF
if ($fileType != "pdf") {
    echo "Only PDF files are allowed.";
    exit;
}

// Move file to target directory
$filePath = $reportTargetDir . uniqid() . "_" . $fileName;
if (move_uploaded_file($_FILES["accReportFile"]["tmp_name"], $filePath)) {
    // Insert record into the database
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);

    $sql = "INSERT INTO tbl_accomplishment_reports (title, description, accReportFile, student_id, club_id, dateAdded) VALUES (:title, :description, :accReportFile, :student_id, :club_id, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":title", $title, PDO::PARAM_STR);
    $stmt->bindParam(":description", $description, PDO::PARAM_STR);
    $stmt->bindParam(":accReportFile", $filePath, PDO::PARAM_STR);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back with success message
        header("Location: /esas/esas_student/ellipsis/accomplishment_reports.php?club_id=" . urlencode($club_id) . "");
        exit();
    } else {
        echo "Something went wrong. Please try again.";
    }
    
} else {
    echo "Failed to upload file.";
}
?>
