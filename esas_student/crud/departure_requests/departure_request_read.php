<?php
require_once "../../../config.php"; // Database config file
session_start();

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Student not logged in.']);
    exit();
}

// Retrieve student_id from the session
$student_id = $_SESSION['student_id'];

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Initialize variable to store departure requests
$departureRequests = [];

$club_id = isset($_GET['club_id']) ? $_GET['club_id'] : null;

if ($club_id === null) {
    die("Club ID is required.");
}
try {
    // Use the existing PDO instance from config.php
    global $pdo;

    // Query to fetch the student's departure requests
    $sql = "
        SELECT dr.departure_id, dr.club_id, dr.dateRequested, dr.status, c.clubName 
        FROM tbl_departure_requests dr 
        INNER JOIN tbl_clubs c ON dr.club_id = c.club_id
        WHERE dr.student_id = :student_id
        AND dr.club_id = :club_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT); // Bind the club_id
    $stmt->execute();


} catch (PDOException $e) {
    // Handle database connection or query error
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Student Departure Request</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 15px;
        }
        .container {
            background-color: white;
            padding: 20px;
        }
    </style>
</head>
<body>
    
    <div class="wrapper">
        <h3 class="text-muted mt-5 mb-3">Departure Requests</h3>

        <div class="container-fluid container">
            <div class="row">
                <?php if (!empty($departureRequests)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Club Name</th>
                                <th>Date Requested</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departureRequests as $request): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($request['clubName']); ?></td>
                                    <td><?php echo date('F j, Y', strtotime($request['dateRequested'])); ?></td>
                                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">You have not submitted any departure requests yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
