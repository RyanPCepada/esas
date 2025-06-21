<?php
require_once '../../config.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Check if club_id is provided in the URL
if (isset($_GET['club_id'])) {
    $club_id = intval($_GET['club_id']); // Ensure club_id is an integer

    try {
        // Prepare the SQL query to fetch officers based on club_id
        $sql = "
        SELECT 
            o.officer_id,
            CONCAT(s.firstName, ' ', LEFT(s.middleName, 1), '. ', s.lastName) AS fullName, -- Only the first letter of middle name followed by a period
            s.profilePic,
            o.position
        FROM 
            tbl_club_officers o
        JOIN 
            tbl_students s ON s.student_id = o.student_id
        WHERE 
            o.club_id = :club_id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['club_id' => $club_id]);
        $officers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($officers) {
            echo json_encode(['success' => true, 'officers' => $officers]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No officers found for this club.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Club ID is required.']);
}
?>
