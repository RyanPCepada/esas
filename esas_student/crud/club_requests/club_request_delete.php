<?php
require_once "../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if request_id is set
    if (isset($_POST['request_id']) && !empty($_POST['request_id'])) {
        $request_id = $_POST['request_id'];

        try {
            // Create a prepared statement to delete the club request
            $query = "DELETE FROM tbl_club_requests WHERE request_id = :request_id";
            $stmt = $pdo->prepare($query);

            // Bind the request_id parameter
            $stmt->bindParam(':request_id', $request_id, PDO::PARAM_INT);

            // Execute the query
            if ($stmt->execute()) {
                // If successful, send a success response
                echo json_encode(['success' => true]);
            } else {
                // If the execution failed, send a failure response
                echo json_encode(['success' => false, 'message' => 'Failed to delete club request.']);
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
