<?php
require_once "../../../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Fetch application questions for the clubs handled by the active moderator
$sql = "
    SELECT q.question_id, q.question, c.clubName, q.dateAdded, q.dateModified
    FROM tbl_application_questions AS q
    JOIN tbl_clubs AS c ON q.club_id = c.club_id
    JOIN tbl_clubs_and_moderators AS cm ON c.club_id = cm.club_id
    WHERE cm.moderator_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process form submission for updating application questions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updatedCount = 0; // To count how many questions have been updated

    foreach ($_POST['questions'] as $questionId => $question) {
        // Update application question
        $updateSql = "
            UPDATE tbl_application_questions 
            SET question = ?, dateModified = NOW() 
            WHERE question_id = ?";
        
        $updateStmt = $pdo->prepare($updateSql);
        if ($updateStmt->execute([$question, $questionId])) {
            // Log the activity after a successful update
            $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
            $logStmt = $pdo->prepare($logSql);
            $logStmt->execute([
                'activity' => "You updated a question (ID: $questionId)",
                'dateAdded' => date('Y-m-d H:i:s'),
                'moderator_id' => $moderator_id
            ]);
            $updatedCount++; // Increment count for successful updates
        }
    }
    
    if ($updatedCount > 0) {
        echo "$updatedCount questions updated successfully!";
    } else {
        echo "No questions were updated.";
    }
    header("location: ../../../settings.php"); // Redirect to settings page
    exit();
}
?>

<h4 class="text-muted mb-3">Update Application Questions</h4>

<?php if ($questions): ?>
    <div class="question-list">
        <ul>
            <?php 
            $currentClub = ''; // Variable to keep track of the current club
            $clubQuestions = []; // Array to hold questions for the current club

            foreach ($questions as $question): 
                // Check if the club name has changed
                if ($currentClub !== $question['clubName']): 
                    // If we have questions for the previous club, output the form
                    if ($clubQuestions): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($currentClub); ?></strong> <!-- Display the club name -->
                        </li>
                        <form class="mt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <?php foreach ($clubQuestions as $clubQuestion): ?>
                                <div class="form-group">
                                    <input type="hidden" name="questions[<?php echo htmlspecialchars($clubQuestion['question_id']); ?>]" value="<?php echo htmlspecialchars($clubQuestion['question']); ?>">
                                    <textarea class="form-control mt-2" name="questions[<?php echo htmlspecialchars($clubQuestion['question_id']); ?>]" rows="4" required><?php echo htmlspecialchars($clubQuestion['question']); ?></textarea>
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-primary mb-3">Update Questions</button>
                        </form>
                    <?php 
                    // Reset club questions for the new club
                    $clubQuestions = []; 
                    endif; 
                    
                    // Update the current club
                    $currentClub = $question['clubName']; 
                endif; 

                // Add the current question to the club's question array
                $clubQuestions[] = $question; 
            endforeach; 

            // Close the form for the last club
            if ($clubQuestions): ?>
                <li>
                    <strong><?php echo htmlspecialchars($currentClub); ?></strong> <!-- Display the club name -->
                </li>
                <form class="mt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <?php foreach ($clubQuestions as $clubQuestion): ?>
                        <div class="form-group">
                            <input type="hidden" name="questions[<?php echo htmlspecialchars($clubQuestion['question_id']); ?>]" value="<?php echo htmlspecialchars($clubQuestion['question']); ?>">
                            <textarea class="form-control mt-2" name="questions[<?php echo htmlspecialchars($clubQuestion['question_id']); ?>]" rows="4" required><?php echo htmlspecialchars($clubQuestion['question']); ?></textarea>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary mb-3">Update Questions</button>
                </form>
            <?php endif; ?>
        </ul>
    </div>
<?php else: ?>
    <p>No application questions found for this club.</p>
<?php endif; ?>
