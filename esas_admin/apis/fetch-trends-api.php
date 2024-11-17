<?php
// Include the database connection configuration file
require_once "../../config.php";  // Ensure the path is correct based on your folder structure
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Prepare the query to fetch all club names from the tbl_clubs table
$query = "SELECT clubName FROM tbl_clubs";

try {
    // Create a PDO instance and prepare the query
    $stmt = $pdo->prepare($query);
    $stmt->execute(); // Execute the query

    // Fetch all results as an associative array
    $clubNames = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any clubs were fetched
    if ($clubNames) {
        // Set the content type to JSON and output the result
        header('Content-Type: application/json');
        echo json_encode($clubNames);
    } else {
        // No clubs found, output an empty array
        header('Content-Type: application/json');
        echo json_encode([]);
    }

} catch (PDOException $e) {
    // Handle any errors that occur during the execution of the query
    header('Content-Type: application/json');
    echo json_encode(["error" => "Database query failed: " . $e->getMessage()]);
}

// No need to manually close the PDO connection, as it will automatically close when the script ends.
?>
