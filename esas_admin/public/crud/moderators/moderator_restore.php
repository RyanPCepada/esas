<?php
session_start();
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['admin_id'])) {
    echo "Admin ID is not set in the session.";
    exit;
}

$admin_id = $_SESSION['admin_id']; // Get admin ID from session

// Fetch moderators from tbl_moderator_archive who are not in tbl_moderators
$stmt = $pdo->prepare("
    SELECT a.* 
    FROM tbl_moderator_archive a
    LEFT JOIN tbl_moderators m ON a.moderator_id = m.moderator_id
    WHERE m.moderator_id IS NULL
    GROUP BY moderator_id
");
$stmt->execute();
$moderators = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the restore form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['restore'])) {
    try {
        // Loop through the selected moderators and restore them
        foreach ($_POST['restore'] as $archive_id) {
            // Fetch moderator details from tbl_moderator_archive
            $stmt = $pdo->prepare("SELECT * FROM tbl_moderator_archive WHERE archive_id = ?");
            $stmt->execute([$archive_id]);
            $moderator = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($moderator) {
                // Restore the moderator to tbl_moderators with a default password and profile picture
                $password = 'password123';
                $profilePic = 'PROF_PIC.png'; // Default profile picture

                $stmt = $pdo->prepare("INSERT INTO tbl_moderators (moderator_id, firstName, middleName, lastName, password, profilePic) 
                                       VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $moderator['moderator_id'],
                    $moderator['firstName'],
                    $moderator['middleName'],
                    $moderator['lastName'],
                    password_hash($password, PASSWORD_DEFAULT), // Hash the password for security
                    $profilePic // Use default profile picture
                ]);

                // Optionally, you can log the restoration action here
                $_SESSION['success_message'] = "Moderator restored successfully.";
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error restoring moderator: " . $e->getMessage();
    }

    // Redirect back to the restore page
    header("Location: moderator_restore.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Restore Moderator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/NBSC_LOGO.png" rel="icon">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-5">Restore Moderator</h2>
            <p>Please select previous moderator(s) to restore.</p>

            <form action="moderator_restore.php" method="POST">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($moderators as $moderator): ?>
                                <tr>
                                    <td><?= $moderator['firstName'] . ' ' . $moderator['middleName'] . ' ' . $moderator['lastName']; ?></td>
                                    <td>
                                        <!-- Restore button for each moderator -->
                                        <button type="submit" class="btn btn-success" name="restore[]" value="<?= $moderator['archive_id']; ?>">Restore</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html> 
