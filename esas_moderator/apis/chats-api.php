<?php
require_once '../../config.php';
session_start();

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    // Get the club ID from the request
    $club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : 0; 

    if ($club_id === 0) {
        echo json_encode(['error' => 'Invalid club ID']);
        exit;
    }

    // Query to fetch active students in the specified club along with their latest message
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
            tbl_chats c ON s.student_id = c.student_id AND c.club_id = :club_id 
        WHERE 
            r.club_id = :club_id 
            AND r.status = 'active'
        GROUP BY 
            s.student_id 
        ORDER BY 
            messageDate DESC  -- Order by the date of the latest message
    ");

    // Bind parameters and execute the query
    $stmt->execute(['club_id' => $club_id]);

    // Fetch all active students
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any students are found
    if (!$students) {
        echo json_encode(['message' => 'No active students found for this club.']);
        exit;
    }

    // Format the messageDate for each student
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

    // Return the students data as JSON
    echo json_encode($students);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
