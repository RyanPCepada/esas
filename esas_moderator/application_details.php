<?php 
require_once "../config.php"; // Database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    echo "Moderator ID is not set in the session.";
    exit;
}

// Application
$moderator_id = $_SESSION['moderator_id']; // Get moderator ID from session

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get club_id and application_id from the URL
$club_id = isset($_GET['club_id']) ? $_GET['club_id'] : null;
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : null;
$application_id = isset($_GET['application_id']) ? $_GET['application_id'] : null;
$fullName = isset($_GET['fullName']) ? $_GET['fullName'] : null;

if (!$club_id || !$application_id) {
    echo "Club ID or Application ID is not provided.";
    exit;
}

// Fetch student's data
$sql = "SELECT * FROM tbl_students WHERE student_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "Student not found.";
    exit;
}

// Fetch application details along with the moderator's name for the specified club and student
$sql_application = "SELECT 
                        a.remark, a.status, a.dateApplied, a.dateDecided, a.dateModified, 
                        m.firstName, m.middleName, m.lastName 
                    FROM tbl_application a
                    JOIN tbl_moderators m ON a.moderator_id = m.moderator_id
                    WHERE a.student_id = ? AND a.club_id = ? AND a.application_id = ?";

$stmt_application = $pdo->prepare($sql_application);
$stmt_application->execute([$student_id, $club_id, $application_id]);
$application = $stmt_application->fetch(PDO::FETCH_ASSOC);

if ($application) {
    // Combine firstName, middleName, and lastName into a single full name
    $moderatorFullName = trim("{$application['firstName']} {$application['middleName']} {$application['lastName']}");
}


if (!$application) {
    echo "Application not found.";
    exit;
}

// Fetch club name
$sql_club = "SELECT clubName FROM tbl_clubs WHERE club_id = ?";
$stmt_club = $pdo->prepare($sql_club);
$stmt_club->execute([$club_id]);
$club = $stmt_club->fetch(PDO::FETCH_ASSOC);

if (!$club) {
    echo "Club not found.";
    exit;
}

// Fetch questions and answers directly from tbl_application_answers
$sql_questions_answers = "SELECT a.answer, a.question 
                          FROM tbl_application_answers a 
                          WHERE a.application_id = ? 
                          AND a.student_id = ? 
                          AND a.club_id = ?";
$stmt_questions_answers = $pdo->prepare($sql_questions_answers);
$stmt_questions_answers->execute([$application_id, $student_id, $club_id]);
$questions_answers = $stmt_questions_answers->fetchAll(PDO::FETCH_ASSOC);

// Fetch activities of the current student
$sql_activities = "SELECT * FROM tbl_activity_logs WHERE student_id = ? ORDER BY dateAdded DESC";
$stmt_activities = $pdo->prepare($sql_activities);
$stmt_activities->execute([$student_id]);
$activities = $stmt_activities->fetchAll(PDO::FETCH_ASSOC);

// Format date function
function formatDate($date) {
    return (new DateTime($date))->format('F j, Y');
}

// Determine the status and icon based on the fetched application
$status = strtolower($application['status']);
$icon = '';
switch ($status) {
    case 'active':
        $icon = '<i class="fas fa-check-circle text-success"></i>';
        break;
    case 'disapproved':
        $icon = '<i class="fas fa-times-circle text-danger"></i>';
        break;
    case 'inactive':
        $icon = '<i class="fas fa-user-times text-warning"></i>';
        break;
    case 'departed':
        $icon = '<i class="fas fa-user-slash text-secondary"></i>';
        break;
    case 'pending':
    default:
        $icon = '<i class="fas fa-hourglass-start text-warning"></i>';
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Application Details</title>
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

        .back-button {
            position: absolute;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 33px;
            height: 33px;
            border-radius: 50%;
            background-color: lightgrey;
            color: #36454F;
            font-size: 18px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: grey;
            color: white;
        }
        
        @media (max-width: 767px) {
            h2 {
                margin-top: 5px;
            }
            .back-button {
                position: relative;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
    <a href="javascript:history.back()" class="back-button">
        <i class="fa fa-arrow-left"></i>
    </a>
        <h2 class="mt-5">Application Details</h2>
        <div class="justify-content-between">
            <p class="text-muted">Review <strong><?php echo htmlspecialchars($fullName); ?></strong>'s application details for <strong><?php echo htmlspecialchars($club['clubName']); ?></strong></p>
            <p>Status: <?php echo $icon; ?> <strong><?php echo ucfirst($status); ?></strong></p>
        </div>
        <div class="container mb-5">
            <div class="row">
                <div class="col">
                    <?php foreach ($questions_answers as $qa): ?>
                        <p><strong><?php echo htmlspecialchars($qa['question']); ?></strong><br><?php echo htmlspecialchars($qa['answer']); ?></p>
                    <?php endforeach; ?>
                    
                    <?php if ($status === 'disapproved'): ?>
                        <p class="text-danger"><strong>Disapproval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remarks available.'; ?></p>
                        <p class="text-primary"><strong>Disapproved by:</strong><br><?php echo !empty($moderatorFullName) ? htmlspecialchars($moderatorFullName) : 'No moderator available.'; ?></p>
                        <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                        <p><strong>Date Disapproved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                    <?php elseif ($status === 'active'): ?>
                        <p class="text-danger"><strong>Approval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remarks available.'; ?></p>
                        <p class="text-primary"><strong>Approved by:</strong><br><?php echo !empty($moderatorFullName) ? htmlspecialchars($moderatorFullName) : 'No moderator available.'; ?></p>
                        <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                        <p><strong>Date Approved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                    <?php elseif ($status === 'inactive'): ?>
                        <p class="text-danger"><strong>Approval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remarks available.'; ?></p>
                        <p class="text-primary"><strong>Approved by:</strong><br><?php echo !empty($moderatorFullName) ? htmlspecialchars($moderatorFullName) : 'No moderator available.'; ?></p>
                        <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                        <p><strong>Date Inactivated:</strong> <?php echo formatDate($application['dateModified']); ?></p>
                    <?php elseif ($status === 'departed'): ?>
                        <p class="text-danger"><strong>Approval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remarks available.'; ?></p>
                        <p class="text-primary"><strong>Approved by:</strong><br><?php echo !empty($moderatorFullName) ? htmlspecialchars($moderatorFullName) : 'No moderator available.'; ?></p>
                        <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                        <p><strong>Date Departed:</strong> <?php echo formatDate($application['dateModified']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
