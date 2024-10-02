<?php
// Database connection
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get student_id and club_id from URL parameters
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : null;
$club_id = isset($_GET['club_id']) ? intval($_GET['club_id']) : null;

// Ensure both student_id and club_id are present
if (!$student_id || !$club_id) {
    die("Invalid student ID or club ID.");
}

// Fetch student details
$studentQuery = $pdo->prepare("SELECT * FROM tbl_students WHERE student_id = ?");
$studentQuery->execute([$student_id]);
$student = $studentQuery->fetch();

// Check if student exists
if (!$student) {
    die("Student not found.");
}

// Fetch the club associated with the selected club_id and student
$clubQuery = $pdo->prepare("
    SELECT cr.status, cl.clubName, cl.club_id, cl.coverPhoto, 
           GROUP_CONCAT(CONCAT(m.firstName, ' ', m.middleName, ' ', m.lastName) SEPARATOR ', ') AS moderatorNames,
           COUNT(m.moderator_id) AS moderatorCount
    FROM tbl_registration cr
    INNER JOIN tbl_clubs cl ON cr.club_id = cl.club_id
    LEFT JOIN tbl_clubs_and_moderators cm ON cm.club_id = cl.club_id
    LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
    WHERE cr.student_id = ? AND cr.club_id = ? AND cr.status = 'active'
    GROUP BY cl.club_id
    LIMIT 1
");

// Execute with both student_id and club_id
$clubQuery->execute([$student_id, $club_id]);
$club = $clubQuery->fetch();

// Check if club is found
if (!$club) {
    die("Club not found or student not registered for this club.");
}

// Now proceed with generating the ID using the correct student and club data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Student ID</title>
    <style>
        /* Your existing styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .id-container {
            display: flex;
            justify-content: center; /* Center horizontally */
            flex-wrap: wrap; /* Allow items to wrap */
            align-items: center; /* Center vertically */
            min-height: 100vh; /* Set a minimum height for vertical centering */
        }
        .id-card {
            border-radius: 10px;
            width: 280px;
            height: 450px;
            margin: 40px;
            padding: 10px;
            position: relative;
            background-size: cover;
            background-position: center;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Space items out within each card */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            flex: 0 1 280px; /* Prevent card from growing too much, maintain width */
        }
        .school-header, .student-info {
            padding: 10px;
        }
        .school-header {
            padding: 5px 10px;
            font-size: 12px;
        }
        .school-header h4, .school-header h5 {
            margin: 2px 0;
        }

        #profpic {
            max-width: 100px;
            height: auto;
            border: solid 3px white;
            border-radius: 50%;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
        }
        .fullname {
            color: black;
            text-shadow: 
                1px 1px 0 rgba(255, 255, 255, 1),  
                -1px -1px 0 rgba(255, 255, 255, 1),  
                1px -1px 0 rgba(255, 255, 255, 1),  
                -1px 1px 0 rgba(255, 255, 255, 1),  
                0 3px 5px rgba(0, 0, 0, .5);
            font-size: 20px;
            margin-top: 0px !important;
        }
        
        .moderator-label {
            font-size: 14px;
            color: black;
            /* text-shadow: 
                -1px -1px 0 white,  
                1px -1px 0 white,
                -1px 1px 0 white,
                1px 1px 0 white; */
            margin-bottom: 10px;
        }
        .moderator-info {
            position: relative;
            z-index: 2;
            padding: 15px;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .moderator-name {
            margin: 0;
            line-height: 2;
            color: white;
            text-shadow: 0 3px 5px rgba(0, 0, 0, .5);
        }

        /* Style for button container */
        .button-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        button {
            padding: 10px 20px;
            margin: 0px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }

        /* Hide buttons when printing */
        @media print {
            .button-container {
                display: none;
            }
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>

<!-- Button container placed at the top-right corner -->
<div class="button-container">
    <button onclick="printPage()">Print</button>
    <button onclick="downloadPDF()">Download as PDF</button>
</div>

<div class="id-container" id="id-container">
    <?php if ($club): ?>
            <div class="id-card" style="background-image: url('/esas/esas_admin/images/<?php echo htmlspecialchars($club['coverPhoto']); ?>');">
                
                <!-- Cover photo at the back -->
                <img src="/esas/esas_admin/images/<?php echo htmlspecialchars($club['coverPhoto']); ?>" alt="Cover Photo" 
                    style="width: 100%; height: 100%; border-radius: 10px; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 0; 
                            opacity: 1; filter: brightness(0.5);">

                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
                            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, .95), rgba(255, 255, 255, 0.1)); 
                            border-radius: 10px; z-index: 1;"></div>

                <!-- ID Background in front of cover photo -->
                <img src="/esas/esas_admin/images/ID_BACKGROUND.png" alt="ID Background" 
                     style="width: 100%; height: 100%; border-radius: 10px; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;">

                <div class="school-details" style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; margin: 0px;">
                    <div style="margin-right: 5px;">
                        <img src="../../../../assets/img/nbsclogo.png" style="height: 0.4in; filter: drop-shadow(0px 3px 5px rgba(0, 0, 0, 0.5));">
                    </div>
                    <div style="text-align: center; color: rgba(255, 255, 255, 1);"> <!-- Center text here -->
                        <p style="font-size: 8px; margin: 0; text-shadow: 0 3px 5px rgba(0, 0, 0, .5);">REPUBLIC OF THE PHILIPPINES</p>
                        <p style="font-size: 10px; margin: 0; text-shadow: 0 3px 5px rgba(0, 0, 0, .5);"><strong>NORTHERN BUKIDNON STATE COLLEGE</strong></p>
                        <p style="font-size: 9px; margin: 0; text-shadow: 0 3px 5px rgba(0, 0, 0, .5);">Kihare, Manolo Fortich, Bukidnon</p>
                    </div>
                    <div style="margin-left: 5px;">
                        <img src="../../../../assets/img/SAS_LOGO.png" style="height: 0.4in; filter: drop-shadow(0px 3px 5px rgba(0, 0, 0, 0.5));">
                    </div>
                </div>

                <div class="student-info" style="position: relative; z-index: 2; text-align: center; padding-top: 0px; margin-top: 0px;">
                    <!-- Club Name -->
                    <h3 style="font-size: 21px; color: gold; line-height: 1; text-shadow: 0 3px 3px rgba(0, 0, 0, 1);">
                        <em><strong><?php echo htmlspecialchars($club['clubName']); ?></strong></em>
                    </h3>

                    <!-- Student Info -->
                    <img id="profpic" src="/esas/esas_student/images/<?php echo htmlspecialchars($student['profilePic']); ?>" alt="Profile Picture">

                    <h3 class="fullname"><?php echo htmlspecialchars($student['firstName'] . ' ' . $student['middleName'] . ' ' . $student['lastName']); ?></h3>

    

                    <div style="margin-top: 20px;">
                        <h5 style="color: black; text-shadow: 
                                    0 0 5px rgba(255, 255, 255, 0.7),  
                                    0 0 10px rgba(255, 255, 255, 0.5),  
                                    0 0 15px rgba(255, 255, 255, 0.3);
                                    margin: 3px 0;">  <!-- Adjusted margin -->
                            <strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?>
                        </h5>
                        <h5 style="color: black; text-shadow: 
                                    0 0 5px rgba(255, 255, 255, 0.7),  
                                    0 0 10px rgba(255, 255, 255, 0.5),  
                                    0 0 15px rgba(255, 255, 255, 0.3);
                                    margin: 3px 0;">  <!-- Adjusted margin -->
                            <strong>Email:</strong> <?php echo htmlspecialchars($student['instiEmail']); ?>
                        </h5>
                        <h5 style="color: black; text-shadow: 
                                    0 0 5px rgba(255, 255, 255, 0.7),  
                                    0 0 10px rgba(255, 255, 255, 0.5),  
                                    0 0 15px rgba(255, 255, 255, 0.3);
                                    margin: 3px 0;">  <!-- Adjusted margin -->
                            <strong>Contact #:</strong> <?php echo htmlspecialchars($student['phoneNumber']); ?>
                        </h5>
                    </div>

                </div>

                <div class="moderator-info">
                    <h5 class="moderator-label" style="margin-top: 0px;">
                        <strong><?php echo $club['moderatorCount'] > 1 ? 'Moderators:' : 'Moderator:'; ?></strong>
                    </h5>
                    <div>
                        <?php 
                        $moderators = explode(', ', $club['moderatorNames']);
                        foreach ($moderators as $moderator) {
                            echo '<h5 class="moderator-name">' . htmlspecialchars($moderator) . '</h5>';
                        }
                        ?>
                    </div>
                </div>


            </div>
    <?php else: ?>
        <p>The student is not an active member of any clubs.</p>
    <?php endif; ?>
</div>


<script>
    // Function to trigger print dialog
    function printPage() {
        window.print();
    }

    // Function to download as PDF using jsPDF
    function downloadPDF() {
        var doc = new jsPDF('p', 'pt', 'a4');
        var elementHTML = document.getElementById('id-container').innerHTML;
        var specialElementHandlers = {
            '#elementH': function (element, renderer) {
                return true;
            }
        };
        doc.fromHTML(elementHTML, 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });

        // Save the generated PDF
        doc.save('Student-ID.pdf');
    }
</script>

<!-- Add the jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</body>
</html>
