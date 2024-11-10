<?php 
session_start();

// Ensure the moderator ID is set in the session
if (isset($_SESSION['admin_id'])) {
    $adminId = $_SESSION['admin_id'];
} else {
    echo json_encode(['error' => 'Admin not logged in.']);
    exit;
}

// Process delete operation after confirmation
// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (isset($_POST["club_id"]) && !empty($_POST["club_id"])) {
    // Include config file
    require_once "../../../../config.php";

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Fetch the club name before deletion
        $sql_select = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->bindParam(":club_id", $param_club_id);
        $param_club_id = trim($_POST["club_id"]);
        $stmt_select->execute();
        $club = $stmt_select->fetch();
        $club_name = $club['clubName'];

        // Prepare a delete statement for the clubs table
        $sql = "DELETE FROM tbl_clubs WHERE club_id = :club_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":club_id", $param_club_id);
        $stmt->execute();

        // Prepare a delete statement for the clubs_and_moderators table
        $sql2 = "DELETE FROM tbl_clubs_and_moderators WHERE club_id = :club_id";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindParam(":club_id", $param_club_id);
        $stmt2->execute();

        // Log the activity into tbl_activity_logs
        $sql_log = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, student_id) 
                    VALUES (:activity, :dateAdded, :admin_id, :student_id)";
        $stmt_log = $pdo->prepare($sql_log);
        $activity_msg = "You deleted " . $club_name . " from the clubs list";
        $stmt_log->bindParam(":activity", $activity_msg);
        $stmt_log->bindParam(":dateAdded", $dateAdded);
        $stmt_log->bindParam(":admin_id", $adminId);  // Use the correctly assigned variable
        $stmt_log->bindParam(":student_id", $student_id); // Assuming you have the student ID or set to NULL

        // Get the current date and time
        $dateAdded = date('Y-m-d H:i:s');
        $student_id = NULL; // Set NULL if not applicable

        $stmt_log->execute();

        // Commit the transaction
        $pdo->commit();

        // Redirect to landing page
        header("location: ../../all_clubs.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statements
    unset($stmt);
    unset($stmt2);
    unset($stmt_log);
    unset($stmt_select);

    // Close connection
    unset($pdo);
} else {
    // Check existence of id parameter
    if (empty(trim($_GET["club_id"]))) {
        // URL doesn't contain club_id parameter. Redirect to error page
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
    <title>ESAS - Delete Club</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/nbsclogo.png" rel="icon">
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
                    <h2 class="mt-5 mb-3">Delete Club</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="club_id" value="<?php echo trim($_GET["club_id"]); ?>"/>
                            <p>Are you sure you want to delete this club?</p>
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