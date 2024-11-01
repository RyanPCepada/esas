<?php

require_once "../../../../config.php"; 
require __DIR__ . '/../../../vendor/autoload.php';

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure the moderator ID is set in the session
if (isset($_SESSION['admin_id'])) {
    $adminId = $_SESSION['admin_id'];
} else {
    echo json_encode(['error' => 'Admin not logged in.']);
    exit;
}

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (isset($_GET["request_id"]) && !empty(trim($_GET["request_id"]))) { 
    $request_id = trim($_GET["request_id"]); 
    $sql = "SELECT 
                r.request_id, 
                r.clubName, 
                r.goal, 
                r.mission,  
                r.vision,   
                r.activities, 
                r.status, 
                r.coverPhoto, 
                r.requestLetter,
                r.dateRequested, 
                r.dateModified,
                s.firstName,
                s.lastName,
                s.profilePic,
                s.student_id
            FROM tbl_club_requests r 
            JOIN tbl_students s ON r.student_id = s.student_id 
            WHERE r.request_id = :request_id"; 

    if ($stmt = $pdo->prepare($sql)) { 
        $stmt->bindParam(":request_id", $request_id, PDO::PARAM_INT); 

        if ($stmt->execute()) { 
            if ($stmt->rowCount() == 1) { 
                $row = $stmt->fetch(PDO::FETCH_ASSOC); 

                // Initialize variables
                $clubName = htmlspecialchars($row["clubName"] ?? ''); 
                $goal = htmlspecialchars($row["goal"] ?? ''); 
                $mission = htmlspecialchars($row["mission"] ?? ''); // New mission variable
                $vision = htmlspecialchars($row["vision"] ?? '');   // New vision variable
                $activities = htmlspecialchars($row["activities"] ?? ''); 
                $status = strtolower(htmlspecialchars($row["status"] ?? '')); 
                $coverPhoto = htmlspecialchars($row["coverPhoto"] ?: "default-cover.jpg"); 
                $requestLetter = htmlspecialchars($row["requestLetter"] ?? ''); // New requestLetter variable
                $dateRequested = !empty($row["dateRequested"]) ? date("F j, Y", strtotime($row["dateRequested"])) : 'None';
                $dateModified = !empty($row["dateModified"]) ? date("F j, Y", strtotime($row["dateModified"])) : 'None';
                $requestedByName = htmlspecialchars($row["firstName"] . ' ' . $row["lastName"] ?? '');
                $profilePic = htmlspecialchars($row["profilePic"] ?: "default-profile.jpg");
                $student_id = htmlspecialchars($row["student_id"] ?? ''); 

                // Check if the club already exists in tbl_clubs
                $clubExists = false;
                $sqlCheckClub = "SELECT COUNT(*) as count FROM tbl_clubs WHERE clubName = :clubName";
                if ($stmtCheck = $pdo->prepare($sqlCheckClub)) {
                    $stmtCheck->bindParam(":clubName", $clubName, PDO::PARAM_STR);
                    if ($stmtCheck->execute()) {
                        $rowCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                        if ($rowCheck['count'] > 0) {
                            $clubExists = true;
                        }
                    }
                    unset($stmtCheck);
                }
                
            } else { 
                echo "No club request found with this ID."; 
                exit(); 
            } 
        } else { 
            echo "Database query failed. Please try again later."; 
            exit(); 
        } 
    } 
    unset($stmt); 
} else { 
    header("location: error.php"); 
    exit(); 
} 


// Handle approval or disapproval
if (isset($_POST["action"]) && in_array($_POST["action"], ['approve', 'disapprove'])) {
    $newStatus = $_POST["action"] === 'approve' ? 'approved' : 'disapproved'; 
    $dateDecided = ($newStatus === 'approved') ? date('Y-m-d H:i:s') : null; 

    // Update the club request status
    $updateSql = "UPDATE tbl_club_requests 
                   SET status = :status, 
                       dateDecided = :dateDecided 
                   WHERE request_id = :request_id";

    if ($updateStmt = $pdo->prepare($updateSql)) {
        $updateStmt->bindParam(":status", $newStatus);
        if ($newStatus === 'approved') {
            $updateStmt->bindParam(":dateDecided", $dateDecided); 
        } else {
            $nullValue = null; 
            $updateStmt->bindParam(":dateDecided", $nullValue, PDO::PARAM_NULL);
        }
        $updateStmt->bindParam(":request_id", $request_id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {

            // Insert into activity logs after updating the request status
            $activity = "You " . $newStatus . " " . $requestedByName . "'s club request";
            $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id)
                       VALUES (:activity, :dateAdded, :admin_id)";

            if ($logStmt = $pdo->prepare($logSql)) {
                $logStmt->bindParam(":activity", $activity);
                $logStmt->bindParam(":dateAdded", date('Y-m-d H:i:s')); 
                $logStmt->bindParam(":admin_id", $adminId, PDO::PARAM_INT); 

                // Execute the log statement
                $logStmt->execute();
            }

            // Fetch requester instiEmail to notify them about the decision
            $studentEmailSql = "SELECT instiEmail FROM tbl_students WHERE student_id = :student_id";
            $studentEmailStmt = $pdo->prepare($studentEmailSql);
            $studentEmailStmt->execute([':student_id' => $student_id]);
            $student = $studentEmailStmt->fetch(PDO::FETCH_ASSOC);
            $studentEmail = $student['instiEmail'];

          

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username   = 'sportsnbscesas@gmail.com';
            $mail->Password   = 'wubj bmsj ckmj nope'; 
     
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('sportsnbscesas@gmail.com', 'Club Request Notification');
                       

            // Send instiEmail to the requester
            try {
                $mail->addAddress($studentEmail); 
                $mail->isHTML(true);
                $mail->Subject = "Your Club Request Has Been " . ucfirst($newStatus);
                $mail->Body = "
                    <p>Dear Student,</p>
                    <p>Your request to form a club has been <strong>" . $newStatus . "</strong>.</p>
                    <p>If you have any questions, feel free to contact us.</p>
                    <p>Thank you!</p>";

                $mail->send();
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
            }

            header("location: club_request_read.php?request_id=" . $request_id . "&status=" . strtolower($newStatus));
            exit();
        } else {
            echo "Error updating the request. Please try again.";
            exit();
        }
    }
    unset($updateStmt);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Club Request Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Club Request Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="/esas/esas_student/images/<?php echo $coverPhoto; ?>" 
                                    alt="<?php echo $clubName; ?> Cover Photo" 
                                    class="img-fluid" style="width: 300px; height: auto; border-radius: 5px; object-fit: cover;">
                                    <h4 class="text-muted mt-3"><?php echo $clubName; ?></h4>
                                    
                                    <div class="card p-3 bg-light" style="width: auto; margin-top: 70px; border-radius: 15px;">
                                        <?php if (!empty($requestLetter)): 
                                            $fileType = pathinfo($requestLetter, PATHINFO_EXTENSION);
                                            $fileIcon = ($fileType === 'pdf') ? '/esas/esas_student/icons/ICON_PDF.png' : ''; 
                                            $linkText = 'View Attached Request Letter'; ?>
                                            <div class="d-flex align-items-center justify-content-start" style="cursor: pointer;" onclick="window.open('/esas/esas_student/request_letters/<?php echo $requestLetter; ?>', '_blank')">
                                                <img src="<?php echo $fileIcon; ?>" alt="<?php echo strtoupper($fileType); ?> File" style="width: 70px; margin-right: 10px;">
                                                <a href="/esas/esas_student/request_letters/<?php echo $requestLetter; ?>" target="_blank" style="color: blue; text-decoration: underline;"><?php echo $linkText; ?></a>
                                            </div>
                                        <?php else: ?>
                                            <p>No attached request letter.</p>
                                        <?php endif; ?>
                                    </div>

                            </div>


                            <div class="col-md-8">
                                <p>
                                    <strong>Request from: </strong><br>
                                    <img class="mt-2 mb-0" src="/esas/esas_student/images/<?php echo $profilePic; ?>" 
                                        alt="Profile Picture" 
                                        style="width: 100px; height: auto; border-radius: 50%;" />
                                    <h3 class="text-muted"><?php echo $requestedByName; ?></h3>
                                    <p><strong>Student ID: </strong><?php echo $student_id; ?></p>
                                </p>
                                <hr>
                                <div class="container mt-3 p-0">
                                    <p><strong>Goal: </strong><br><?php echo htmlspecialchars_decode($goal); ?></p>
                                    <p><strong>Mission: </strong><br><?php echo htmlspecialchars_decode($mission); ?></p>
                                    <p><strong>Vision: </strong><br><?php echo htmlspecialchars_decode($vision); ?></p>
                                    <p><strong>Activities: </strong><br><?php echo htmlspecialchars_decode($activities); ?></p>
                                    <hr>
                                    <p><strong>Status: </strong><?php echo htmlspecialchars_decode($status); ?></p>
                                    <p><strong>Date Requested: </strong><?php echo htmlspecialchars_decode($dateRequested); ?></p> 
                                </div>
                            </div>
                        </div>

                        <div class="text-center d-flex justify-content-center mt-3">
                            <?php if ($status === 'approved' && $clubExists): ?>
                                <div class="alert alert-info custom-alert" role="alert">
                                    <p class="lead mb-0">This club request has already been added to the clubs list.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>


                    <script>
                        function confirmAction(action) {
                            const confirmation = confirm(`Are you sure you want to ${action} this request?`);
                            if (confirmation) {
                                document.getElementById('action').value = action;
                                document.getElementById('approvalForm').submit();
                            }
                        }
                    </script>

                    <div class="card-footer text-center">
                        <?php if ($status === 'pending'): ?>
                            <form id="approvalForm" method="post">
                                <input type="hidden" name="action" id="action" value="">
                                <button type="button" onclick="confirmAction('approve')" class="btn btn-success">Approve</button>
                                <button type="button" onclick="confirmAction('disapprove')" class="btn btn-danger">Disapprove</button>
                                <a href="../../club_requests.php" class="btn btn-secondary">Go Back</a>
                            </form>
                        <?php elseif ($status === 'approved' && $clubExists): ?>
                            <a href="../../club_requests.php" class="btn btn-secondary">Go Back</a>
                        <?php elseif ($status === 'approved'): ?>
                            <a href="../../crud/club_requests/club_add_request.php?clubName=<?php echo urlencode($clubName); ?>&coverPhoto=<?php echo urlencode($coverPhoto); ?>&student_id=<?php echo urlencode($student_id); ?>
                            &mission=<?php echo urlencode($mission); ?>&vision=<?php echo urlencode($vision); ?>&requestedByName=<?php echo urlencode($requestedByName); ?>"
                             class="btn btn-success">Add to Clubs List</a>
                            <a href="../../club_requests.php" class="btn btn-secondary">Go Back</a>
                        <?php else: ?>
                            <a href="../../club_requests.php" class="btn btn-secondary">Go Back</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
