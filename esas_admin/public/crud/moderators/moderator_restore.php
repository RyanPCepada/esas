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
    <style>
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }

        .restore-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 15px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .restore-btn:hover {
            background-color: #218838;
        }

        .moderator-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            margin-bottom: 15px;
        }

        .moderator-name {
            font-size: 16px;
            font-weight: 600;
        }

        .row-separator {
            margin-top: 40px;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function confirmRestore() {
            return confirm('Are you sure you want to restore this moderator?');
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <h2 class="mt-5">Restore Moderator</h2>
        <p class="text-muted">Please select previous moderator(s) to restore.</p>

        <!-- Display moderators in a container with restore button -->
        <div class="restore-table">
            <form action="moderator_restore.php" method="POST" onsubmit="return confirmRestore();">
                <?php foreach ($moderators as $moderator): ?>
                    <div class="moderator-card">
                        <span class="moderator-name"><?= htmlspecialchars($moderator['firstName'] . ' ' . $moderator['middleName'] . ' ' . $moderator['lastName']); ?></span>
                        <button type="submit" class="btn restore-btn text-light" name="restore[]" value="<?= htmlspecialchars($moderator['archive_id']); ?>">Restore</button>
                    </div>
                <?php endforeach; ?>
            </form>
        </div>

        <!-- Option to go back -->
        <div class="text-start">
            <a href="../../moderators.php" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</body>
</html>
