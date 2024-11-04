<?php 
require_once "../config.php";
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo "Admin ID is not set in the session.";
    exit;
}

$admin_id = $_SESSION['admin_id'];
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

// Fetch all clubs the student is associated with, ordered by status priority
$sql_all_clubs = "
    SELECT * FROM tbl_application 
    WHERE student_id = ? 
    AND status IN ('active', 'inactive', 'pending', 'disapproved', 'departed', 'maxed')
    ORDER BY FIELD(status, 'active', 'inactive', 'pending', 'disapproved', 'departed', 'maxed')
";
$stmt_all_clubs = $pdo->prepare($sql_all_clubs);
$stmt_all_clubs->execute([$student_id]);
$applications = $stmt_all_clubs->fetchAll(PDO::FETCH_ASSOC);

// Fetch questions related to the club
$sql_questions = "SELECT * FROM tbl_application_questions WHERE club_id = ?";
$stmt_questions = $pdo->prepare($sql_questions);
$stmt_questions->execute([$club_id]);
$questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC);

// Fetch answers for the application
$sql_answers = "
    SELECT aa.answer, aq.question_id 
    FROM tbl_application_answers aa 
    JOIN tbl_application_questions aq ON aa.question_id = aq.question_id 
    WHERE aa.application_id = ? AND aa.student_id = ? AND aa.club_id = ?
";
$stmt_answers = $pdo->prepare($sql_answers);
$stmt_answers->execute([$application_id, $student_id, $club_id]);
$answers = $stmt_answers->fetchAll(PDO::FETCH_ASSOC);

// Format answers by question_id
$formatted_answers = [];
foreach ($answers as $answer) {
    $formatted_answers[$answer['question_id']] = htmlspecialchars($answer['answer']);
}

function formatDate($date) {
    return (new DateTime($date))->format('F j, Y');
}

// Group applications by status
$grouped_applications = [];
foreach ($applications as $application) {
    $status = strtolower($application['status']);
    $grouped_applications[$status][] = $application;
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
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
        body { background-color: #f4f4f9; }
        .wrapper { max-width: 700px; margin: 0 auto; padding: 15px; }
        .container { background-color: white; padding: 25px; }
    </style>
</head>
<body>
  
<div class="wrapper">
    <h2 class="mt-5">Application Details</h2>
    <p class="text-muted">Review <strong><?php echo htmlspecialchars($fullName); ?></strong>'s application details across all clubs</p>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="statusTabs" role="tablist">
        <?php
        $statuses = ['active', 'inactive', 'pending', 'disapproved', 'departed', 'maxed'];
        foreach ($statuses as $index => $status) {
            $activeClass = $index === 0 ? 'active' : '';
            $icon = '';
            switch ($status) {
                case 'active': $icon = '<i class="fas fa-check-circle text-success"></i>'; break;
                case 'inactive': $icon = '<i class="fas fa-user-times text-danger"></i>'; break;
                case 'pending': $icon = '<i class="fas fa-hourglass-start text-warning"></i>'; break;
                case 'disapproved': $icon = '<i class="fas fa-times-circle text-danger"></i>'; break;
                case 'departed': $icon = '<i class="fas fa-user-slash text-secondary"></i>'; break;
                case 'maxed': $icon = '<i class="fas fa-exclamation-triangle text-danger"></i>'; break;
                default: $icon = '<i class="fas fa-question-circle text-muted"></i>'; break;
            }
            echo "<li class='nav-item'>
                    <a class='nav-link $activeClass' id='{$status}-tab' data-toggle='tab' href='#{$status}' role='tab' aria-controls='{$status}' aria-selected='true'>{$icon} " . ucfirst($status) . "</a>
                  </li>";
        }
        ?>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="statusTabsContent">
        <?php foreach ($statuses as $index => $status): ?>
            <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>" id="<?php echo $status; ?>" role="tabpanel" aria-labelledby="<?php echo $status; ?>-tab">
                <?php if (isset($grouped_applications[$status])): ?>
                    <?php foreach ($grouped_applications[$status] as $application): ?>
                        <?php
                        // Fetch club name
                        $sql_club = "SELECT clubName FROM tbl_clubs WHERE club_id = ?";
                        $stmt_club = $pdo->prepare($sql_club);
                        $stmt_club->execute([$application['club_id']]);
                        $club = $stmt_club->fetch(PDO::FETCH_ASSOC);

                        // Fetch questions related to the club
                        $sql_questions = "SELECT * FROM tbl_application_questions WHERE club_id = ?";
                        $stmt_questions = $pdo->prepare($sql_questions);
                        $stmt_questions->execute([$application['club_id']]); // Use application club_id here
                        $questions = $stmt_questions->fetchAll(PDO::FETCH_ASSOC);

                        // Fetch answers for the application
                        $sql_answers = "
                            SELECT aa.answer, aq.question_id 
                            FROM tbl_application_answers aa 
                            JOIN tbl_application_questions aq ON aa.question_id = aq.question_id 
                            WHERE aa.application_id = ? AND aa.student_id = ? AND aa.club_id = ?
                        ";
                        $stmt_answers = $pdo->prepare($sql_answers);
                        $stmt_answers->execute([$application['application_id'], $student_id, $application['club_id']]); // Use application details here
                        $answers = $stmt_answers->fetchAll(PDO::FETCH_ASSOC);

                        // Format answers by question_id
                        $formatted_answers = [];
                        foreach ($answers as $answer) {
                            $formatted_answers[$answer['question_id']] = htmlspecialchars($answer['answer']);
                        }
                        ?>

                        <div class="container-fluid container mb-5">
                            <h5 class="mb-4"><strong><i class="fas fa-university text-primary" style="font-size: 25px;"></i></strong> <strong class="text-muted"><?php echo htmlspecialchars($club['clubName']); ?></strong></h5>
                            
                            <?php
                                // Display questions and answers
                                foreach ($questions as $question) {
                                    echo "<p><strong>" . htmlspecialchars($question['question']) . "</strong><br>";
                                    $answer = !empty($formatted_answers[$question['question_id']]) ? $formatted_answers[$question['question_id']] : 'No answer provided.';
                                    echo html_entity_decode(htmlspecialchars($answer)); // Decode and then display the answer
                                    echo "</p>";
                                }
                            ?>
                            
                            <?php if ($status === 'active'): ?>
                                <div class="status-block">
                                    <p class="text-danger"><strong>Approval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remarks available.'; ?></p>
                                    <hr>
                                    <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                                    <p><strong>Date Approved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                                </div>
                            <?php elseif ($status === 'disapproved'): ?>
                                <div class="status-block">
                                    <p class="text-danger"><strong>Disapproval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remarks available.'; ?></p>
                                    <hr>
                                    <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                                    <p><strong>Date Disapproved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                                </div>
                            <?php elseif ($status === 'pending'): ?>
                                <div class="status-block">
                                    <hr>
                                    <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                                </div>
                            <?php elseif ($status === 'inactive'): ?>
                                <div class="status-block">
                                    <p class="text-danger"><strong>Approval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remarks available.'; ?></p>
                                    <hr>
                                    <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                                    <p><strong>Date Approved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                                    <p><strong>Date Inactivated:</strong> <?php echo formatDate($application['dateModified']); ?></p>
                                </div>
                            <?php elseif ($status === 'departed'): ?>
                                <div class="status-block">
                                    <p class="text-danger"><strong>Approval Remarks:</strong><br><?php echo !empty($application['remark']) ? htmlspecialchars($application['remark']) : 'No remarks available.'; ?></p>
                                    <hr>
                                    <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                                    <p><strong>Date Approved:</strong> <?php echo formatDate($application['dateDecided']); ?></p>
                                    <p><strong>Date Departed:</strong> <?php echo formatDate($application['dateModified']); ?></p>
                                </div>
                            <?php elseif ($status === 'maxed'): ?>
                                <div class="status-block">
                                    <hr>
                                    <p><strong>Date Applied:</strong> <?php echo formatDate($application['dateApplied']); ?></p>
                                    <p><strong>Date Maxed:</strong> <?php echo formatDate($application['dateModified']); ?></p>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted mt-3">No applications found under this status.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
