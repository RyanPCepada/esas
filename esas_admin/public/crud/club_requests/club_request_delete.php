<?php
// Process delete operation after confirmation
// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (isset($_POST["request_id"]) && !empty($_POST["request_id"])) {
    // Include config file
    require_once "../../../../config.php";

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Prepare a delete statement for the club_requests table
        $sql = "DELETE FROM tbl_club_requests WHERE request_id = :request_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":request_id", $param_request_id);
        $param_request_id = trim($_POST["request_id"]);
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Redirect to landing page
        header("location: ../../club_requests.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    unset($stmt);
    
    // Close connection
    unset($pdo);
} else {
    // Check existence of id parameter
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
