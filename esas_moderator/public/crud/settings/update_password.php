<?php 
require_once "../../../../config.php";
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

$sql = "SELECT * FROM tbl_moderators WHERE moderator_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$moderator = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$moderator) {
    die("Moderator not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $response = [];

    // Step 1: Verify current password
    if ($currentPassword !== $moderator['password']) {
        $response['error'] = "Current password is incorrect.";
    }
    // Step 2: Check if new password matches confirmation password
    elseif ($newPassword !== $confirmPassword) {
        $response['error'] = "New password and confirmation do not match.";
    }
    // Step 3: Check if new password meets length and composition requirements
    elseif (strlen($newPassword) < 8 || !preg_match('/[A-Za-z]/', $newPassword) || !preg_match('/\d/', $newPassword)) {
        $response['error'] = "New password must be at least 8 characters long and contain both letters and numbers.";
    } else {
        // Update password in database
        $updateSql = "UPDATE tbl_moderators SET password = ? WHERE moderator_id = ?";
        $updateStmt = $pdo->prepare($updateSql);

        if ($updateStmt->execute([$newPassword, $moderator_id])) {
            // Log the activity
            $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([
                'activity' => 'You changed your password',
                'dateAdded' => date('Y-m-d H:i:s'),
                'moderator_id' => $moderator_id
            ]);
            $response['success'] = "Password updated successfully!";
        } else {
            $response['error'] = "Error updating password: " . implode(", ", $updateStmt->errorInfo());
        }
    }
    echo json_encode($response);
    exit;
}
?>

<h4 class="text-muted mb-3">Update Password</h4>
<form id="updatePasswordForm">
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
    $('#updatePasswordForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '../esas_moderator/public/crud/settings/update_password.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.success);
                } else if (response.error) {
                    alert(response.error);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>
