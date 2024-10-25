<?php
require_once "../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if request_id is set
    if (isset($_POST['request_id']) && !empty($_POST['request_id'])) {
        $request_id = $_POST['request_id'];

        try {
            // Create a prepared statement to fetch the club name first
            $fetchQuery = "SELECT clubName FROM tbl_club_requests WHERE request_id = :request_id";
            $fetchStmt = $pdo->prepare($fetchQuery);
            $fetchStmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
            $fetchStmt->execute();
            $club = $fetchStmt->fetch(PDO::FETCH_ASSOC);

            // Check if the club exists
            if ($club) {
                $clubName = $club['clubName']; // Get the club name for logging
                // Create a prepared statement to delete the club request
                $query = "DELETE FROM tbl_club_requests WHERE request_id = :request_id";
                $stmt = $pdo->prepare($query);
                // Bind the request_id parameter
                $stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);
                // Execute the query
                if ($stmt->execute()) {
                    // Log the deletion in the activity_logs table
                    logActivity($pdo, $request_id, $clubName);
                    // If successful, send a success response
                    echo json_encode(['success' => true]);
                } else {
                    // If the execution failed, send a failure response
                    echo json_encode(['success' => false, 'message' => 'Failed to delete club request.']);
                }
            } else {
                // If the club doesn't exist
                echo json_encode(['success' => false, 'message' => 'No club request found with this ID.']);
            }
        } catch (PDOException $e) {
            // Handle any errors
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // If request_id is not provided, return an error response
        echo json_encode(['success' => false, 'message' => 'Invalid request. Request ID is missing.']);
    }
} else {
    // If the request method is not POST, return an error response
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Function to log activity
function logActivity($pdo, $request_id, $clubName) {
    try {
        // Retrieve the student ID from the session
        session_start();
        if (isset($_SESSION['student_id'])) {
            $student_id = $_SESSION['student_id'];
        } else {
            return; // If no student ID found, exit the function
        }

        // Log the activity in tbl_activity_logs
        $activity = "You deleted your club request '$clubName'";
        $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, student_id) 
                   VALUES (:activity, NOW(), :student_id)";
        $logStmt = $pdo->prepare($logSQL);

        // Bind parameters
        $logStmt->bindParam(":activity", $activity);
        $logStmt->bindParam(":student_id", $student_id, PDO::PARAM_INT); // Use student_id from session

        // Execute the log insertion
        $logStmt->execute();
    } catch (PDOException $e) {
        // Handle any logging errors (optional)
        // You may want to log this error to a file or notify an administrator
    }
}
