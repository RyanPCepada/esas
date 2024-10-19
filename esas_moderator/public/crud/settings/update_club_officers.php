<?php
require_once "../../../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Fetch clubs handled by the active moderator
$sql = "
    SELECT c.club_id, c.clubName 
    FROM tbl_clubs AS c
    JOIN tbl_clubs_and_moderators AS cm ON c.club_id = cm.club_id
    WHERE cm.moderator_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch members (students with 'active' status) of the selected club
function getActiveMembers($pdo, $clubId) {
    $sql = "
        SELECT s.student_id, s.firstName, s.lastName, s.profilePic 
        FROM tbl_students AS s
        JOIN tbl_registration AS r ON s.student_id = r.student_id
        WHERE r.club_id = ? AND r.status = 'active'
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$clubId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch existing officers for the club
function getClubOfficers($pdo, $clubId) {
    $sql = "
        SELECT o.president, o.vicePresident, o.secretary, o.treasurer, o.pio, o.srgtAtArms,
               s1.firstName AS presidentFirstName, s1.lastName AS presidentLastName, s1.profilePic AS presidentPic,
               s2.firstName AS vicePresidentFirstName, s2.lastName AS vicePresidentLastName, s2.profilePic AS vicePresidentPic,
               s3.firstName AS secretaryFirstName, s3.lastName AS secretaryLastName, s3.profilePic AS secretaryPic,
               s4.firstName AS treasurerFirstName, s4.lastName AS treasurerLastName, s4.profilePic AS treasurerPic,
               s5.firstName AS pioFirstName, s5.lastName AS pioLastName, s5.profilePic AS pioPic,
               s6.firstName AS srgtAtArmsFirstName, s6.lastName AS srgtAtArmsLastName, s6.profilePic AS srgtAtArmsPic
        FROM tbl_club_officers AS o
        LEFT JOIN tbl_students AS s1 ON o.president = s1.student_id
        LEFT JOIN tbl_students AS s2 ON o.vicePresident = s2.student_id
        LEFT JOIN tbl_students AS s3 ON o.secretary = s3.student_id
        LEFT JOIN tbl_students AS s4 ON o.treasurer = s4.student_id
        LEFT JOIN tbl_students AS s5 ON o.pio = s5.student_id
        LEFT JOIN tbl_students AS s6 ON o.srgtAtArms = s6.student_id
        WHERE o.club_id = ?
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$clubId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result ? $result : []; // Return an empty array if no officers are found
}

// Process form submission for updating club officers
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['club_id'])) {
    $clubId = $_POST['club_id'];
    $president_id = $_POST['president'] ?? null;
    $vice_president_id = $_POST['vice_president'] ?? null;
    $secretary_id = $_POST['secretary'] ?? null;
    $treasurer_id = $_POST['treasurer'] ?? null;
    $pio_id = $_POST['pio'] ?? null;
    $srgtAtArms_id = $_POST['srgt_at_arms'] ?? null;

    // Fetch the club name for logging purposes
    $clubNameSql = "SELECT clubName FROM tbl_clubs WHERE club_id = ?";
    $clubNameStmt = $pdo->prepare($clubNameSql);
    $clubNameStmt->execute([$clubId]);
    $clubName = $clubNameStmt->fetchColumn();

    // Check if officers already exist for the club
    $checkSql = "SELECT officer_id FROM tbl_club_officers WHERE club_id = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$clubId]);
    $existingOfficer = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingOfficer) {
        // Update existing officers
        $updateSql = "
            UPDATE tbl_club_officers 
            SET president = ?, vicePresident = ?, secretary = ?, treasurer = ?, pio = ?, srgtAtArms = ?, dateModified = NOW()
            WHERE club_id = ?
        ";
        $updateStmt = $pdo->prepare($updateSql);
        $success = $updateStmt->execute([$president_id, $vice_president_id, $secretary_id, $treasurer_id, $pio_id, $srgtAtArms_id, $clubId]);
    } else {
        // Insert new officers
        $insertSql = "
            INSERT INTO tbl_club_officers (president, vicePresident, secretary, treasurer, pio, srgtAtArms, club_id, dateAdded, dateModified)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ";
        $insertStmt = $pdo->prepare($insertSql);
        $success = $insertStmt->execute([$president_id, $vice_president_id, $secretary_id, $treasurer_id, $pio_id, $srgtAtArms_id, $clubId]);
    }

    if ($success) {
        // Log the activity after a successful update/insert
        $logSql = "INSERT INTO tbl_activity_logs (activity, dateAdded, moderator_id) VALUES (:activity, :dateAdded, :moderator_id)";
        $logStmt = $pdo->prepare($logSql);
        $logStmt->execute([
            'activity' => "You updated the club officers for $clubName",
            'dateAdded' => date('Y-m-d H:i:s'),
            'moderator_id' => $moderator_id
        ]);

        echo "Club officers updated successfully!";
        header("location: ../../../settings.php");
        exit();
    } else {
        echo "Error updating club officers.";
    }
}
?>


<h4 class="text-muted mb-3">Update Club Officers</h4>

<?php if ($clubs): ?>
    <div class="club-list">
        <ul>
            <?php foreach ($clubs as $club): ?>
                <?php 
                // Fetch active members and existing officers for the current club
                $activeMembers = getActiveMembers($pdo, $club['club_id']);
                $officers = getClubOfficers($pdo, $club['club_id']);
                ?>
                <li>
                    <strong><?php echo htmlspecialchars($club['clubName']); ?></strong><br>

                    <!-- Update Officers Form -->
                    <form class="mt-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="club_id" value="<?php echo htmlspecialchars($club['club_id']); ?>">

                        <!-- President -->
<div class="form-group">
    <label for="president">President</label>
    <select class="form-control" id="president" name="president">
        <option value="">Select President</option>
        <?php foreach ($activeMembers as $member): ?>
            <option value="<?php echo $member['student_id']; ?>" 
                <?php echo (isset($officers['president']) && $member['student_id'] == $officers['president']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($member['firstName'] . ' ' . $member['lastName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Vice President -->
<div class="form-group">
    <label for="vice_president">Vice President</label>
    <select class="form-control" id="vice_president" name="vice_president">
        <option value="">Select Vice President</option>
        <?php foreach ($activeMembers as $member): ?>
            <option value="<?php echo $member['student_id']; ?>" 
                <?php echo (isset($officers['vicePresident']) && $member['student_id'] == $officers['vicePresident']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($member['firstName'] . ' ' . $member['lastName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Secretary -->
<div class="form-group">
    <label for="secretary">Secretary</label>
    <select class="form-control" id="secretary" name="secretary">
        <option value="">Select Secretary</option>
        <?php foreach ($activeMembers as $member): ?>
            <option value="<?php echo $member['student_id']; ?>" 
                <?php echo (isset($officers['secretary']) && $member['student_id'] == $officers['secretary']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($member['firstName'] . ' ' . $member['lastName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Treasurer -->
<div class="form-group">
    <label for="treasurer">Treasurer</label>
    <select class="form-control" id="treasurer" name="treasurer">
        <option value="">Select Treasurer</option>
        <?php foreach ($activeMembers as $member): ?>
            <option value="<?php echo $member['student_id']; ?>" 
                <?php echo (isset($officers['treasurer']) && $member['student_id'] == $officers['treasurer']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($member['firstName'] . ' ' . $member['lastName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- PIO -->
<div class="form-group">
    <label for="pio">PIO</label>
    <select class="form-control" id="pio" name="pio">
        <option value="">Select PIO</option>
        <?php foreach ($activeMembers as $member): ?>
            <option value="<?php echo $member['student_id']; ?>" 
                <?php echo (isset($officers['pio']) && $member['student_id'] == $officers['pio']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($member['firstName'] . ' ' . $member['lastName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Sergeant at Arms -->
<div class="form-group">
    <label for="srgt_at_arms">Sergeant at Arms</label>
    <select class="form-control" id="srgt_at_arms" name="srgt_at_arms">
        <option value="">Select Sergeant at Arms</option>
        <?php foreach ($activeMembers as $member): ?>
            <option value="<?php echo $member['student_id']; ?>" 
                <?php echo (isset($officers['srgtAtArms']) && $member['student_id'] == $officers['srgtAtArms']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($member['firstName'] . ' ' . $member['lastName']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>


                        <button type="submit" class="btn btn-primary mb-3">Update Officers</button>
                        
                    <!-- <div class="mt-2 text-center align-items-center justify-content-center">
                        <a href="public/home.php" class="btn btn-secondary">Go Back</a>
                    </div> -->
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <p>No clubs assigned to you.</p>
<?php endif; ?>
