<?php
// Start the session to access the session variables
session_start();

// Include database configuration file
require_once '../../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $clubName = $_POST['clubName'];
    $goal = $_POST['goal'];
    $mission = $_POST['mission']; // New field for Mission
    $vision = $_POST['vision']; // New field for Vision
    $activities = $_POST['activities'] ?? '';
    $status = 'pending'; // Default status for a new request
    $dateRequested = date('Y-m-d H:i:s'); // Current timestamp
    $dateModified = $dateRequested; // Default modified date
    $dateApproved = NULL; // NULL since the club is not approved yet

    // Retrieve student_id from the session
    if (isset($_SESSION['student_id'])) {
        $student_id = $_SESSION['student_id'];
    } else {
        die("Error: Student ID not found in the session.");
    }

    // File upload logic for cover photo
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
                die("Error uploading the cover photo.");
            }
        } else {
            die("Invalid cover photo type or file too large. Only JPG, JPEG, PNG, GIF under 10MB allowed.");
        }
    } else {
        die("Please upload a cover photo.");
    }

    // File upload logic for request letter
    $requestLetter = '';
    $letterTargetDir = "/esas/esas_student/request_letter/"; // Directory for uploaded letters
    $allowedLetterTypes = ['pdf', 'doc', 'docx']; // Allowed types for letters

    if (isset($_FILES['requestLetter']) && $_FILES['requestLetter']['error'] == 0) {
        $letterName = basename($_FILES['requestLetter']['name']);
        $letterSize = $_FILES['requestLetter']['size'];
        $letterTmpName = $_FILES['requestLetter']['tmp_name'];
        $letterType = pathinfo($letterName, PATHINFO_EXTENSION);

        // Validate file type and size (10MB limit)
        if (in_array(strtolower($letterType), $allowedLetterTypes) && $letterSize <= 10 * 1024 * 1024) {
            $newLetterFileName = uniqid() . "." . $letterType; // Generate unique file name
            $letterTargetFilePath = $_SERVER['DOCUMENT_ROOT'] . $letterTargetDir . $newLetterFileName;

            if (move_uploaded_file($letterTmpName, $letterTargetFilePath)) {
                $requestLetter = $newLetterFileName; // Store the file name for the database
            } else {
                die("Error uploading the request letter.");
            }
        } else {
            die("Invalid request letter type or file too large. Only PDF, DOC, DOCX under 10MB allowed.");
        }
    } else {
        die("Please upload a request letter.");
    }

    // Insert data into the database
    $sql = "INSERT INTO tbl_club_requests (clubName, goal, mission, vision, activities, status, coverPhoto, requestLetter, dateRequested, dateModified, dateApproved, student_id) 
            VALUES (:clubName, :goal, :mission, :vision, :activities, :status, :coverPhoto, :requestLetter, :dateRequested, :dateModified, :dateApproved, :student_id)";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':clubName', $clubName);
        $stmt->bindParam(':goal', $goal);
        $stmt->bindParam(':mission', $mission); // Bind the mission
        $stmt->bindParam(':vision', $vision); // Bind the vision
        $stmt->bindParam(':activities', $activities);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':coverPhoto', $coverPhoto);
        $stmt->bindParam(':requestLetter', $requestLetter); // Bind the request letter
        $stmt->bindParam(':dateRequested', $dateRequested);
        $stmt->bindParam(':dateModified', $dateModified);
        $stmt->bindParam(':dateApproved', $dateApproved); // NULL for pending requests
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT); // Use student_id from session

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
