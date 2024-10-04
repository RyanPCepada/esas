<?php
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id']; // Ensure you retrieve event_id
    $club_id = $_POST['club_id'];
    $moderator_id = $_POST['moderator_id'];
    
    // Delete event from tbl_events using PDO
    $sql = "DELETE FROM tbl_events WHERE event_id = :event_id"; // Ensure the WHERE clause matches the correct field name
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':event_id' => $event_id]);

        // Redirect to home.php after successful deletion
        header("Location: /esas/esas_moderator/public/home.php?success=1&club_id=" . urlencode($club_id));
        exit(); // Make sure to call exit after redirecting
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Handle error appropriately
    }
}
?>
