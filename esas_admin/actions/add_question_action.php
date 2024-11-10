<?php
require_once "../../config.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    die("You are not logged in.");
}

$admin_id = $_SESSION['admin_id'];

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_question'], $_POST['club_id'])) {
    $newQuestion = $_POST['new_question'];
    $clubId = $_POST['club_id'];

    // Fetch the club name based on club_id
    $clubNameSql = "SELECT clubName FROM tbl_clubs WHERE club_id = ?";
    $clubNameStmt = $pdo->prepare($clubNameSql);
    $clubNameStmt->execute([$clubId]);
    $clubNameRow = $clubNameStmt->fetch(PDO::FETCH_ASSOC);
    $clubName = $clubNameRow ? $clubNameRow['clubName'] : 'Unknown Club';

    // Insert new question into the database
    $insertSql = "INSERT INTO tbl_application_questions (question, club_id, dateAdded) VALUES (?, ?, NOW())";
    $insertStmt = $pdo->prepare($insertSql);

    if ($insertStmt->execute([$newQuestion, $clubId])) {
        // Log the addition activity
        $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id) VALUES (:activity, :dateAdded, :admin_id)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([
            'activity' => "You added a new question to " . htmlspecialchars($clubName) . "'s application form",
            'dateAdded' => date('Y-m-d H:i:s'),
            'admin_id' => $admin_id
        ]);
        // echo "New question added successfully!";
        header("location: ../settings.php");
    } else {
        echo "Error adding new question.";
    }
}
?>
