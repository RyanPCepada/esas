<?php
require_once "../../../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Fetch application questions along with club names for the club handled by the active moderator
$sql = "
    SELECT q.question_id, q.question, c.clubName, q.club_id 
    FROM tbl_application_questions AS q
    JOIN tbl_clubs AS c ON q.club_id = c.club_id
    JOIN tbl_clubs_and_moderators AS cm ON c.club_id = cm.club_id
    WHERE cm.moderator_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process form submission for updating application questions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['club_id'])) {
    $clubId = $_POST['club_id'];
    $updatedQuestions = $_POST['questions']; // Array of questions

    // Loop through each question and update it
    foreach ($updatedQuestions as $questionId => $questionText) {
        // Update application question
        $updateSql = "
            UPDATE tbl_application_questions 
            SET question = ?, dateModified = NOW() 
            WHERE question_id = ?";

        $updateStmt = $pdo->prepare($updateSql);
        if ($updateStmt->execute([$questionText, $questionId])) {
            // Log the activity after a successful update
            $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([
                'activity' => "You updated a question with ID $questionId",
                'dateAdded' => date('Y-m-d H:i:s'),
                'moderator_id' => $moderator_id
            ]);
        }
    }
    echo "Questions updated successfully!";
    header("location: ../../../settings.php");
    exit();
}
?>

<style>
    .dashed-border {
        height: 2px; /* Thickness of the border */
        border-top: 2px dashed #ccc; /* Dashed line */
        margin: 30px 0; /* Space above and below */
    }

    .delete-icon {
        position: absolute; /* Use absolute positioning */
        top: -5px; /* Adjust positioning */
        right: -10px; /* Adjust positioning */
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

<h4 class="text-muted mb-3">Update Application Questions</h4>

<?php if ($questions): ?>
    <div class="question-list">
        <?php 
        // Group questions by club
        $clubQuestions = [];
        foreach ($questions as $question) {
            $clubQuestions[$question['clubName']][] = $question;
        }
        
        // Display each club and its questions
        foreach ($clubQuestions as $clubName => $clubQuestionsArray): ?>
            <ul>
                <li>
                    <strong><?php echo htmlspecialchars($clubName); ?></strong>
                </li>
                <form class="mt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($clubQuestionsArray[0]['club_id']); ?>">
                    <?php foreach ($clubQuestionsArray as $question): ?>
                        <div class="form-group position-relative">
                            <textarea class="form-control" id="question_<?php echo htmlspecialchars($question['question_id']); ?>" name="questions[<?php echo htmlspecialchars($question['question_id']); ?>]" rows="4" required><?php echo htmlspecialchars($question['question']); ?></textarea>
                            <span class="delete-icon" title="Delete Question" onclick="deleteQuestion(<?php echo htmlspecialchars($question['question_id']); ?>)"><i class="fas fa-times"></i></span>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary mb-3">Update Questions</button>
<button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#appQuestionAddModal">Add New Question</button>

                </form>
                <div class="dashed-border"></div>
            </ul>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No questions found for this moderator.</p>
<?php endif; ?>

<!-- Add New Application Question Modal -->
<div class="modal fade" id="appQuestionAddModal" tabindex="-1" aria-labelledby="appQuestionAddModalLabel" aria-hidden="true">
    <div class="modal-dialog app-question-modal-dialog">
        <div class="modal-content app-question-modal-content">
            <div class="modal-header app-question-modal-header">
                <h5 class="modal-title app-question-modal-title" id="appQuestionAddModalLabel">Add New Application Question</h5>
                <button type="button" class="close app-question-modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="appQuestionAddForm" action="add_question_action.php" method="post">
                <div class="modal-body app-question-modal-body">
                    <div class="form-group app-question-form-group">
                        <label for="appNewQuestion" class="app-question-label">Question</label>
                        <textarea class="form-control app-question-textarea" id="appNewQuestion" name="new_question" rows="4" required></textarea>
                    </div>
                    <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($clubQuestionsArray[0]['club_id']); ?>">
                </div>
                <div class="modal-footer app-question-modal-footer">
                    <button type="button" class="btn btn-secondary app-question-cancel-btn" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary app-question-add-btn">Add Question</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>|
<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function deleteQuestion(questionId) {
    if (confirm("Are you sure you want to delete this question?")) {
        $.ajax({
            url: '/esas/esas_moderator/actions/delete_question_action.php', // Change to the path of your deletion script
            type: 'POST',
            data: { delete_question_id: questionId },
            success: function(response) {
                // alert(response); // Show success message
                location.reload(); // Reload the page to update the question list
            },
            error: function() {
                alert('Error deleting question.');
            }
        });
    }
}


<script>
$(document).ready(function() {
    $('#addQuestionForm').on('submit', function(event) {
        event.preventDefault();

        $.ajax({
            url: '/esas/esas_moderator/actions/add_question_action.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                alert(response); // Optional: Display response message
                $('#addQuestionModal').modal('hide'); // Close the modal
                location.reload(); // Reload to update the question list
            },
            error: function() {
                alert('Error adding new question.');
            }
        });
    });
});
</script>

</script>
