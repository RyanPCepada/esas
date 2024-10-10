<?php
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $club_id = $_POST['club_id'];
    $moderator_id = $_POST['moderator_id'];
    $event_id = $_POST['event_id']; // Ensure you retrieve event_id
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $timeStarts = $_POST['timeStarts']; // Separate start time
    $timeEnds = $_POST['timeEnds']; // Separate end time
    $location = $_POST['location'];
    $registrationLink = $_POST['registrationLink'];
    
    // Update tbl_events using PDO
    $sql = "UPDATE tbl_events 
            SET title = :title, description = :description, date = :date, 
                timeStarts = :timeStarts, timeEnds = :timeEnds, location = :location, 
                registrationLink = :registrationLink, club_id = :club_id, moderator_id = :moderator_id, dateModified = NOW()
            WHERE event_id = :event_id"; // Ensure the WHERE clause matches the correct field name
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':event_id' => $event_id,
            ':title' => $title,
            ':description' => $description,
            ':date' => $date,
            ':timeStarts' => $timeStarts,
            ':timeEnds' => $timeEnds,
            ':location' => $location,
            ':registrationLink' => $registrationLink,
            ':club_id' => $club_id,
            ':moderator_id' => $moderator_id
        ]);

        // Redirect to home.php after successful update
        header("Location: /esas/esas_moderator/public/home.php?success=1&club_id=" . urlencode($club_id));
        exit(); // Make sure to call exit after redirecting
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Handle error appropriately
    }
}
?>
