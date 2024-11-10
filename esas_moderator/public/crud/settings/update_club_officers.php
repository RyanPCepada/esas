<?php
require_once "../../../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Fetch officers and positions for the club handled by the active moderator
$sql = "
    SELECT c.club_id, c.clubName, o.officer_id, o.position, o.student_id
    FROM tbl_clubs AS c
    LEFT JOIN tbl_club_officers AS o ON c.club_id = o.club_id
    JOIN tbl_clubs_and_moderators AS cm ON c.club_id = cm.club_id
    WHERE cm.moderator_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$officers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if there are officers to display
$clubName = !empty($officers) ? $officers[0]['clubName'] : null;

// Process form submission for updating officers
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['club_id'])) {
    $clubId = $_POST['club_id'];
    $updatedOfficers = $_POST['officers']; // Array of officers with student_id and position

    // Initialize a flag to check if any officer was updated
    $anyUpdate = false;

    // Loop through each officer and update it
    foreach ($updatedOfficers as $officerId => $officerData) {
        $studentId = $officerData['student_id'];
        $position = $officerData['position'];

        // Update officer details
        $updateSql = "
            UPDATE tbl_club_officers
            SET student_id = ?, position = ?, dateModified = NOW()
            WHERE officer_id = ? AND club_id = ?
        ";
        $updateStmt = $pdo->prepare($updateSql);
        if ($updateStmt->execute([$studentId, $position, $officerId, $clubId])) {
            // Set flag to true if at least one officer was updated
            $anyUpdate = true;
        }
    }

    // Log the activity only if at least one officer was updated
    if ($anyUpdate) {
        $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([
            'activity' => "You updated the officers in " . htmlspecialchars($clubName) . "'s application form",
            'dateAdded' => date('Y-m-d H:i:s'),
            'moderator_id' => $moderator_id
        ]);
    }

    echo "Officers updated successfully!";
    header("location: ../../../settings.php");
    exit();
}

// Fetch active students for dropdown, filtered by current club
$studentsSql = "
    SELECT DISTINCT s.student_id, CONCAT(s.firstName, ' ', s.lastName) AS fullName
    FROM tbl_students s
    JOIN tbl_application a ON s.student_id = a.student_id
    WHERE a.status = 'active' AND a.club_id = ?
    ORDER BY fullName
";
$studentsStmt = $pdo->prepare($studentsSql);
$club_id = isset($officers[0]['club_id']) ? $officers[0]['club_id'] : 0; // Ensure $club_id is correctly set
$studentsStmt->execute([$club_id]);

// Fetch the students
$students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);

?>


<style>
    .dashed-border {
        height: 2px; /* Thickness of the border */
        border-top: 2px dashed #ccc; /* Dashed line */
        margin: 30px 0; /* Space above and below */
    }

    .form-group {
        position: relative; /* Ensure the delete icon is positioned relative to this container */
    }

    .delete-icon {
        position: absolute;
        top: 30%;
        right: -10px; /* Adjust the right distance as needed */
        transform: translateY(-50%); /* Vertically center the icon */
        cursor: pointer;
        background-color: #a9a9a9;
        border-radius: 50%;
        color: white;
        width: 22px;
        height: 22px;
        text-align: center;
        line-height: 21px; /* Center the text vertically */
        border: none; /* Remove default button styling */
    }

    .delete-icon:hover {
        background-color: #8f8f8f;
    }
    
    body.modal-open {
        padding-right: 0 !important;
    }
</style>

<h4 class="text-muted mb-3">Update Club Officers</h4>

<?php if ($officers): ?>
    <div class="officer-list">
        <?php 
        // Group officers by club
        $clubOfficers = [];
        foreach ($officers as $officer) {
            $clubOfficers[$officer['clubName']][] = $officer;
        }

        foreach ($clubOfficers as $clubName => $officersArray): ?>
            <ul>
                <li>
                    <strong><?php echo htmlspecialchars($clubName); ?></strong>
                </li>

                
                <?php if (!empty($officersArray[0]['position'])): ?>
                    <form class="mt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="officer_id" value="<?php echo htmlspecialchars($officersArray[0]['officer_id']); ?>">

                        <?php foreach ($officersArray as $officer): ?>
                            <div class="form-group">
                                <label for="officer_position_<?php echo htmlspecialchars($officer['officer_id']); ?>">
                                    <?php echo htmlspecialchars($officer['position']) ?>
                                    Club ID: <?php echo htmlspecialchars($officer['club_id']) ?>
                                    Officer ID: <?php echo htmlspecialchars($officer['officer_id']) ?>
                                </label>

                                <span class="delete-icon" title="Delete Position" onclick="deleteOfficer(<?php echo htmlspecialchars($officer['officer_id']); ?>, <?php echo htmlspecialchars($officer['club_id']); ?>)">
                                    <i class="fas fa-times"></i>
                                </span>

                                <input type="text" class="form-control mb-1" id="officer_position_<?php echo htmlspecialchars($officer['officer_id']); ?>"
                                    name="officers[<?php echo htmlspecialchars($officer['officer_id']); ?>][position]" value="<?php echo htmlspecialchars($officer['position']); ?>" placeholder="Add Position here...">
                                
                                <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($officersArray[0]['club_id']); ?>">


                                <!-- <label for="officer_student_<?php echo htmlspecialchars($officer['officer_id']); ?>">Officer: </label> -->
                                <select class="form-control" id="officer_student_<?php echo htmlspecialchars($officer['officer_id']); ?>" name="officers[<?php echo htmlspecialchars($officer['officer_id']); ?>][student_id]">
                                    <option value="">-- Select student --</option>
                                    <?php 
                                    // Fetch students for the current club
                                    $studentsSql = "
                                        SELECT DISTINCT s.student_id, CONCAT(s.firstName, ' ', s.lastName) AS fullName
                                        FROM tbl_students s
                                        JOIN tbl_application a ON s.student_id = a.student_id
                                        WHERE a.status = 'active' AND a.club_id = ?
                                        ORDER BY fullName
                                    ";
                                    $studentsStmt = $pdo->prepare($studentsSql);
                                    $studentsStmt->execute([$officer['club_id']]);  // Use the club_id of the current officer's club
                                    $students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    foreach ($students as $student): ?>
                                        <option value="<?php echo htmlspecialchars($student['student_id']); ?>" <?php echo $student['student_id'] == $officer['student_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($student['fullName']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>

                        <button type="submit" class="btn btn-primary mb-3">Update Officers</button>
                        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addOfficerModal" data-club-id="<?php echo htmlspecialchars($officer['club_id']); ?>">
                            <i class="fas fa-plus"></i> Add New Officer
                        </button>
                        <a href="../esas_moderator/actions/delete_officer_action.php?officer_id=<?php echo urlencode($officer['officer_id']); ?>&club_id=<?php echo urlencode($officer['club_id']); ?>" >
                            Delete Officer
                        </a>
                    
                    </form>
                    
                <?php else: ?>
                    <form class="mt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="officer_id" value="<?php echo htmlspecialchars($officersArray[0]['officer_id']); ?>">
                        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addOfficerModal" data-club-id="<?php echo htmlspecialchars($officer['club_id']); ?>">
                            <i class="fas fa-plus"></i> Add New Officer
                        </button>

                        <a href="../esas_moderator/actions/delete_officer_action.php?officer_id=<?php echo urlencode($officer['officer_id']); ?>&club_id=<?php echo urlencode($officer['club_id']); ?>" >
                        Delete Officer
                        </a>
                    </form>

                <?php endif; ?>

                <div class="dashed-border"></div>
            </ul>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No officers found for this moderator.</p>
<?php endif; ?>

<!-- Add New Officer Modal -->
<div class="modal fade" id="addOfficerModal" tabindex="-1" aria-labelledby="addOfficerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOfficerModalLabel">Add New Officer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addOfficerForm" action="../esas_moderator/actions/add_officer_action.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="officer_position">Position</label>
                        <input type="text" class="form-control" id="officer_position" name="position" placeholder="Enter Position" required>
                    </div>
                    <div class="form-group">
                        <label for="officer_student">Select Officer (Student)</label>
                        <select class="form-control" id="officer_student" name="student_id">
                            <option value="">-- Select Student --</option>
                            <!-- Student options will be populated here dynamically via JavaScript -->
                        </select>
                    </div>
                    <input type="hidden" id="club_id" name="club_id" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Officer</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
    function deleteOfficer(officerId, clubId) {
        if (confirm("Are you sure you want to delete this officer from club ID " + clubId + " and officer ID " + officerId + "?")) {
            // Make an AJAX request to delete the officer
            window.location.href = "../esas_moderator/actions/delete_officer_action.php?officer_id=" + officerId + "&club_id=" + clubId;
        }
    }

    // Populate club_id in Officer Add Modal
    $(document).ready(function() {
    $('#addOfficerModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var clubId = button.data('club-id'); // Get the club_id from the button's data attribute
        
        var modal = $(this);
        modal.find('#club_id').val(clubId); // Set the hidden input field's value
        
        // Display the club_id in the modal, e.g., in a label or paragraph if desired
        modal.find('.modal-body').prepend('<p><strong>Club ID:</strong> ' + clubId + '</p>');
    });
});


    // Handle the form submission for adding an officer
    // Populate club_id in Officer Add Modal
$(document).ready(function() {
    $('#addOfficerModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var clubId = button.data('club-id'); // Get the club_id from the button's data attribute
        
        var modal = $(this);
        modal.find('#club_id').val(clubId); // Set the hidden input field's value
        
        // Remove any previously added club_id info
        modal.find('.modal-body p').remove();

        // Display the current club_id in the modal body
        modal.find('.modal-body').prepend('<p><strong>Club ID:</strong> ' + clubId + '</p>');
    });
});


</script>

