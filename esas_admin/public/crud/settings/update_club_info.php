<?php
require_once "../../../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Fetch clubs handled by the active moderator
$sql = "
    SELECT c.club_id, c.clubName, c.description, c.mission, c.vision, c.history, c.coverPhoto, c.slots 
    FROM tbl_clubs AS c
    JOIN tbl_clubs_and_moderators AS cm ON c.club_id = cm.club_id
    WHERE cm.moderator_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process form submission for updating club description
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['club_id'])) {
    $clubId = $_POST['club_id'];
    $clubName = $_POST['clubName'];
    $description = $_POST['description'];
    $mission = $_POST['mission'];
    $vision = $_POST['vision'];
    $history = $_POST['history'];
    $coverPhoto = $_FILES['coverPhoto']['name'] ? $_FILES['coverPhoto']['name'] : null;
    $slots = $_POST['slots']; // Retrieve the slots value from the form

    // Handle cover photo upload
    if ($coverPhoto) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/esas/esas_admin/images/';

        if (in_array($_FILES['coverPhoto']['type'], $allowedTypes) && $_FILES['coverPhoto']['size'] <= $maxFileSize) {
            $fileName = uniqid() . '-' . basename($coverPhoto);
            $targetFilePath = $uploadDir . $fileName;

            // Move the uploaded file to the desired location
            if (!move_uploaded_file($_FILES['coverPhoto']['tmp_name'], $targetFilePath)) {
                echo "Error uploading the cover photo.";
            }
        } else {
            echo "Invalid file type or size exceeds 10MB.";
            $coverPhoto = null; // Reset if there's an error
        }
    } else {
        // If no new cover photo uploaded, keep the existing cover photo
        $coverPhoto = null; // Set this to null to not update the coverPhoto field
    }

    // Update club description
    $updateSql = "
        UPDATE tbl_clubs 
        SET clubName = ?, description = ?, mission = ?, vision = ?, history = ?, coverPhoto = COALESCE(?, coverPhoto), slots = ?, dateModified = NOW() 
        WHERE club_id = ?";
    
    $updateStmt = $pdo->prepare($updateSql);
    if ($updateStmt->execute([$clubName, $description, $mission, $vision, $history, $fileName ?? null, $slots, $clubId])) {
        // Log the activity after a successful update
        $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([
            'activity' => "You updated $clubName description",
            'dateAdded' => date('Y-m-d H:i:s'),
            'moderator_id' => $moderator_id
        ]);

        echo "Club description updated successfully!";
        header("location: ../../../settings.php");
        exit();
    } else {
        echo "Error updating club description.";
    }
}
?>

<style>
    .dashed-border {
        height: 2px; /* Thickness of the border */
        border-top: 2px dashed #ccc; /* Dashed line */
        margin: 30px 0; /* Space above and below */
    }
</style>

<h4 class="text-muted mb-3">Update Club Information</h4>

<?php if ($clubs): ?>
    <div class="club-list">
        <ul>
            <?php foreach ($clubs as $club): ?>
                <li>
                    <strong><?php echo htmlspecialchars($club['clubName']); ?></strong><br>

                    <!-- Update Form -->
                    <form class="mt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($club['club_id']); ?>">
                        <div class="form-group">
                            <label for="clubName">Club Name</label>
                            <input type="text" class="form-control" id="clubName" name="clubName" value="<?php echo htmlspecialchars($club['clubName']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($club['description']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="mission">Mission</label>
                            <textarea class="form-control" id="mission" name="mission" rows="4" required><?php echo htmlspecialchars($club['mission']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="vision">Vision</label>
                            <textarea class="form-control" id="vision" name="vision" rows="4" required><?php echo htmlspecialchars($club['vision']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="history">History</label>
                            <textarea class="form-control" id="history" name="history" rows="4" required><?php echo htmlspecialchars($club['history']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="slots">Membership Limit</label>
                            <input type="number" class="form-control" id="slots" name="slots" value="<?php echo htmlspecialchars($club['slots']); ?>" style="width: 40%;">
                        </div>
                        <div class="form-group">
                            <label for="coverPhoto">Cover Photo</label>
                            <div class="cover-photo-container d-flex align-items-start">
                                <?php if (!empty($club['coverPhoto'])): ?>
                                    <div class="mb-2">
                                        <img src="/esas/esas_admin/images/<?php echo htmlspecialchars($club['coverPhoto']); ?>" alt="Cover Photo" style="width: 430px; height: 240px; object-fit: cover;" id="img_<?php echo htmlspecialchars($club['club_id']); ?>">
                                    </div>
                                <?php endif; ?>

                                <input type="file" class="d-none" id="coverPhoto_<?php echo htmlspecialchars($club['club_id']); ?>" name="coverPhoto" accept="image/*" onchange="updateImagePreview(this, document.getElementById('img_<?php echo htmlspecialchars($club['club_id']); ?>'))">
                                
                                <label for="coverPhoto_<?php echo htmlspecialchars($club['club_id']); ?>" class="btn btn-light text-primary edit-icon" style="cursor: pointer;">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3">Update Club</button>
                    </form>
                </li>
                <div class="dashed-border"></div>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <p>No clubs found for this moderator.</p>
<?php endif; ?>

<script>
    function updateImagePreview(input, imgTag) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgTag.src = e.target.result; // Update the image source to the new file
            };
            reader.readAsDataURL(input.files[0]); // Read the file as a data URL
        }
    }
</script>
