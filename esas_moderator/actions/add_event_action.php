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
    $timeStarts = $_POST['timeStarts']; // Updated to timeStarts
    $timeEnds = $_POST['timeEnds'];     // Added timeEnds
    $location = $_POST['location'];
    $registrationLink = $_POST['registrationLink'];
    $club_id = $_POST['club_id'];
    $moderator_id = $_POST['moderator_id'];

    // Get current date and time for dateAdded and dateModified
    $currentDateTime = date('Y-m-d H:i:s');

    // Insert into tbl_events using PDO
    $sql = "INSERT INTO tbl_events (title, description, date, timeStarts, timeEnds, location, registrationLink, dateAdded, dateModified, club_id, moderator_id) 
            VALUES (:title, :description, :date, :timeStarts, :timeEnds, :location, :registrationLink, :dateAdded, :dateModified, :club_id, :moderator_id)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':date' => $date,
            ':timeStarts' => $timeStarts,
            ':timeEnds' => $timeEnds,
            ':location' => $location,
            ':registrationLink' => $registrationLink,
            ':dateAdded' => $currentDateTime,       // Set dateAdded to the current datetime
            ':dateModified' => $currentDateTime,    // Set dateModified to the current datetime
            ':club_id' => $club_id,
            ':moderator_id' => $moderator_id
        ]);

        // Redirect to home.php after successful insertion
        header("Location: /esas/esas_moderator/public/home.php?success=1&club_id=" . urlencode($club_id));
        exit(); // Make sure to call exit after redirecting
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // Handle error appropriately
    }
}
?>
