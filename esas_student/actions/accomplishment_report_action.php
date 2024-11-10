<?php
// Start the session to access session variables
session_start();

// Include database configuration file
require_once '../../config.php';

// Set default timezone
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $student_id = $_POST['student_id'];
    $club_id = $_POST['club_id'];
    $dateAdded = date('Y-m-d H:i:s'); // Current timestamp
    $dateModified = $dateAdded; // Default modified date

    // File upload logic for accomplishment report
    $accReportFile = '';
    $targetDir = "/esas/esas_student/accomplishment_reports/"; // Directory for uploaded reports
    $allowedTypes = ['pdf']; // Allowed types for accomplishment reports

    if (isset($_FILES['accReportFile']) && $_FILES['accReportFile']['error'] == 0) {
        $fileName = basename($_FILES['accReportFile']['name']); // Original filename
        $originalFileName = $fileName; // Store the original name for the database
        $fileSize = $_FILES['accReportFile']['size'];
        $fileTmpName = $_FILES['accReportFile']['tmp_name'];
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    
        // Validate file type and size (10MB limit)
        if (in_array(strtolower($fileType), $allowedTypes) && $fileSize <= 10 * 1024 * 1024) {
            $newFileName = uniqid() . "." . $fileType; // Generate unique file name
            $targetFilePath = $_SERVER['DOCUMENT_ROOT'] . $targetDir . $newFileName;
    
            if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                $accReportFile = $newFileName; // Store the unique file name for the database
            } else {
                die("Error uploading the accomplishment report.");
            }
        } else {
            die("Invalid accomplishment report type or file too large. Only PDF under 10MB allowed.");
        }
    } else {
        die("Please upload an accomplishment report.");
    }
    
    // Insert data into the database with original filename
    $sql = "INSERT INTO tbl_accomplishment_reports (accReportFile, originalFileName, student_id, club_id, dateAdded, dateModified) 
            VALUES (:accReportFile, :originalFileName, :student_id, :club_id, :dateAdded, :dateModified)";
    
    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':accReportFile', $accReportFile); // Unique filename
        $stmt->bindParam(':originalFileName', $originalFileName); // Original filename
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
        $stmt->bindParam(':dateAdded', $dateAdded);
        $stmt->bindParam(':dateModified', $dateModified);
    
        // Execute the query
        if ($stmt->execute()) {
            // Redirect to success page or show success message
            echo "<script>alert('Accomplishment report submitted successfully!'); window.location.href = '../ellipsis/accomplishment_reports.php?student_id=$student_id&club_id=$club_id';</script>";
        } else {
            echo "Something went wrong. Please try again.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
    
}
?>
