<?php
require_once "../../../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id']; // Get moderator ID from session

// Fetch moderator data
$sql = "SELECT * FROM tbl_moderators WHERE moderator_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$moderator = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array

if (!$moderator) {
    die("Moderator not found.");
}

// Process form submission via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $response = []; // Initialize response array

    // Verify current password
    if ($currentPassword !== $moderator['password']) { // Compare directly without hashing
        $response['error'] = "Current password is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        $response['error'] = "New password and confirmation do not match.";
    } else {
        // Update password in the database directly
        $updateSql = "UPDATE tbl_moderators SET password = ? WHERE moderator_id = ?";
        $updateStmt = $pdo->prepare($updateSql);

        if ($updateStmt->execute([$newPassword, $moderator_id])) { // No hashing here
            $response['success'] = "Password updated successfully!";
        } else {
            // Get error information for debugging
            $response['error'] = "Error updating password: " . implode(", ", $updateStmt->errorInfo());
        }
    }
    echo json_encode($response); // Send response back to AJAX
    exit; // Prevent further execution
}
?>

<h4 class="text-muted">Update Password</h4>
<form class="mt-3" id="updatePasswordForm">
    <!-- <div id="responseMessage" class="mb-3"></div> -->
    <div class="form-group">
        <label for="currentPassword">Current Password</label>
        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
    </div>
    <div class="form-group">
        <label for="newPassword">New Password</label>
        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
    </div>
    <div class="form-group">
        <label for="confirmPassword">Confirm New Password</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Password</button>
</form>

<script>
$(document).ready(function() {
    // Handle form submission
    $('#updatePasswordForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        $.ajax({
            url: '../esas_moderator/public/crud/settings/update_password.php', // Current script to handle AJAX request
            type: 'POST',
            data: $(this).serialize(), // Serialize form data
            dataType: 'json',
            success: function(response) {
                // Clear previous messages
                if (response.success) {
                    alert(response.success); // Display success message
                } else if (response.error) {
                    alert(response.error); // Display error message
                }
            },
            error: function() {
                alert('An error occurred. Please try again.'); // General error alert
            }
        });
    });
});
</script>

