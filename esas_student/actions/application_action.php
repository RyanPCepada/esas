<?php
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Define variables and initialize with empty values
$club_id = $status = "";
$club_id_err = "";

// Start the session
session_start();

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('Student not logged in.'); window.history.back();</script>";
    exit();
}

// Retrieve student_id from the session
$student_id = $_SESSION['student_id'];

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    if (empty($club_id_err)) {
        // Prepare an insert statement for answers
        $sql = "INSERT INTO tbl_application_answers (answer, question_id, student_id, club_id) VALUES (:answer, :question_id, :student_id, :club_id)";
        
        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Prepare to insert answers
        $questions = [
            'question1' => 1, // Assuming question1 corresponds to question_id 1
            'question2' => 2, // Assuming question2 corresponds to question_id 2
            'question3' => 3  // Assuming question3 corresponds to question_id 3
        ];

        $status = 'pending'; // Default status if needed for other tables

        foreach ($questions as $question_key => $question_id) {
            // Validate each question answer
            $input_answer = trim($_POST[$question_key]);
            if (!empty($input_answer)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":answer", $input_answer);
                $stmt->bindParam(":question_id", $question_id, PDO::PARAM_INT);
                $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
                $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

                // Attempt to execute the prepared statement
                if (!$stmt->execute()) {
                    echo "Oops! Something went wrong while submitting the answer. Please try again later.";
                    exit();
                }
            }
        }

        // Log the activity in tbl_activity_logs
        $activity = "You submitted an application to join the club with ID $club_id";
        $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, student_id) VALUES (:activity, NOW(), :student_id)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->bindParam(":activity", $activity);
        $logStmt->bindParam(":student_id", $student_id);
        $logStmt->execute(); // Execute the log insertion

        // Redirect to landing page
        echo "<script>alert('Application submitted successfully!');</script>";
        echo "<script>window.location.href = '/esas/esas_student/all_clubs.php';</script>";
        exit();
    }
}

// Close statement
unset($stmt);
?>
