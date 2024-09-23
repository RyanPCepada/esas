<?php
// Start the session
session_start();

// Include the configuration file
require_once '../../../../config.php';

// Get the moderator ID from the URL or session
$moderator_id = $_GET['moderator_id'] ?? $_SESSION['moderator_id'];

// Fetch moderator information (name, etc.)
$moderatorName = '';
try {
    $stmt = $pdo->prepare("SELECT CONCAT(firstName, ' ', COALESCE(middleName, ''), ' ', lastName) AS fullName FROM tbl_moderators WHERE moderator_id = ?");
    $stmt->execute([$moderator_id]);
    $moderator = $stmt->fetch(PDO::FETCH_ASSOC);
    $moderatorName = htmlspecialchars($moderator['fullName']);
} catch (PDOException $e) {
    die("Error fetching moderator details: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Update</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="../../../assets/css/styles.css" rel="stylesheet" />
    <style>
        /* Use the same font and wrapper styles as previous pages */
        body {
            font-family: 'Arial', sans-serif; /* Replace 'Arial' with your preferred font */
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
        <p>Clubs Handled by <?php echo $moderatorName; ?></p>

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

                foreach ($clubs as $club) {
                    echo '
                    <div class="club-item">
                        <span>' . htmlspecialchars($club['clubName']) . '</span>
                        <form action="moderator_remove.php" method="POST">
                            <input type="hidden" name="club_id" value="' . htmlspecialchars($club['club_id']) . '">
                            <input type="hidden" name="moderator_id" value="' . htmlspecialchars($moderator_id) . '">
                            <button type="submit" class="remove-btn">Remove as Moderator</button>
                        </form>
                    </div>';
                }

            } catch (PDOException $e) {
                echo "Error fetching clubs: " . $e->getMessage();
            }
            ?>
        </div>

        <!-- Assign Moderator to Another Club -->
        <div class="assign-section">
            <h3>Assign to Another Club</h3>
            <form action="assign_moderator.php" method="POST">
                <input type="hidden" name="moderator_id" value="<?php echo htmlspecialchars($moderator_id); ?>">
                <label for="club">Select Club:</label>
                <select name="club_id" id="club" class="dropdown mt-0">
                    <option value="" disabled selected>-- Select from exisitng clubs --</option>
                    <?php
                    // Fetch all clubs that the moderator isn't already assigned to
                    try {
                        $stmt = $pdo->prepare("
                            SELECT c.club_id, c.clubName
                            FROM tbl_clubs c
                            WHERE c.club_id NOT IN (
                                SELECT club_id FROM tbl_clubs_and_moderators WHERE moderator_id = ?
                            )
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

                <div class="text-center d-flex justify-content-between mt-2">
                    <button type="submit" class="assign-btn">Assign to Club</button>
                    <a href="javascript:window.history.back();" class="btn btn-secondary">Back to Moderators List</a>
                </div>
                

            </form>
        </div>
    </div>
</body>
</html>
