<?php
// Process delete operation after confirmation
if (isset($_POST["moderator_id"]) && !empty($_POST["moderator_id"])) {
    // Include config file
    require_once "../../../../config.php";

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Prepare a delete statement for the moderators table
        $sql = "DELETE FROM tbl_moderators WHERE moderator_id = :moderator_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":moderator_id", $param_moderator_id);
        $param_moderator_id = trim($_POST["moderator_id"]);
        $stmt->execute();

        // Optionally, delete from any related tables if necessary
        // For example, removing associations in clubs_and_moderators
        $sql2 = "DELETE FROM tbl_clubs_and_moderators WHERE moderator_id = :moderator_id";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindParam(":moderator_id", $param_moderator_id);
        $stmt2->execute();

        // Commit the transaction
        $pdo->commit();

        // Redirect to landing page
        header("location: ../../moderators.php");
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
    <title>eSAS - Delete Moderator</title>
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
                    <h2 class="mt-5 mb-3">Delete Moderator</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="moderator_id" value="<?php echo trim($_GET["moderator_id"]); ?>"/>
                            <p>Are you sure you want to delete this moderator?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="javascript:window.history.back();" class="btn btn-secondary ml-2">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
