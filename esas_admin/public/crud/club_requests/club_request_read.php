<?php

require_once "../../../../config.php"; 

if (isset($_GET["request_id"]) && !empty(trim($_GET["request_id"]))) { 
    $request_id = trim($_GET["request_id"]); 
    $sql = "SELECT 
                r.request_id, 
                r.clubName, 
                r.goal, 
                r.mission,  -- Add mission to the selection
                r.vision,   -- Add vision to the selection
                r.activities, 
                r.status, 
                r.coverPhoto, 
                r.dateRequested, 
                r.dateModified,
                s.firstName,
                s.lastName,
                s.profilePic
            FROM tbl_club_requests r 
            JOIN tbl_students s ON r.student_id = s.student_id 
            WHERE r.request_id = :request_id"; 

    if ($stmt = $pdo->prepare($sql)) { 
        $stmt->bindParam(":request_id", $request_id, PDO::PARAM_INT); 

        if ($stmt->execute()) { 
            if ($stmt->rowCount() == 1) { 
                $row = $stmt->fetch(PDO::FETCH_ASSOC); 

                // Initialize variables
                $clubName = htmlspecialchars($row["clubName"] ?? ''); 
                $goal = htmlspecialchars($row["goal"] ?? ''); 
                $mission = htmlspecialchars($row["mission"] ?? ''); // New mission variable
                $vision = htmlspecialchars($row["vision"] ?? '');   // New vision variable
                $activities = htmlspecialchars($row["activities"] ?? ''); 
                $status = strtolower(htmlspecialchars($row["status"] ?? '')); 
                $coverPhoto = htmlspecialchars($row["coverPhoto"] ?: "default-cover.jpg"); 
                $dateRequested = !empty($row["dateRequested"]) ? date("F j, Y", strtotime($row["dateRequested"])) : 'None';
                $dateModified = !empty($row["dateModified"]) ? date("F j, Y", strtotime($row["dateModified"])) : 'None';
                $requestedByName = htmlspecialchars($row["firstName"] . ' ' . $row["lastName"] ?? '');
                $profilePic = htmlspecialchars($row["profilePic"] ?: "default-profile.jpg");
                
            } else { 
                echo "No club request found with this ID."; 
                exit(); 
            } 
        } else { 
            echo "Database query failed. Please try again later."; 
            exit(); 
        } 
    } 
    unset($stmt); 
} else { 
    header("location: error.php"); 
    exit(); 
} 



// Handle approval or disapproval
if (isset($_POST["action"]) && in_array($_POST["action"], ['approve', 'disapprove'])) {
    $newStatus = $_POST["action"] === 'approve' ? 'approved' : 'disapproved'; // Changed to lowercase
    $dateApproved = null; // Default to null for disapproval
    if ($newStatus === 'approved') {
        $dateApproved = date('Y-m-d H:i:s'); // Get current date and time
    }

    $updateSql = "UPDATE tbl_club_requests 
                   SET status = :status, 
                       dateApproved = :dateApproved 
                   WHERE request_id = :request_id";

    if ($updateStmt = $pdo->prepare($updateSql)) {
        $updateStmt->bindParam(":status", $newStatus);
        $updateStmt->bindParam(":dateApproved", $dateApproved);
        $updateStmt->bindParam(":request_id", $request_id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header("location: club_request_read.php?request_id=" . $request_id . "&status=" . strtolower($newStatus));
            exit();
        } else {
            echo "Error updating the request. Please try again.";
            exit();
        }
    }
    unset($updateStmt);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Request Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Club Request Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img src="/esas/esas_student/images/<?php echo $coverPhoto; ?>" 
                                    alt="<?php echo $clubName; ?> Cover Photo" 
                                    class="img-fluid" style="width: 300px; height: auto; border-radius: 5px; object-fit: cover;">
                                    <h4 class="text-muted mt-3"><?php echo $clubName; ?></h4>
                            </div>
                            <div class="col-md-8">
                                <p>
                                    <strong>Requested by: </strong><br>
                                    <img class="mt-2 mb-0" src="/esas/esas_student/images/<?php echo $profilePic; ?>" 
                                        alt="Profile Picture" 
                                        style="width: 70px; height: auto; border-radius: 50%;" />
                                    <h4><?php echo $requestedByName; ?></h4>
                                </p>
                                <hr>
                                <p><strong>Goal: </strong><?php echo $goal; ?></p>
                                <p><strong>Mission: </strong><?php echo $mission; ?></p> <!-- Display mission -->
                                <p><strong>Vision: </strong><?php echo $vision; ?></p>   <!-- Display vision -->
                                <p><strong>Activities: </strong><?php echo $activities; ?></p>
                                <p><strong>Status: </strong><?php echo $status; ?></p>
                                <p><strong>Date Requested: </strong><?php echo $dateRequested; ?></p> 
                                <p><strong>Date Modified: </strong><?php echo $dateModified; ?></p> 
                            </div>
                        </div>
                    </div>


                    <div class="card-footer text-center">
                        <?php if ($status === 'pending'): ?>
                            <form method="post">
                                <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                <button type="submit" name="action" value="disapprove" class="btn btn-danger">Disapprove</button>
                                <a href="../../club_requests.php" class="btn btn-secondary">Back to Requests List</a>
                            </form>
                        <?php elseif ($status === 'approved'): ?>
                            <a href="../../crud/all_clubs/club_create.php?clubName=<?php echo urlencode($clubName); ?>&coverPhoto=<?php echo urlencode($coverPhoto); ?>" class="btn btn-success">Add to Clubs</a>
                            <a href="../../club_requests.php" class="btn btn-secondary">Back to Requests List</a>
                        <?php else: ?>
                            <a href="../../club_requests.php" class="btn btn-secondary">Back to Requests List</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
