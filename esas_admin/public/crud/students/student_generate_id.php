<?php
// Database connection
require_once "../../../../config.php";

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Get student_id from URL
$student_id = $_GET['student_id'];

// Fetch student details
$studentQuery = $pdo->prepare("SELECT * FROM tbl_students WHERE student_id = ?");
$studentQuery->execute([$student_id]);
$student = $studentQuery->fetch();

// Fetch active club registrations and concatenate moderator names for each club
$clubQuery = $pdo->prepare("
    SELECT cr.status, cl.clubName, cl.club_id, cl.coverPhoto, 
           GROUP_CONCAT(CONCAT(m.firstName, ' ', m.middleName, ' ', m.lastName) SEPARATOR ', ') AS moderatorNames,
           COUNT(m.moderator_id) AS moderatorCount
    FROM tbl_application cr
    INNER JOIN tbl_clubs cl ON cr.club_id = cl.club_id
    LEFT JOIN tbl_clubs_and_moderators cm ON cm.club_id = cl.club_id
    LEFT JOIN tbl_moderators m ON cm.moderator_id = m.moderator_id
    WHERE cr.student_id = ? AND cr.status = 'active'
    GROUP BY cl.club_id
");
$clubQuery->execute([$student_id]);
$clubs = $clubQuery->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSAS - Generate Student ID</title>
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
            margin-top: 50px;
        }

        /* Container for each pair of front and back cards */
        .id-card-pair {
            display: flex;
            flex-direction: row; /* Align front and back side-by-side */
            gap: 10px; /* Space between front and back */
        }

        .id-card-front, .id-card-back {
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

        @media print {
            /* Hide buttons when printing */
            .button-container {
                display: none;
            }

            /* Set body and container margins for print */
            body {
                margin: 0;
            }

            .id-container {
                margin-top: 0;
                display: block; /* Ensure it uses block layout in print */
                width: 100%;
            }

            /* Ensure background images and colors are printed */
            .id-card-front, .id-card-back {
                background-image: inherit;
                -webkit-print-color-adjust: exact; /* For Webkit browsers */
                print-color-adjust: exact; /* For other browsers */
            }

            /* Keep card pairs together on the same page */
            .id-card-pair {
                display: flex;
                flex-direction: row;
                page-break-inside: avoid; /* Avoid breaking card pairs across pages */
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
    <?php if (count($clubs) > 0): ?>
        <?php foreach ($clubs as $club): ?>
            
            <div class="id-card-pair">

                <!-- FRONT -->
                <div class="id-card-front" style="background-image: url('/esas/esas_admin/images/<?php echo htmlspecialchars($club['coverPhoto']); ?>');">
                    
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


                <!-- BACK -->
                <div class="id-card-back" style="background-image: url('/esas/esas_admin/images/<?php echo htmlspecialchars($club['coverPhoto']); ?>');">
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

                    <div class="student-details" style="position: relative; z-index: 2; display: flex; justify-content: center;">
                            <div style="text-align: start; color: rgba(255, 255, 255, 1);">
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <div style="display: flex; justify-content: flex-start;">
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 35%; margin: 0;">
                                            <strong>Gender:</strong>
                                        </h5>
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 65%; flex-grow: 1; margin: 0;">
                                            <?php echo htmlspecialchars($student['gender']); ?>
                                        </h5>
                                    </div>
                                    <div style="display: flex; justify-content: flex-start;">
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 35%; margin: 0;">
                                            <strong>Birthdate:</strong>
                                        </h5>
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 65%; flex-grow: 1; margin: 0;">
                                            <?php echo date("m/d/Y", strtotime($student['birthday'])); ?>
                                        </h5>
                                    </div>
                                    <div style="display: flex; justify-content: flex-start;">
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 35%; margin: 0;">
                                            <strong>Blood Type:</strong>
                                        </h5>
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 65%; flex-grow: 1; margin: 0;">
                                            <?php echo htmlspecialchars($student['bloodType']); ?>
                                        </h5>
                                    </div>
                                    <div style="display: flex; justify-content: flex-start;">
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 35%; margin: 0;">
                                            <strong>Contact #:</strong>
                                        </h5>
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 65%; flex-grow: 1; margin: 0;">
                                            <?php echo htmlspecialchars($student['phoneNumber']); ?>
                                        </h5>
                                    </div>
                                    <div style="display: flex; justify-content: flex-start;">
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 35%; margin: 0;">
                                            <strong>Address:</strong>
                                        </h5>
                                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5); width: 65%; flex-grow: 1; margin: 0;">
                                            <?php 
                                                echo htmlspecialchars($student['street']) . ', ' . 
                                                    htmlspecialchars($student['barangay']) . ', ' . 
                                                    htmlspecialchars($student['municipality']) . ', ' . 
                                                    htmlspecialchars($student['province']); 
                                            ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="guardian" style="position: relative; z-index: 2; text-align: center; margin-top: 5px;">
                        
                        <!-- Guardian Table -->
                        <table style="width: 100%; margin: 10px auto; border-collapse: collapse; font-size: 13px; z-index: 2;">
                            <thead>
                                <tr>
                                    <th colspan="4" style="border: 1px solid #fff; padding: 3px; color: white; background: rgba(0, 0, 0, 0.5);">INCASE OF EMERGENCY PLEASE NOTIFY</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" style="border: 1px solid #fff; padding: 5px; color: white; background: rgba(0, 0, 0, 0.3);">
                                        <strong>
                                            Mr. & Mrs. <?php echo htmlspecialchars($student['fatherFirstName']) . ' ' . htmlspecialchars($student['fatherLastName']); ?>
                                        </strong><br>Parent/Guardian
                                    </td>
                                    <td colspan="2" style="border: 1px solid #fff; padding: 5px; color: white; background: rgba(0, 0, 0, 0.3);">
                                        <strong><?php echo htmlspecialchars($student['guardianPhoneNumber']); ?></strong><br>Contact #
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>


                    <div class="validation" style="position: relative; z-index: 2; text-align: center; margin-top: 5px;">
                        
                        <!-- Validity Table -->
                        <table style="width: 100%; border-collapse: collapse; font-size: 13px; z-index: 2;">
                            <thead>
                                <tr>
                                    <th colspan="4" style="border: 1px solid #fff; padding: 3px; color: white; background: rgba(0, 0, 0, 0.5);">VALIDATION</th>
                                </tr>
                                <tr>
                                    <th style="border: 1px solid #fff; padding: 3px; color: white; background: rgba(0, 0, 0, 0.4);">A.Y.</th>
                                    <th style="border: 1px solid #fff; padding: 3px; color: white; background: rgba(0, 0, 0, 0.4);">1st Sem</th>
                                    <th style="border: 1px solid #fff; padding: 3px; color: white; background: rgba(0, 0, 0, 0.4);">2nd Sem</th>
                                    <th style="border: 1px solid #fff; padding: 3px; color: white; background: rgba(0, 0, 0, 0.4);">Summer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Use the date student was added to calculate starting academic year
                                $startYear = date("Y", strtotime($student['dateAdded'])); // Extract the year from dateAdded

                                // Loop to generate rows for five academic years
                                for ($i = 0; $i < 5; $i++) {
                                    $year1 = $startYear + $i;
                                    $year2 = $year1 + 1;
                                    $academicYear = $year1 . '-' . $year2; // Format like "2022-2023"
                                    echo "<tr style='text-align: center;'>
                                            <td style='border: 1px solid #fff; padding: 5px; color: #333333;'>$academicYear</td>
                                            <td style='border: 1px solid #fff; padding: 5px; color: #333333;'></td>
                                            <td style='border: 1px solid #fff; padding: 5px; color: #333333;'></td>
                                            <td style='border: 1px solid #fff; padding: 5px; color: #333333;'></td>
                                        </tr>";
                            
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>

                    
                    <div class="validation" style="position: relative; z-index: 2; text-align: center; margin-top: 5px;">
                        
                        <h5 style="color: white; text-shadow: 0 3px 5px rgba(0, 0, 0, .5);">
                            <p style="margin-bottom: 3px;">____________________</p>Student Signature
                        </h5>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
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
