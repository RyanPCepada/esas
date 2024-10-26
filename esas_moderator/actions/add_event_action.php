<?php
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $timeStarts = $_POST['timeStarts'];
    $timeEnds = $_POST['timeEnds'];
    $location = $_POST['location'];
    $applicationLink = $_POST['applicationLink'];
    $club_id = $_POST['club_id'];
    $moderator_id = $_POST['moderator_id'];

    // Get current date and time for dateAdded and dateModified
    $currentDateTime = date('Y-m-d H:i:s');

    // Insert into tbl_events using PDO
    $sql = "INSERT INTO tbl_events (title, description, date, timeStarts, timeEnds, location, applicationLink, dateAdded, dateModified, club_id, moderator_id) 
            VALUES (:title, :description, :date, :timeStarts, :timeEnds, :location, :applicationLink, :dateAdded, :dateModified, :club_id, :moderator_id)";
    
    try {
        $pdo->beginTransaction();

        // Insert event into tbl_events
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':date' => $date,
            ':timeStarts' => $timeStarts,
            ':timeEnds' => $timeEnds,
            ':location' => $location,
            ':applicationLink' => $applicationLink,
            ':dateAdded' => $currentDateTime,
            ':dateModified' => $currentDateTime,
            ':club_id' => $club_id,
            ':moderator_id' => $moderator_id
        ]);

        // Get the club name
        $club_sql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
        $club_stmt = $pdo->prepare($club_sql);
        $club_stmt->execute([':club_id' => $club_id]);
        $club = $club_stmt->fetch(PDO::FETCH_ASSOC);
        $clubName = $club['clubName'];

        // Insert into tbl_activity_logs without club_id
        $activity = "You added an event for " . $clubName;
        $log_sql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) 
                    VALUES (:activity, :dateAdded, :moderator_id)";
        $log_stmt = $pdo->prepare($log_sql);
        $log_stmt->execute([
            ':activity' => $activity,
            ':dateAdded' => $currentDateTime,
            ':moderator_id' => $moderator_id
        ]);

        $pdo->commit();

        // Redirect to home.php after successful insertion
        header("Location: /esas/esas_moderator/public/home.php?success=1&club_id=" . urlencode($club_id));
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
