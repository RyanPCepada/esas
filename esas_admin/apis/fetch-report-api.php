<?php
require_once "../../config.php";  // Database connection
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Initialize query variable
    $query = "";

    // Fetch data based on report type
    switch ($reportType) {
        case 'all_clubs':
            $query = "SELECT clubName AS 'Club Name', dateAdded AS 'Date Added' FROM tbl_clubs";
            break;

        case 'all_moderators':
            $query = "SELECT moderator_id AS 'Moderator ID', CONCAT(firstName, ' ', lastName) AS 'Full Name', gender AS 'Gender', email AS 'Email', phoneNumber AS 'Phone Number', department AS 'Department', dateAdded AS 'Date Added' FROM tbl_moderators";
            break;

        case 'student_profiles':
            $query = "SELECT student_id AS 'Student ID', CONCAT(firstName, ' ', lastName) AS 'Full Name', gender AS 'Gender', instiEmail AS 'Institutional Email', phoneNumber AS 'Phone Number', department AS 'Department', year AS 'Year' FROM tbl_students";
            break;

        case 'moderators_and_clubs_overview':
            $query = "SELECT CONCAT(m.firstName, ' ', m.lastName) AS 'Moderator Full Name', c.clubName AS 'Club Name', m.email AS 'Email', cm.dateAdded AS 'Date Added' FROM tbl_clubs c JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id";
            break;

        case 'students_and_clubs_overview':
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS 'Student Full Name', c.clubName AS 'Club Name', s.instiEmail AS 'Institutional Email', s.year AS 'Year' FROM tbl_clubs c JOIN tbl_registration r ON c.club_id = r.club_id JOIN tbl_students s ON r.student_id = s.student_id WHERE status = 'active'";
            break;

        case 'student_club_requests':
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS 'Student Name', cr.clubName AS 'Club Name', cr.status AS 'Status', cr.dateRequested AS 'Date Requested', cr.dateDecided AS 'Date Decided', cr.status AS 'Status' FROM tbl_club_requests cr JOIN tbl_students s ON cr.student_id = s.student_id";
            break;

        case 'student_registration_status':
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS 'Student Name', c.clubName AS 'Club Name', r.status AS 'Status', r.dateApproved AS 'Date Approved' FROM tbl_registration r JOIN tbl_students s ON r.student_id = s.student_id JOIN tbl_clubs c ON r.club_id = c.club_id";
            break;

        default:
            echo 'Invalid report type selected.';
            exit;
    }

    // Add date filtering if startDate and endDate are provided
    if (!empty($startDate) && !empty($endDate)) {
        // Ensure the dates are in the correct format for SQL
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        switch ($reportType) {
            case 'all_clubs':
            case 'all_moderators':
            case 'student_profiles':
                // Assuming dateAdded is the field to filter for all clubs and moderators
                $query .= " WHERE dateAdded BETWEEN '$startDate' AND '$endDate'";
                break;
                
            case 'clubs_and_moderators_overview':
                $query .= " WHERE cm.dateAdded BETWEEN '$startDate' AND '$endDate'";
                break;

            case 'clubs_and_students_overview':
            case 'student_registration_status':
                // Assuming registration date is relevant for students
                $query .= " WHERE r.dateApproved BETWEEN '$startDate' AND '$endDate'";
                break;

            case 'club_activity_summary':
                $query .= " WHERE dateRequested BETWEEN '$startDate' AND '$endDate'";
                break;

            case 'student_club_requests':
                $query .= " WHERE cr.dateRequested BETWEEN '$startDate' AND '$endDate'";
                break;
        }
    }

    // Execute query and return results
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Dynamically generate table
    if (!empty($data)) {
        echo "<table class='table table-bordered'><thead><tr>";
        foreach (array_keys($data[0]) as $column) {
            echo "<th>" . ucfirst(str_replace('_', ' ', $column)) . "</th>";
        }
        echo "</tr></thead><tbody>";
        foreach ($data as $row) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                // Check if the column is for coverPhoto or profilePic and display the image
                if ($key === 'coverPhoto' || $key === 'profilePic') {
                    // Assuming images are stored in a specific directory
                    $imagePath = '/esas/esas_admin/images/' . $value;
                    echo "<td><img src='$imagePath' alt='Image' style='width: 100px; height: 57px; object-fit: cover;'></td>";
                } 
                // Check if the field is a date and format it as Month Day, Year
                elseif (strpos($key, 'date') !== false || strpos($key, 'Added') !== false || strpos($key, 'Requested') !== false || strpos($key, 'Approved') !== false || strpos($key, 'Decided') !== false) {
                    // Check if the date is '0000-00-00 00:00:00' or empty
                    if ($value === '0000-00-00 00:00:00' || empty($value)) {
                        echo "<td></td>";  // Display empty cell
                    } else {
                        // Format date as Month Day, Year
                        $formattedDate = date('F j, Y', strtotime($value));
                        echo "<td>$formattedDate</td>";
                    }
                } else {
                    // For other fields, just display the value
                    echo "<td>$value</td>";
                }
            }
            echo "</tr>";
        }        
        echo "</tbody></table>";
    } else {
        echo '<p class="text-danger"><em>No data found for the selected report.</em></p>';
    }
}
?>
