<?php
require_once '../../config.php';
session_start();

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json');

// Ensure the moderator ID is set in the session
if (isset($_SESSION['moderator_id'])) {
    $moderator_id = $_SESSION['moderator_id'];
} else {
    echo json_encode(['error' => 'Moderator not logged in.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    // Get the club ID from the request
    $club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : 0; 

    if ($club_id === 0) {
        echo json_encode(['error' => 'Invalid club ID']);
        exit;
    }

    // Query to fetch active students and other moderators in the specified club
    $stmt = $pdo->prepare("
        SELECT 
            s.student_id, 
            s.firstName, 
            s.middleName, 
            s.lastName, 
            s.department,
            s.profilePic,
            MAX(c.message) AS message,  -- Get the latest message
            MAX(c.dateAdded) AS messageDate -- Get the date of the latest message
        FROM 
            tbl_students s 
        JOIN 
            tbl_registration r ON s.student_id = r.student_id 
        LEFT JOIN 
            tbl_chats c ON (s.student_id = c.sender_id OR s.student_id = c.recipient_id) 
            AND c.club_id = :club_id
            AND c.recipient_id = :moderator_id  -- Ensure chats where the moderator is the recipient
        WHERE 
            r.club_id = :club_id 
            AND r.status = 'active'
        GROUP BY 
            s.student_id 

        UNION 

        SELECT 
            m.moderator_id AS student_id, 
            m.firstName, 
            m.middleName, 
            m.lastName, 
            m.department,
            m.profilePic,
            MAX(c.message) AS message,  -- Get the latest message
            MAX(c.dateAdded) AS messageDate -- Get the date of the latest message
        FROM 
            tbl_moderators m
        JOIN 
            tbl_clubs_and_moderators cm ON m.moderator_id = cm.moderator_id 
        LEFT JOIN 
            tbl_chats c ON (m.moderator_id = c.sender_id OR m.moderator_id = c.recipient_id)
            AND c.club_id = :club_id
            AND c.recipient_id = :moderator_id -- Ensure chats where the current moderator is the recipient
        WHERE 
            cm.club_id = :club_id 
            AND m.moderator_id != :moderator_id -- Exclude the current moderator
        GROUP BY 
            m.moderator_id 
        ORDER BY 
            messageDate DESC  -- Order by the date of the latest message
    ");

    // Bind parameters and execute the query
    $stmt->execute([
        'club_id' => $club_id,
        'moderator_id' => $moderator_id
    ]);

    // Fetch all active students and other moderators
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any students or moderators are found
    if (!$students) {
        echo json_encode(['message' => 'No active students or moderators found for this club.']);
        exit;
    }

    // Format the messageDate for each student or moderator
    foreach ($students as &$student) {
        if ($student['messageDate']) {
            $dateTime = new DateTime($student['messageDate']);
            $now = new DateTime(); // Current date and time
            $yesterday = (clone $now)->modify('-1 day');

            // Check if the message date is today or yesterday
            if ($dateTime->format('Y-m-d') === $now->format('Y-m-d')) {
                $student['messageDate'] = 'Today ' . $dateTime->format('g:i A'); // Display 'Today' with time
            } elseif ($dateTime->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                $student['messageDate'] = 'Yesterday ' . $dateTime->format('g:i A'); // Display 'Yesterday' with time
            } else {
                // For other dates, keep the original formatting
                $student['messageDate'] = $dateTime->format('M j, Y g:i A'); // Change 'F' to 'M' for abbreviated month
            }
        }
    }

    // Return the students and moderators data as JSON
    echo json_encode($students);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
