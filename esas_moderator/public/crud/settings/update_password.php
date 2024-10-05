<?php
require_once "../../../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Fetch moderator data
$sql = "SELECT * FROM tbl_moderators WHERE moderator_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$moderator = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$moderator) {
    die("Moderator not found.");
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form inputs
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Verify current password
    if (!password_verify($currentPassword, $moderator['password'])) {
        echo "Current password is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        echo "New password and confirmation do not match.";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update password in the database
        $updateSql = "UPDATE tbl_moderators SET password = ? WHERE moderator_id = ?";
        $updateStmt = $pdo->prepare($updateSql);

        if ($updateStmt->execute([$hashedPassword, $moderator_id])) {
            echo "Password updated successfully!";
            header("Location: ../../../settings.php");
            exit();
        } else {
            // Get detailed error info for debugging
            $errorInfo = $updateStmt->errorInfo();
            echo "Error updating password: " . $errorInfo[2]; // More detailed error message
        }
    }
}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
