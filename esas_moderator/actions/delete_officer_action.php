<?php
require_once "../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_officer_id'])) {
    $officerId = $_POST['delete_officer_id'];

    // Fetch the club name associated with the officer before deletion
    $clubSql = "SELECT c.clubName 
                FROM tbl_application_officers AS q 
                JOIN tbl_clubs AS c ON q.club_id = c.club_id 
                WHERE q.officer_id = ?";
    $clubStmt = $pdo->prepare($clubSql);
    $clubStmt->execute([$officerId]);
    $club = $clubStmt->fetch(PDO::FETCH_ASSOC);

    // If club is found, proceed with deletion
    if ($club) {
        // Deletion logic
        $deleteSql = "DELETE FROM tbl_club_officers WHERE officer_id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        if ($deleteStmt->execute([$officerId])) {
            // Log the deletion activity with the actual club name
            $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) 
                       VALUES (:activity, :dateAdded, :moderator_id)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([
                'activity' => "You deleted an officer in " . htmlspecialchars($club['clubName']) . ".",
                'dateAdded' => date('Y-m-d H:i:s'),
                'moderator_id' => $moderator_id
            ]);
            // Set success message in session and redirect
            // $_SESSION['message'] = "Officer deleted successfully!";
        } else {
            $_SESSION['message'] = "Error deleting officer.";
        }
    } else {
        $_SESSION['message'] = "Club not found for this officer.";
    }
}
?>
