<?php
// Include config file
require_once "../../../../config.php";

// Define variables and initialize with empty values
$moderator_id = $firstName = $middleName = $lastName = $age = $birthday = $gender = $email = $password = $phoneNumber = $department = $profession = "";
$firstName_err = $lastName_err = $email_err = $phoneNumber_err = $department_err = $profession_err = "";

// Prepare a select statement
$sql = "SELECT * FROM tbl_moderators WHERE moderator_id = :moderator_id";

if ($stmt = $pdo->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":moderator_id", $param_moderator_id);
    
    // Set parameters
    $param_moderator_id = trim($_GET["moderator_id"]);
    
    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            // Fetch result row as an associative array
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // Retrieve individual field values
            $firstName = $row["firstName"];
            $middleName = $row["middleName"];
            $lastName = $row["lastName"];
            $age = $row["age"];
            $birthday = $row["birthday"];
            $gender = $row["gender"];
            $email = $row["email"];
            $password = $row["password"]; // Handle password securely if needed
            $phoneNumber = $row["phoneNumber"];
            $department = $row["department"];
            $profession = $row["profession"];
        } else {
            // Moderator ID not found, redirect to error page
            header("location: ../public/error.php");
            exit();
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate fields
    $input_firstName = trim($_POST["firstName"]);
    if (empty($input_firstName)) {
        $firstName_err = "Please enter a first name.";
    } else {
        $firstName = $input_firstName;
    }

    $input_lastName = trim($_POST["lastName"]);
    if (empty($input_lastName)) {
        $lastName_err = "Please enter a last name.";
    } else {
        $lastName = $input_lastName;
    }

    $input_email = trim($_POST["email"]);
    if (empty($input_email)) {
        $email_err = "Please enter an email address.";
    } elseif (!filter_var($input_email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = $input_email;
    }

    $input_phoneNumber = trim($_POST["phoneNumber"]);
    if (empty($input_phoneNumber)) {
        $phoneNumber_err = "Please enter a phone number.";
    } else {
        $phoneNumber = $input_phoneNumber;
    }

    $input_department = trim($_POST["department"]);
    if (empty($input_department)) {
        $department_err = "Please enter a department.";
    } else {
        $department = $input_department;
    }

    $input_profession = trim($_POST["profession"]);
    if (empty($input_profession)) {
        $profession_err = "Please enter a profession.";
    } else {
        $profession = $input_profession;
    }

    // Check input errors before updating in database
    if (empty($firstName_err) && empty($lastName_err) && empty($email_err) && empty($phoneNumber_err) && empty($department_err) && empty($profession_err)) {
        // Prepare an update statement
        $sql = "UPDATE tbl_moderators SET firstName = :firstName, middleName = :middleName, lastName = :lastName, age = :age, birthday = :birthday, gender = :gender, email = :email, phoneNumber = :phoneNumber, department = :department, profession = :profession WHERE moderator_id = :moderator_id";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":firstName", $firstName);
            $stmt->bindParam(":middleName", $middleName);
            $stmt->bindParam(":lastName", $lastName);
            $stmt->bindParam(":age", $age);
            $stmt->bindParam(":birthday", $birthday);
            $stmt->bindParam(":gender", $gender);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phoneNumber", $phoneNumber);
            $stmt->bindParam(":department", $department);
            $stmt->bindParam(":profession", $profession);
            $stmt->bindParam(":moderator_id", $moderator_id);

            // Set the moderator ID
            $moderator_id = $_POST["moderator_id"];

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to landing page
                header("location: ../../crud/moderators/moderator_read.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        unset($stmt);
    }

    // Close connection
    unset($pdo);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Moderator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<style>
    .wrapper {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 15px;
    }
</style>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <h2 class="mt-5">Update Moderator</h2>
        <p>Please edit the input values and submit to update the moderator record.</p>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="firstName" class="form-control <?php echo (!empty($firstName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstName; ?>">
                <span class="invalid-feedback"><?php echo $firstName_err; ?></span>
            </div>
            <div class="form-group">
                <label>Middle Name</label>
                <input type="text" name="middleName" class="form-control" value="<?php echo $middleName; ?>">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="lastName" class="form-control <?php echo (!empty($lastName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastName; ?>">
                <span class="invalid-feedback"><?php echo $lastName_err; ?></span>
            </div>
            <div class="form-group">
                <label>Age</label>
                <input type="number" name="age" class="form-control" value="<?php echo $age; ?>">
            </div>
            <div class="form-group">
                <label>Birthday</label>
                <input type="date" name="birthday" class="form-control" value="<?php echo $birthday; ?>">
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control">
                    <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?php echo ($gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phoneNumber" class="form-control <?php echo (!empty($phoneNumber_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phoneNumber; ?>">
                <span class="invalid-feedback"><?php echo $phoneNumber_err; ?></span>
            </div>
            <div class="form-group">
                <label>Department</label>
                <input type="text" name="department" class="form-control <?php echo (!empty($department_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $department; ?>">
                <span class="invalid-feedback"><?php echo $department_err; ?></span>
            </div>
            <div class="form-group">
                <label>Profession</label>
                <input type="text" name="profession" class="form-control <?php echo (!empty($profession_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $profession; ?>">
                <span class="invalid-feedback"><?php echo $profession_err; ?></span>
            </div>
            <div class="form-group">
                <input type="hidden" name="moderator_id" value="<?php echo $moderator_id; ?>"/>
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="../moderators.php" class="btn btn-secondary ml-2">Cancel</a>
            </div>
        </form>
    </div>    
</div>
</body>
</html>
