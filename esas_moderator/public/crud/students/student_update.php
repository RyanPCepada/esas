<?php 
// Database connection
require_once "../../../../config.php";
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get student_id and club_id from URL parameters
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : null;
$club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : null;

// Ensure both student_id and club_id are present
if (!$student_id || !$club_id) {
    die("Invalid student ID or club ID.");
}

// Fetch student details (assuming you have a table named tbl_students)
$sql = "SELECT firstName, lastName, profilePic FROM tbl_students WHERE student_id = :student_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $student_id);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch the current status of the student for the specific club
$statusSql = "SELECT status FROM tbl_registration WHERE student_id = :student_id AND club_id = :club_id";
$statusStmt = $pdo->prepare($statusSql);
$statusStmt->bindParam(":student_id", $student_id);
$statusStmt->bindParam(":club_id", $club_id);
$statusStmt->execute();
$currentStatusRow = $statusStmt->fetch(PDO::FETCH_ASSOC);
$current_status = $currentStatusRow ? $currentStatusRow['status'] : 'active'; // Default to 'active' if no status found

// Define variables and initialize with empty values
$update_status = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST["status"];
    
    // Prepare an update statement to set the student's status for the specific club
    $sql = "UPDATE tbl_registration 
            SET status = :status, dateModified = NOW() 
            WHERE student_id = :student_id AND club_id = :club_id";
    
    if ($stmt = $pdo->prepare($sql)) {
        // Bind parameters
        $stmt->bindParam(":student_id", $student_id);
        $stmt->bindParam(":club_id", $club_id);  // Include the club_id in the WHERE clause
        $stmt->bindParam(":status", $status);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Prepare to log the activity
            $activity = "You updated " . htmlspecialchars($student['firstName'] . ' ' . $student['lastName']) . " status into " . htmlspecialchars($status);

            // Insert activity log
            $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) 
                        VALUES (:activity, NOW(), :moderator_id)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->bindParam(":activity", $activity);
            // $logStmt->bindParam(":student_id", $student_id);
            // $logStmt->bindParam(":admin_id", $admin_id); // Replace with the actual admin ID
            $logStmt->bindParam(":moderator_id", $moderator_id); // Replace with the actual moderator ID
            $logStmt->execute(); // Execute the logging statement

            // Status updated successfully
            header("Location: student_read.php?student_id=$student_id&club_id=$club_id"); // Redirect to the correct page
            exit(); // Always use exit() after header redirection
        } else {
            $update_status = "Error: Unable to update student status. Please try again.";
        }
    }
    
    // Close statement
    unset($stmt);
    unset($logStmt); // Close log statement if used
    // Close connection
    unset($pdo);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Status</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/nbsclogo.png" rel="icon">
    <style>
        .card {
            max-width: 700px;
            margin: 0 auto;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .student-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .student-info div {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4><i class="fas fa-user-edit"></i> Update Student Status</h4>
            </div>
            <div class="card-body">

                <!-- Student Info -->
                <div class="student-info row">
                    <div class="col-md-4 text-start">
                        <img src="<?php echo "/esas/esas_student/images/" . htmlspecialchars($student['profilePic']); ?>" alt="Profile Picture" class="profile-img">
                    </div>
                    <div class="col-md-8 text-start">
                        <h5><?php echo htmlspecialchars($student['firstName'] . ' ' . $student['lastName']); ?></h5>
                        <p>ID: <?php echo htmlspecialchars($student_id); ?></p>
                    </div>
                </div>

                <!-- Update form -->
                <form id="statusForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?student_id=$student_id&club_id=$club_id"; ?>" method="post">
                    <div class="form-group px-3">
                        <label for="status">Change Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" <?php echo ($current_status === 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($current_status === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            <option value="departed" <?php echo ($current_status === 'departed') ? 'selected' : ''; ?>>Departed</option>
                        </select>
                    </div>
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-warning" onclick="confirmUpdate()">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                        <!-- Cancel button -->
                        <button type="button" class="btn btn-secondary" onclick="goBack()">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for showing alert message -->
    <script>
        // Confirmation and status update alert
        function confirmUpdate() {
            var status = document.getElementById("status").value;
            var currentStatus = "<?php echo $current_status; ?>"; // Get the current status from PHP

            if (status === currentStatus) {
                alert("No changes made."); // No changes to be made
            } else {
                var confirmationMessage = "Are you sure you want to update the student's status to " + status + "?";

                if (confirm(confirmationMessage)) {
                    // Submit the form and show the successful update alert
                    document.getElementById("statusForm").submit();
                    alert("Student's status updated successfully.");
                }
            }
        }

        // Redirect to the previous page on cancel
        function goBack() {
            window.location.href = "student_read.php?student_id=<?php echo $student_id; ?>&club_id=<?php echo $_GET['club_id']; ?>"; // Update with the correct page
        }

        // Show a PHP status update alert if needed
        <?php if (!empty($update_status)): ?>
            alert("<?php echo $update_status; ?>");
            // Redirect after updating status
            window.location.href = "student_read.php?student_id=<?php echo $student_id; ?>&club_id=<?php echo $_GET['club_id']; ?>";
        <?php endif; ?>
    </script>

</body>
</html>
