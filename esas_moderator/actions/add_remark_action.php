<?php
require_once "../../config.php";
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $application_id = isset($_POST['application_id']) ? htmlspecialchars($_POST['application_id']) : '';
    $remark = isset($_POST['remark']) ? htmlspecialchars($_POST['remark']) : '';

    if (!empty($application_id) && !empty($remark)) {
        $sql = "UPDATE tbl_application SET remark = ?, dateModified = NOW() WHERE application_id = ?";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind values to the parameters and execute
            if ($stmt->execute([$remark, $application_id])) {
                echo "success"; // Response text for AJAX
            } else {
                echo "Error: Could not execute the query. " . implode(", ", $stmt->errorInfo());
            }
        } else {
            echo "Error: Could not prepare the statement. " . implode(", ", $pdo->errorInfo());
        }
    } else {
        echo "Error: Missing application_id or remark.";
    }
} else {
    echo "Error: Invalid request method.";
}

// Ensure this is called after processing
header("Location: /esas/esas_moderator/public/pending_approvals.php");
exit();
?>
