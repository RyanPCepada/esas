<?php
require_once "../../config.php";  // Database connection
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Determine the school year based on the user's selection
$schoolYear = $_POST['schoolYear'] ?? '';  // Fetch school year if provided

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];
    // Ensure school year is in the 'YYYY-YYYY' format
    if (!empty($schoolYear)) {
        list($startYear, $endYear) = explode('-', $schoolYear);
        $startDate = $startYear . '-08-01';  // School year starts in August
        $endDate = $endYear . '-07-31';     // School year ends in July
    }

    switch ($reportType) {
        case 'all_clubs':
            // Fetch all clubs' names and date added
            $query = "SELECT clubName AS 'Club Name', 
                             DATE_FORMAT(dateAdded, '%m-%d-%Y') AS 'Date Added' 
                      FROM tbl_clubs";
            
            // Apply school year filter using dateAdded
            if (!empty($schoolYear)) {
                $query .= " AND dateAdded BETWEEN :startDate AND :endDate";
            }
            break;
    
        case 'all_moderators':
            // Fetch all moderators' basic information
            $query = "SELECT moderator_id AS 'Moderator ID', 
                             CONCAT(firstName, ' ', lastName) AS 'Full Name', 
                             gender AS 'Gender', 
                             email AS 'Email', 
                             phoneNumber AS 'Phone Number', 
                             department AS 'Department', 
                             DATE_FORMAT(dateAdded, '%m-%d-%Y') AS 'Date Added' 
                      FROM tbl_moderators";
            
            // Apply school year filter using dateAdded
            if (!empty($schoolYear)) {
                $query .= " AND dateAdded BETWEEN :startDate AND :endDate";
            }
            break;
    
        case 'student_profiles':
            // Fetch student profiles' information
            $query = "SELECT student_id AS 'Student ID', 
                             CONCAT(firstName, ' ', lastName) AS 'Full Name', 
                             gender AS 'Gender', 
                             instiEmail AS 'Institutional Email', 
                             phoneNumber AS 'Phone Number', 
                             department AS 'Department', 
                             year AS 'Year' 
                      FROM tbl_students";
            
            // Apply school year filter using dateAdded
            if (!empty($schoolYear)) {
                $query .= " AND dateAdded BETWEEN :startDate AND :endDate";
            }
            break;
    
        case 'moderators_and_clubs_overview':
            // Fetch moderator and club overview
            $query = "SELECT CONCAT(m.firstName, ' ', m.lastName) AS 'Moderator Full Name', 
                             c.clubName AS 'Club Name', 
                             m.email AS 'Email', 
                             DATE_FORMAT(cm.dateAdded, '%m-%d-%Y') AS 'Date Added' 
                      FROM tbl_clubs c 
                      JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id 
                      JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id";
            
            // Apply school year filter using dateAdded
            if (!empty($schoolYear)) {
                $query .= " AND cm.dateAdded BETWEEN :startDate AND :endDate";
            }
            break;
    
        case 'students_and_clubs_overview':
            // Fetch student and club overview
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS 'Student Full Name', 
                             c.clubName AS 'Club Name', 
                             s.instiEmail AS 'Institutional Email', 
                             s.year AS 'Year' 
                      FROM tbl_clubs c 
                      JOIN tbl_application r ON c.club_id = r.club_id 
                      JOIN tbl_students s ON r.student_id = s.student_id 
                      WHERE r.status = 'active'";
            
            // Apply school year filter using dateAdded
            if (!empty($schoolYear)) {
                $query .= " AND r.dateAdded BETWEEN :startDate AND :endDate";
            }
            break;
    
        case 'student_club_requests':
            // Fetch student club requests
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS 'Student Name', 
                             cr.clubName AS 'Club Name', 
                             cr.status AS 'Status', 
                             DATE_FORMAT(cr.dateRequested, '%m-%d-%Y') AS 'Date Requested', 
                             DATE_FORMAT(cr.dateDecided, '%m-%d-%Y') AS 'Date Decided' 
                      FROM tbl_club_requests cr 
                      JOIN tbl_students s ON cr.student_id = s.student_id";
            
            // Apply school year filter using dateRequested
            if (!empty($schoolYear)) {
                $query .= " AND cr.dateRequested BETWEEN :startDate AND :endDate";
            }
            break;
    
        case 'student_application_status':
            // Fetch student application status
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS 'Student Name', 
                             c.clubName AS 'Club Name', 
                             r.status AS 'Status', 
                             DATE_FORMAT(r.dateDecided, '%m-%d-%Y') AS 'Date Approved' 
                      FROM tbl_application r 
                      JOIN tbl_students s ON r.student_id = s.student_id 
                      JOIN tbl_clubs c ON r.club_id = c.club_id";
            
            // Apply school year filter using dateDecided
            if (!empty($schoolYear)) {
                $query .= " AND r.dateDecided BETWEEN :startDate AND :endDate";
            }
            break;
    
        default:
            echo '<div class="alert alert-danger"><p><em>Invalid report type selected.</em></p></div>';
            exit;
    }
    
    

    // Prepare and bind parameters
    $stmt = $pdo->prepare($query);

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
