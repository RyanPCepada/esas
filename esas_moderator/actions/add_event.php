<?php
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $registrationLink = $_POST['registrationLink'];
    $club_id = $_POST['club_id'];
    $moderator_id = $_POST['moderator_id'];

    // Insert into tbl_events using PDO
    $sql = "INSERT INTO tbl_events (title, description, date, time, location, registrationLink, club_id, moderator_id) 
            VALUES (:title, :description, :date, :time, :location, :registrationLink, :club_id, :moderator_id)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':date' => $date,
            ':time' => $time,
            ':location' => $location,
            ':registrationLink' => $registrationLink,
            ':club_id' => $club_id,
            ':moderator_id' => $moderator_id
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
