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
                        <div class="form-group">
                            <textarea class="form-control" id="question_<?php echo htmlspecialchars($question['question_id']); ?>" name="questions[<?php echo htmlspecialchars($question['question_id']); ?>]" rows="4" required><?php echo htmlspecialchars($question['question']); ?></textarea>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary mb-3">Update Questions</button>
                </form>
                <div class="dashed-border"></div>
            </ul>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No questions found for this moderator.</p>
<?php endif; ?>
