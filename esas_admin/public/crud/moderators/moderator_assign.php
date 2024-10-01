<?php
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Define the default profile picture constant
define('PROF_PIC_DEFAULT', 'PROF_PIC.png'); // Change 'PROF_PIC.png' to your actual default image path if necessary

$moderatorQuery = "SELECT moderator_id, CONCAT(firstName, ' ', lastName) AS moderator_name FROM tbl_moderators";
if ($stmt = $pdo->prepare($moderatorQuery)) {
    if ($stmt->execute()) {
        $moderators = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$firstName = $middleInitial = $lastName = $email = $password = "";
$firstName_err = $middleInitial_err = $lastName_err = $email_err = $password_err = "";
$clubs = [];

// Fetch clubs for selection
$clubQuery = "SELECT club_id, clubName FROM tbl_clubs";
if ($stmt = $pdo->prepare($clubQuery)) {
    if ($stmt->execute()) {
        $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (isset($_POST['fetch_type'])) {
    if ($_POST['fetch_type'] == 'assigned_clubs' && isset($_POST['moderator_id'])) {
        // Fetch clubs assigned to the selected moderator
        $moderator_id = $_POST['moderator_id'];
        $sql = "SELECT club_id FROM tbl_clubs_and_moderators WHERE moderator_id = :moderator_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':moderator_id', $moderator_id);
        $stmt->execute();
        $assignedClubs = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($assignedClubs);
        exit();
    } elseif ($_POST['fetch_type'] == 'assigned_moderators' && isset($_POST['club_id'])) {
        // Fetch moderators assigned to the selected club
        $club_id = $_POST['club_id'];
        $sql = "SELECT moderator_id FROM tbl_clubs_and_moderators WHERE club_id = :club_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':club_id', $club_id);
        $stmt->execute();
        $assignedModerators = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($assignedModerators);
        exit();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["moderator"]) && !empty($_POST["club"])) {
        $selectedModeratorId = $_POST["moderator"];
        $selectedClubId = $_POST["club"];

        // Insert into tbl_clubs_and_moderators
        $sql = "INSERT INTO tbl_clubs_and_moderators (club_id, moderator_id, dateAdded) 
                VALUES (:clubId, :moderatorId, NOW())";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":clubId", $selectedClubId);
            $stmt->bindParam(":moderatorId", $selectedModeratorId);
            if ($stmt->execute()) {
                header("location: ../../moderators.php"); // Redirect to a list of moderators
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Add Moderator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link href="../../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../../assets/img/nbsclogo.png" rel="icon">

    <style>
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Assign a Moderator</h2>
                    <p>Please select from dropboxes below to assign a new moderator.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group mb-2">
                            <label>Select a Moderator</label>
                            <select name="moderator" id="moderatorSelect" class="form-control">
                                <option value="">-- Select From Existing Moderators --</option>
                                <?php foreach ($moderators as $moderator): ?>
                                    <option value="<?php echo htmlspecialchars($moderator['moderator_id']); ?>">
                                        <?php echo htmlspecialchars($moderator['moderator_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label>Assign to a Club</label>
                            <select name="club" id="clubSelect" class="form-control">
                                <option value="">-- Select From Existing Clubs --</option>
                                <?php foreach ($clubs as $club): ?>
                                    <option value="<?php echo htmlspecialchars($club['club_id']); ?>">
                                        <?php echo htmlspecialchars($club['clubName']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../../moderators.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>

        // ALERT MESSAGE FOR UNSELECTED BOTH MODERATORS AND CLUBS
        document.querySelector('form').addEventListener('submit', function(event) {
            var moderatorSelect = document.getElementById('moderatorSelect');
            var clubSelect = document.getElementById('clubSelect');

            if (moderatorSelect.value === "" || clubSelect.value === "") {
                event.preventDefault(); // Prevent form submission
                alert('Please select both a moderator and a club.'); // Show alert
            }
        });
        document.getElementById('moderatorSelect').addEventListener('change', function () {
            var moderatorId = this.value;
            if (moderatorId !== "") {
                fetchAssignedClubs(moderatorId);
            }
        });
        document.getElementById('clubSelect').addEventListener('change', function () {
            var clubId = this.value;
            if (clubId !== "") {
                fetchAssignedModerators(clubId);
            }
        });
        // ALERT MESSAGE FOR UNSELECTED BOTH MODERATORS AND CLUBS







        document.getElementById('moderatorSelect').addEventListener('change', function () {
            var moderatorId = this.value;
            if (moderatorId !== "") {
                fetchAssignedClubs(moderatorId);
            }
        });

        document.getElementById('clubSelect').addEventListener('change', function () {
            var clubId = this.value;
            if (clubId !== "") {
                fetchAssignedModerators(clubId);
            }
        });

        function fetchAssignedClubs(moderatorId) {
            fetch("", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "fetch_type=assigned_clubs&moderator_id=" + encodeURIComponent(moderatorId),
            })
            .then((response) => response.json())
            .then((data) => {
                var clubSelect = document.getElementById("clubSelect");
                var options = clubSelect.getElementsByTagName("option");

                // Convert all assigned clubs to strings for comparison
                const assignedClubs = data.map(String);

                for (var i = 0; i < options.length; i++) {
                    if (assignedClubs.includes(options[i].value)) {
                        options[i].disabled = true;
                        // Check if " (Current)" is already in the text
                        if (!options[i].text.includes(" (Current)")) {
                            options[i].text += " (Current)"; // Add "Current" to the option text
                        }
                    } else {
                        options[i].disabled = false;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function fetchAssignedModerators(clubId) {
            fetch("", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "fetch_type=assigned_moderators&club_id=" + encodeURIComponent(clubId),
            })
            .then((response) => response.json())
            .then((data) => {
                var moderatorSelect = document.getElementById("moderatorSelect");
                var options = moderatorSelect.getElementsByTagName("option");

                // Convert all assigned moderators to strings for comparison
                const assignedModerators = data.map(String);

                for (var i = 0; i < options.length; i++) {
                    if (assignedModerators.includes(options[i].value)) {
                        options[i].disabled = true;
                        // Check if " (Current)" is already in the text
                        if (!options[i].text.includes(" (Current)")) {
                            options[i].text += " (Current)"; // Add "Current" to the option text
                        }
                    } else {
                        options[i].disabled = false;
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }


    </script>
</body>
</html>
