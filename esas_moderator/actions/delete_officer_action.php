<?php
require_once "../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Check if officer_id and club_id are provided
if (!isset($_GET['officer_id']) || !isset($_GET['club_id'])) {
    die("club_id or officer_id is not provided.");
}

$officer_id = $_GET['officer_id'];
$club_id = $_GET['club_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['officerId'])) {
    $officerId = $_POST['officerId'];

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
        $deleteSql = "DELETE FROM tbl_club_officers WHERE officer_id = ? AND club_id = ?";
        $deleteStmt = $pdo->prepare($deleteSql);
        if ($deleteStmt->execute([$officerId, $club_id])) {
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
            $_SESSION['message'] = "Officer deleted successfully!";
            header("Location: /esas/esas_moderator/public/dashboard.php"); // Replace with your redirect page
            exit();
        } else {
            $_SESSION['message'] = "Error deleting officer.";
            header("Location: /esas/esas_moderator/public/my_clubs.php"); // Replace with your redirect page
            exit();
        }
    } else {
        $_SESSION['message'] = "Club not found for this officer.";
        header("Location: /esas/esas_moderator/public/students.php"); // Replace with your redirect page
        exit();
    }
} else {
    $_SESSION['message'] = "Invalid request. Officer ID or Club ID is missing.";
    header("Location: /esas/esas_moderator/public/pending_approvals.php"); // Replace with your redirect page
    exit();
}

header("Location: /esas/esas_moderator/public/departure_requests.php"); // Replace with your redirect page
exit();
?>
