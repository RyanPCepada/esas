<?php 
require_once "../config.php"; // Database config file
session_start();

if (!isset($_SESSION['student_id'])) {
    echo "Student ID is not set in the session.";
    exit;
}

$student_id = $_SESSION['student_id']; // Get student ID from session

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get club_id and registration_id from the URL
$club_id = isset($_GET['club_id']) ? $_GET['club_id'] : null;
$registration_id = isset($_GET['registration_id']) ? $_GET['registration_id'] : null;

// Check if club_id and registration_id are provided
if (!$club_id) {
    echo "Club ID is not provided.";
    exit;
} else if (!$registration_id) {
    echo "Registration ID is not provided.";
    exit;
}

// Fetch student's data
$sql = "SELECT * FROM tbl_students WHERE student_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array

// Check if the student was found
if (!$student) {
    echo "Student not found.";
    exit; // Exit if the student is not found
}

// Fetch application details for the specified club and student
$sql_application = "SELECT *, remarks FROM tbl_registration 
                    WHERE student_id = ? 
                    AND club_id = ? 
                    AND (registration_id = ? OR (status = 'pending' AND registration_id IS NOT NULL))
                    AND (status IN ('pending', 'active', 'disapproved'))"; // Include 'active'
                    //AND (status = 'pending' OR status = 'active' OR status = 'disapproved')";
$stmt_application = $pdo->prepare($sql_application);
$stmt_application->execute([$student_id, $club_id, $registration_id]);
$application = $stmt_application->fetch(PDO::FETCH_ASSOC); // Fetch application details


// Check if the application was found
if (!$application) {
    echo "Application not found.";
    exit; // Exit if the application is not found
}

// Fetch the club name
$sql_club = "SELECT clubName FROM tbl_clubs WHERE club_id = ?";
$stmt_club = $pdo->prepare($sql_club);
$stmt_club->execute([$club_id]);
$club = $stmt_club->fetch(PDO::FETCH_ASSOC); // Fetch club details

// Check if the club was found
if (!$club) {
    echo "Club not found.";
    exit; // Exit if the club is not found
}

// Fetch activities of the current student
$sql_activities = "SELECT * FROM tbl_activity_logs WHERE student_id = ? ORDER BY dateAdded DESC";
$stmt_activities = $pdo->prepare($sql_activities);
$stmt_activities->execute([$student_id]);
$activities = $stmt_activities->fetchAll(PDO::FETCH_ASSOC); // Fetch all activities

// Format date function
function formatDate($date) {
    return (new DateTime($date))->format('F j, Y');
}

// Determine the status and icon based on the fetched application
$status = strtolower($application['status']); // Get the status from the application details
$icon = '';
switch ($status) {
    case 'approved':
        $icon = '<i class="fas fa-check-circle text-success"></i>'; // Approved icon
        break;
    case 'disapproved':
        $icon = '<i class="fas fa-times-circle text-danger"></i>'; // Disapproved icon
        break;
    case 'active':
        $icon = '<i class="fas fa-check-circle text-primary"></i>'; // Active icon
        break;
    case 'pending':
    default:
        $icon = '<i class="fas fa-hourglass-start text-warning"></i>'; // Pending icon
        break;
}

// Display the status
// echo "Status: " . $icon . " <strong>" . ucfirst($status) . "</strong><br>";
// echo "Club ID: " . htmlspecialchars($club_id) . "<br>";
// echo "Registration ID: " . htmlspecialchars($registration_id) . "<br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Application Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            max-width: 700px; /* Increased to occupy more space */
            margin: 0 auto;
            padding: 15px;
        }
        .container {
            height: auto;
            background-color: white;
            padding: 25px;
        }
    </style>
</head>
<body>
    
    <div class="wrapper">
            <h2 class="mt-5">Application Details</h2>
            <div class="justify-content-between">
                <p class="text-muted">Review your application details for <strong><?php echo htmlspecialchars($club['clubName']); ?></strong></p>
                <p>Status: <?php echo $icon; ?> <strong><?php echo ucfirst($status); ?></strong></p>
            </div>
            <div class="container-fluid container auto-scroll">
                <div class="row">
                    <div class="col">
                        <p><strong>Why do you want to join this club?</strong><br><?php echo htmlspecialchars($application['question1']); ?></p>
                        <p><strong>What skills or experiences do you have that will contribute to the club's activities?</strong><br><?php echo htmlspecialchars($application['question2']); ?></p>
                        <p><strong>How do you plan to balance your time between club activities and your academic responsibilities?</strong><br><?php echo htmlspecialchars($application['question3']); ?></p>
                        <hr>
                        
                        <?php if ($status === 'pending'): ?>
                            <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                        <?php elseif ($status === 'disapproved'): ?>
                            <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                            <p><strong>Date Disapproved:</strong> <?php echo formatDate($application['dateApproved']); ?></p>
                            <p><strong>Remarks:</strong> <?php echo !empty($application['remarks']) ? htmlspecialchars($application['remarks']) : 'No remarks available.'; ?></p>
                        <?php elseif ($status === 'active'): ?>
                            <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                            <p><strong>Date Approved:</strong> <?php echo formatDate($application['dateApproved']); ?></p>
                            <p><strong>Remarks:</strong> <?php echo !empty($application['remarks']) ? htmlspecialchars($application['remarks']) : 'No remarks available.'; ?></p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
    </div>
</body>
</html>
