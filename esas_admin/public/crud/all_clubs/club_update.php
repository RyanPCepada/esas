<?php
require_once "../../../../config.php";

    $clubId = $_GET['club_id'] ?? null; // Get the club ID from the query string
    $clubName = $information = $coverPhoto = "";
    $clubName_err = $information_err = $coverPhoto_err = "";
    define('COVERPHOTO_DEFAULT', 'COVERPHOTO_DEFAULT.png');

    // Fetch moderators
    $moderatorQuery = "SELECT moderator_id, CONCAT(firstName, ' ', lastName) AS moderator_name FROM tbl_moderators";
    if ($stmt = $pdo->prepare($moderatorQuery)) {
        if ($stmt->execute()) {
            $moderators = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // Fetch current moderators for the club
    $currentModerators = [];
    if ($clubId) {
        $moderatorCountQuery = "SELECT cm.clubmod_id, cm.moderator_id, cm.club_id, CONCAT(m.firstName, ' ', m.lastName) AS moderator_name 
                                FROM tbl_clubs_and_moderators cm
                                JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
                                WHERE cm.club_id = :clubId";
        if ($stmt = $pdo->prepare($moderatorCountQuery)) {
            $stmt->bindParam(":clubId", $clubId);
            if ($stmt->execute()) {
                $currentModerators = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }


    $currentModeratorId = null; // Variable to hold current moderator ID

    if ($clubId) {
        // Update club query to fetch current moderator ID
        $clubQuery = "SELECT c.*, cm.moderator_id FROM tbl_clubs c
                    LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                    WHERE c.club_id = :clubId";
        if ($stmt = $pdo->prepare($clubQuery)) {
            $stmt->bindParam(":clubId", $clubId);
            if ($stmt->execute()) {
                $club = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($club) {
                    $clubName = $club['clubName'];
                    $information = $club['information'];
                    $coverPhoto = $club['coverPhoto'] ?: COVERPHOTO_DEFAULT; // Set default if no cover photo exists
                    $currentModeratorId = $club['moderator_id']; // Fetch current moderator ID
                } else {
                    echo "No club found with that ID.";
                    exit();
                }
            }
        }
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $input_clubName = trim($_POST["clubName"]);
        if (empty($input_clubName)) {
            $clubName_err = "Please enter a club name.";
        } else {
            $clubName = $input_clubName;
        }

        $input_information = trim($_POST["information"]);
        if (empty($input_information)) {
            $information_err = "Please enter club information.";
        } else {
            $information = $input_information;
        }

        // Handle cover photo upload
        if (isset($_FILES['coverPhoto']) && $_FILES['coverPhoto']['name']) {
            $coverPhotoName = $_FILES['coverPhoto']['name'];
            $coverPhotoSize = $_FILES['coverPhoto']['size'];
            $coverPhotoTmpName = $_FILES['coverPhoto']['tmp_name'];

            $validImageExtensions = ['jpg', 'jpeg', 'png'];
            $imageExtension = strtolower(pathinfo($coverPhotoName, PATHINFO_EXTENSION));

            if (!in_array($imageExtension, $validImageExtensions)) {
                $coverPhoto_err = "Invalid image extension. Only JPG, JPEG, and PNG are allowed.";
            } elseif ($coverPhotoSize > 5000000) {
                $coverPhoto_err = "Image size is too large (max 5MB).";
            } else {
                $newCoverPhotoName = 'club_' . uniqid() . '.' . $imageExtension;
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_admin/images/';
                $uploadPath = $uploadDir . $newCoverPhotoName;

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                if (!move_uploaded_file($coverPhotoTmpName, $uploadPath)) {
                    $coverPhoto_err = "Failed to upload image.";
                } else {
                    $coverPhoto = $newCoverPhotoName;
                }
            }
        } else {
            // Retain old cover photo if not uploaded
            $coverPhoto = $club['coverPhoto'] ?: COVERPHOTO_DEFAULT;
        }

        // Get selected moderator
        $selectedModerator = $_POST["moderator"] ?? null;


    // Update the club if no errors
if (empty($clubName_err) && empty($information_err) && empty($coverPhoto_err)) {
    $sql = "UPDATE tbl_clubs SET clubName = :clubName, information = :information, coverPhoto = :coverPhoto WHERE club_id = :clubId";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":clubName", $clubName);
        $stmt->bindParam(":information", $information);
        $stmt->bindParam(":coverPhoto", $coverPhoto);
        $stmt->bindParam(":clubId", $clubId);

        if ($stmt->execute()) {
            // Handle moderator association
            if (!empty($_POST['moderator'])) { // Check if there are selected moderators
                foreach ($_POST['moderator'] as $index => $moderatorId) {
                    if ($moderatorId === 'none') {
                        // Get the current moderator ID for this dropdown
                        $currentModeratorId = $currentModerators[$index]['moderator_id'];
                        
                        // Delete the specific moderator association
                        $deleteSql = "DELETE FROM tbl_clubs_and_moderators WHERE club_id = :clubId AND moderator_id = :moderatorId";
                        if ($deleteStmt = $pdo->prepare($deleteSql)) {
                            $deleteStmt->bindParam(":clubId", $clubId);
                            $deleteStmt->bindParam(":moderatorId", $currentModeratorId);
                            $deleteStmt->execute();
                        }
                    } else {
                        // Associate the existing moderator with the club
                        $sql2 = "INSERT INTO tbl_clubs_and_moderators (club_id, moderator_id) VALUES (:clubId, :moderatorId)";
                        if ($stmt2 = $pdo->prepare($sql2)) {
                            $stmt2->bindParam(":clubId", $clubId);
                            $stmt2->bindParam(":moderatorId", $moderatorId);
                            if (!$stmt2->execute()) { // Check if the insertion was successful
                                // Log or handle the error
                                echo "Failed to associate moderator ID $moderatorId with club. Error: " . implode(", ", $stmt2->errorInfo());
                            }
                        }
                    }
                }
            } else {
                echo "No moderators selected for association.";
            }

            header("location: ../../all_clubs.php");
            exit();
        } else {
            echo "Oops! Something went wrong with the club update. Please try again later.";
        }
    }
}



    unset($stmt);
    }

unset($pdo);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Update Club</title>
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
        #coverPhotoPreview {
            width: 100%;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>


    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Club</h2>
                    <p>Please fill this form to update the club information.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?club_id=' . $clubId; ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-2">
                            <label>Club Name</label>
                            <input type="text" name="clubName" class="form-control <?php echo (!empty($clubName_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($clubName); ?>">
                            <span class="invalid-feedback"><?php echo $clubName_err; ?></span>
                        </div>
                        <div class="form-group mb-2">
                            <label>Information</label>
                            <textarea name="information" class="form-control <?php echo (!empty($information_err)) ? 'is-invalid' : ''; ?>" rows="5"><?php echo htmlspecialchars($information); ?></textarea>
                            <span class="invalid-feedback"><?php echo $information_err; ?></span>
                        </div>
                        <div class="form-group mb-2">
                            <label>Cover Photo</label>
                            <input type="file" name="coverPhoto" id="coverPhoto" class="form-control-file <?php echo (!empty($coverPhoto_err)) ? 'is-invalid' : ''; ?>" onchange="previewImageUpdate()">
                            <span class="invalid-feedback"><?php echo $coverPhoto_err; ?></span>
                            <img src="/esas/esas_admin/images/<?php echo htmlspecialchars($coverPhoto); ?>" id="coverPhotoPreview" alt="" style="display: block; margin-top: 10px; width: 100%; height: auto;">
                        </div>



                        <!-- <hr>
                        <div class="form-group mb-2"> 
                            <div id="moderatorDropdowns">
                                <php if (!empty($currentModerators)): ?>
                                    <label>Change Moderators</label>
                                    <php foreach ($currentModerators as $moderator): ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <select name="moderator[]" class="form-control mr-2" required>
                                                <option value="">-- Select Moderator --</option>
                                                <option value="none">None</option>
                                                <optgroup label="Existing Moderators">
                                                    <php foreach ($moderators as $existingModerator): ?>
                                                        <option value="<php echo htmlspecialchars($existingModerator['moderator_id']); ?>"
                                                            <php echo ($existingModerator['moderator_id'] == $moderator['moderator_id']) ? 'selected disabled' : ''; ?>>
                                                            <php echo htmlspecialchars($existingModerator['moderator_name']); ?>
                                                        </option>
                                                    <php endforeach; ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                    <php endforeach; ?>
                                <php else: ?>
                                    <p>No moderators currently associated with this club.</p>
                                    <div class="d-flex align-items-center mb-2">
                                        <select name="moderator[]" class="form-control mr-2" required>
                                            <option value="">-- Select Moderator --</option>
                                            <option value="none">None</option>
                                            <optgroup label="Existing Moderators">
                                                <php foreach ($moderators as $existingModerator): ?>
                                                    <option value="<php echo htmlspecialchars($existingModerator['moderator_id']); ?>">
                                                        <php echo htmlspecialchars($existingModerator['moderator_name']); ?>
                                                    </option>
                                                <php endforeach; ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                <php endif; ?>
                            </div>
                        </div> -->





                        <!-- <div class="row mt-3 m-0">
                            <div class="col-md-4 p-0"><hr></div>
                            <div class="col-md-4 text-center"><label>Or Add New</label></div>
                            <div class="col-md-4 p-0"><hr></div>
                        </div> -->

                        <!-- <select name="moderator" id="moderatorSelectNew" class="form-control mb-2">
                            <option value="">-- Select From Other Existing Moderators --</option>
                            <optgroup label="">
                                <?php foreach ($moderators as $moderator): ?>
                                    <?php if ($moderator['moderator_id'] == $currentModeratorId): ?>
                                        <option value="<?php echo htmlspecialchars($moderator['moderator_id']); ?>" disabled>
                                            <?php echo htmlspecialchars($moderator['moderator_name']) . ' (current)'; ?>
                                        </option>
                                    <?php else: ?>
                                        <option value="<?php echo htmlspecialchars($moderator['moderator_id']); ?>">
                                            <?php echo htmlspecialchars($moderator['moderator_name']); ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </optgroup>
                            <optgroup label=" ">
                                <option value="add_new_moderator" style="font-weight: bold;">+ Add New Moderator</option>
                            </optgroup>
                        </select> -->

                        <input type="submit" class="btn btn-primary" value="Update">
                        <a href="javascript:history.back()" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <!-- Add New Moderator Modal -->
    <div class="modal fade" id="addModeratorModal" tabindex="-1" role="dialog" aria-labelledby="addModeratorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModeratorModalLabel">Add New Moderator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addModeratorForm">
                        <div class="form-group mb-2">
                            <label>First Name:</label>
                            <input type="text" name="firstName" class="form-control underline-input" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Middle Initial:</label>
                            <input type="text" name="middleInitial" maxlength="1" class="form-control underline-input">
                        </div>
                        <div class="form-group mb-2">
                            <label>Last Name:</label>
                            <input type="text" name="lastName" class="form-control underline-input">
                        </div>
                        <div class="form-group mb-2">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control underline-input" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Temporary Password:</label>
                            <input type="password" name="password" class="form-control underline-input" required>
                        </div>
                        <input type="hidden" id="newModeratorId" name="newModeratorId" value="">
                        <input type="submit" class="btn btn-primary" value="Add Moderator">
                    </form>
                </div>
            </div>
        </div>
    </div>






    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Include Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include Cropper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>


    <script>

        function openAddModeratorModal() {
            $('#addModeratorModal').modal('show'); // Open the modal using Bootstrap's modal method
        }
        
        document.getElementById("moderatorSelectNew").addEventListener("change", function() {
            if (this.value === "add_new_moderator") {
                $("#addModeratorModal").modal("show");
            }
        });
        
        document.getElementById("moderatorSelect").addEventListener("change", function() {
            if (this.value === "add_new_moderator") {
                $("#addModeratorModal").modal("show");
            }
        });

        document.getElementById("addModeratorForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            formData.append('action', 'add_moderator');

            fetch("<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>", {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById("moderatorSelect");
                    const newOption = document.createElement("option");
                    newOption.value = data.moderatorId;
                    newOption.text = data.fullName;
                    select.appendChild(newOption);
                    select.value = data.moderatorId;

                    $("#addModeratorModal").modal("hide");
                    document.getElementById('newModeratorId').value = data.moderatorId;
                } else {
                    alert("Error adding moderator.");
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>





<script>
    function previewImageUpdate() {
        const fileInput = document.getElementById('coverPhoto');
        const preview = document.getElementById('coverPhotoPreview');
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            // Set the preview image src to the file's data URL
            preview.src = reader.result;
        }, false);

        if (file) {
            reader.readAsDataURL(file); // Read the file as a data URL
        }
    }
</script>



</body>
</html>
