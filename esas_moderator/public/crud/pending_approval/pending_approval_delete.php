<?php
// Process delete operation after confirmation
// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (isset($_POST["student_id"]) && !empty($_POST["student_id"])) {
    // Include config file
    require_once "../../../../config.php";

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Prepare a delete statement for the students table
        $sql = "DELETE FROM tbl_students WHERE student_id = :student_id AND club_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":student_id", $param_student_id);
        $param_student_id = trim($_POST["student_id"]);
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Redirect to landing page
        header("location: ../../students.php"); // Redirecting to students page
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    unset($stmt);
    unset($stmt2);
    
    // Close connection
    unset($pdo);
} else {
    // Check existence of id parameter
    if (empty(trim($_GET["student_id"]))) {
        // URL doesn't contain student_id parameter. Redirect to error page
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
    <title>eSAS - Delete Pending Approval</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                    <h2 class="mt-5 mb-3">Delete Student</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="student_id" value="<?php echo trim($_GET["student_id"]); ?>"/>
                            <p>Are you sure you want to delete this student?</p>
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
