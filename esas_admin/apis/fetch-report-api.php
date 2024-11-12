<?php
require_once "../../config.php";  // Database connection
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Ensure POST request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the school year from POST data (if provided)
    $schoolYear = $_POST['schoolYear'] ?? '';
    $reportType = $_POST['reportType'] ?? '';  // Get the report type
    
    // Check if the report type is provided
    if (empty($reportType)) {
        echo 'No report type selected.';
        exit;
    }

    // Determine the start and end dates for the school year, if provided
    if (!empty($schoolYear) && $schoolYear !== 'all') {
        list($startYear, $endYear) = explode('-', $schoolYear);
        $startDate = $startYear . '-08-01';  // School year starts in August
        $endDate = $endYear . '-07-31';     // School year ends in July
    }

    // Default query
    $query = '';
    switch ($reportType) {
        case 'all_clubs':
            $query = "SELECT clubName AS 'Club Name', dateAdded AS 'Date Added' FROM tbl_clubs";
            break;

        case 'all_moderators':
            $query = "SELECT moderator_id AS 'Moderator ID', CONCAT(firstName, ' ', lastName) AS 'Full Name', gender AS 'Gender', email AS 'Email', phoneNumber AS 'Phone Number', department AS 'Department', dateAdded AS 'Date Added' FROM tbl_moderators";
            break;

        case 'student_profiles':
            $query = "SELECT student_id AS 'Student ID', CONCAT(firstName, ' ', lastName) AS 'Full Name', gender AS 'Gender', instiEmail AS 'Institutional Email', phoneNumber AS 'Phone Number', department AS 'Department', year AS 'Year' FROM tbl_students";
            if ($schoolYear !== 'all' && !empty($schoolYear)) {
                $query .= " WHERE year = :schoolYear";
            }
            break;

        case 'moderators_and_clubs_overview':
            $query = "SELECT CONCAT(m.firstName, ' ', m.lastName) AS 'Moderator Full Name', c.clubName AS 'Club Name', m.email AS 'Email', cm.dateAdded AS 'Date Added' FROM tbl_clubs c JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id";
            break;

        case 'students_and_clubs_overview':
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS 'Student Full Name', c.clubName AS 'Club Name', s.instiEmail AS 'Institutional Email', s.year AS 'Year' FROM tbl_clubs c JOIN tbl_application r ON c.club_id = r.club_id JOIN tbl_students s ON r.student_id = s.student_id WHERE status = 'active'";
            if ($schoolYear !== 'all' && !empty($schoolYear)) {
                $query .= " AND s.year = :schoolYear"; // Filter by school year
            }
            break;

        case 'student_club_requests':
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS 'Student Name', cr.clubName AS 'Club Name', cr.status AS 'Status', cr.dateRequested AS 'Date Requested', cr.dateDecided AS 'Date Decided' FROM tbl_club_requests cr JOIN tbl_students s ON cr.student_id = s.student_id";
            break;

        case 'student_application_status':
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS 'Student Name', c.clubName AS 'Club Name', r.status AS 'Status', r.dateDecided AS 'Date Approved' FROM tbl_application r JOIN tbl_students s ON r.student_id = s.student_id JOIN tbl_clubs c ON r.club_id = c.club_id";
            break;

        default:
            echo 'Invalid report type selected.';
            exit;
    }

    // Prepare the query
    try {
        $stmt = $pdo->prepare($query);

        // Bind the school year parameter if needed
        if ($schoolYear !== 'all' && !empty($schoolYear)) {
            $stmt->bindParam(':schoolYear', $schoolYear, PDO::PARAM_STR);
        }

        // Execute the query
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Dynamically generate the table for displaying data
        if (!empty($data)) {
            echo "<table class='table table-bordered'><thead><tr>";
            foreach (array_keys($data[0]) as $column) {
                echo "<th>" . ucfirst(str_replace('_', ' ', $column)) . "</th>";
            }
            echo "</tr></thead><tbody>";
            foreach ($data as $row) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    // Format date fields if applicable
                    if (strpos($key, 'date') !== false && !empty($value)) {
                        $formattedDate = date('F j, Y', strtotime($value));
                        echo "<td>$formattedDate</td>";
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
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
