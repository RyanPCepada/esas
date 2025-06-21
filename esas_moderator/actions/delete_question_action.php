<?php
require_once "../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Set default timezone
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_question_id'])) {
    $questionId = $_POST['delete_question_id'];

    // Fetch the club name associated with the question before deletion
    $clubSql = "SELECT c.clubName 
                FROM tbl_application_questions AS q 
                JOIN tbl_clubs AS c ON q.club_id = c.club_id 
                WHERE q.question_id = ?";
    $clubStmt = $pdo->prepare($clubSql);
    $clubStmt->execute([$questionId]);
    $club = $clubStmt->fetch(PDO::FETCH_ASSOC);

    // If club is found, proceed with deletion
    if ($club) {
        // Deletion logic
        $deleteSql = "DELETE FROM tbl_application_questions WHERE question_id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        if ($deleteStmt->execute([$questionId])) {
            // Log the deletion activity with the actual club name
            $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([
                'activity' => "You deleted a question in " . htmlspecialchars($club['clubName']) . "'s application form.",
                'dateAdded' => date('Y-m-d H:i:s'),
                'moderator_id' => $moderator_id
            ]);
            // echo "Question deleted successfully!";
        } else {
            echo "Error deleting question.";
        }
    } else {
        echo "Club not found for this question.";
    }
}
?>
