<?php
require_once "../../config.php";  // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportType = $_POST['reportType'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Fetch data based on report type
    switch ($reportType) {
        case 'all_clubs':
            $query = "SELECT coverPhoto, clubName, dateAdded FROM tbl_clubs";
            break;

        case 'all_moderators':
            $query = "SELECT CONCAT(firstName, ' ', lastName) AS fullName, gender, email, phoneNumber, department, dateAdded FROM tbl_moderators";
            break;

        case 'student_profiles':
            $query = "SELECT student_id, CONCAT(firstName, ' ', lastName) AS fullName, gender, instiEmail, phoneNumber, department, year FROM tbl_students";
            break;

        case 'clubs_and_moderators_overview':
            $query = "SELECT c.clubName, CONCAT(m.firstName, ' ', m.lastName) AS moderatorName, m.email, cm.dateAdded FROM tbl_clubs c JOIN tbl_clubs_and_moderators cm ON c.club_id = cm.club_id JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id";
            break;

        case 'clubs_and_students_overview':
            $query = "SELECT c.clubName, CONCAT(s.firstName, ' ', s.lastName) AS studentName, s.instiEmail, s.year FROM tbl_clubs c JOIN tbl_registration r ON c.club_id = r.club_id JOIN tbl_students s ON r.student_id = s.student_id";
            break;

        case 'club_activity_summary':
            $query = "SELECT clubName, description, activities, dateRequested, status FROM tbl_club_requests";
            break;

        case 'student_club_requests':
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS studentName, cr.clubName, cr.description, cr.activities, cr.dateRequested, cr.status FROM tbl_club_requests cr JOIN tbl_students s ON cr.student_id = s.student_id";
            break;            

        case 'student_registration_status':
            $query = "SELECT CONCAT(s.firstName, ' ', s.lastName) AS studentName, c.clubName, r.status, r.dateApproved FROM tbl_registration r JOIN tbl_students s ON r.student_id = s.student_id JOIN tbl_clubs c ON r.club_id = c.club_id";
            break;

        default:
            echo 'Invalid report type selected.';
            exit;
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
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No data found for the selected report.</p>";
    }
}
?>
