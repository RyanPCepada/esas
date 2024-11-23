<?php

require_once "../../../../config.php";
require __DIR__ . '/../../../vendor/autoload.php';

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get the moderator ID from the URL or session
$moderator_id = $_GET['moderator_id'] ?? $_SESSION['moderator_id'];

// Fetch moderator information (name, email, etc.)
$moderatorName = '';
$moderatorEmail = '';
try {
    $stmt = $pdo->prepare("SELECT CONCAT(firstName, ' ', COALESCE(middleName, ''), ' ', lastName) AS fullName, email FROM tbl_moderators WHERE moderator_id = ?");
    $stmt->execute([$moderator_id]);
    $moderator = $stmt->fetch(PDO::FETCH_ASSOC);
    $moderatorName = htmlspecialchars($moderator['fullName']);
    $moderatorEmail = htmlspecialchars($moderator['email']);
} catch (PDOException $e) {
    die("Error fetching moderator details: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the club ID from the POST data
    $club_id = $_POST['club_id'];

    // Assign the moderator to the selected club
    try {
        // Fetch the club name before inserting
        $stmt = $pdo->prepare("SELECT clubName FROM tbl_clubs WHERE club_id = ?");
        $stmt->execute([$club_id]);
        $club = $stmt->fetch(PDO::FETCH_ASSOC);
        $clubName = htmlspecialchars($club['clubName']);

        // Insert the assignment
        $stmt = $pdo->prepare("INSERT INTO tbl_clubs_and_moderators (club_id, moderator_id, dateAdded) VALUES (?, ?, NOW())");
        $stmt->execute([$club_id, $moderator_id]);

        // Log the activity
        $activity = "You assigned $moderatorName to $clubName";
        $admin_id = $_SESSION['admin_id']; // Assuming admin ID is stored in session

        $logStmt = $pdo->prepare("INSERT INTO tbl_activity_logs (activity, dateAdded, admin_id, moderator_id, student_id) VALUES (?, NOW(), ?, ?, ?)");
        $logStmt->execute([$activity, $admin_id, null, null]);

        // Send email notification to the moderator
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();                                       // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                  // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                              // Enable SMTP authentication
            $mail->Username   = 'nbsc.esas@gmail.com';         // SMTP username
            $mail->Password   = 'cxef aobn ozbq qpxv';             // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    // Enable TLS encryption
            $mail->Port       = 587;                               // TCP port to connect to

            // Email content
            $mail->setFrom('nbsc.esas@gmail.com', 'Club Assignment Update');      
            $mail->addAddress($moderatorEmail, $moderatorName); // Add the moderator's email

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Assigned as Club Moderator';
            $mail->Body = "Hello {$moderatorName}
                           You have been assigned as a moderator for the {$clubName} club.
                           Best regards, Your Admin Team";

            // Send the email
            $mail->send();
            // Optionally log or handle successful email sending
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect back to the moderator update page with a success message
        $_SESSION['message'] = 'Moderator assigned successfully and email sent!';
         header("Location: moderator_update.php?moderator_id=" . htmlspecialchars($moderator_id));
        exit;

    } catch (PDOException $e) {
        // Handle the error
        die("Error assigning moderator: " . $e->getMessage());
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESAS - Update Moderator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/NBSC_LOGO.png" rel="icon">
    <style>
        /* Use the same font and wrapper styles as previous pages */
        body {
            /* font-family: 'Arial', sans-serif; Replace 'Arial' with your preferred font */
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }

        .club-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        
        .remove-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 14px;
            transition: background-color 0.3s; /* Smooth transition */
        }

        .remove-btn:hover {
            background-color: #cc0000;
        }

        .assign-section {
            margin-top: 30px;
        }

        .assign-btn {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 14px;
            transition: background-color 0.3s; /* Smooth transition */
        }

        .assign-btn:hover {
            background-color: darkgreen; /* Slightly darker green */
        }


        .dropdown {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <h2 class="mt-5">Update Moderator:<br><div class="text-muted"><?php echo $moderatorName; ?></div></h2>
        <hr>

        <div class="clubs-handled">
            <?php
            // Fetch all clubs handled by the moderator
            try {
                $stmt = $pdo->prepare("
                    SELECT c.club_id, c.clubName
                    FROM tbl_clubs c
                    JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                    WHERE cm.moderator_id = ?
                ");
                $stmt->execute([$moderator_id]);
                $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Dynamic text based on club count
                echo '<p>' . (count($clubs) === 1 ? 'Club Handled by' : 'Clubs Handled by') . ' <strong>' . $moderatorName . '</strong></p>';


                // Check if any clubs are found
                if (count($clubs) === 0) {
                    echo '<div class="alert alert-danger p-2 ps-3">
                                <em>No clubs found.</em>
                            </div>';
                          
                } else {
                    foreach ($clubs as $club) {
                        echo '
                        <div class="club-item">
                            <span>' . htmlspecialchars($club['clubName']) . '</span>
                            <form action="moderator_remove.php" method="POST">
                                <input type="hidden" name="club_id" value="' . htmlspecialchars($club['club_id']) . '">
                                <input type="hidden" name="moderator_id" value="' . htmlspecialchars($moderator_id) . '">
                                <button type="submit" class="btn remove-btn text-light">Remove as Moderator</button>
                            </form>
                        </div>';
                    }
                }

            } catch (PDOException $e) {
                echo "Error fetching clubs: " . $e->getMessage();
            }
            ?>
        </div>

        <div class="row mt-5 m-0">
            <div class="col-md-5 p-0"><hr></div>
            <div class="col-md-2 text-center"><label>or</label></div>
            <div class="col-md-5 p-0"><hr></div>
        </div>
        <!-- Assign Moderator to Another Club -->
        <div class="assign-section">
            <h4>Assign to Another Club</h4>
            <form action="" method="POST" id="assignForm">
                <input type="hidden" name="moderator_id" value="<?php echo htmlspecialchars($moderator_id); ?>">
                <div class="form-group">
                    <label for="club">Select Club:</label>
                    <select name="club_id" id="club" class="form-control">
                        <option value="" disabled selected>-- Select from existing clubs --</option>
                        <?php
                        // Fetch all clubs that the moderator is assigned to
                        try {
                            $stmt = $pdo->prepare("
                                SELECT c.club_id, c.clubName
                                FROM tbl_clubs c
                                JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                                WHERE cm.moderator_id = ?
                                ORDER BY c.clubName ASC
                            ");
                            $stmt->execute([$moderator_id]);
                            $currentClubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($currentClubs as $club) {
                                echo '<option value="' . htmlspecialchars($club['club_id']) . '" disabled>' . htmlspecialchars($club['clubName']) . ' (Current)</option>';
                            }
                        } catch (PDOException $e) {
                            echo "Error fetching current clubs: " . $e->getMessage();
                        }

                        // Fetch all clubs that the moderator isn't already assigned to
                        try {
                            $stmt = $pdo->prepare("
                                SELECT c.club_id, c.clubName
                                FROM tbl_clubs c
                                WHERE c.club_id NOT IN (
                                    SELECT club_id FROM tbl_clubs_and_moderators WHERE moderator_id = ?
                                )
                                ORDER BY c.clubName ASC
                            ");
                            $stmt->execute([$moderator_id]);
                            $availableClubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($availableClubs as $club) {
                                echo '<option value="' . htmlspecialchars($club['club_id']) . '">' . htmlspecialchars($club['clubName']) . '</option>';
                            }
                        } catch (PDOException $e) {
                            echo "Error fetching available clubs: " . $e->getMessage();
                        }
                        ?>
                    </select>
                </div>

                <div class="text-center d-flex justify-content-between mt-2">
                    <button type="submit" class="btn assign-btn text-light">Assign to Club</button>
                    <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
                </div>
            </form>
        </div>

        <script>
            document.getElementById('assignForm').addEventListener('submit', function(event) {
                var clubSelect = document.getElementById('club');
                var clubId = clubSelect.value;

                // Check if no club is selected
                if (!clubId) {
                    // Display the alert
                    alert('Please select a club before assigning a moderator.');
                    event.preventDefault(); // Prevent form submission
                }
            });
        </script>

    </div>

</body>
</html>
