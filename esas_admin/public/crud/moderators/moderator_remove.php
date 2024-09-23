<?php
// Start the session
session_start();

// Include the configuration file
require_once '../../../../config.php';

// Check if both club_id and moderator_id are provided
if (isset($_POST['club_id']) && isset($_POST['moderator_id'])) {
    $club_id = $_POST['club_id'];
    $moderator_id = $_POST['moderator_id'];

    // Fetch the moderator's full name
    $stmt = $pdo->prepare("SELECT firstName, middleName, lastName FROM tbl_moderators WHERE moderator_id = ?");
    $stmt->execute([$moderator_id]);
    $moderator = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($moderator) {
        $moderator_name = trim($moderator['firstName'] . ' ' . $moderator['middleName'] . ' ' . $moderator['lastName']);
    } else {
        $moderator_name = "Unknown Moderator";
    }

    // Fetch the club's name
    $stmt = $pdo->prepare("SELECT clubName FROM tbl_clubs WHERE club_id = ?");
    $stmt->execute([$club_id]);
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($club) {
        $club_name = $club['clubName'];
    } else {
        $club_name = "Unknown Club";
    }
} else {
    // Missing club_id or moderator_id
    $_SESSION['error_message'] = "Invalid request. Club ID or Moderator ID is missing.";
    header("Location: moderator_update.php?moderator_id=" . urlencode($moderator_id));
    exit;
}

// If confirmation is received
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    try {
        // Prepare the SQL statement to remove the moderator from the specific club
        $stmt = $pdo->prepare("DELETE FROM tbl_clubs_and_moderators WHERE club_id = ? AND moderator_id = ?");
        $stmt->execute([$club_id, $moderator_id]);

        if ($stmt->rowCount() > 0) {
            // Successfully removed the moderator
            $_SESSION['success_message'] = "Moderator removed successfully from the club.";
        } else {
            // No rows affected, meaning the moderator wasn't assigned to this club
            $_SESSION['error_message'] = "Error: Moderator wasn't assigned to this club.";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Error removing moderator: " . $e->getMessage();
    }

    // Redirect back to the moderator update page
    header("Location: moderator_update.php?moderator_id=" . urlencode($moderator_id));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Removal</title>
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
                    <h2 class="mt-5 mb-3">Remove Moderator Confirmation</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($club_id); ?>"/>
                            <input type="hidden" name="moderator_id" value="<?php echo htmlspecialchars($moderator_id); ?>"/>
                            <p>Are you sure you want to remove <strong><?php echo htmlspecialchars($moderator_name); ?></strong> as a moderator of <strong><?php echo htmlspecialchars($club_name); ?></strong>?</p>
                            <p>
                                <input type="submit" name="confirm" value="Yes" class="btn btn-danger">
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
