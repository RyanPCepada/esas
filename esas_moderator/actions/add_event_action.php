<?php
// Include config file
require_once "../../config.php";
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $timeStarts = $_POST['timeStarts'];
    $timeEnds = $_POST['timeEnds'];
    $location = $_POST['location'];
    $applicationLink = $_POST['applicationLink'];
    $club_id = $_POST['club_id'];
    $moderator_id = $_POST['moderator_id'];

    // Get current date and time for dateAdded and dateModified
    $currentDateTime = date('Y-m-d H:i:s');

    // Insert into tbl_events using PDO
    $sql = "INSERT INTO tbl_events (title, description, date, timeStarts, timeEnds, location, applicationLink, dateAdded, dateModified, club_id, moderator_id) 
            VALUES (:title, :description, :date, :timeStarts, :timeEnds, :location, :applicationLink, :dateAdded, :dateModified, :club_id, :moderator_id)";
    
    try {
        $pdo->beginTransaction();

        // Insert event into tbl_events
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':date' => $date,
            ':timeStarts' => $timeStarts,
            ':timeEnds' => $timeEnds,
            ':location' => $location,
            ':applicationLink' => $applicationLink,
            ':dateAdded' => $currentDateTime,
            ':dateModified' => $currentDateTime,
            ':club_id' => $club_id,
            ':moderator_id' => $moderator_id
        ]);

        // Get the club name
        $club_sql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
        $club_stmt = $pdo->prepare($club_sql);
        $club_stmt->execute([':club_id' => $club_id]);
        $club = $club_stmt->fetch(PDO::FETCH_ASSOC);
        $clubName = $club['clubName'];

        // Insert into tbl_activity_logs without club_id
        $activity = "You added an event for " . $clubName;
        $log_sql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) 
                    VALUES (:activity, :dateAdded, :moderator_id)";
        $log_stmt = $pdo->prepare($log_sql);
        $log_stmt->execute([
            ':activity' => $activity,
            ':dateAdded' => $currentDateTime,
            ':moderator_id' => $moderator_id
        ]);

        // Notify all active students registered in the club
        $student_sql = "SELECT tbl_students.student_id, tbl_students.instiEmail, 
                        tbl_students.firstName, tbl_students.middleName, tbl_students.lastName 
                        FROM tbl_students
                        INNER JOIN tbl_application ON tbl_students.student_id = tbl_application.student_id
                        WHERE tbl_application.club_id = :club_id AND tbl_application.status = 'active'";
        $student_stmt = $pdo->prepare($student_sql);
        $student_stmt->execute([':club_id' => $club_id]);
        $students = $student_stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<script>console.log('Retrieved active students:', " . json_encode($students) . ");</script>";

        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();                                       // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                  // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                              // Enable SMTP authentication
        $mail->Username   = 'sportsnbscesas@gmail.com';         // SMTP username
        $mail->Password   = 'wubj bmsj ckmj nope';             // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption
        $mail->Port       = 587;                               // TCP port to connect to

        $mail->setFrom('sportsnbscesas@gmail.com', 'NBSC Club Organizations Notifications');
                       
        // Prepare the email content
        $subject = "New Event in {$clubName}";
        
        // Send notifications to each active student
        foreach ($students as $student) {
            $final_email = $student['instiEmail'];
            $fullName = "{$student['firstName']} {$student['middleName']} {$student['lastName']}";
            
            $body = "Dear $fullName,<br><br>A new event has been scheduled: <b>{$title}</b>.<br>Description: {$description}<br>Date: {$date}<br>Time: {$timeStarts} - {$timeEnds}<br>Location: {$location}<br><br>For more details, please check your club's home page.";

            // Set recipient
            $mail->addAddress($final_email, $fullName);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->isHTML(true); // Set email format to HTML

            // Send the email
            if (!$mail->send()) {
                echo "<script>console.log('Message could not be sent to {$fullName}. Mailer Error: {$mail->ErrorInfo}');</script>";
            } else {
                echo "<script>console.log('Email sent to {$fullName}');</script>";
            }

            // Clear all addresses for the next iteration
            $mail->clearAddresses();

            // Insert notification for each student
            $notification_sql = "INSERT INTO tbl_notifications (notification, student_id, club_id, event_id, is_read, dateAdded)
                                VALUES ('Posted an event', :student_id, :club_id, LAST_INSERT_ID(), 0, NOW())";
                                // VALUES ('Posted an event: {$title}', :student_id, :club_id, LAST_INSERT_ID(), 0, NOW())";
            $notification_stmt = $pdo->prepare($notification_sql);
            $notification_stmt->execute([
                ':student_id' => $student['student_id'],
                ':club_id' => $club_id
            ]);
            echo "<script>console.log('Notification added for {$fullName}');</script>";
        }

        $pdo->commit();

        // Redirect to home.php after successful insertion
        header("Location: /esas/esas_moderator/public/home.php?success=1&club_id=" . urlencode($club_id));
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>