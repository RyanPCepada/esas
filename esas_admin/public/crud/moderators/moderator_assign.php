<?php
require_once "../../../../config.php";

// Define the default profile picture constant
define('PROF_PIC_DEFAULT', 'PROF_PIC.png'); // Change 'PROF_PIC.png' to your actual default image path if necessary

$moderatorQuery = "SELECT moderator_id, CONCAT(firstName, ' ', lastName) AS moderator_name FROM tbl_moderators";
if ($stmt = $pdo->prepare($moderatorQuery)) {
    if ($stmt->execute()) {
        $moderators = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$firstName = $middleInitial = $lastName = $email = $password = "";
$firstName_err = $middleInitial_err = $lastName_err = $email_err = $password_err = "";
$clubs = [];

// Fetch clubs for selection
$clubQuery = "SELECT club_id, clubName FROM tbl_clubs";
if ($stmt = $pdo->prepare($clubQuery)) {
    if ($stmt->execute()) {
        $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["moderator"]) && !empty($_POST["club"])) {
        $selectedModeratorId = $_POST["moderator"];
        $selectedClubId = $_POST["club"];

        // Insert into tbl_clubs_and_moderators
        $sql = "INSERT INTO tbl_clubs_and_moderators (club_id, moderator_id, dateAdded) 
                VALUES (:clubId, :moderatorId, NOW())";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":clubId", $selectedClubId);
            $stmt->bindParam(":moderatorId", $selectedModeratorId);
            if ($stmt->execute()) {
                header("location: ../../moderators.php"); // Redirect to a list of moderators
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    } else {
        echo "Please select both a moderator and a club.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Add Moderator</title>
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
                <h2 class="mt-5">Assign Moderator</h2>
                <p>Please fill this form to assign a new moderator.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group mb-2">
                        <label>Select a Moderator</label>
                        <select name="moderator" id="moderatorSelect" class="form-control">
                            <option value="">-- Select From Existing Moderators --</option>
                            <optgroup label="">
                                <?php foreach ($moderators as $moderator): ?>
                                    <option value="<?php echo htmlspecialchars($moderator['moderator_id']); ?>">
                                        <?php echo htmlspecialchars($moderator['moderator_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label>Assign to a Club</label>
                        <select name="club" class="form-control">
                            <option value="">-- Select From Existing Clubs --</option>
                            <?php foreach ($clubs as $club): ?>
                                <option value="<?php echo htmlspecialchars($club['club_id']); ?>">
                                    <?php echo htmlspecialchars($club['clubName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="../../moderators.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
