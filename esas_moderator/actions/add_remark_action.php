<?php
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $application_id = isset($_POST['application_id']) ? htmlspecialchars($_POST['application_id']) : '';
    $remark = isset($_POST['remark']) ? htmlspecialchars($_POST['remark']) : '';
    $action = isset($_POST['action']) ? htmlspecialchars($_POST['action']) : '';

    // Validate the inputs
    if (!empty($application_id) && !empty($remark)) {
        // Prepare the SQL statement
        $sql = "UPDATE tbl_application SET remark = ?, status = ?, dateModified = NOW() WHERE application_id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("ssi", $remark, $action, $application_id);
            
            // Execute the statement
            if ($stmt->execute()) {
                // Success message
                echo "<script>alert('Remark added successfully!'); window.location.href='your_redirect_page.php';</script>";
            } else {
                // Error message
                echo "<script>alert('Error adding remark. Please try again later.'); window.history.back();</script>";
            }
            // Close statement
            $stmt->close();
        } else {
            // Error preparing the statement
            echo "<script>alert('Database error. Please try again later.'); window.history.back();</script>";
        }
    } else {
        // Error message for empty fields
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
    }
} else {
    // Redirect if the form is not submitted correctly
    header("Location: your_redirect_page.php");
    exit();
}

// Close database connection
$conn->close();
?>
