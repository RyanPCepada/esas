<?php 
require_once "../../config.php"; // Database config file
session_start();

if (!isset($_SESSION['student_id'])) {
    echo "Student ID is not set in the session.";
    exit;
}

$student_id = $_SESSION['student_id']; // Get student ID from session
date_default_timezone_set('Asia/Manila'); // Set timezone

// Get club_id and application_id from the URL
$club_id = isset($_GET['club_id']) ? $_GET['club_id'] : null;
$application_id = isset($_GET['application_id']) ? $_GET['application_id'] : null;
$fromPendingPage = isset($_GET['from_pending_page']) ? $_GET['from_pending_page'] : null;

// Check if club_id and application_id are provided
if (!$club_id) {
    echo "Club ID is not provided.";
    exit;
} elseif (!$application_id) {
    echo "Application ID is not provided.";
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
                    WHERE a.student_id = ? AND a.club_id = ? AND a.application_id = ?
                    AND a.status IN ('pending', 'active', 'disapproved')";

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


// Determine the status icon
$status = strtolower($application['status']);
$icon = '';
switch ($status) {
    case 'approved':
        $icon = '<i class="fas fa-check-circle text-success"></i>';
        break;
    case 'disapproved':
        $icon = '<i class="fas fa-times-circle text-danger"></i>';
        break;
    case 'active':
        $icon = '<i class="fas fa-check-circle text-primary"></i>';
        break;
    case 'pending':
    default:
        $icon = '<i class="fas fa-hourglass-start text-warning"></i>';
        break;
}

function formatDate($date) {
    return (new DateTime($date))->format('F j, Y');
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
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/NBSC_LOGO.png" rel="icon">
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
        h2 {
            margin-top: 48px;
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
        <h2>Application Details</h2>
        <div class="justify-content-between">
            <p class="text-muted">Review your application details for <strong><?php echo htmlspecialchars($club['clubName']); ?></strong></p>
            <p>Status: <?php echo $icon; ?> <strong><?php echo ucfirst($status); ?></strong></p>
        </div>
        <div class="container-fluid container mb-5 auto-scroll">
            <div class="row">
                <div class="col">
                    <?php foreach ($questions_answers as $qa): ?>
                        <p><strong><?php echo htmlspecialchars($qa['question']); ?></strong><br><?php echo htmlspecialchars($qa['answer']); ?></p>
                    <?php endforeach; ?>

                    <?php if ($status === 'pending'): ?>
                        <hr>
                        <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                    <?php elseif ($status === 'disapproved'): ?>
                        <p class="text-danger"><strong>Disapproval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remark available.'; ?></p>
                        <p class="text-primary"><strong>Disapproved by:</strong><br><?php echo !empty($moderatorFullName) ? htmlspecialchars($moderatorFullName) : 'No moderator available.'; ?></p>
                        <hr>
                        <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                        <p><strong>Date Disapproved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                        <br>
                        <a href="/esas/esas_student/club_info.php?application_id=<?php echo $application_id; ?>&club_id=<?php echo $club_id; ?>" style="font-size: 16px;">
                            See Club Information
                        </a>
                    <?php elseif ($status === 'active'): ?>
                        <p class="text-danger"><strong>Approval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remark available.'; ?></p>
                        <p class="text-primary"><strong>Approved by:</strong><br><?php echo !empty($moderatorFullName) ? htmlspecialchars($moderatorFullName) : 'No moderator available.'; ?></p>
                        <hr>
                        <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                        <p><strong>Date Approved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                    <?php endif; ?>

                    <div class="text-center">
                        <!-- Cancel Application button (only if status is 'pending') -->
                        <?php if ($status === 'pending'): ?>
                            <!-- <?php echo $fromPendingPage?> -->
                            <button class="btn btn-danger mt-5 mb-2" onclick="showConfirm(<?php echo $application_id; ?>, <?php echo $club_id; ?>, '<?php echo $fromPendingPage; ?>')">
                                Cancel Application
                            </button>
                            <br>
                            <a href="/esas/esas_student/club_info.php?application_id=<?php echo $application_id; ?>&club_id=<?php echo $club_id; ?>" style="font-size: 16px;">
                                Club Information
                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function showConfirm(applicationId, clubId, fromPendingPage) {
            console.log("fromPendingPage:", fromPendingPage);  // Check the value in console
            const userConfirmed = confirm("Are you sure you want to cancel your application?");
            if (userConfirmed) {
                window.location.href = "/esas/esas_student/actions/cancel_application_action.php?application_id=" + applicationId +
                                        "&club_id=" + clubId +
                                        "&from_pending_page=" + encodeURIComponent(fromPendingPage);
            }
        }
    </script>

</body>
</html>
