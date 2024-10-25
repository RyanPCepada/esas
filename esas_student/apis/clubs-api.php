<?php
require_once '../../config.php';

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Set the content type to JSON
header('Content-Type: application/json');

// Handle HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Check if an ID parameter is provided
        if (isset($_GET['club_id'])) {
            // Read operation (fetch a single club by club_id)
            $club_id = $_GET['club_id'];
            $stmt = $pdo->prepare('
                SELECT c.*, 
                       GROUP_CONCAT(m.firstName ORDER BY m.firstName SEPARATOR ", ") AS moderatorFirstName,
                       GROUP_CONCAT(m.profilePic ORDER BY m.firstName SEPARATOR ", ") AS moderatorProfilePic,
                       c.slots  -- Make sure to select the slots field here
                FROM tbl_clubs c
                LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
                WHERE c.club_id = ?
                GROUP BY c.club_id
            ');
            $stmt->execute([$club_id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                // Fetch count of students in this club (only active members)
                $stmt_count = $pdo->prepare('SELECT COUNT(*) as member_count FROM tbl_registration WHERE club_id = ? AND status = \'active\'');
                $stmt_count->execute([$club_id]);
                $member_count = $stmt_count->fetch(PDO::FETCH_ASSOC)['member_count'];

                // Append member count and calculate remaining slots
                foreach ($result as &$club) {
                    $club['membersCount'] = $member_count;

                    // Calculate remaining slots
                    if ($club['slots'] === null || $club['slots'] === 0) {
                        $club['slotsRemaining'] = null; // Treat as unlimited slots
                    } else {
                        $club['slotsRemaining'] = $club['slots'] - $member_count;
                        if ($club['slotsRemaining'] < 0) {
                            $club['slotsRemaining'] = 0; // Ensure it's not negative
                        }
                    }

                    // Format the moderators
                    $moderatorNames = explode(", ", $club['moderatorFirstName']);
                    $moderatorPics = explode(", ", $club['moderatorProfilePic']);
                    $club['formattedModerators'] = formatModerators($moderatorNames, $moderatorPics);
                }

                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Club not found']);
            }
        } else if (isset($_GET['department'])) {
            // Read operation (fetch clubs by department)
            $department = $_GET['department'];
            $stmt = $pdo->prepare('
                SELECT c.club_id, c.clubName, c.description, c.coverPhoto, c.dateAdded, c.dateModified,
                       GROUP_CONCAT(m.firstName ORDER BY m.firstName SEPARATOR ", ") AS moderators,
                       GROUP_CONCAT(m.profilePic ORDER BY m.firstName SEPARATOR ", ") AS profilePics,
                       c.slots  -- Make sure to select the slots field here
                FROM tbl_clubs c
                INNER JOIN tbl_club_recommendations cr ON c.club_id = cr.club_id
                LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
                WHERE cr.department = ?
                GROUP BY c.club_id
            ');
            $stmt->execute([$department]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch counts of students for the filtered clubs (only active members)
            foreach ($result as &$club) {
                $stmt_count = $pdo->prepare('SELECT COUNT(*) as member_count FROM tbl_registration WHERE club_id = ? AND status = \'active\'');
                $stmt_count->execute([$club['club_id']]);
                $club['membersCount'] = $stmt_count->fetch(PDO::FETCH_ASSOC)['member_count'];

                // Calculate remaining slots
                if ($club['slots'] === null || $club['slots'] === 0) {
                    $club['slotsRemaining'] = null; // Treat as unlimited slots
                } else {
                    $club['slotsRemaining'] = $club['slots'] - $club['membersCount'];
                    if ($club['slotsRemaining'] < 0) {
                        $club['slotsRemaining'] = 0; // Ensure it's not negative
                    }
                }

                // Format the moderators
                $moderatorNames = explode(", ", $club['moderators']);
                $moderatorPics = explode(", ", $club['profilePics']);
                $club['formattedModerators'] = formatModerators($moderatorNames, $moderatorPics);
            }

            echo json_encode($result);
        } else {
            // Read operation (fetch all clubs)
            $stmt = $pdo->query('
                SELECT c.club_id, c.clubName, c.description, c.coverPhoto, c.dateAdded, c.dateModified,
                       GROUP_CONCAT(m.firstName ORDER BY m.firstName SEPARATOR ", ") AS moderators,
                       GROUP_CONCAT(m.profilePic ORDER BY m.firstName SEPARATOR ", ") AS profilePics,
                       c.slots  -- Make sure to select the slots field here
                FROM tbl_clubs c
                LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
                GROUP BY c.club_id
            ');
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch counts of students for all clubs (only active members)
            foreach ($result as &$club) {
                $stmt_count = $pdo->prepare('SELECT COUNT(DISTINCT student_id) as member_count FROM tbl_registration WHERE club_id = ? AND status = \'active\'');
                $stmt_count->execute([$club['club_id']]);
                $club['membersCount'] = $stmt_count->fetch(PDO::FETCH_ASSOC)['member_count'];

                // Calculate remaining slots
                if ($club['slots'] === null || $club['slots'] === 0) {
                    $club['slotsRemaining'] = null; // Treat as unlimited slots
                } else {
                    $club['slotsRemaining'] = $club['slots'] - $club['membersCount'];
                    if ($club['slotsRemaining'] < 0) {
                        $club['slotsRemaining'] = 0; // Ensure it's not negative
                    }
                }

                // Format the moderators
                $moderatorNames = explode(", ", $club['moderators']);
                $moderatorPics = explode(", ", $club['profilePics']);
                $club['formattedModerators'] = formatModerators($moderatorNames, $moderatorPics);
            }

            echo json_encode($result);
        }
        break;
        
    default:
        // Invalid method
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

// Function to format moderators
function formatModerators($moderatorNames, $moderatorPics) {
    $count = count($moderatorNames);
    $formattedNames = '';
    if ($count === 0) {
        $formattedNames = 'No moderators';
    } elseif ($count === 1) {
        $formattedNames = $moderatorNames[0];
    } elseif ($count === 2) {
        $formattedNames = implode(' & ', $moderatorNames);
    } else {
        $last = array_pop($moderatorNames);
        $formattedNames = implode(', ', $moderatorNames) . ' & ' . $last;
    }

    // Add profile pictures
    $picsHTML = '';
    foreach ($moderatorPics as $pic) {
        if ($pic) {
            $picsHTML .= "<img src='/esas/esas_moderator/images/$pic' alt='Profile Pic' class='moderator-pic'>";
        }
    }

    return "<div class='moderator-pics'>$picsHTML</div><div class='moderator-names'>$formattedNames</div>";
}
?>
