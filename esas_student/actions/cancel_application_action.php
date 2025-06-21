<?php
// Include config file
require_once "../../config.php";  // This already creates a PDO instance

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Start the session
session_start();

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Student not logged in.'); window.history.back();</script>";
    exit();
}

// Retrieve student_id from the session
$student_id = $_SESSION['student_id'];
$fromPendingPage = $_GET['from_pending_page'] ?? '';

// Check if application_id and club_id are provided in the request (GET or POST method)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['application_id']) && isset($_POST['club_id'])) {
    $application_id = $_POST['application_id'];
    $club_id = $_POST['club_id'];
    $fromPendingPage = $_POST['fromPendingPage'] ?? '';
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['application_id']) && isset($_GET['club_id'])) {
    $application_id = $_GET['application_id'];
    $club_id = $_GET['club_id'];
    $fromPendingPage = $_GET['fromPendingPage'] ?? '';
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
    exit();
}

try {
    // Use the PDO instance created in config.php
    global $pdo;

    // Prepare the SQL statement to update the status
    $sql = "UPDATE tbl_application SET status = 'cancelled', dateModified = NOW() WHERE application_id = :application_id AND student_id = :student_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':application_id', $application_id, PDO::PARAM_INT);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        // Determine the redirect URL based on fromPendingPage
        if (!empty($fromPendingPage) && $fromPendingPage === 'yes') {
            echo "<script>
                alert('Application has been cancelled successfully.');
                window.location.href = '/esas/esas_student/my_clubs.php?club_id=" . urlencode($club_id) . "&application_id=" . urlencode($application_id) . "';
            </script>";
        } else {
            echo "<script>
                alert('Application has been cancelled successfully.');
                window.location.href = '/esas/esas_student/club_info.php?club_id=" . urlencode($club_id) . "&application_id=" . urlencode($application_id) . "';
            </script>";
        }        
    } else {
        echo "<script>alert('Failed to cancel application. Please try again.'); window.history.back();</script>";
    }
} catch (PDOException $e) {
    echo "<script>alert('Database error: " . $e->getMessage() . "'); window.history.back();</script>";
}

// Close the database connection
$pdo = null;

?>
