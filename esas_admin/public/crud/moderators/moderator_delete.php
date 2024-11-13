<?php
session_start();

// Ensure the moderator ID is set in the session
if (isset($_SESSION['admin_id'])) {
    $adminId = $_SESSION['admin_id'];
} else {
    echo json_encode(['error' => 'Admin not logged in.']);
    exit;
}

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (isset($_POST["moderator_id"]) && !empty($_POST["moderator_id"])) {
    // Include config file
    require_once "../../../../config.php";

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // First, fetch the moderator's details from tbl_moderators
        $moderator_id = trim($_POST["moderator_id"]);

        // Fetch moderator's full name and club associations
        $fetchModeratorSQL = "
            SELECT m.firstName, m.middleName, m.lastName, c.clubName, cm.dateAdded AS dateAssigned
            FROM tbl_moderators m
            LEFT JOIN tbl_clubs_and_moderators cm ON m.moderator_id = cm.moderator_id
            LEFT JOIN tbl_clubs c ON cm.club_id = c.club_id
            WHERE m.moderator_id = :moderator_id
        ";
        $fetchModeratorStmt = $pdo->prepare($fetchModeratorSQL);
        $fetchModeratorStmt->bindParam(":moderator_id", $moderator_id);
        $fetchModeratorStmt->execute();
        $moderatorData = $fetchModeratorStmt->fetchAll(PDO::FETCH_ASSOC);

        // Ensure we have the moderator details
        if ($moderatorData) {
            // Loop through the clubs and insert the data into tbl_moderator_archive
            foreach ($moderatorData as $data) {
                $moderatorFullName = $data['firstName'] . ' ' . $data['middleName'] . ' ' . $data['lastName'];
                $clubName = $data['clubName'] ? $data['clubName'] : "None"; // Use "None" if no club is associated
                $dateAssigned = $data['dateAssigned'];
                $dateUnassigned = date("Y-m-d H:i:s"); // Date when moderator is unassigned

                // Insert into tbl_moderator_archive
                $archiveSQL = "
                    INSERT INTO tbl_moderator_archive (moderator_id, fullName, clubName, dateAssigned, dateUnassigned)
                    VALUES (:moderator_id, :fullName, :clubName, :dateAssigned, :dateUnassigned)
                ";
                $archiveStmt = $pdo->prepare($archiveSQL);
                $archiveStmt->bindParam(":moderator_id", $moderator_id); // Store moderator_id
                $archiveStmt->bindParam(":fullName", $moderatorFullName);
                $archiveStmt->bindParam(":clubName", $clubName); // Insert "None" if no club
                $archiveStmt->bindParam(":dateAssigned", $dateAssigned);
                $archiveStmt->bindParam(":dateUnassigned", $dateUnassigned);
                $archiveStmt->execute();
            }

            // Prepare a delete statement for the moderators table
            $sql = "DELETE FROM tbl_moderators WHERE moderator_id = :moderator_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":moderator_id", $moderator_id);
            $stmt->execute();

            // Optionally, delete from any related tables if necessary
            // For example, removing associations in clubs_and_moderators
            $sql2 = "DELETE FROM tbl_clubs_and_moderators WHERE moderator_id = :moderator_id";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->bindParam(":moderator_id", $moderator_id);
            $stmt2->execute();

            // Log the activity in tbl_activity_logs
            $activity = "You deleted {$moderatorFullName} from the moderator's list.";
            $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id) VALUES (:activity, NOW(), :admin_id, null)";
            $logStmt = $pdo->prepare($logSQL);
            $logStmt->bindParam(":activity", $activity);
            $logStmt->bindParam(":admin_id", $adminId);
            $logStmt->execute();

            // Commit the transaction
            $pdo->commit();

            // Redirect to the moderators page
            header("location: ../../moderators.php");
            exit();
        } else {
            // No moderator found for the moderator_id
            echo "Moderator not found.";
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statements and connection
    unset($stmt);
    unset($stmt2);
    unset($logStmt);
    unset($archiveStmt);
    unset($pdo);
} else {
    // Check existence of moderator_id parameter
    if (empty(trim($_GET["moderator_id"]))) {
        // URL doesn't contain moderator_id parameter. Redirect to error page
        header("location: ../public/error.php");
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Delete Moderator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/NBSC_LOGO.png" rel="icon">
    <style>
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Moderator</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="moderator_id" value="<?php echo trim($_GET["moderator_id"]); ?>"/>
                            <p>Are you sure you want to delete this moderator?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="javascript:window.history.back();" class="btn btn-secondary ml-1">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
