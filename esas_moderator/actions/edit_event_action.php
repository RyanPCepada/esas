<?php
// Include config file
require_once "../../config.php";

// Start the session
session_start();

// Check if the moderator is logged in
if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $club_id = $_POST['club_id'];
    $event_id = $_POST['event_id']; // Ensure you retrieve event_id
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $timeStarts = $_POST['timeStarts']; // Separate start time
    $timeEnds = $_POST['timeEnds']; // Separate end time
    $location = $_POST['location'];
    $applicationLink = $_POST['applicationLink'];
    
    // Fetch the current event title before updating
    $eventSql = "SELECT title FROM tbl_events WHERE event_id = :event_id";
    $eventStmt = $pdo->prepare($eventSql);
    $eventStmt->execute([':event_id' => $event_id]);
    $event = $eventStmt->fetch(PDO::FETCH_ASSOC);
    //$old_event_name = $event['title']; // Save the old event name
    
    // Update tbl_events using PDO
    $sql = "UPDATE tbl_events 
            SET title = :title, description = :description, date = :date, 
                timeStarts = :timeStarts, timeEnds = :timeEnds, location = :location, 
                applicationLink = :applicationLink, dateModified = NOW()
            WHERE event_id = :event_id";
    
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
            ':applicationLink' => $applicationLink
        ]);

        // Fetch the club name for logging activity
        $clubSql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
        $clubStmt = $pdo->prepare($clubSql);
        $clubStmt->execute([':club_id' => $club_id]);
        $club = $clubStmt->fetch(PDO::FETCH_ASSOC);
        $clubName = $club['clubName'];

        // Log the activity in tbl_activity_logs
        $activity = "You updated the event '{$title}' information in {$clubName}";
        $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) 
                   VALUES (:activity, NOW(), :moderator_id)";
        $logStmt = $pdo->prepare($logSQL);
        $logStmt->bindParam(":activity", $activity);
        $logStmt->bindParam(":moderator_id", $moderator_id);
        $logStmt->execute();

        // Redirect to home.php after successful update
        header("Location: /esas/esas_moderator/public/home.php?success=1&club_id=" . urlencode($club_id));
        exit(); // Make sure to call exit after redirecting
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Handle error appropriately
    }
}
?>
