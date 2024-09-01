<?php
// Include config file
require_once "../config.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $clubName = trim($_POST['clubName']);
    $description = trim($_POST['description']);
    $activities = trim($_POST['activities']);
    $status = 'Pending'; // Initial status for the request
    $student_id = $_SESSION['student_id'];
    $registration_id = $_SESSION['registration_id'];

    // Insert the request into the database
    $sql = "INSERT INTO tbl_club_requests (clubName, description, activities, status, dateRequested, student_id, registration_id) 
            VALUES (?, ?, ?, ?, NOW(), ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters: "ssssi" corresponds to the data types (string, string, string, string, integer, integer)
        $stmt->bind_param("ssssi", $clubName, $description, $activities, $status, $student_id, $registration_id);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Request submitted successfully! Please wait for the Admin approval of your request.');</script>";
        } else {
            echo "<script>alert('Error submitting your request. Please try again.');</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing the SQL statement.');</script>";
    }

    // Close the connection
    $conn->close();
}
?>