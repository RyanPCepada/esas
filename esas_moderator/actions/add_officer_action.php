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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['club_id'])) {
    $position = isset($_POST['position']) ? $_POST['position'] : ''; // Default to empty if not set
    $new_position = isset($_POST['new_position']) ? $_POST['new_position'] : ''; // Default to empty if not set
    $clubId = $_POST['club_id'];

    // Fetch the club name based on club_id
    $clubNameSql = "SELECT clubName FROM tbl_clubs WHERE club_id = ?";
    $clubNameStmt = $pdo->prepare($clubNameSql);
    $clubNameStmt->execute([$clubId]);
    $clubNameRow = $clubNameStmt->fetch(PDO::FETCH_ASSOC);
    $clubName = $clubNameRow ? $clubNameRow['clubName'] : 'Unknown Club';

    // Handle the cases for position and new position
    if (!empty($position) && empty($new_position)) {
        // Case 1: Only position selected from dropdown
        $insertSql = "INSERT INTO tbl_club_officers (club_id, position, dateAdded) VALUES (?, ?, NOW())";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([$clubId, $position]);

    } elseif (empty($position) && !empty($new_position)) {
        // Case 2: Only new position entered (fix applied)
        $insertSql = "INSERT INTO tbl_club_officers (club_id, position, dateAdded) VALUES (?, ?, NOW())";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([$clubId, $new_position]);

    } elseif (!empty($position) && !empty($new_position)) {
        // Case 3: Both position selected and new position entered
        // Insert both the dropdown position and the custom new position
        $insertSql1 = "INSERT INTO tbl_club_officers (club_id, position, dateAdded) VALUES (?, ?, NOW())";
        $insertStmt1 = $pdo->prepare($insertSql1);
        $insertStmt1->execute([$clubId, $position]);

        $insertSql2 = "INSERT INTO tbl_club_officers (club_id, position, dateAdded) VALUES (?, ?, NOW())";
        $insertStmt2 = $pdo->prepare($insertSql2);
        $insertStmt2->execute([$clubId, $new_position]);
    }

    // Log the addition activity
    $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
    $logStmt = $pdo->prepare($logSql);
    $logStmt->execute([
        'activity' => "You added a new officer to " . htmlspecialchars($clubName),
        'dateAdded' => date('Y-m-d H:i:s'),
        'moderator_id' => $moderator_id
    ]);

    // Redirect to the settings page
    header("location: ../settings.php");
    exit();
} else {
    // Redirect if no POST data
    header("location: ../settings.php");
    exit();
}
?>
