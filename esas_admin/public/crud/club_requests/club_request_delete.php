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

// Check if request_id is set
if (isset($_POST["request_id"]) && !empty($_POST["request_id"])) {
    // Include config file
    require_once "../../../../config.php";

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // First, fetch the student_id and student name from tbl_club_requests and tbl_students
        $request_id = trim($_POST["request_id"]);
        
        // Fetch the student_id and student details from tbl_club_requests
        $fetchStudentSQL = "
            SELECT r.student_id, s.firstName, s.middleName, s.lastName 
            FROM tbl_club_requests AS r
            JOIN tbl_students AS s ON r.student_id = s.student_id 
            WHERE r.request_id = :request_id
        ";
        $fetchStudentStmt = $pdo->prepare($fetchStudentSQL);
        $fetchStudentStmt->bindParam(":request_id", $request_id);
        $fetchStudentStmt->execute();
        $studentData = $fetchStudentStmt->fetch(PDO::FETCH_ASSOC);

        // Ensure we have the student details
        if ($studentData) {
            $student_id = $studentData['student_id'];
            $studentName = $studentData['firstName'] . ' ' . $studentData['middleName'] . ' ' . $studentData['lastName'];

            // Now, delete the request from tbl_club_requests
            $sql = "DELETE FROM tbl_club_requests WHERE request_id = :request_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":request_id", $request_id);
            $stmt->execute();

            // Log the activity in tbl_activity_logs
            $activity = "You deleted {$studentName}'s club request";
            $logSQL = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, student_id) VALUES (:activity, NOW(), :admin_id, :student_id)";
            $logStmt = $pdo->prepare($logSQL);
            $logStmt->bindParam(":activity", $activity);
            $logStmt->bindParam(":admin_id", $adminId);
            $logStmt->bindParam(":student_id", $student_id);
            $logStmt->execute();

            // Commit the transaction
            $pdo->commit();

            // Redirect to the club requests page
            header("location: ../../club_requests.php");
            exit();
        } else {
            // No student found for the request_id
            echo "Student not found for the club request.";
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement and connection
    unset($stmt);
    unset($pdo);
} else {
    // Check existence of request_id parameter
    if (empty(trim($_GET["request_id"]))) {
        // URL doesn't contain request_id parameter. Redirect to error page
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
    <title>eSAS - Delete Club Request</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                    <h2 class="mt-5 mb-3">Delete Club Request</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="request_id" value="<?php echo trim($_GET["request_id"]); ?>"/>
                            <p>Are you sure you want to delete this club request?</p>
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
