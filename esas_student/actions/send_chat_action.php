<?php
session_start(); // Start the session

// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the message and recipient ID from the POST request
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $recipientId = isset($_POST['recipient_id']) ? intval($_POST['recipient_id']) : 0;
    $clubId = isset($_POST['club_id']) ? intval($_POST['club_id']) : 0; // Add club_id if needed

    // Get the sender ID from the session
    $senderId = isset($_SESSION['student_id']) ? intval($_SESSION['student_id']) : 0; // Changed this line

    // Validate inputs
    if (empty($message)) {
        echo json_encode(['error' => 'Message cannot be empty.']);
        exit;
    }
    if ($recipientId === 0) {
        echo json_encode(['error' => 'Invalid recipient.']);
        exit;
    }
    if ($senderId === 0) {
        echo json_encode(['error' => 'Invalid sender.']);
        exit;
    }

    try {
        // Use the existing PDO instance from the config
        global $pdo; // Access the PDO instance defined in your config file

        // Prepare the SQL statement to insert the message
        $stmt = $pdo->prepare("INSERT INTO tbl_chats (message, replied_id, sender_id, recipient_id, club_id, dateAdded, dateModified) 
                                VALUES (:message, NULL, :sender_id, :recipient_id, :club_id, NOW(), NOW())");

        // Bind parameters
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':sender_id', $senderId);
        $stmt->bindParam(':recipient_id', $recipientId);
        $stmt->bindParam(':club_id', $clubId);

        // Execute the statement
        $stmt->execute();

        // Return a success message
        echo json_encode(['success' => 'Message sent successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // If not a POST request
    echo json_encode(['error' => 'Invalid request method.']);
}
?>

<!-- FIXED -->
<!-- FOR RECOMMIT PURPOSES DUE TO AN ACCIDENTAL CLICK -->