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

// Fetch accomplishment reports for the student in this club
$sql = "SELECT * FROM tbl_accomplishment_reports WHERE student_id = :student_id AND club_id = :club_id ORDER BY dateAdded DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
$stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $student_id = $_POST["student_id"];
    $club_id = $_POST["club_id"];

    // File upload configuration
    $targetDir = "../../uploads/"; // Correct path for file uploads
    $fileName = basename($_FILES["accReportFile"]["name"]);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Check if file is a PDF
    if ($fileType != "pdf") {
        echo "Only PDF files are allowed.";
        exit;
    }

    // Move file to target directory
    $filePath = $targetDir . uniqid() . "_" . $fileName;
    if (move_uploaded_file($_FILES["accReportFile"]["tmp_name"], $filePath)) {
        // Insert record into the database
        $sql = "INSERT INTO tbl_accomplishment_reports (title, description, accReportFile, student_id, club_id, dateAdded) VALUES (:title, :description, :accReportFile, :student_id, :club_id, NOW())";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->bindParam(":description", $description, PDO::PARAM_STR);
            $stmt->bindParam(":accReportFile", $filePath, PDO::PARAM_STR);
            $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
            $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: accomplishment_report.php?club_id=" . $club_id);
                exit();
            } else {
                echo "Something went wrong. Please try again.";
            }
        }
        unset($stmt);
    } else {
        echo "Failed to upload file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>eSAS - Accomplishment Reports</title>
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
        .no-report {
            text-align: center;
            padding: 50px 0;
        }
        .no-report i {
            font-size: 50px;
            color: #ccc;
        }
        .btn-plus {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 30px;
            color: #007bff;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }
        .btn-plus:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2 class="mt-5">Accomplishment Reports</h2>
    <div class="container-fluid container mb-5 auto-scroll">
    <?php if (count($reports) > 0): ?>
            <div class="reports-list">
                <?php foreach ($reports as $report): ?>
                    <div class="report-item">
                        <h3><?php echo htmlspecialchars($report['title']); ?></h3>
                        <p><?php echo htmlspecialchars($report['description']); ?></p>
                        <a href="../../uploads/<?php echo htmlspecialchars($report['accReportFile']); ?>" target="_blank">View Report</a>
                        <p class="date">Submitted on: <?php echo htmlspecialchars($report['dateAdded']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-report">
                <p>No accomplishment reports found.</p>
                <!-- Use an icon for no reports -->
                <i class="fas fa-file-pdf"></i>
            </div>
        <?php endif; ?>

        <!-- Plus icon button to open modal -->
<button class="btn-plus" onclick="openAccomplishmentReportModal()">+</button>

        <!-- Accomplishment Report Modal -->
<div id="accomplishmentReportModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgb(0,0,0); background-color: rgba(0,0,0,0.4);">
    <div style="background-color: white; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px;">
        <span style="color: #4CAF50; font-weight: bold;">Add Accomplishment Report</span>
        <p>Please fill out the form to upload your accomplishment report.</p>
        <form id="accomplishmentReportForm" action="../actions/add_accreport_action.php" method="post" enctype="multipart/form-data" onsubmit="submitAccomplishmentReport(event)">
            <div class="form-group">
                <label for="reportTitle">Title:</label>
                <input type="text" name="title" id="reportTitle" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="reportDescription">Description:</label>
                <textarea name="description" id="reportDescription" class="form-control" required></textarea>
            </div>

            <div class="form-group">
                <label for="accReportFile">Upload PDF:</label>
                <input type="file" name="accReportFile" id="accReportFile" accept="application/pdf" class="form-control" required>
            </div>

            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
            <input type="hidden" name="club_id" value="<?php echo $club_id; ?>">

            <button type="submit" class="btn btn-success">Submit Report</button>
            <button type="button" class="btn btn-secondary" onclick="closeAccomplishmentReportModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
    // Function to open the modal
    function openAccomplishmentReportModal() {
        document.getElementById("accomplishmentReportModal").style.display = "block";
    }

    // Function to close the modal
    function closeAccomplishmentReportModal() {
        document.getElementById("accomplishmentReportModal").style.display = "none";
    }

    // Function to handle the form submission (for custom handling if needed)
    function submitAccomplishmentReport(event) {
        event.preventDefault();
        var form = document.getElementById("accomplishmentReportForm");
        
        // If using AJAX or any custom handling, you can handle it here
        form.submit();  // This will submit the form normally
    }
</script>


</body>
</html>
