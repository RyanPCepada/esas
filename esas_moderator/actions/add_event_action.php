<?php
// Include config file
require_once "../../config.php";
require __DIR__ . '/../../../vendor/autoload.php';


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

        // Retrieve students registered in the club
        $student_sql = "SELECT email FROM tbl_application 
                        JOIN tbl_students ON tbl_application.student_id = tbl_students.student_id 
                        WHERE club_id = :club_id AND status = 'active'";
        $student_stmt = $pdo->prepare($student_sql);
        $student_stmt->execute([':club_id' => $club_id]);
        $students = $student_stmt->fetchAll(PDO::FETCH_ASSOC);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username   = 'sportsnbscesas@gmail.com';
        $mail->Password   = 'wubj bmsj ckmj nope'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom('sportsnbscesas@gmail.com', 'Club Events');
                         
        // Loop through each student and send an email
        foreach ($students as $student) {
            try {
                $mail->clearAddresses(); 
                $mail->addAddress($student['email']);
                
                $mail->isHTML(true);
                $mail->Subject = "New Event in $clubName";
                $mail->Body = "
                    <p>Dear Student,</p>
                    <p>We are excited to announce a new event for the $clubName club!</p>
                    <p><strong>Event Title:</strong> {$title}</p>
                    <p><strong>Description:</strong> {$description}</p>
                    <p><strong>Date:</strong> {$date}</p>
                    <p><strong>Time:</strong> {$timeStarts} to {$timeEnds}</p>
                    <p><strong>Location:</strong> {$location}</p>
                    <p>For more details, please visit: <a href='{$applicationLink}'>Event Link</a></p>
                    <p>Thank you!</p>";

                $mail->send();
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
            }
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
