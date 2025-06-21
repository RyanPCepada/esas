<?php
require_once "../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$officer_id = $_POST['officer_id'];
$club_id = $_POST['club_id'];

// Check if the officer ID and club ID are set
if (isset($_POST['officer_id']) && isset($_POST['club_id'])) {
    // Fetch the club name associated with the officer before deletion
    $clubSql = "SELECT c.clubName 
                FROM tbl_clubs AS c 
                JOIN tbl_club_officers AS q ON q.club_id = c.club_id 
                WHERE q.officer_id = ? AND q.club_id = ?";
    $clubStmt = $pdo->prepare($clubSql);
    $clubStmt->execute([$officer_id, $club_id]);
    $club = $clubStmt->fetch(PDO::FETCH_ASSOC);

    // If club is found, proceed with deletion
    if ($club) {
        // Deletion logic
        $deleteSql = "DELETE FROM tbl_club_officers WHERE officer_id = ? AND club_id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        if ($deleteStmt->execute([$officer_id, $club_id])) {
            // Log the deletion activity with the actual club name
            $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) 
                       VALUES (:activity, :dateAdded, :moderator_id)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([
                'activity' => "You deleted an officer in " . htmlspecialchars($club['clubName']) . ".",
                'dateAdded' => date('Y-m-d H:i:s'),
                'moderator_id' => $moderator_id
            ]);
            // Set success message in session
            echo "Officer deleted successfully!";
            exit();
        } else {
            $_SESSION['message'] = "Error deleting officer.";
            exit();
        }
    } else {
        $_SESSION['message'] = "Club not found for this officer.";
        exit();
    }
} else {
    echo "Officer ID and Club ID are missing.";
    exit();
}
?>
