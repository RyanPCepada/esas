<?php

require_once "../../../../config.php"; 

if (isset($_GET["request_id"]) && !empty(trim($_GET["request_id"]))) { 
    $request_id = trim($_GET["request_id"]); 
    $sql = "SELECT 
                r.request_id, 
                r.clubName, 
                r.description, 
                r.activities, 
                r.status, 
                r.coverPhoto, 
                r.dateRequested, 
                r.dateModified 
            FROM tbl_club_requests r 
            WHERE r.request_id = :request_id"; 

    if ($stmt = $pdo->prepare($sql)) { 
        $stmt->bindParam(":request_id", $request_id, PDO::PARAM_INT); 

        if ($stmt->execute()) { 
            if ($stmt->rowCount() == 1) { 
                $row = $stmt->fetch(PDO::FETCH_ASSOC); 

                // Initialize variables
                $clubName = htmlspecialchars($row["clubName"] ?? ''); 
                $description = htmlspecialchars($row["description"] ?? ''); 
                $activities = htmlspecialchars($row["activities"] ?? ''); 
                $status = htmlspecialchars($row["status"] ?? ''); 
                $coverPhoto = htmlspecialchars($row["coverPhoto"] ?: "default-cover.jpg"); 
                $dateRequested = htmlspecialchars($row["dateRequested"] ?? ''); 
                $dateModified = htmlspecialchars($row["dateModified"] ?? ''); 

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
unset($pdo); 

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
                            <div class="col-md-3 text-center">
                                <img src="/esas/esas_student/images/<?php echo $coverPhoto; ?>" 
                                     alt="<?php echo $clubName; ?> Cover Photo" 
                                     class="img-fluid" style="width: 300px; height: auto; border-radius: 5px; object-fit: cover;">
                                     <!-- class="img-fluid" style="width: 300px; height: 171px; border-radius: 5px; object-fit: cover;"> -->
                            </div>
                            <div class="col-md-9">
                                <h4 class="text-muted mb-3"><?php echo $clubName; ?></h4>
                                <hr>
                                <p><strong>Description: </strong><?php echo $description; ?></p>
                                <p><strong>Activities: </strong><?php echo $activities; ?></p>
                                <p><strong>Status: </strong><?php echo $status; ?></p>
                                <p><strong>Date Requested: </strong><?php echo $dateRequested; ?></p>
                                <p><strong>Date Modified: </strong><?php echo $dateModified; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center">
                        <?php if ($status === 'pending'): ?>
                            <a href="approve_request.php?request_id=<?php echo $request_id; ?>" class="btn btn-success">Approve</a>
                            <a href="disapprove_request.php?request_id=<?php echo $request_id; ?>" class="btn btn-danger">Disapprove</a>
                            <a href="javascript:window.history.back();" class="btn btn-secondary">Back to Requests List</a>
                        <?php else: ?>
                            <a href="javascript:window.history.back();" class="btn btn-secondary">Back to Requests List</a>
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
