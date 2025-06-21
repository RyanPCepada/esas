<?php 
// Include config file
require_once "../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

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
    $club_id = isset($_POST["club_id"]) ? intval($_POST["club_id"]) : 0;
    if ($club_id <= 0) {
        echo "<script>alert('Invalid club ID.'); window.history.back();</script>";
        exit();
    }

    // Set application status to "pending"
    $status = 'pending';

    // Insert the application record into tbl_application
    $sql = "INSERT INTO tbl_application (student_id, status, club_id, dateApplied) 
            VALUES (:student_id, :status, :club_id, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
    
    // Execute the application insertion
    if ($stmt->execute()) {
        // Get the inserted application ID
        $application_id = $pdo->lastInsertId();

        // Fetch the questions for the specified club
        $questionSql = "SELECT question_id, question FROM tbl_application_questions WHERE club_id = :club_id";
        $questionStmt = $pdo->prepare($questionSql);
        $questionStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
        $questionStmt->execute();
        $questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through each answer in POST data to insert into tbl_application_answers
        foreach ($_POST as $key => $answer) {
            if (strpos($key, 'question') === 0 && !empty($answer)) {
                // Extract question_id from the form field name (e.g., "question" => 1)
                $question_index = intval(substr($key, 8)) - 1; // Adjust for zero-based index
                if (isset($questions[$question_index])) {
                    $question_text = $questions[$question_index]['question'];
                    $question_id = $questions[$question_index]['question_id'];
                    
                    // Insert answer into tbl_application_answers
                    $answerSql = "INSERT INTO tbl_application_answers (answer, question_id, question, application_id, student_id, club_id, dateAdded)
                                  VALUES (:answer, :question_id, :question, :application_id, :student_id, :club_id, NOW())";
                    $answerStmt = $pdo->prepare($answerSql);
                    $answerStmt->bindParam(":answer", $answer, PDO::PARAM_STR);
                    $answerStmt->bindParam(":question_id", $question_id, PDO::PARAM_INT);
                    $answerStmt->bindParam(":question", $question_text, PDO::PARAM_STR);
                    $answerStmt->bindParam(":application_id", $application_id, PDO::PARAM_INT);
                    $answerStmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
                    $answerStmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
                    $answerStmt->execute();
                }
            }
        }

        // Log the activity in tbl_activity_logs
        $activity = "You submitted an application to join club with ID $club_id";
        $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, student_id) VALUES (:activity, NOW(), :student_id)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->bindParam(":activity", $activity);
        $logStmt->bindParam(":student_id", $student_id);
        $logStmt->execute(); // Execute the log insertion

        // Success message and redirection
        echo "<script>alert('Application submitted successfully!');</script>";
        echo "<script>window.location.href = '/esas/esas_student/all_clubs.php';</script>";
        exit();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    unset($stmt);
}

// Close connection (if not using persistent connection)
// unset($pdo); // Uncomment if $pdo is not a persistent connection
?>
