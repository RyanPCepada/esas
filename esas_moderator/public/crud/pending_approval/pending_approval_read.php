<?php
// Include config file
require_once "../../../../config.php";

// Check existence of student_id, club_id, and registration_id parameter before processing further
if (isset($_GET["student_id"]) && !empty(trim($_GET["student_id"])) 
    && isset($_GET["club_id"]) && !empty(trim($_GET["club_id"])) 
    && isset($_GET["registration_id"]) && !empty(trim($_GET["registration_id"]))) {

    // Set parameters
    $param_student_id = trim($_GET["student_id"]);
    $param_club_id = trim($_GET["club_id"]);
    $param_registration_id = trim($_GET["registration_id"]); // Get registration_id from URL

    // Prepare a select statement to fetch the student details
    $sql = "SELECT * FROM tbl_students WHERE student_id = :student_id";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":student_id", $param_student_id);

        // Set parameters
        $param_student_id = trim($_GET["student_id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                // Fetch result row as an associative array
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $student_id = $row["student_id"];
                $firstName = !empty($row["firstName"]) ? $row["firstName"] : '';
                $middleName = !empty($row["middleName"]) ? $row["middleName"] : '';
                $lastName = !empty($row["lastName"]) ? $row["lastName"] : '';
                $fullName = trim("$firstName $middleName $lastName");

                // For profile picture
                $profilePic = !empty($row['profilePic']) ? '/esas/esas_student/images/' . $row['profilePic'] : 'No Image Available';
            } else {
                // Redirect to error page if no valid id is found
                header("location: ../public/error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    unset($stmt);


    // Prepare a select statement to fetch questions and dateApplied from tbl_registration
    $sql_questions = "SELECT question1, question2, question3, dateApplied FROM tbl_registration WHERE student_id = :student_id AND club_id = :club_id";

    if ($stmt = $pdo->prepare($sql_questions)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":student_id", $param_student_id);
        $stmt->bindParam(":club_id", $param_club_id);

        // Set parameters
        $param_student_id = trim($_GET["student_id"]);
        $param_club_id = trim($_GET["club_id"]); 

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Fetch the questions and dateApplied together in one call
            $questions = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch all as associative array

            if ($questions) {
                $dateApplied = $questions['dateApplied']; // Date applied from the fetched array
            } else {
                echo "No registration details found.";
            }
        } else {
            echo "Could not fetch questions. Please try again later.";
        }
    }


    // Close statement
    unset($stmt);
    
} else {
    // URL doesn't contain student_id or club_id parameter. Redirect to error page
    header("location: ../public/error.php");
    exit();
}

// Handle approval or disapproval
if (isset($_POST["action"]) && in_array($_POST["action"], ['approve', 'disapprove'])) {
    // Set new status based on action
    $newStatus = $_POST["action"] === 'approve' ? 'active' : 'disapproved';

    // Prepare the SQL statement to update the specific registration record
    $updateSql = "UPDATE tbl_registration 
                  SET status = :status, dateApproved = NOW() 
                  WHERE student_id = :student_id 
                  AND club_id = :club_id 
                  AND registration_id = :registration_id"; // Include registration_id

    if ($updateStmt = $pdo->prepare($updateSql)) {
        // Bind parameters
        $updateStmt->bindParam(":status", $newStatus);
        $updateStmt->bindParam(":student_id", $param_student_id, PDO::PARAM_INT);
        $updateStmt->bindParam(":club_id", $param_club_id, PDO::PARAM_INT);
        $updateStmt->bindParam(":registration_id", $param_registration_id, PDO::PARAM_INT); // Bind registration_id

        // Execute the update statement
        if ($updateStmt->execute()) {
            header("location: ../../pending_approvals.php");
            exit();
        } else {
            echo "Error updating the request. Please try again.";
            exit();
        }
    }
    unset($updateStmt);
}


    
// Fetch the student's registration status for the current club
$stmt = $pdo->prepare("SELECT status FROM tbl_registration WHERE student_id = :student_id AND club_id = :club_id");
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$stmt->bindParam(':club_id', $club_id, PDO::PARAM_INT);
$stmt->execute();
$status = $stmt->fetchColumn(); // Fetch the registration status (e.g., 'active', 'pending', etc.)

// Count how many clubs the student is currently "active" in
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_registration WHERE student_id = :student_id AND status = 'active'");
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$stmt->execute();
$clubsCount = $stmt->fetchColumn();

// Count how many times the student has been "disapproved" for this club
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_registration WHERE student_id = :student_id AND club_id = :club_id AND status = 'disapproved'");
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
    <title>eSAS - Pending Approvals</title>
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
                            <hr>

                            <h5>Registration Details:</h5>
                            <!-- Display the questions -->
                            <div class="container mt-3 p-0">
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <p><strong>Why do you want to join this club?:</strong><br><?php echo htmlspecialchars($questions['question1']); ?></p>
                                    </div>
                                </div>
                                
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <p><strong>What skills or experiences do you have that will contribute to the club's activities?:</strong><br><?php echo htmlspecialchars($questions['question2']); ?></p>
                                    </div>
                                </div>
                                
                                <div class="card mb-3 bg-light">
                                    <div class="card-body">
                                        <p><strong>How do you plan to balance your time between club activities and your academic responsibilities?:</strong><br><?php echo htmlspecialchars($questions['question3']); ?></p>
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


                <script>
                    function confirmAction(action) {
                        const confirmation = confirm(`Are you sure you want to ${action} this student?`);
                        if (confirmation) {
                            document.getElementById('action').value = action;
                            document.getElementById('approvalForm').submit();
                        }
                    }
                </script>


                <div class="card-footer text-center">
                    <form id="approvalForm" method="post">
                        <input type="hidden" name="action" id="action" value="">
                        <input type="hidden" name="registration_id" value="<?php echo htmlspecialchars($param_registration_id); ?>">
                        <?php if ($clubsCount >= 2): ?>
                            <!-- <button type="button" onclick="confirmAction('disapprove')" class="btn btn-danger">Disapprove Student</button> -->
                            <a href="javascript:window.history.back();" class="btn btn-secondary">Back to Students List</a>
                        <?php else: ?>
                            <button type="button" onclick="confirmAction('approve')" class="btn btn-success">Approve Student</button>
                            <button type="button" onclick="confirmAction('disapprove')" class="btn btn-danger">Disapprove Student</button>
                            <a href="javascript:window.history.back();" class="btn btn-secondary">Back to Students List</a>
                        <?php endif; ?>
                    </form>
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
