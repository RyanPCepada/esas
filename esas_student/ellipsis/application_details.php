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

// Fetch application details
$sql_application = "SELECT *, remark FROM tbl_application 
                    WHERE student_id = ? AND club_id = ? AND application_id = ?
                    AND status IN ('pending', 'active', 'disapproved')";
$stmt_application = $pdo->prepare($sql_application);
$stmt_application->execute([$student_id, $club_id, $application_id]);
$application = $stmt_application->fetch(PDO::FETCH_ASSOC); 

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

// Fetch questions and answers for the specific application
$sql_questions_answers = "SELECT q.question, a.answer 
                          FROM tbl_application_questions q
                          LEFT JOIN tbl_application_answers a ON q.question_id = a.question_id 
                          AND a.application_id = ? AND a.student_id = ? AND a.club_id = ?
                          WHERE a.application_id = ? AND a.student_id = ?";
$stmt_questions_answers = $pdo->prepare($sql_questions_answers);
$stmt_questions_answers->execute([$application_id, $student_id, $club_id, $application_id, $student_id]);
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
    <title>eSAS - Application Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/nbsclogo.png" rel="icon">
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
                    <p><strong>Disapproval Remarks:</strong><br><?php echo htmlspecialchars($application['remark']); ?></p>
                    <hr>
                    <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                    <p><strong>Date Disapproved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                <?php elseif ($status === 'active'): ?>
                    <p><strong>Approval Remarks:</strong><br><?php echo htmlspecialchars($application['remark']); ?></p>
                    <hr>
                    <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                    <p><strong>Date Approved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                <?php endif; ?>

                <div class="text-end">
                    <!-- Cancel Application button (only if status is 'pending') -->
                    <?php if ($status === 'pending'): ?>
                            <button class="btn btn-danger mt-3" onclick="showConfirm(<?php echo $application_id; ?>, <?php echo $club_id; ?>)">
                                Cancel Application
                            </button>
                        <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
    </div>

    <script>
        function showConfirm(applicationId, clubId) {
            const userConfirmed = confirm("Are you sure you want to cancel your application?");
            if (userConfirmed) {
                // Redirect to the cancellation action with application_id and club_id
                window.location.href = "/esas/esas_student/actions/cancel_application_action.php?application_id=" + applicationId + "&club_id=" + clubId;
            }
        }
    </script>

</body>
</html>
