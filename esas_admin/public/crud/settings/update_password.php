<?php 
require_once "../../../../config.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    die("You are not logged in.");
}

$admin_id = $_SESSION['admin_id'];

$sql = "SELECT * FROM tbl_admin WHERE admin_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die("Admin not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $response = [];

    // Step 1: Verify current password using password_verify
    if (!password_verify($currentPassword, $admin['password'])) {
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
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password in database
        $updateSql = "UPDATE tbl_admin SET password = ? WHERE admin_id = ?";
        $updateStmt = $pdo->prepare($updateSql);

        if ($updateStmt->execute([$hashedPassword, $admin_id])) {
            // Log the activity
            $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id) VALUES (:activity, :dateAdded, :admin_id)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([
                'activity' => 'You changed your password',
                'dateAdded' => date('Y-m-d H:i:s'),
                'admin_id' => $admin_id
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
            url: '../esas_admin/public/crud/settings/update_password.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.success);
                    // Clear input fields after successful update
                    $('#currentPassword').val('');
                    $('#newPassword').val('');
                    $('#confirmPassword').val('');
                    $('.btn').blur();
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
