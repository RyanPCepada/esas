<?php
// Include database configuration file
require_once '../../config.php';

// Start the session
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the user is logged in and session data is available
if (!isset($_SESSION['student_id']) || !isset($_SESSION['registration_id'])) {
    die("You must be logged in to submit a club request.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $clubName = $_POST['clubName'];
    $description = $_POST['description'];
    $activities = $_POST['activities'] ?? '';
    $status = 'pending'; // Default status for a new request
    $dateRequested = date('Y-m-d H:i:s'); // Current timestamp
    

    // File upload logic
    $coverPhoto = '';
    $targetDir = "/esas/esas_student/images/"; // Directory for uploaded images
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (isset($_FILES['coverPhoto']) && $_FILES['coverPhoto']['error'] == 0) {
        $fileName = basename($_FILES['coverPhoto']['name']);
        $fileSize = $_FILES['coverPhoto']['size'];
        $fileTmpName = $_FILES['coverPhoto']['tmp_name'];
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        
        // Validate file type and size (10MB limit)
        if (in_array(strtolower($fileType), $allowedTypes) && $fileSize <= 10 * 1024 * 1024) {
            $newFileName = uniqid() . "." . $fileType; // Generate unique file name
            $targetFilePath = $_SERVER['DOCUMENT_ROOT'] . $targetDir . $newFileName;
            
            if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                $coverPhoto = $newFileName; // Store the file name for the database
            } else {
                die("Error uploading the file.");
            }
        } else {
            die("Invalid file type or file too large. Only JPG, JPEG, PNG, GIF under 10MB allowed.");
        }
    } else {
        die("Please upload a cover photo.");
    }

    // Insert data into the database
    $sql = "INSERT INTO tbl_club_requests (clubName, description, activities, status, coverPhoto, dateRequested, student_id, registration_id) 
            VALUES (:clubName, :description, :activities, :status, :coverPhoto, :dateRequested, :student_id, :registration_id)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':clubName', $clubName);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':activities', $activities);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':coverPhoto', $coverPhoto);
        $stmt->bindParam(':dateRequested', $dateRequested);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':registration_id', $registration_id);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to success page or show success message
            echo "<script>alert('Club request submitted successfully!'); window.location.href = '../club_requests.php';</script>";
        } else {
            echo "Something went wrong. Please try again.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
