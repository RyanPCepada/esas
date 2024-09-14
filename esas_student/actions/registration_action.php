<?php
// Include config file
require_once "../../config.php";

// Define variables and initialize with empty values
$question1 = $question2 = $question3 = $club_id = $status = "";
$question1_err = $question2_err = $question3_err = $club_id_err = "";

// Start the session
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Student not logged in.'); window.history.back();</script>";
    exit();
}

// Retrieve student_id from the session
$student_id = $_SESSION['student_id'];

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate question1
    $input_question1 = trim($_POST["question1"]);
    if (empty($input_question1)) {
        $question1_err = "Please answer question 1.";
    } else {
        $question1 = $input_question1;
    }

    // Validate question2
    $input_question2 = trim($_POST["question2"]);
    if (empty($input_question2)) {
        $question2_err = "Please answer question 2.";
    } else {
        $question2 = $input_question2;
    }

    // Validate question3
    $input_question3 = trim($_POST["question3"]);
    if (empty($input_question3)) {
        $question3_err = "Please answer question 3.";
    } else {
        $question3 = $input_question3;
    }

    // Validate club_id
    $input_club_id = trim($_POST["club_id"]);
    if (empty($input_club_id)) {
        $club_id_err = "Please enter the club ID.";
    } elseif (!is_numeric($input_club_id)) {
        $club_id_err = "Please enter a valid club ID.";
    } else {
        $club_id = $input_club_id;
    }

    // Check input errors before inserting into the database
    if (empty($question1_err) && empty($question2_err) && empty($question3_err) && empty($club_id_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO tbl_registration (student_id, question1, question2, question3, status, club_id) 
                VALUES (:student_id, :question1, :question2, :question3, :status, :club_id)";

        $stmt = $pdo->prepare($sql);

        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
        $stmt->bindParam(":question1", $question1);
        $stmt->bindParam(":question2", $question2);
        $stmt->bindParam(":question3", $question3);
        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

        // Add status with default value "pending"
        $status = 'pending';
        $stmt->bindParam(":status", $status);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records created successfully. Redirect to landing page
            echo "<script>alert('Registration successful!');</script>";
            echo "<script>window.location.href = '/esas/esas_student/all_clubs.php';</script>";
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
            // Debug: echo $stmt->errorInfo(); // Uncomment for debugging
        }
    }

    // Close statement
    unset($stmt);
}

// Close connection (if not using persistent connection)
// unset($pdo); // Uncomment if $pdo is not a persistent connection
?>
