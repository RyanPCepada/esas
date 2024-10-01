<?php
require_once "../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    // Check if request_id is set in the URL
    if (isset($_GET['request_id'])) {
        $request_id = $_GET['request_id'];

        // Prepare a SQL statement to fetch club details
        $stmt = $pdo->prepare('SELECT * FROM tbl_club_requests WHERE request_id = :request_id');
        $stmt->execute(['request_id' => $request_id]);
        $club = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($club) {
            // Display the club details
            echo "<h3>Request ID: {$club['request_id']}</h3>";
            echo "<p>Club Name: {$club['clubName']}</p>";
            echo "<p>Goal: {$club['goal']}</p>";
            echo "<p>Mission: {$club['mission']}</p>";
            echo "<p>Vision: {$club['vision']}</p>";
            echo "<p>Activities: {$club['activities']}</p>";
            echo "<p>Status: {$club['status']}</p>";
            echo "<p>Date Requested: {$club['dateRequested']}</p>";
            echo "<p>Date Approved: {$club['dateApproved']}</p>";
            echo "<p>Cover Photo: <img src='/esas/esas_student/images/{$club['coverPhoto']}' alt='Cover Photo' style='width:100px'></p>";
        } else {
            echo "<p>No club found for the given Request ID.</p>";
        }
    } else {
        echo "<p>No Request ID provided.</p>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
