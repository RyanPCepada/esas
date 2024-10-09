<?php
require_once "../../config.php";  // Database connection
session_start();

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];
    $club_id = $_POST['club_id'];  // Assuming the club_id of the current club is passed
    $startDate = $_POST['startDate'] ?? null; // Fetch start date if provided
    $endDate = $_POST['endDate'] ?? null; // Fetch end date if provided

    // Fetch data based on report type for a specific club
    switch ($reportType) {
        case 'club_students_list':
            // Fetch basic information of students in the club
            $query = "SELECT s.student_id, s.firstName, s.middleName, s.lastName, s.instiEmail 
                      FROM tbl_students s 
                      JOIN tbl_registration r ON s.student_id = r.student_id 
                      WHERE r.status = 'active' AND r.club_id = :club_id";
            // Apply date filters if provided
            if ($startDate && $endDate) {
                $query .= " AND r.dateApplied BETWEEN :startDate AND :endDate";
            }
            break;

        case 'pending_approvals':
            // Fetch students with pending application approvals
            $query = "SELECT s.student_id, s.firstName, s.middleName, s.lastName, s.instiEmail 
                      FROM tbl_students s 
                      JOIN tbl_registration r ON s.student_id = r.student_id 
                      WHERE r.status = 'pending' AND r.club_id = :club_id";
            // Apply date filters if provided
            if ($startDate && $endDate) {
                $query .= " AND r.dateApplied BETWEEN :startDate AND :endDate";
            }
            break;

        case 'disapproved_applications':
            // Fetch disapproved applications
            $query = "SELECT s.student_id, s.firstName, s.middleName, s.lastName, s.instiEmail 
                      FROM tbl_students s 
                      JOIN tbl_registration r ON s.student_id = r.student_id 
                      WHERE r.status = 'disapproved' AND r.club_id = :club_id";
            // Apply date filters if provided
            if ($startDate && $endDate) {
                $query .= " AND r.dateApplied BETWEEN :startDate AND :endDate";
            }
            break;

        case 'pending_departure_requests':
            // Fetch pending departure requests
            $query = "SELECT s.student_id, s.firstName, s.middleName, s.lastName, s.instiEmail 
                      FROM tbl_students s 
                      JOIN tbl_departure_requests d ON s.student_id = d.student_id 
                      WHERE d.status = 'departed' AND d.club_id = :club_id";
            // Apply date filters if provided
            if ($startDate && $endDate) {
                $query .= " AND d.dateRequested BETWEEN :startDate AND :endDate";
            }
            break;

        case 'upcoming_events':
            // Fetch upcoming events for the current club
            $query = "SELECT title, date, time, location 
                      FROM tbl_events 
                      WHERE club_id = :club_id AND date >= CURDATE()";
            // Apply date filters if provided
            if ($startDate && $endDate) {
                $query .= " AND date BETWEEN :startDate AND :endDate";
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
    
    // Bind date parameters if they are provided
    if ($startDate && $endDate) {
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
