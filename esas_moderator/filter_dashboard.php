<?php
session_start();
require_once "../config.php";  // Assuming this file holds your PDO connection

if (!isset($_SESSION['moderator_id'])) {
    echo "Moderator ID is not set in the session.";
    exit;
}

$moderator_id = $_SESSION['moderator_id']; // Get moderator ID from session


// Retrieve school year and moderator ID from query parameters
$schoolYear = isset($_GET['schoolYear']) ? $_GET['schoolYear'] : '';
$moderator_id = isset($_GET['moderator_id']) ? $_GET['moderator_id'] : ''; // Assuming moderator_id is passed

try {
    // Validate the school year format (e.g., YYYY-YYYY)
    if (!preg_match('/^\d{4}-\d{4}$/', $schoolYear)) {
        throw new Exception('Invalid school year format');
    }

    // Prepare and execute the SQL queries with the filtered school year

    // Total clubs
    $stmt_clubs = $pdo->prepare("
        SELECT COUNT(DISTINCT tbl_clubs.club_id) AS total_clubs 
        FROM tbl_clubs_and_moderators
        JOIN tbl_clubs ON tbl_clubs.club_id = tbl_clubs_and_moderators.club_id
        WHERE tbl_clubs_and_moderators.moderator_id = :moderator_id
        AND DATE_FORMAT(tbl_clubs.dateAdded, '%Y-%Y') = :schoolYear
    ");
    $stmt_clubs->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $stmt_clubs->bindParam(':schoolYear', $schoolYear, PDO::PARAM_STR);
    $stmt_clubs->execute();
    $total_clubs = $stmt_clubs->fetchColumn();

    // Total students
    $stmt_students = $pdo->prepare("
        SELECT COUNT(DISTINCT tr.student_id) AS total_students 
        FROM tbl_registration tr
        JOIN tbl_clubs tc ON tr.club_id = tc.club_id 
        JOIN tbl_moderators tm ON tc.club_id = tm.club_id 
        WHERE tm.moderator_id = :moderator_id AND tr.status = 'active'
        AND DATE_FORMAT(tc.dateAdded, '%Y-%Y') = :schoolYear
    ");
    $stmt_students->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $stmt_students->bindParam(':schoolYear', $schoolYear, PDO::PARAM_STR);
    $stmt_students->execute();
    $total_students = $stmt_students->fetchColumn();

    // Total pending
    $stmt_pending = $pdo->prepare("
        SELECT COUNT(tr.student_id) AS total_pending 
        FROM tbl_registration tr
        JOIN tbl_clubs tc ON tr.club_id = tc.club_id
        JOIN tbl_moderators tm ON tc.club_id = tm.club_id
        WHERE tr.status = 'pending' AND tm.moderator_id = :moderator_id
        AND DATE_FORMAT(tc.dateAdded, '%Y-%Y') = :schoolYear
    ");
    $stmt_pending->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
    $stmt_pending->bindParam(':schoolYear', $schoolYear, PDO::PARAM_STR);
    $stmt_pending->execute();
    $total_pending = $stmt_pending->fetchColumn();

    // Leave requests (assuming no filtering by school year)
    $leave_requests = 0; // Update with your actual query if needed

    // Return the results as JSON
    echo json_encode([
        'total_clubs' => $total_clubs,
        'total_students' => $total_students,
        'total_pending' => $total_pending,
        'leave_requests' => $leave_requests
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
