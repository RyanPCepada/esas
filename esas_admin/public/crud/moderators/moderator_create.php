<?php
require_once "../../../../config.php";

// Define the default profile picture constant
define('PROF_PIC_DEFAULT', 'PROF_PIC.png'); // Change 'PROF_PIC.png' to your actual default image path if necessary

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
    $input_firstName = trim($_POST["firstName"]);
    if (empty($input_firstName)) {
        $firstName_err = "Please enter the first name.";
    } else {
        $firstName = $input_firstName;
    }

    $input_middleInitial = trim($_POST["middleInitial"]);
    if (empty($input_middleInitial)) {
        $middleInitial_err = "Please enter the middle initial.";
    } else {
        $middleInitial = $input_middleInitial;
    }

    $input_lastName = trim($_POST["lastName"]);
    if (empty($input_lastName)) {
        $lastName_err = "Please enter the last name.";
    } else {
        $lastName = $input_lastName;
    }

    $input_email = trim($_POST["email"]);
    if (empty($input_email)) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = $input_email;
    }

    $input_password = trim($_POST["password"]);
    if (empty($input_password)) {
        $password_err = "Please enter a password.";
    } else {
        $password = $input_password; // No hashing
    }

    // Set default profile picture
    $profilePic = PROF_PIC_DEFAULT;

    if (empty($firstName_err) && empty($middleInitial_err) && empty($lastName_err) && empty($email_err) && empty($password_err)) {
        $sql = "INSERT INTO tbl_moderators (firstName, middleName, lastName, email, password, profilePic, dateAdded) 
                VALUES (:firstName, :middleInitial, :lastName, :email, :password, :profilePic, NOW())";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":firstName", $firstName);
            $stmt->bindParam(":middleInitial", $middleInitial);
            $stmt->bindParam(":lastName", $lastName);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":profilePic", $profilePic);

            if ($stmt->execute()) {
                $newModeratorId = $pdo->lastInsertId();

                // Associate moderator with club if selected
                if (!empty($_POST["club"])) {
                    $selectedClubId = $_POST["club"];
                    $sql2 = "INSERT INTO tbl_clubs_and_moderators (club_id, moderator_id, dateAdded) 
                             VALUES (:clubId, :moderatorId, NOW())";
                    if ($stmt2 = $pdo->prepare($sql2)) {
                        $stmt2->bindParam(":clubId", $selectedClubId);
                        $stmt2->bindParam(":moderatorId", $newModeratorId);
                        $stmt2->execute();
                    }
                }

                header("location: ../../moderators.php"); // Redirect to a list of moderators
                exit();
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
                <h2 class="mt-5">Add New Moderator</h2>
                <p>Please fill this form to add a new moderator.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group mb-2">
                        <label>First Name</label>
                        <input type="text" name="firstName" class="form-control <?php echo (!empty($firstName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>" required>
                        <span class="invalid-feedback"><?php echo $firstName_err; ?></span>
                    </div>
                    <div class="form- mb-2">
                        <label>Middle Initial</label>
                        <input type="text" name="middleInitial" maxlength="1" class="form-control <?php echo (!empty($middleInitial_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $middleInitial; ?>" required>
                        <span class="invalid-feedback"><?php echo $middleInitial_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Last Name</label>
                        <input type="text" name="lastName" class="form-control <?php echo (!empty($lastName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>" required>
                        <span class="invalid-feedback"><?php echo $lastName_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" required>
                        <span class="invalid-feedback"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Temporary Password</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
                        <span class="invalid-feedback"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group mb-2">
                        <label>Assign to a Club</label>
                        <select name="club" class="form-control">
                            <option value="">-- Select a Club (Optional) --</option>
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
