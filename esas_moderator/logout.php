<?php
// Initialize the session
session_start();
require_once '../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Function to get the club_id associated with the moderator
function getClubIdByModerator($pdo, $moderator_id) {
    $query = "SELECT club_id FROM tbl_clubs_and_moderators WHERE moderator_id = :moderator_id LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['moderator_id' => $moderator_id]);
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    return $club ? $club['club_id'] : null;
}

// Check if the moderator is logged in
if (isset($_SESSION['moderator_id'])) {
    // Get the club_id associated with the moderator
    $club_id = getClubIdByModerator($pdo, $_SESSION['moderator_id']);

    // Function to insert activity log
    function insertActivityLog($pdo, $moderator_id, $club_id) {
        $query = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id, club_id) VALUES (:activity, :dateAdded, :moderator_id, :club_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'activity' => 'You logged out of your account',
            'dateAdded' => date('Y-m-d H:i:s'), // current timestamp
            'moderator_id' => $moderator_id,
            'club_id' => $club_id
        ]);
    }

    // Insert the activity log for logout with the club_id
    insertActivityLog($pdo, $_SESSION['moderator_id'], $club_id);
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: ../esas_moderator/login.php");
exit;
?>
