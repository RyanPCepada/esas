<?php
// Include config file
require_once "../../../../config.php";
require __DIR__ . '/../../../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

$final_email ='';
$student_id =[];
$club_id=[];
$application_id;

echo $final_email;

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');
// Check existence of student_id, club_id, and application_id parameter before processing further
if (isset($_GET["student_id"]) && !empty(trim($_GET["student_id"])) 
    && isset($_GET["club_id"]) && !empty(trim($_GET["club_id"])) 
    && isset($_GET["application_id"]) && !empty(trim($_GET["application_id"]))) {

    // Set parameters
    $param_student_id = trim($_GET["student_id"]);
    $param_club_id = trim($_GET["club_id"]);
    $param_application_id = trim($_GET["application_id"]); 

    // Log student, club, and application IDs
    echo "<script>console.log('Student ID: " . $param_student_id . "');</script>";
    echo "<script>console.log('Club ID: " . $param_club_id . "');</script>";
    echo "<script>console.log('Application ID: " . $param_application_id . "');</script>";

    $application_id = trim($_GET["application_id"]);
    $student_id = trim($_GET["student_id"]);
    $club_id = trim($_GET["club_id"]);

    // Prepare a select statement to fetch the student details
    $sql = "SELECT * FROM tbl_students WHERE student_id = :student_id";

    echo "<script>console.log('Preparing SQL to fetch student details');</script>";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":student_id", $param_student_id);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "<script>console.log('SQL executed successfully');</script>";
            if ($stmt->rowCount() == 1) {
                // Fetch result row as an associative array
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Assigning values from the fetched row
                $student_id = $row["student_id"];
                $firstName = !empty($row["firstName"]) ? $row["firstName"] : '';
                $middleName = !empty($row["middleName"]) ? $row["middleName"] : '';
                $lastName = !empty($row["lastName"]) ? $row["lastName"] : '';
                $email = !empty($row["instiEmail"]) ? $row["instiEmail"] : '';

                $final_email = $email;
                echo "<script>console.log('Final Email: " . $final_email . "');</script>";
                $fullName = trim("$firstName $middleName $lastName");

                // For profile picture
                $profilePic = !empty($row['profilePic']) ? '/esas/esas_student/images/' . $row['profilePic'] : 'No Image Available';
                echo "<script>console.log('Profile Picture: " . $profilePic . "');</script>";
            } else {
                // Redirect to error page if no valid id is found
                echo "<script>console.log('No valid student ID found');</script>";
                header("location: ../public/error.php");
                exit();
            }
        } else {
            // Handle the error when executing the statement
            echo "<script>console.log('Error executing the student details query');</script>";
            echo "Error executing the query. Please try again.";
            exit();
        }
    }

    // Close statement
    unset($stmt);

    // Prepare a select statement to fetch questions and dateApplied from tbl_application
    $sql_questions = "SELECT question1, question2, question3, dateApplied FROM tbl_application WHERE student_id = :student_id AND club_id = :club_id";

    echo "<script>console.log('Preparing SQL to fetch application questions and dateApplied');</script>";

    if ($stmt = $pdo->prepare($sql_questions)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":student_id", $param_student_id);
        $stmt->bindParam(":club_id", $param_club_id);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "<script>console.log('SQL executed successfully for questions');</script>";
            // Fetch the questions and dateApplied together in one call
            $questions = $stmt->fetch(PDO::FETCH_ASSOC); 

            if ($questions) {
                $dateApplied = $questions['dateApplied']; 
                echo "<script>console.log('Date Applied: " . $dateApplied . "');</script>";
            } else {
                echo "<script>console.log('No application details found');</script>";
                echo "No application details found.";
            }
        } else {
            echo "<script>console.log('Error executing the questions query');</script>";
            echo "Could not fetch questions. Please try again later.";
        }
    }

    // Close statement
    unset($stmt);
    
} else {
    // URL doesn't contain student_id or club_id parameter. Redirect to error page
    echo "<script>console.log('Missing required parameters in URL');</script>";
    header("location: ../public/error.php");
    exit();
}

// Function to update the application status and remark
function updateApplicationStatus($pdo, $student_id, $club_id, $application_id, $newStatus, $remark) {
    echo "<script>console.log('Updating application status to " . $newStatus . "');</script>";
    $updateSql = "UPDATE tbl_application 
                  SET status = :status, dateDecided = NOW(), remark = :remark 
                  WHERE student_id = :student_id 
                  AND club_id = :club_id 
                  AND application_id = :application_id";

    $stmt = $pdo->prepare($updateSql);
    $stmt->execute([
        ':status' => $newStatus,
        ':remark' => $remark, // Add the remark parameter here
        ':student_id' => $student_id,
        ':club_id' => $club_id,
        ':application_id' => $application_id,
    ]);
    $rowCount = $stmt->rowCount();
    echo "<script>console.log('Rows affected: " . $rowCount . "');</script>";
    return $rowCount;
}


// Function to count active memberships
function countActiveMemberships($pdo, $student_id) {
    echo "<script>console.log('Counting active memberships for student ID: " . $student_id . "');</script>";
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_application WHERE student_id = :student_id AND status = 'active'");
    $stmt->execute([':student_id' => $student_id]);
    $activeCount = $stmt->fetchColumn();
    echo "<script>console.log('Active Memberships: " . $activeCount . "');</script>";
    return $activeCount;
}

// Function to update pending applications status
function updatePendingApplications($pdo, $student_id) {
    echo "<script>console.log('Updating pending applications for student ID: " . $student_id . "');</script>";
    $updatePendingSql = "UPDATE tbl_application SET status = 'maxed' WHERE student_id = :student_id AND status = 'pending'";
    $stmt = $pdo->prepare($updatePendingSql);
    $stmt->execute([':student_id' => $student_id]);
}

// Function to fetch club name
function fetchClubName($pdo, $club_id) {
    echo "<script>console.log('Fetching club name for club ID: " . $club_id . "');</script>";
    $stmt = $pdo->prepare("SELECT clubName FROM tbl_clubs WHERE club_id = :club_id");
    $stmt->execute([':club_id' => $club_id]);
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    $clubName = $club['clubName'] ?? 'Unknown Club';
    echo "<script>console.log('Club Name: " . $clubName . "');</script>";
    return $clubName;
}


// Handle approval or disapproval
if (isset($_POST["action"]) && in_array($_POST["action"], ['approve', 'disapprove'])) {
    // Set new status based on action
    $newStatus = $_POST["action"] === 'approve' ? 'active' : 'disapproved';
   
    // Log action and new status
    echo "<script>console.log('Action: " . $_POST["action"] . "');</script>";
    echo "<script>console.log('New Status: " . $newStatus . "');</script>";
   
    // Get values for student_id, club_id, application_id (assuming they are coming from POST data)
    echo "<script>console.log('Fetching student_id, club_id, application_id');</script>";
    
    // Debug global variables
    echo "<script>console.log('Student ID: " . json_encode($student_id) . "');</script>";
    echo "<script>console.log('Club ID: " . json_encode($club_id) . "');</script>";
    echo "<script>console.log('Application ID: " . json_encode($application_id) . "');</script>";

    // Update applications status
    echo "<script>console.log('Updating applications status...');</script>";
    if (updateApplicationStatus($pdo, $student_id, $club_id, $application_id, $newStatus, $_POST['remark']) > 0) {
        echo "<script>console.log('Applications status updated successfully');</script>";
        
        // Check the current active memberships after approval
        if ($newStatus === 'active') {
            echo "<script>console.log('New status is active. Counting active memberships...');</script>";
            // Count how many clubs the student is currently "active" in
            $clubsCount = countActiveMemberships($pdo, $student_id);

            echo "<script>console.log('Active Clubs Count: " . $clubsCount . "');</script>";

            // If the student already has 2 active memberships, update the status of other pending applications
            if ($clubsCount >= 2) {
                echo "<script>console.log('Student has 2 or more active memberships. Updating pending applications...');</script>";
                updatePendingApplications($pdo, $student_id);
            }

         
            $clubName = fetchClubName($pdo, $club_id);
            echo "<script>console.log('Club Name: " . $clubName . "');</script>";

          
            echo "<script>console.log('Logging approval action...');</script>";
            logActivity($pdo, $fullName, $student_id, $clubName, true); 

            echo "<script>console.log('Sending approval email...');</script>";
           
            sendNotificationEmail($final_email, $fullName, $clubName, 'approved');
        } else {
            // YOU CAN DELETE NING MGA CONCOLE LOGS NAKO. FOR DEBUGGING PURPOSES RANA
            echo "<script>console.log('New status is disapproved');</script>";

            $clubName = fetchClubName($pdo, $club_id);
            echo "<script>console.log('Club Name: " . $clubName . "');</script>";

            echo "<script>console.log('Logging disapproval action...');</script>";
            logActivity($pdo, $fullName, $student_id, $clubName, false); 

            echo "<script>console.log('Sending disapproval email...');</script>";
            sendNotificationEmail($final_email, $fullName, $clubName, 'disapproved');
        }

        echo "<script>console.log('Redirecting to pending approvals...');</script>";
         header("location: ../../pending_approvals.php");
        //  header("location: pending_approval_read.php?student_id=$student_id");
        exit();
    } else {
        echo "<script>console.log('Error updating the request');</script>";
        echo "Error updating the request. Please try again.";
        exit();
    }
}

// Function to send email notification using PHPMailer
function sendNotificationEmail($final_email, $fullName, $clubName, $action) {
    // Load PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        echo "<script>console.log('Starting to send email...');</script>";
    
        // Server settings
        echo "<script>console.log('Configuring SMTP settings...');</script>";
        $mail->isSMTP();                                       // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                  // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                              // Enable SMTP authentication
        $mail->Username   = 'nbsc.esas@gmail.com';         // SMTP username
        $mail->Password   = 'cxef aobn ozbq qpxv';             // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption
        $mail->Port       = 587;                               // TCP port to connect to
        
        echo "<script>console.log('SMTP configuration completed.');</script>";
    
        // Recipients
        echo "<script>console.log('Adding recipient: {$final_email}');</script>";
        $mail->setFrom('nbsc.esas@gmail.com', 'NBSC Club Organizations');
        $mail->addAddress($final_email, $fullName);           
    
        // Content
        echo "<script>console.log('Setting up email content...');</script>";
        $mail->isHTML(true);                                  
        $subject = $action === 'approved' ? 'Club Application Approved' : 'Club Application Disapproved';
        $mail->Subject = $subject;
    
        $message = $action === 'approved' ? 
            "Dear $fullName,<br><br>Congratulations! Your application for the club <b>$clubName</b> has been approved." : 
            "Dear $fullName,<br><br>We regret to inform you that your application for the club <b>$clubName</b> has been disapproved.";
        
        $mail->Body    = $message;
        echo "<script>console.log('Email subject and body set.');</script>";
    
        // Attempt to send the email
        echo "<script>console.log('Attempting to send the email...');</script>";
        $mail->send();
        
        // Log success
        echo 'Email has been sent';
        echo "<script>console.log('Email sent successfully to {$final_email}');</script>";
    } catch (Exception $e) {

        // Handle specific SMTP authentication errors
        if (strpos($mail->ErrorInfo, '535') !== false) {
            echo "<script>console.log('Error: Incorrect username or password.');</script>";
        } elseif (strpos($mail->ErrorInfo, '534') !== false) {
            echo "<script>console.log('Error: Gmail is blocking the sign-in attempt. Ensure that Less Secure Apps are enabled or use an App Password.');</script>";
        } else {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            echo "<script>console.log('Mailer Error: {$mail->ErrorInfo}');</script>";
        }
    }
    
}


function logActivity($pdo, $fullName, $student_id, $clubName, $isApproved) {
    // Prepare the SQL statement to insert activity log
    $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id, student_id) 
                VALUES (:activity, :dateAdded, :moderator_id, NULL)";
    
    $stmt = $pdo->prepare($logSql);
    $activity = $isApproved 
                ? "You approved $fullName's application in $clubName" 
                : "You disapproved $fullName's application in $clubName"; // Construct the activity message based on approval/disapproval
    $dateAdded = date('Y-m-d H:i:s'); // Get the current timestamp

    // Assuming you have a way to get the current moderator ID, replace `current_moderator_id` with the actual variable
    $moderator_id = $_SESSION['moderator_id']; // Assuming you have this session variable set during login

    // Bind parameters and execute the log insertion (student_id set as NULL)
    $stmt->execute([
        'activity' => $activity,
        'dateAdded' => $dateAdded,
        'moderator_id' => $moderator_id
    ]);
}


// Fetch the student's application status for the current club
$stmt = $pdo->prepare("SELECT status FROM tbl_application WHERE student_id = :student_id AND club_id = :club_id");
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt->execute();
$status = $stmt->fetchColumn(); // Fetch the application status (e.g., 'active', 'pending', etc.)

// Count how many clubs the student is currently "active" in
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_application WHERE student_id = :student_id AND status = 'active'");
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$stmt->execute();
$clubsCount = $stmt->fetchColumn();

// Count how many times the student has been "disapproved" for this club
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_application WHERE student_id = :student_id AND club_id = :club_id AND status = 'disapproved'");
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt->execute();
$disapprovedCount = $stmt->fetchColumn();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Pending Approval Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row mt-5 mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Pending Approval</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="<?php echo $profilePic; ?>" 
                                 alt="<?php echo htmlspecialchars($fullName); ?> Profile Picture" 
                                 class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
                        </div>
                        <div class="col-md-9">
                            <h3 class="text-muted mb-3"><?php echo htmlspecialchars($fullName); ?></h3>
                            <p><strong>Student ID: </strong><?php echo $student_id; ?></p>
                            
                            <!-- <p><strong>Application ID: </strong><?php echo $application_id; ?></p> -->
                            <hr>

                            <h5>Application Details:</h5>
                            <!-- Display the questions -->
                            <div class="container mt-3 p-0">
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <p><strong>Why do you want to join this club?</strong><br><?php echo htmlspecialchars($questions['question1']); ?></p>
                                    </div>
                                </div>
                                
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <p><strong>What skills or experiences do you have that will contribute to the club's activities?</strong><br><?php echo htmlspecialchars($questions['question2']); ?></p>
                                    </div>
                                </div>
                                
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <p><strong>How do you plan to balance your time between club activities and your academic responsibilities?</strong><br><?php echo htmlspecialchars($questions['question3']); ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <p>Date Applied: 
                                    <?php 
                                    $formattedDate = (new DateTime($dateApplied))->format('F d, Y'); 
                                    echo htmlspecialchars($formattedDate); 
                                    ?>
                                </p>
                            </div>


                        </div>
                    </div>

                    <div class="club-register-now mt-4 text-center align-items-center justify-content-center"> 
                        <?php if ($clubsCount >= 2): ?>
                            <div class="alert alert-warning custom-alert" role="alert">
                                <p class="lead mb-0">This student has reached the maximum of 2 club memberships allowed.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


                <!-- Approval Form and Buttons -->
                <div class="card-footer text-center">
                    <form id="approvalForm" method="post">
                        <input type="hidden" name="action" id="action" value="">
                        <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($param_application_id); ?>">
                        <input type="hidden" name="remark" id="remark" value=""> <!-- Hidden field for remark -->
                        <?php if ($clubsCount >= 2): ?>
                            <a href="javascript:window.history.back();" class="btn btn-secondary">Go Back</a>
                        <?php else: ?>
                            <button type="button" onclick="confirmAction('approve', <?php echo $param_application_id; ?>)" class="btn btn-success">Approve Student</button>
                            <button type="button" onclick="confirmAction('disapprove', <?php echo $param_application_id; ?>)" class="btn btn-danger">Disapprove Student</button>
                            <a href="javascript:window.history.back();" class="btn btn-secondary">Go Back</a>
                        <?php endif; ?>
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>

<!-- HERE -->

<script>
    function confirmAction(action, applicationId) {
        const confirmation = confirm(`Are you sure you want to ${action} this student?`);
        if (confirmation) {
            document.getElementById('action').value = action;
            document.getElementById('application_id').value = applicationId; // Set the application ID
            showRemarksModal();
        }
    }

    function showRemarksModal() {
        document.getElementById('remarksModal').style.display = 'block';
    }

    function submitRemarks(event) {
        event.preventDefault(); // Prevent the default form submission

        const remark = document.querySelector('textarea[name="remark"]').value; // Get the remark
        document.getElementById('remark').value = remark; // Set the remark in the hidden input

        // Now submit the approval form
        document.getElementById('approvalForm').submit();
    }

</script>


<!-- Remarks Modal -->
<div id="remarksModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: white; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px;">
        <span style="color: #333; font-weight: bold;">Leave a Remark</span>
        <p>Your feedback helps the student understand the outcome of their application.</p>
        <form id="remarksForm" action="" method="POST" onsubmit="submitRemarks(event)">
            <input type="hidden" id="application_id" name="application_id" value=""> <!-- Hidden field for application ID -->
            <div class="form-group">
                <textarea name="remark" class="form-control" rows="3" placeholder="Enter your remarks here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mb-1">Submit Remark</button>
            <button type="button" class="btn btn-secondary mb-1" onclick="document.getElementById('remarksModal').style.display='none'">Cancel</button>
        </form>
    </div>
</div>


        <!-- <php echo applicationId; ?> -->
<!-- <input type="hidden" id="application_id" name="application_id" value="<php echo applicationId; ?>"> -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
</script>
</body>
</html>
