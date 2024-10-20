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
        .history-delete-button, .history-clearall-button {
            width: 90px;
            height: auto;
            text-decoration: none;
            /* background-color: white; */
            color: black;
            border: solid 1px grey;
            border-radius: 50px;
            padding: 6px 16px;
            line-height: 1.5;
            display: inline-block;
            text-align: center;
            
        }

        .history-delete-button:hover, .history-clearall-button:hover {
            text-decoration: none;
            background-color: white;
            background-color: #36454F; /*Charcoal black*/
            /* color: white; */
            color: black;
            color: white;
            /* border: solid 1px white; */
            border: solid 1px grey;
        }

        .history-x-btn {
            margin-left: 10px;
            font-size: 18px;
            color: grey;
        }
        .history-x-btn:hover {
            color: darkgrey;
        }
    </style>
</head>
<body>
    
    <div class="wrapper">
        <form method="post" action="../esas_admin/actions/history_delete.php">
            <h2 class="mt-5">History</h2>
            <div class="d-flex justify-content-between mb-2">
                <p class="text-muted">All your interactions within the system</p>
                <div>
                    <button type="submit" name="delete" class="history-delete-button" 
                        onclick="
                            const checkboxes = document.querySelectorAll('input[type=checkbox]:checked'); 
                            if (checkboxes.length === 0) {
                                alert('No activities selected.');
                                return false;
                            } 
                            if (<?php echo empty($activities) ? 'true' : 'false'; ?>) { 
                                alert('No activities to delete.'); 
                                return false; 
                            } 
                            return confirm('Are you sure you want to delete selected activities?');
                        ">
                        Delete
                    </button>
                    <button type="submit" name="clear_all" class="history-clearall-button" 
                        onclick="if (<?php echo empty($activities) ? 'true' : 'false'; ?>) { alert('No activities to clear.'); return false; } return confirm('Are you sure you want to clear all activities?');">
                        Clear All
                    </button>
                </div>
            </div>
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
                                <a href="../esas_admin/actions/history_delete.php?id=<?php echo htmlspecialchars($activity['activity_id']); ?>" onclick="return confirm('Are you sure you want to delete this activity?');"
                                    style="text-decoration: none;"><i class="fas fa-times-circle history-x-btn"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center">No activities found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </form>

    </div>
</body>
</html>
