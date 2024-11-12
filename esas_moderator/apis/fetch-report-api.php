<?php
require_once "../../config.php";  // Database connection
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Determine the school year based on the user's selection
$schoolYear = $_POST['schoolYear'] ?? '';  // Fetch school year if provided

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];
    $club_id = $_POST['club_id'];  // Assuming the club_id of the current club is passed
    // Ensure school year is in the 'YYYY-YYYY' format
    if (!empty($schoolYear)) {
        list($startYear, $endYear) = explode('-', $schoolYear);
        $startDate = $startYear . '-08-01';  // School year starts in August
        $endDate = $endYear . '-07-31';     // School year ends in July
    }

    // Fetch data based on report type for a specific club
    switch ($reportType) {
        case 'club_students_list':
            // Fetch basic information of students in the club, filtering by dateDecided
            $query = "SELECT s.student_id AS 'Student ID', s.firstName AS 'First Name', 
                            s.middleName AS 'Middle Name', s.lastName AS 'Last Name', 
                            s.instiEmail AS 'Institutional Email', s.dateAdded AS 'Date Added' 
                      FROM tbl_students s 
                      JOIN tbl_application r ON s.student_id = r.student_id 
                      WHERE r.status = 'active' AND r.club_id = :club_id";
    
            // Apply school year filter using dateDecided
            if (!empty($schoolYear)) {
                $query .= " AND r.dateDecided BETWEEN :startDate AND :endDate";
            }
            break;

        case 'pending_approvals':
            // Fetch students with pending application approvals, filtering by dateApplied
            $query = "SELECT s.student_id AS 'Student ID', s.firstName AS 'First Name', 
                            s.middleName AS 'Middle Name', s.lastName AS 'Last Name', 
                            s.instiEmail AS 'Institutional Email' 
                    FROM tbl_students s 
                    JOIN tbl_application r ON s.student_id = r.student_id 
                    WHERE r.status = 'pending' AND r.club_id = :club_id";
            // Apply school year filter using dateApplied
            if (!empty($schoolYear)) {
                $query .= " AND r.dateApplied BETWEEN :startDate AND :endDate";
            }
            break;

        case 'disapproved_applications':
            // Fetch disapproved applications, filtering by dateDecided
            $query = "SELECT s.student_id AS 'Student ID', s.firstName AS 'First Name', 
                            s.middleName AS 'Middle Name', s.lastName AS 'Last Name', 
                            s.instiEmail AS 'Institutional Email' 
                    FROM tbl_students s 
                    JOIN tbl_application r ON s.student_id = r.student_id 
                    WHERE r.status = 'disapproved' AND r.club_id = :club_id";
            // Apply school year filter using dateDecided
            if (!empty($schoolYear)) {
                $query .= " AND r.dateDecided BETWEEN :startDate AND :endDate";
            }
            break;

        case 'pending_departure_requests':
            // Fetch pending departure requests, filtering by dateRequested
            $query = "SELECT s.student_id AS 'Student ID', s.firstName AS 'First Name', 
                            s.middleName AS 'Middle Name', s.lastName AS 'Last Name', 
                            s.instiEmail AS 'Institutional Email' 
                    FROM tbl_students s 
                    JOIN tbl_departure_requests d ON s.student_id = d.student_id 
                    WHERE d.status = 'pending' AND d.club_id = :club_id";
            // Apply school year filter using dateRequested
            if (!empty($schoolYear)) {
                $query .= " AND d.dateRequested BETWEEN :startDate AND :endDate";
            }
            break;

        case 'upcoming_events':
            // Fetch upcoming events for the current club, filtering by dateAdded
            $query = "SELECT title AS 'Event Title', 
                            CONCAT(DATE_FORMAT(timeStarts, '%h:%i %p'), ' - ', DATE_FORMAT(timeEnds, '%h:%i %p')) AS 'Event Time', 
                            location AS 'Event Location', 
                            registrationLink AS 'Link' 
                    FROM tbl_events 
                    WHERE club_id = :club_id AND date >= CURDATE()";

            // Apply school year filter using dateAdded
            if (!empty($schoolYear)) {
                $query .= " AND dateAdded BETWEEN :startDate AND :endDate";
            }

            $query .= " ORDER BY date ASC";
            break;


        default:
            echo '<div class="alert alert-danger"><p><em>Invalid report type selected.</em></p></div>';
            exit;
    }

    // Prepare and bind parameters
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':club_id', $club_id);

    // Bind school year parameters if it is provided
    if (!empty($schoolYear)) {
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
    }

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
                // Check if the column is for profilePic and display the image
                if ($key === 'profilePic') {
                    // Assuming images are stored in a specific directory for students
                    $imagePath = '/esas/esas_student/images/' . $value;
                    echo "<td><img src='$imagePath' alt='Profile Image' style='width: 35px; height: 35px; border-radius: 50%;'></td>";
                } 
                // Format date fields if applicable
                elseif (strpos($key, 'date') !== false) {
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
}
?>
