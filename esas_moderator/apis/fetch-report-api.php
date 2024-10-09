<?php
require_once "../../config.php";  // Database connection

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Fetch data based on report type for moderators
    switch ($reportType) {
        case 'moderator_clubs_summary':
            $query = "SELECT clubName, dateAdded FROM tbl_clubs WHERE moderator_id = :moderator_id";
            break;

        case 'club_student_counts':
            $query = "SELECT c.clubName, COUNT(r.student_id) AS studentCount FROM tbl_clubs c 
                      JOIN tbl_registration r ON c.club_id = r.club_id 
                      WHERE c.moderator_id = :moderator_id GROUP BY c.club_id";
            break;

        case 'pending_approvals':
            $query = "SELECT s.firstName, s.lastName, s.instiEmail, r.dateAdded FROM tbl_students s 
                      JOIN tbl_registration r ON s.student_id = r.student_id 
                      JOIN tbl_clubs c ON r.club_id = c.club_id 
                      WHERE r.status = 'Pending' AND c.moderator_id = :moderator_id";
            break;

        case 'approved_disapproved_requests':
            $query = "SELECT s.firstName, s.lastName, s.instiEmail, r.status, r.dateApproved FROM tbl_students s 
                      JOIN tbl_registration r ON s.student_id = r.student_id 
                      JOIN tbl_clubs c ON r.club_id = c.club_id 
                      WHERE r.status IN ('Approved', 'Disapproved') AND c.moderator_id = :moderator_id";
            break;

        case 'moderator_events':
            $query = "SELECT eventName, eventDate FROM tbl_events WHERE moderator_id = :moderator_id";
            break;

        case 'pending_departure_requests':
            $query = "SELECT s.firstName, s.lastName, s.instiEmail, d.dateRequested FROM tbl_students s 
                      JOIN tbl_departure_requests d ON s.student_id = d.student_id 
                      JOIN tbl_clubs c ON d.club_id = c.club_id 
                      WHERE d.status = 'Pending' AND c.moderator_id = :moderator_id";
            break;

        case 'club_notifications':
            $query = "SELECT notificationContent, dateSent FROM tbl_notifications WHERE club_id IN 
                      (SELECT club_id FROM tbl_clubs WHERE moderator_id = :moderator_id)";
            break;

        default:
            echo 'Invalid report type selected.';
            exit;
    }

    // Bind moderator_id dynamically (assuming it's stored in the session)
    $moderator_id = $_SESSION['moderator_id'];  // Modify based on your authentication method
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':moderator_id', $moderator_id);
    
    // Execute the query
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Dynamically generate the table
    if (!empty($data)) {
        echo "<table class='table table-bordered'><thead><tr>";
        foreach (array_keys($data[0]) as $column) {
            echo "<th>" . ucfirst(str_replace('_', ' ', $column)) . "</th>";
        }
        echo "</tr></thead><tbody>";
        foreach ($data as $row) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                // Format date fields
                if (strpos($key, 'date') !== false || strpos($key, 'Added') !== false || strpos($key, 'Approved') !== false) {
                    $formattedDate = date('F j, Y', strtotime($value));
                    echo "<td>$formattedDate</td>";
                } else {
                    echo "<td>$value</td>";
                }
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No data found for the selected report.</p>";
    }
}
?>
