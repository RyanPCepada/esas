<?php
// Include config file
require_once "../../config.php";
session_start();

if (!isset($_SESSION['student_id'])) {
    echo "Student ID is not set in the session.";
    exit;
}

// Get student ID from session
$student_id = $_SESSION['student_id'];

// Check and retrieve club_id from URL
if (isset($_GET["club_id"]) && !empty(trim($_GET["club_id"]))) {
    $club_id = trim($_GET["club_id"]);
} else {
    echo "Club ID is required.";
    exit;
}

// Set default timezone
date_default_timezone_set('Asia/Manila');

// Fetch club name
$sql_club = "SELECT clubName FROM tbl_clubs WHERE club_id = ?";
$stmt_club = $pdo->prepare($sql_club);
$stmt_club->execute([$club_id]);
$club = $stmt_club->fetch(PDO::FETCH_ASSOC); 

// Fetch accomplishment reports for the student in this club
$sql = "SELECT * FROM tbl_accomplishment_reports WHERE club_id = :club_id ORDER BY dateAdded DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to label reports by date
function getDateLabel($date) {
    $today = new DateTime('today');
    $yesterday = new DateTime('yesterday');
    $dateObj = new DateTime($date);

    if ($dateObj >= $today) {
        return "Today";
    } elseif ($dateObj >= $yesterday) {
        return "Yesterday";
    } elseif ($dateObj >= new DateTime('last monday')) {
        return "Earlier This Week";
    } elseif ($dateObj >= new DateTime('last sunday -1 week')) {
        return "Last Week";
    } elseif ($dateObj->format('Y-m') === $today->format('Y-m')) {
        return "Earlier This Month";
    } elseif ($dateObj->format('Y-m') === $today->modify('-1 month')->format('Y-m')) {
        return "Last Month";
    } elseif ($dateObj->format('Y') === $today->format('Y')) {
        return $dateObj->format('F');
    } else {
        return "Last Year";
    }
}

// Group reports by their date label
$groupedReports = [];
foreach ($reports as $report) {
    $label = getDateLabel($report['dateAdded']);
    $groupedReports[$label][] = $report;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Accomplishment Reports</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px;
        }

        .container {
            height: auto;
            background-color: white;
            padding: 25px;
            padding-top: 0px;
        }

        .no-report {
            text-align: center;
            padding: 50px 0;
        }

        .no-report i {
            font-size: 50px;
            color: #ccc;
        }

        .btn-plus {
            font-size: 60px;
            color: #007bff;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .btn-plus:hover {
            color: #0056b3;
        }

        #fileIconPreview {
            width: 100px;
            height: auto;
            display: none;
        }

        .reports-list {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            justify-content: center;
        }

        .report-item {
            width: 180px;
            background-color: #f9f9f9;
            padding: 15px;
            border: solid 1px lightgrey;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .report-item:hover {
            background-color: #f1f1f1;
            border: solid 1px grey;
            cursor: pointer;
        }

        .report-item img {
            width: 100%;
            height: auto;
        }

        .report-item h5 {
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Limit to 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            line-height: 1.2em; /* Adjust line height for better fit */
            max-height: 3.6em; /* Ensure three full lines fit */
            overflow-wrap: break-word; /* Allows words to break cleanly */
        }

        .report-item .date {
            display: none; /* Hide date visually but keep in title */
        }

        h2 {    
            margin-top: 40px !important;
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
                margin-top: 5px !important;
            }

            .report-item {
                width: 140px;
                background-color: #f9f9f9;
                padding: 15px;
                border: solid 1px lightgrey;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                text-align: center;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            .reports-list {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
            }
            #original-filename {
                font-size: 14px;
            }
            h4 {
                font-size: 18px;
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
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div class="row">
            <h2>Accomplishment Reports</h2>
            <!-- <p class="text-muted">All your accomplishment reports submitted in <strong><?php echo htmlspecialchars($club['clubName']); ?></strong></p> -->
        </div>
        <button class="btn-plus" onclick="openAccomplishmentReportModal()">+</button>
    </div>

    <div class="container-fluid container mb-5 auto-scroll">
        <?php if (!empty($groupedReports)): ?> 
            <?php foreach ($groupedReports as $label => $reports): ?>
                <h4 class="mt-4 mb-4"><?php echo htmlspecialchars($label); ?></h4>
                <div class="reports-list">
                    <?php foreach ($reports as $report): ?>
                        <div class="report-item" 
                            title="<?php echo htmlspecialchars($report['originalFileName']) . "\n" . date('m/d/Y h:i A', strtotime($report['dateAdded'])); ?>" 
                            onclick="window.open('/esas/esas_student/accomplishment_reports/<?php echo urlencode($report['accReportFile']); ?>', '_blank')">
                            <img src="/esas/esas_student/icons/ICON_PDF.png" alt="PDF Icon">
                            <h5 id="original-filename"><?php echo htmlspecialchars($report['originalFileName']); ?></h5>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-report">
                <p>No accomplishment reports found.</p>
                <i class="fas fa-file-pdf"></i>
            </div>
        <?php endif; ?>


        <!-- Accomplishment Report Modal -->
        <div id="accomplishmentReportModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgb(0,0,0); background-color: rgba(0,0,0,0.4);">
            <div style="background-color: white; margin: 4% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px;">
                <span style="color: #4CAF50; font-weight: bold;">Add Accomplishment Report</span>
                <p>Please fill out the form to upload your accomplishment report.</p>
                <form id="accomplishmentReportForm" action="../actions/accomplishment_report_action.php" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="accReportFile">Upload PDF:</label>
                        <input type="file" name="accReportFile" id="accReportFile" accept="application/pdf" class="form-control" required onchange="previewFile(event)">
                        <small class="form-text text-muted">Accepted format: PDF only.</small>
                    </div>
                    <div class="form-group mb-3">
                        <img id="fileIconPreview" src="#" alt="File Icon Preview" style="display: none;" />
                        <p id="fileNamePreview" style="display:none;"></p>
                    </div>
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <input type="hidden" name="club_id" value="<?php echo $club_id; ?>">
                    <button type="submit" class="btn btn-success">Submit Report</button>
                    <button type="button" class="btn btn-danger" onclick="closeAccomplishmentReportModal()">Close</button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
// Function to preview selected file's icon
function previewFile(event) {
    const fileInput = event.target;
    const fileIconPreview = document.getElementById("fileIconPreview");
    const fileNamePreview = document.getElementById("fileNamePreview");

    if (fileInput.files && fileInput.files[0]) {
        fileIconPreview.style.display = "block";
        fileIconPreview.src = "/esas/esas_student/icons/ICON_PDF.png";
        fileNamePreview.style.display = "block";
        fileNamePreview.textContent = fileInput.files[0].name;
    } else {
        fileIconPreview.style.display = "none";
        fileNamePreview.style.display = "none";
    }
}

// Function to open report in a new tab
function openTab(fileName) {
    window.open(fileName, '_blank');
}

// Function to open modal
function openAccomplishmentReportModal() {
    document.getElementById("accomplishmentReportModal").style.display = "block";
}

// Function to close modal
function closeAccomplishmentReportModal() {
    document.getElementById("accomplishmentReportModal").style.display = "none";
}


</script>
</body>
</html>
