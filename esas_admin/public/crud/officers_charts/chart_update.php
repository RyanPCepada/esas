<?php
// Start the session
session_start();

// Include the configuration file
require_once '../../../../config.php';

// Ensure the moderator ID is set in the session
if (isset($_SESSION['admin_id'])) {
    $adminId = $_SESSION['admin_id'];
} else {
    echo json_encode(['error' => 'Admin not logged in.']);
    exit;
}

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Initialize variables to hold errors
$chart_err = "";
$chart = "";
$chart_id = ""; // Store the chart_id received from the form
$uploadPath = ""; // Path where the new image will be uploaded

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve chart ID from hidden input
    $chart_id = trim($_POST["chart_id"]);
    
    // Handle new chart image upload
    if (isset($_FILES['chart_image']) && $_FILES['chart_image']['name']) {
        $chartImageName = $_FILES['chart_image']['name'];
        $chartImageSize = $_FILES['chart_image']['size'];
        $chartImageTmpName = $_FILES['chart_image']['tmp_name'];

        $validImageExtensions = ['jpg', 'jpeg', 'png'];
        $imageExtension = strtolower(pathinfo($chartImageName, PATHINFO_EXTENSION));

        if (!in_array($imageExtension, $validImageExtensions)) {
            $chart_err = "Invalid image extension. Only JPG, JPEG, and PNG are allowed.";
        } elseif ($chartImageSize > 10000000) {
            $chart_err = "Image size is too large (max 10MB).";
        } else {
            $newChartImageName = 'chart_' . uniqid() . '.' . $imageExtension;
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_admin/images/';
            $uploadPath = $uploadDir . $newChartImageName;

            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Move the uploaded file to the target directory
            if (!move_uploaded_file($chartImageTmpName, $uploadPath)) {
                $chart_err = "Failed to upload image.";
            } else {
                $chart = $newChartImageName; // Store the new chart image name for database update
            }
        }
    }

    // Update the chart in the database if no errors
    if (empty($chart_err)) {
        $sql = "UPDATE tbl_officers_charts SET chart = :chart, dateModified = NOW() WHERE chart_id = :chartId";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":chart", $chart);
            $stmt->bindParam(":chartId", $chart_id);
            
            if ($stmt->execute()) {
                // Determine the organization type for the activity log
                $sql = "SELECT organizationType FROM tbl_officers_charts WHERE chart_id = :chartId";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":chartId", $chart_id);
                $stmt->execute();
                $organizationType = $stmt->fetchColumn();

                // Determine the correct organization type for the message
                if ($organizationType === 'SBO') {
                    $activityMessage = "You updated the SBO Officers chart";
                } elseif ($organizationType === 'CSG') {
                    $activityMessage = "You updated the CSG Officers chart";
                } else {
                    $activityMessage = "You updated the Officers chart";
                }

                // Activity logging
                $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id) VALUES (:activity, NOW(), :adminId)";
                if ($logStmt = $pdo->prepare($logSql)) {
                    $logStmt->bindParam(":activity", $activityMessage);
                    $logStmt->bindParam(":adminId", $adminId);
                    $logStmt->execute(); // Execute the log insert
                }
                
                $_SESSION['success_message'] = "Chart updated successfully!";
                header("location: ../../officers_charts.php");
                exit;
            } else {
                $_SESSION['error_message'] = "Something went wrong. Please try again.";
            }
        } else {
            $_SESSION['error_message'] = "Something went wrong with the SQL statement.";
        }
    } else {
        $_SESSION['error_message'] = $chart_err; // Store the error message to display later
    }
}

// Close the database connection
unset($pdo);
?>
