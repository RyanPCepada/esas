<?php
// Include the config file to connect to the database
include('config.php');

// Set default timezone
date_default_timezone_set('Asia/Manila');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form values
    $student_id = $_POST['student_id'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $department = $_POST['department'];
    $year = $_POST['year'];

    // Set default values for password and profilePic
    $password = "1"; // default password
    $profilePic = "PROF_PIC.png"; // default profile picture

    // Set the current date for dateAdded and dateModified
    $dateAdded = date('Y-m-d H:i:s');
    $dateModified = date('Y-m-d H:i:s');

    // Prepare the SQL query to insert the data
    $sql = "INSERT INTO tbl_students 
            (student_id, firstName, middleName, lastName, department, year, password, profilePic, dateAdded, dateModified) 
            VALUES 
            (:student_id, :firstName, :middleName, :lastName, :department, :year, :password, :profilePic, :dateAdded, :dateModified)";

    // Prepare statement
    $stmt = $pdo->prepare($sql);

    // Bind values to the prepared statement
    $stmt->bindParam(':student_id', $student_id);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':middleName', $middleName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':profilePic', $profilePic);
    $stmt->bindParam(':dateAdded', $dateAdded);
    $stmt->bindParam(':dateModified', $dateModified);

    // Execute the query
    if ($stmt->execute()) {
        // Use JavaScript to display an alert
        echo "<script type='text/javascript'>alert('Thank you so much for helping us!'); window.location.href = 'input_student.php';</script>";
    } else {
        // Handle error if insertion fails
        echo "<script type='text/javascript'>alert('Error adding student. Please try again.');</script>";
    }

} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="wrapper mb-5">
    <h2 class="wrapper mb-4" style="margin-left: -20px;">System Testing Form</h2>

    <form action="input_student.php" method="POST">

        <!-- Student ID -->
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" class="form-control" id="student_id" name="student_id" required>
        </div>

        <!-- First Name -->
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" required>
        </div>

        <!-- Middle Name -->
        <div class="form-group">
            <label for="middleName">Middle Name</label>
            <input type="text" class="form-control" id="middleName" name="middleName" required>
        </div>

        <!-- Last Name -->
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" required>
        </div>

        <!-- Department (Radio Buttons) -->
        <div class="form-group">
            <label>Department</label><br>
            <input type="radio" id="TEP" name="department" value="TEP" required>
            <label for="TEP">TEP</label><br>
            <input type="radio" id="BSBA" name="department" value="BSBA">
            <label for="BSBA">BSBA</label><br>
            <input type="radio" id="CCS" name="department" value="CCS">
            <label for="CCS">CCS</label>
        </div>

        <!-- Year (Radio Buttons) -->
        <div class="form-group">
            <label>Year</label><br>
            <input type="radio" id="1stYear" name="year" value="1st Year" required>
            <label for="1stYear">1st Year</label><br>
            <input type="radio" id="2ndYear" name="year" value="2nd Year">
            <label for="2ndYear">2nd Year</label><br>
            <input type="radio" id="3rdYear" name="year" value="3rd Year">
            <label for="3rdYear">3rd Year</label><br>
            <input type="radio" id="4thYear" name="year" value="4th Year">
            <label for="4thYear">4th Year</label>
        </div>

        <!-- Hidden fields for default values -->
        <input type="hidden" name="password" value="1">
        <input type="hidden" name="profilePic" value="PROF_PIC.png">

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script src="path/to/bootstrap.bundle.min.js"></script> <!-- Include Bootstrap JS -->

</body>
</html>
