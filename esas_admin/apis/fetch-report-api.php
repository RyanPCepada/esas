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
            // Fetch all clubs' names, date added, count of moderators, and count of members
            $query = "SELECT c.clubName AS 'Club Name', 
                             COUNT(DISTINCT cm.moderator_id) AS 'Number of Moderators',
                             COUNT(DISTINCT a.student_id) AS 'Number of Students',
                             DATE_FORMAT(c.dateAdded, '%m-%d-%Y') AS 'Date Added' 
                      FROM tbl_clubs c
                      LEFT JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id
                      LEFT JOIN tbl_application a ON c.club_id = a.club_id AND a.status = 'active'";
            
            // Apply school year filter using dateAdded, if applicable
            if (!empty($schoolYear)) {
                $query .= " WHERE c.dateAdded BETWEEN :startDate AND :endDate";
            }
        
            // Group the results by club ID
            $query .= " GROUP BY c.club_id";
            break;
        
    
            case 'all_moderators':
                // Fetch all moderators' basic information without concatenating full name
                $query = "SELECT moderator_id AS 'Moderator ID', 
                                 CONCAT(firstName, ' ', lastName) AS 'Full Name', 
                                 gender AS 'Gender', 
                                 email AS 'Email', 
                                 phoneNumber AS 'Phone Number', 
                                 department AS 'Department', 
                                 DATE_FORMAT(dateAdded, '%m-%d-%Y') AS 'Date Added' 
                          FROM tbl_moderators";
                
                // Apply school year filter using dateAdded, if applicable
                if (!empty($schoolYear)) {
                    $query .= " WHERE dateAdded BETWEEN :startDate AND :endDate";
                }
                break;
            
    
        case 'student_profiles':
            // Fetch student profiles from tbl_application with active status and club names
            $query = "SELECT DISTINCT s.student_id AS 'Student ID', 
                                    CONCAT(s.firstName, ' ', s.lastName) AS 'Full Name', 
                                    s.gender AS 'Gender', 
                                    s.instiEmail AS 'Institutional Email', 
                                    s.phoneNumber AS 'Phone Number', 
                                    s.year AS 'Year', 
                                    s.department AS 'Department', 
                                    s.course AS 'Course',
                                    GROUP_CONCAT(c.clubName ORDER BY c.clubName SEPARATOR ', ') AS 'Clubs'
                    FROM tbl_students s
                    INNER JOIN tbl_application a ON s.student_id = a.student_id
                    INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                    WHERE a.status = 'active'";

            // Apply school year filter using dateApplied from tbl_application, if applicable
            if (!empty($schoolYear)) {
                $query .= " AND a.dateApplied BETWEEN :startDate AND :endDate";
            }
            
            // Group by student_id to ensure each student is listed once with their clubs
            $query .= " GROUP BY s.student_id";

            break;

                
                
    
        case 'moderators_and_clubs_overview':
            // Fetch moderator and club overview
            $query = "SELECT m.moderator_id AS 'Moderator ID', 
                            CONCAT(m.firstName, ' ', m.lastName) AS 'Moderator Full Name', 
                            GROUP_CONCAT(c.clubName ORDER BY c.clubName SEPARATOR ', ') AS 'Club Name(s)', 
                            m.email AS 'Email', 
                            DATE_FORMAT(cm.dateAdded, '%m-%d-%Y') AS 'Date Assigned' 
                    FROM tbl_clubs c 
                    JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id 
                    JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id";
            
            // Apply school year filter using dateAdded from tbl_clubs_and_moderators, if applicable
            if (!empty($schoolYear)) {
                $query .= " WHERE cm.dateAdded BETWEEN :startDate AND :endDate";
            }
            
            // Group by moderator_id to show one row per moderator
            $query .= " GROUP BY m.moderator_id";

            break;

    
        case 'students_and_clubs_overview':
            // Fetch student and club overview
            $query = "SELECT s.student_id AS 'Student ID', 
                            CONCAT(s.firstName, ' ', s.lastName) AS 'Full Name', 
                            GROUP_CONCAT(c.clubName ORDER BY c.clubName SEPARATOR ', ') AS 'Clubs', 
                            s.instiEmail AS 'Institutional Email', 
                            s.year AS 'Year', 
                            s.department AS 'Department', 
                            s.course AS 'Course' 
                    FROM tbl_clubs c 
                    JOIN tbl_application a ON c.club_id = a.club_id 
                    JOIN tbl_students s ON a.student_id = s.student_id 
                    WHERE a.status = 'active'";
            
            // Apply school year filter using dateAdded from tbl_application, if applicable
            if (!empty($schoolYear)) {
                $query .= " AND a.dateDecided BETWEEN :startDate AND :endDate";
            }
            
            // Group by student_id to show one row per student
            $query .= " GROUP BY s.student_id";

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
            
            // Apply school year filter using dateRequested, if applicable
            if (!empty($schoolYear)) {
                $query .= " WHERE cr.dateRequested BETWEEN :startDate AND :endDate";
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
            
            // Apply school year filter using dateDecided, if applicable
            if (!empty($schoolYear)) {
                $query .= " WHERE r.dateDecided BETWEEN :startDate AND :endDate";
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
