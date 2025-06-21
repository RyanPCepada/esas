<?php
// Start the session to access the session variables
session_start();

// Include database configuration file
require_once '../../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $reason = $_POST['reason']; // Reason for leaving the club
    $status = 'pending'; // Default status for a new request
    $dateRequested = date('Y-m-d H:i:s'); // Current timestamp
    $dateDecided = NULL; // NULL since the request is not approved yet

    // Retrieve student_id from the session
    if (isset($_SESSION['student_id'])) {
        $student_id = $_SESSION['student_id'];
    } else {
        die("Error: Student ID not found in the session.");
    }

    // Retrieve club_id from POST request (ensure it is being sent)
    if (isset($_POST['club_id'])) {
        $club_id = $_POST['club_id'];
    } else {
        die("Error: Club ID not found in the request.");
    }

    // Insert data into the database
    $sql = "INSERT INTO tbl_departure_requests (reason, status, dateRequested, dateDecided, student_id, club_id) 
            VALUES (:reason, :status, :dateRequested, :dateDecided, :student_id, :club_id)";

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':dateRequested', $dateRequested);
        $stmt->bindParam(':dateDecided', $dateDecided); // NULL for pending requests
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT); // Use student_id from session
        $stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT); // Use club_id from POST

        // Execute the query
        if ($stmt->execute()) {
            // Return success response
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Something went wrong. Please try again.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => "Error: " . $e->getMessage()]);
    }
}
?>
