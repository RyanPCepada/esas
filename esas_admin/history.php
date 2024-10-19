<?php
require_once "../config.php"; // Database config file
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo "Moderator ID is not set in the session.";
    exit;
}

$admin_id = $_SESSION['admin_id']; // Get admin ID from session

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Fetch admin's data
$sql = "SELECT * FROM tbl_admin WHERE admin_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array

// Check if the admin was found
if (!$admin) {
    echo "Moderator not found.";
    exit; // Exit if the admin is not found
}

// Fetch activities of the current admin
$sql_activities = "SELECT * FROM tbl_activity_logs WHERE admin_id = ? ORDER BY dateAdded DESC";
$stmt_activities = $pdo->prepare($sql_activities);
$stmt_activities->execute([$admin_id]);
$activities = $stmt_activities->fetchAll(PDO::FETCH_ASSOC); // Fetch all activities

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Admin History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            max-width: 700px; /* Increased to occupy more space */
            margin: 0 auto;
            padding: 15px;
        }
        .container {
            background-color: white;
        }
        .main-content {
            padding: 0; /* Remove padding to occupy more space */
        }
        .activity-card {
            margin-bottom: 10px; /* Spacing between cards */
            padding: 10px 15px; /* Smaller padding for the card */
            border-radius: 5px; /* Rounded corners */
            background-color: #ffffff; /* White background */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            display: flex;
            justify-content: space-between;
            align-items: flex-start; /* Align items at the start */
        }
        .activity-card input[type="checkbox"] {
            margin-right: 10px;
            align-self: flex-start; /* Align checkbox at the top */
        }
        .activity-content {
            flex-grow: 1; /* Allow content to grow */
        }
        .activity-title {
            font-weight: bold;
        }
        .activity-date {
            font-size: 0.85rem;
            color: #6c757d; /* Muted text color */
        }
        .history-delete-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: darkgrey;
            margin-left: 10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }
        .history-delete-btn:hover {
            background-color: grey;
        }
    </style>
</head>
<body>
    
    <div class="wrapper">
        <h2 class="mt-5">History</h2>
        <p class="text-muted">All your interactions within the system</p>

        <form method="post" action="">
            <div class="container-fluid container auto-scroll">
                <div class="row">
                    <!-- Activity cards -->
                    <?php if ($activities): ?>
                        <?php foreach ($activities as $activity): ?>
                            <div class="activity-card">
                                <input class="mt-1" type="checkbox" name="activity_ids[]" value="<?php echo htmlspecialchars($activity['activity_id']); ?>">
                                <div class="activity-content">
                                    <div class="activity-title"><?php echo htmlspecialchars($activity['activity']); ?></div>
                                    <div class="activity-date"><?php echo htmlspecialchars(date('F j, Y | g:i A', strtotime($activity['dateAdded']))); ?></div>
                                </div>
                                <div class="history-delete-btn">
                                    <a href="delete_activity.php?id=<?php echo htmlspecialchars($activity['activity_id']); ?>" onclick="return confirm('Are you sure you want to delete this activity?');"
                                    style="color: white; text-decoration: none;">x</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center">No activities found.</div>
                    <?php endif; ?>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" name="delete" class="btn btn-danger">Delete Selected</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
