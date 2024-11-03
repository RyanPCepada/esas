<?php
session_start();
require_once "../../config.php";
require __DIR__ . '/../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Fetch the current moderator's ID
$moderator_id = $_SESSION['moderator_id'];

// Initialize variables and error messages
$postContent = "";
$postContent_err = "";
$club_id = ""; 

// Check if club_id is provided in the POST request
if (isset($_POST['club_id'])) {
    $club_id = intval($_POST['club_id']); // Use intval to ensure it's an integer

    // Validate post content
    $input_postContent = trim($_POST["postContent"]);
    if (empty($input_postContent)) {
        $postContent_err = "Please enter the post content.";
    } else {
        $postContent = $input_postContent;
    }

    // Check for errors before inserting into the database
    if (empty($postContent_err)) {
        
        try {
            // Begin transaction
            $pdo->beginTransaction();

            // Prepare an insert statement for the post
            $sql = "INSERT INTO tbl_posts (post, dateAdded, club_id, moderator_id) VALUES (:post, NOW(), :club_id, :moderator_id)";
            $stmt = $pdo->prepare($sql);

            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":post", $postContent);
            $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT); 
            $stmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Get the ID of the inserted post
                $post_id = $pdo->lastInsertId();

              

                $student_sql = "SELECT
                tbl_students.student_id, 
                tbl_students.instiEmail, 
                tbl_students.firstName, 
                tbl_students.middleName, 
                tbl_students.lastName, 
                tbl_clubs.clubName
            FROM
                tbl_students
                INNER JOIN tbl_application ON tbl_students.student_id = tbl_application.student_id
                INNER JOIN tbl_clubs ON tbl_application.club_id = tbl_clubs.club_id
            WHERE
                tbl_application.club_id = :club_id AND
                tbl_application.`status` = 'active'";
            $student_stmt = $pdo->prepare($student_sql);
            $student_stmt->execute([':club_id' => $club_id]);
            $students = $student_stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<script>console.log('Retrieved active students:', " . json_encode($students) . ");</script>";
            

                // Check if any students were found
                if (!empty($students)) {
                    $clubName = $students[0]['clubName'];

                    foreach ($students as $student) {
                        // Get the student's email and full name
                        $final_email = $student['instiEmail'];
                        $fullName = "{$student['firstName']} {$student['middleName']} {$student['lastName']}";

                        // SMTP Email sending logic
                        $mail = new PHPMailer(true); // Create a new PHPMailer instance
                        try {
                            // Server settings
                            $mail->isSMTP();                                        // Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                  // Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                              // Enable SMTP authentication
                            $mail->Username   = 'sportsnbscesas@gmail.com';         // SMTP username
                            $mail->Password   = 'wubj bmsj ckmj nope';             // SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption
                            $mail->Port       = 587;                               // TCP port to connect to

                            // Recipients
                            $mail->setFrom('sportsnbscesas@gmail.com', 'NBSC Club Organizations');
                            $mail->addAddress($final_email, $fullName);           // Add a recipient

                            // Content
                            $mail->isHTML(true);                                  
                            $mail->Subject = "New Announcement in {$clubName}";
                            $mail->Body    = "Dear $fullName,<br><br>A new post has been made in the club <b>'$clubName'</b>.<br><br>Thank you for being part of the <b>$clubName</b> family!";

                            // Send the email
                            $mail->send();
                        } catch (Exception $e) {
                            // Log or handle the error
                            error_log("Message could not be sent to {$fullName}. Mailer Error: {$mail->ErrorInfo}");
                            echo "<script>console.log('Message could not be sent to {$fullName}. Mailer Error: {$mail->ErrorInfo}');</script>";
          
                        }

                        // Insert notification for each student
                        $sql = "INSERT INTO tbl_notifications (notification, student_id, club_id, post_id, is_read, dateAdded)
                                VALUES ('Posted an announcement', :student_id, :club_id, :post_id, 0, NOW())";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(":student_id", $student['student_id'], PDO::PARAM_INT);
                        $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
                        $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
                
                // Log the post creation activity in tbl_activity_logs
                $activity = "You created a post in the club with ID {$club_id}";
                $sql = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id, student_id) 
                        VALUES (:activity, NOW(), NULL, :moderator_id, NULL)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":activity", $activity);
                $stmt->bindParam(":moderator_id", $moderator_id, PDO::PARAM_INT);
                $stmt->execute();

                // Commit transaction
                $pdo->commit();
                echo "<script>console.log('Transaction committed successfully');</script>";

            
                header("Location: " . $_SERVER['PHP_SELF'] . "?club_id=" . $club_id);
             
            } else {
                throw new Exception("Post insertion failed.");
                echo "<script>console.log('Error during transaction: " . addslashes($e->getMessage()) . "');</script>";
  
            }

        } catch (Exception $e) {
            // Rollback transaction on error
            $pdo->rollBack();
            echo json_encode(["success" => false, "message" => "Oops! Something went wrong. Please try again later."]);
            echo "<script>console.log('Error during transaction: " . addslashes($e->getMessage()) . "');</script>";
  
        }
    } else {
        echo json_encode(["success" => false, "message" => $postContent_err]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Club ID is required."]);
}

// Close connection
unset($pdo);
?>
