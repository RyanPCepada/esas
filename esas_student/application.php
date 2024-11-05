<?php
// Include config file
require_once "../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Initialize variables
$club_id = "";
$clubName = "";
$questions = [];

// Fetch club name and club_id if club_id is provided in the query parameter
if (isset($_GET['club_id'])) {
    $club_id = intval($_GET['club_id']);
    $sql = "SELECT clubName FROM tbl_clubs WHERE club_id = :club_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
    $stmt->execute();
    $club = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($club) {
        $clubName = $club['clubName'];
    }
    unset($stmt);

    // Fetch questions for the selected club
    $sql = "SELECT question_id, question FROM tbl_application_questions WHERE club_id = :club_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":club_id", $club_id, PDO::PARAM_INT);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    unset($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Student Application</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        /* body {
            font: 14px Helvetica;
        } */
        body {
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 0px;
        }
        .container-fluid {
            padding: 20px;
        }
        .form-group {
            background: white;
            padding: 10px 15px;
            border-radius: 10px;
            /* margin-bottom: 15px; */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* box-shadow: 0 5px 8px rgba(0, 0, 0, 0.2); */
        }
        .form-group select {
            max-height: auto !important;
            overflow-y: auto !important;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php if (!empty($clubName)): ?>
                    <h2><strong><?php echo htmlspecialchars($clubName); ?></strong></h2>
                    <h4 class="text-muted">Student Application <i class="fas fa-file-alt"></i></h4>
                <?php endif; ?>
                <p class="mb-5">Please fill this form and submit to apply.</p>
                <form action="../esas_student/actions/application_action.php" method="post">
                    <?php if (!empty($questions)): ?>
                        <?php foreach ($questions as $index => $question): ?>
                            <div class="form-group">
                                <!-- <label><?php echo htmlspecialchars($question['question_id']); ?></label> -->
                                <label><strong><?php echo htmlspecialchars($question['question']); ?></strong></label>
                                <textarea name="question<?php echo $index + 1; ?>" rows="3" class="form-control" required></textarea>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No questions are available for this club at the moment.</p>
                    <?php endif; ?>
                    <br>
                    <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($club_id); ?>">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="#" onclick="window.history.back();" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
