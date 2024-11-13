<?php
session_start();
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['admin_id'])) {
    echo "Admin ID is not set in the session.";
    exit;
}

$admin_id = $_SESSION['admin_id']; // Get admin ID from session

// Define the default profile picture constant
define('PROF_PIC_DEFAULT', 'PROF_PIC.png'); 

$firstName = $middleName = $lastName = $password = "";
$firstName_err = $middleName_err = $lastName_err = $password_err = "";
$clubs = [];

// Fetch clubs for selection
$clubQuery = "SELECT club_id, clubName FROM tbl_clubs";
if ($stmt = $pdo->prepare($clubQuery)) {
    if ($stmt->execute()) {
        $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Function to get the school year code '2425' for the 2024-2025 school year
function getSchoolYearCode() {
    $currentYear = date('Y');
    $nextYear = $currentYear + 1; // School year spans two years
    return substr($currentYear, 2, 2) . substr($nextYear, 2, 2); // Returns '2425' for 2024-2025
}

// Function to get the next available 4-digit increment for the moderator ID
function getNextModeratorIncrement($pdo, $schoolYearCode) {
    $sql = "SELECT moderator_id FROM tbl_moderators WHERE moderator_id LIKE :schoolYearCode ORDER BY moderator_id DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $likePattern = $schoolYearCode . '%';
    $stmt->bindParam(':schoolYearCode', $likePattern);
    $stmt->execute();
    
    $lastModeratorId = $stmt->fetchColumn();
    
    if ($lastModeratorId) {
        $lastIncrement = (int)substr($lastModeratorId, 4, 4);
        $nextIncrement = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT); 
    } else {
        $nextIncrement = '0001';
    }
    
    return $nextIncrement;
}

// Function to check if the generated moderator ID already exists
function checkModeratorIdExistence($pdo, $moderator_id) {
    // Check in tbl_moderators
    $sql = "SELECT COUNT(*) FROM tbl_moderators WHERE moderator_id = :moderator_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':moderator_id', $moderator_id);
    $stmt->execute();
    $moderatorExists = $stmt->fetchColumn() > 0;

    // Check in tbl_moderator_archive
    if (!$moderatorExists) {
        $sql = "SELECT COUNT(*) FROM tbl_moderator_archive WHERE moderator_id = :moderator_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':moderator_id', $moderator_id);
        $stmt->execute();
        $moderatorExists = $stmt->fetchColumn() > 0;
    }

    return $moderatorExists;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_firstName = trim($_POST["firstName"]);
    if (empty($input_firstName)) {
        $firstName_err = "Please enter the first name.";
    } else {
        $firstName = $input_firstName;
    }

    $input_middleName = trim($_POST["middleName"]);
    if (empty($input_middleName)) {
        $middleName_err = "Please enter the middle name.";
    } else {
        $middleName = $input_middleName;
    }

    $input_lastName = trim($_POST["lastName"]);
    if (empty($input_lastName)) {
        $lastName_err = "Please enter the last name.";
    } else {
        $lastName = $input_lastName;
    }

    $input_password = trim($_POST["password"]);
    if (empty($input_password)) {
        $password_err = "Please enter a password.";
    } else {
        $password = $input_password;
    }

    // Generate moderator ID
    $schoolYearCode = getSchoolYearCode();
    $nextIncrement = getNextModeratorIncrement($pdo, $schoolYearCode);
    $moderator_id = $schoolYearCode . $nextIncrement; // Example: '24250022'

    // Check if the generated ID already exists
    while (checkModeratorIdExistence($pdo, $moderator_id)) {
        $nextIncrement = str_pad((int)substr($moderator_id, 4, 4) + 1, 4, '0', STR_PAD_LEFT); 
        $moderator_id = $schoolYearCode . $nextIncrement; // Regenerate ID if it exists
    }

    // Set default profile picture
    $profilePic = PROF_PIC_DEFAULT;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (empty($firstName_err) && empty($middleName_err) && empty($lastName_err) && empty($password_err)) {
        $sql = "INSERT INTO tbl_moderators (firstName, middleName, lastName, moderator_id, password, profilePic, dateAdded) 
                VALUES (:firstName, :middleName, :lastName, :moderator_id, :password, :profilePic, NOW())";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":firstName", $firstName);
            $stmt->bindParam(":middleName", $middleName);
            $stmt->bindParam(":lastName", $lastName);
            $stmt->bindParam(":moderator_id", $moderator_id);
            $stmt->bindParam(":password", $hashed_password);
            $stmt->bindParam(":profilePic", $profilePic);

            if ($stmt->execute()) {
                // Fetch the full name of the newly added moderator
                $fullName = $firstName . ' ' . $middleName . ' ' . $lastName;

                // Insert activity log
                $sqlLog = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id) 
                            VALUES (:activity, NOW(), :admin_id, NULL)"; // Set moderator_id to NULL
                if ($logStmt = $pdo->prepare($sqlLog)) {
                    $activity = "You added $fullName to the moderators list.";
                    $logStmt->bindParam(":activity", $activity);
                    $logStmt->bindParam(":admin_id", $admin_id); // Use the admin_id from the session

                    if ($logStmt->execute()) {
                        // Insert into clubs and moderators
                        if (!empty($_POST["club"])) {
                            $selectedClubId = $_POST["club"];
                            $sql2 = "INSERT INTO tbl_clubs_and_moderators (club_id, moderator_id, dateAdded) 
                                     VALUES (:clubId, :moderatorId, NOW())";
                            if ($stmt2 = $pdo->prepare($sql2)) {
                                $stmt2->bindParam(":clubId", $selectedClubId);
                                $stmt2->bindParam(":moderatorId", $moderator_id); // Use $moderator_id here
                                $stmt2->execute();
                            }
                        }

                        // Redirect to moderators page
                        header("location: ../../moderators.php"); 
                        exit();
                    } else {
                        echo "Failed to log the activity.";
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        unset($stmt);
    }
    unset($pdo);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, name-scale=1.0">
    <title>ESAS - Add Moderator</title>
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
                <h2 class="mt-5">Add New Moderator</h2>
                <p>Please fill this form to add a new moderator.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group mb-2">
                        <label>First Name</label>
                        <input type="text" name="firstName" class="form-control <?php echo (!empty($firstName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>" required>
                        <span class="invalid-feedback"><?php echo $firstName_err; ?></span>
                    </div>
                    <div class="form- mb-2">
                        <label>Middle Name</label>
                        <input type="text" name="middleName" class="form-control <?php echo (!empty($middleName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $middleName; ?>" required>
                        <span class="invalid-feedback"><?php echo $middleName_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Last Name</label>
                        <input type="text" name="lastName" class="form-control <?php echo (!empty($lastName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>" required>
                        <span class="invalid-feedback"><?php echo $lastName_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Temporary Password</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>

                    <!-- <div class="form-group mb-2">
                        <label>Assign to a Club</label>
                        <select name="club" class="form-control">
                            <option value="">-- Select a Club (Optional) --</option>
                            <php foreach ($clubs as $club): ?>
                                <option value="<php echo htmlspecialchars($club['club_id']); ?>">
                                    <php echo htmlspecialchars($club['clubName']); ?>
                                </option>
                            <php endforeach; ?>
                        </select>
                    </div> -->

                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="../../moderators.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
