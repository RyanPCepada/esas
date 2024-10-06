<?php
require_once "../../../config.php"; // Database config file
session_start();

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Student not logged in.']);
    exit();
}

// Retrieve student_id from the session
$student_id = $_SESSION['student_id'];

// Get data from the request
$club_id = isset($_POST['club_id']) ? $_POST['club_id'] : null;
$reason = isset($_POST['reason']) ? $_POST['reason'] : null;

if ($club_id === null || $reason === null) {
    echo json_encode(['success' => false, 'message' => 'Club ID and reason are required.']);
    exit();
}

try {
    global $pdo;

    // Update the departure request
    $sql = "UPDATE tbl_departure_requests SET reason = :reason WHERE student_id = :student_id AND club_id = :club_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":reason", $reason);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Departure request updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update departure request.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
