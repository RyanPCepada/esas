<?php
require_once "../../config.php";
session_start();

// Ensure moderator is logged in
if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['position'], $_POST['club_id'])) {
    $position = $_POST['position'];
    $clubId = $_POST['club_id'];

    // Fetch the club name based on club_id
    $clubNameSql = "SELECT clubName FROM tbl_clubs WHERE club_id = ?";
    $clubNameStmt = $pdo->prepare($clubNameSql);
    $clubNameStmt->execute([$clubId]);
    $clubNameRow = $clubNameStmt->fetch(PDO::FETCH_ASSOC);
    $clubName = $clubNameRow ? $clubNameRow['clubName'] : 'Unknown Club';

    // Insert new officer into the database
    $insertSql = "INSERT INTO tbl_club_officers (club_id, position, dateAdded) VALUES (?, ?, NOW())";
    $insertStmt = $pdo->prepare($insertSql);

    if ($insertStmt->execute([$clubId, $position])) {
        // Log the addition activity
        $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([
            'activity' => "You added a new officer to " . htmlspecialchars($clubName),
            'dateAdded' => date('Y-m-d H:i:s'),
            'moderator_id' => $moderator_id
        ]);

        // Send a success response to the AJAX call
        // echo "Success: Officer added!";
        header("location: ../settings.php");
        exit();
    } else {
        // Send an error response to the AJAX call
        // echo "Error adding new officer.";
        header("location: ../settings.php");
        exit();
    }
}
?>
