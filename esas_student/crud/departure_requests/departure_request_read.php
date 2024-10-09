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
        SELECT dr.departure_id, dr.club_id, dr.reason, dr.dateRequested, dr.status, c.clubName 
        FROM tbl_departure_requests dr 
        INNER JOIN tbl_clubs c ON dr.club_id = c.club_id
        WHERE dr.student_id = :student_id
        AND dr.club_id = :club_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":student_id", $student_id, PDO::PARAM_INT);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
    $stmt->execute();

    $departureRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>eSAS - Departure Request Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/nbsclogo.png" rel="icon">
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
    <h3 class="text-muted mt-5 mb-3">Departure Request</h3>
    <div class="container-fluid container">
        <div class="row">
            <?php if (!empty($departureRequests)): ?>
                <?php foreach ($departureRequests as $request): ?>
                    <div class="col-md-6">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <?php echo htmlspecialchars($request['clubName']); ?>
                            </h5>
                            <br>
                            <p class="card-text mb-1">
                                <strong>Date Requested:</strong> 
                                <?php echo date('F j, Y', strtotime($request['dateRequested'])); ?>
                            </p>
                            <p class="card-text">
                                <strong>Status:</strong> 
                                <span class="badge badge-<?php echo $request['status'] == 'Approved' ? 'success' : ($request['status'] == 'Pending' ? 'warning' : 'danger'); ?>">
                                    <?php echo htmlspecialchars($request['status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <p class="card-text mb-2">
                            <div class="card-body">
                                <hr>
                                <strong>Reason for Departure:</strong><br>
                                <em id="reason-<?php echo $request['club_id']; ?>">
                                    <?php echo htmlspecialchars($request['reason']); ?>
                                </em>
                            </div>
                        </p>
                        <br>
                        <div class="p-3 d-flex justify-content-between">
                            <div>
                                <button class="btn btn-outline-danger btn-sm mt-2" onclick="showEditModal('<?php echo $request['club_id']; ?>', '<?php echo htmlspecialchars($request['reason']); ?>')">
                                    Edit Request
                                </button>
                                <button class="btn btn-outline-secondary btn-sm mt-2" onclick="withdrawRequest('<?php echo $request['club_id']; ?>', '<?php echo htmlspecialchars($request['clubName']); ?>')">
                                    Withdraw Request
                                </button>
                            </div>
                            <button class="btn btn-primary btn-sm mt-2">
                                <a class="text-light" href="../../home.php?club_id=<?php echo $_GET['club_id']; ?>" style="text-decoration: none;">Go Back</a>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center d-flex align-items-center justify-content-center">
                    <div class="alert alert-transparent w-50 text-center py-5">
                        <i class="fas fa-check-circle fa-2x mb-2" style="height: 80px;"></i> <!-- Icon -->
                        <div>Departure request has been withdrawn.</div>
                    </div>
                </div>
                <div class="p-3 d-flex justify-content-end">
                    <button class="btn btn-outline-info btn-sm mt-2">
                        <a class="text-dark" style="text-decoration: none;" href="../../home.php?club_id=<?php echo $_GET['club_id']; ?>">Go Back</a>
                    </button>
                </div> 
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Departure Request Edit Modal -->
<div id="departureModal" class="modal" style="display:none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: white; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px;">
        <span style="color: red; font-weight: bold;">Edit Departure Reason</span>
        <p>Please select your reason for leaving the club:</p>
        <form id="departureRequestForm" action="/esas/esas_student/crud/departure_requests/departure_request_update.php" method="POST">
            <div class="form-group">
                <label><input type="radio" name="departureReason" value="Drop" required> Drop</label><br>
                <label><input type="radio" name="departureReason" value="Graduate" required> Graduate</label><br>
                <label><input type="radio" name="departureReason" value="Others" onchange="toggleOtherReasonInput(this)"> Others:</label>
                <input type="text" id="otherReasonInput" class="form-control" style="display:none;" placeholder="Please specify..." oninput="validateOtherReason()">
            </div>
            <input type="hidden" id="reasonInput" name="reason" value="<?php echo htmlspecialchars($request['reason']); ?>">
            <input type="hidden" id="clubIdInput" name="club_id" value="<?php echo $club_id; ?>">
            <button type="submit" class="btn btn-danger mb-1">Save Changes</button>
            <button type="button" class="btn btn-secondary mb-1" onclick="closeDepartureModal()">Cancel</button>
        </form>
    </div>
</div>

<script>
function closeDepartureModal() {
    document.getElementById('departureModal').style.display = 'none';
}

function showEditModal(clubId, reason) {
    document.getElementById('clubIdInput').value = clubId;

    // Set the radio buttons based on the reason
    const reasonInputs = document.getElementsByName('departureReason');
    reasonInputs.forEach(input => {
        input.checked = (input.value === reason);
        // Also update the hidden reason input based on the selected value
        if (input.checked) {
            document.getElementById('reasonInput').value = input.value;
        }
    });

    // Show the other reason input if applicable
    const otherReasonInput = document.getElementById('otherReasonInput');
    if (reason === 'Others') {
        otherReasonInput.style.display = 'block';
    } else {
        otherReasonInput.style.display = 'none';
    }

    document.getElementById('departureModal').style.display = 'block';
}


function toggleOtherReasonInput(radio) {
    const otherReasonInput = document.getElementById('otherReasonInput');
    otherReasonInput.style.display = (radio.value === 'Others') ? 'block' : 'none';
}

function validateOtherReason() {
    const otherReasonInput = document.getElementById('otherReasonInput');
    const reasonInputs = document.getElementsByName('departureReason');
    const othersSelected = Array.from(reasonInputs).some(input => input.value === 'Others' && otherReasonInput.value.trim() === '');

    // Disable submit button if 'Others' is selected but no input is provided
    document.querySelector('button[type="submit"]').disabled = othersSelected;
}
</script>

</body>
</html>
