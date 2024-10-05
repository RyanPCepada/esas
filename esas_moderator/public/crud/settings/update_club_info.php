<?php
require_once "../../../../config.php"; // Include your database config file
session_start();

if (!isset($_SESSION['moderator_id'])) {
    die("You are not logged in.");
}

$moderator_id = $_SESSION['moderator_id'];

// Fetch clubs handled by the active moderator
$sql = "
    SELECT c.club_id, c.clubName, c.information, c.coverPhoto 
    FROM tbl_clubs AS c
    JOIN tbl_clubs_and_moderators AS cm ON c.club_id = cm.club_id
    WHERE cm.moderator_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$moderator_id]);
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h4 class="text-muted">Update Club Information</h4>

<?php if ($clubs): ?>
    <div class="club-list">
        <h5>Your Clubs:</h5>
        <ul>
            <?php foreach ($clubs as $club): ?>
                <li>
                    <strong><?php echo htmlspecialchars($club['clubName']); ?></strong><br>
                    <p><?php echo htmlspecialchars($club['information']); ?></p>
                    <?php if (!empty($club['coverPhoto'])): ?>
                        <img src="/esas/esas_moderator/images/<?php echo htmlspecialchars($club['coverPhoto']); ?>" alt="<?php echo htmlspecialchars($club['clubName']); ?>" style="width: 215px; height: 120px; border-radius: 5px; object-fit: cover;">
                    <?php endif; ?>
                </li>
                <hr>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <p>No clubs found for this moderator.</p>
<?php endif; ?>