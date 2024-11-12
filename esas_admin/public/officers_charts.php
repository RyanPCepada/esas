<?php 
session_start();
require_once "../../config.php";  // Assuming this file holds your PDO connection

// Set the default timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['admin_id'])) {
    echo "Admin ID is not set in the session.";
    exit;
}

$admin_id = $_SESSION['admin_id']; // Get admin ID from session

try {
    // Use the existing PDO instance from config.php
    global $pdo;

    // Fetch admin email (already done in your code)
    $sql = "SELECT email FROM tbl_admin WHERE admin_id = :admin_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $result ? strtoupper($result['email']) : "UNKNOWN";
    
    // Fetch the latest officer charts for each organizationType and department
    $sql_charts = "
        SELECT chart_id, chart, organizationType, department 
        FROM tbl_officers_charts t
        WHERE dateAdded = (
            SELECT MAX(dateAdded) 
            FROM tbl_officers_charts 
            WHERE organizationType = t.organizationType AND (department = t.department OR department IS NULL)
        )
        ORDER BY organizationType, department";
    $stmt_charts = $pdo->prepare($sql_charts);
    $stmt_charts->execute();
    $officer_charts = $stmt_charts->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handle database connection or query error
    die("Database error: " . $e->getMessage());
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>ESAS - CSG & SBO OFFICERS</title>
    <link href="../../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.6.0.js"></script>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/img/NBSC_LOGO.png" rel="icon">
    <style>
        .nav-link.active {
          color: white !important;
          background-color: black;
        }
        .left-sidebar {
            font-size: 16px;
            text-align: start;
        }
        .mainbar h2 {
            margin-left: 15px;
            margin-bottom: 32px;
        }
        .card-body {
            width: 100%;
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 50px;
        }
        

        .chart-entry {
    border: 2px solid #ccc; /* Optional: Add a border */
    border-radius: 8px; /* Optional: Rounded corners */
    padding: 10px; /* Spacing inside the border */
    margin: 20px 0; /* Space between chart entries */
    text-align: center; /* Center the content */
    background-color: #f9f9f9; /* Light background for contrast */
    display: block; /* Ensure each chart entry takes the full width */
}

.chart-entry img {
    display: block; /* Change to block for proper centering */
    width: 70%; /* Set image width to 70% of its container */
    max-width: 600px; /* Set a maximum width */
    height: auto; /* Maintain aspect ratio */
    margin: 0 auto; /* Center the image */
}

.chart-entry h5 {
    font-size: 18px;
    margin-bottom: 10px;
}

.change-button {
    background-color: #007bff; /* Button background color */
    color: #fff; /* Button text color */
    margin-top: 10px;
    border: none; /* Remove border */
    border-radius: 4px; /* Rounded corners */
    padding: 5px 15px; /* Add some padding */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Change cursor on hover */
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

.change-button:hover {
    background-color: #0056b3; /* Darker blue on hover */
    /* Remove the underline effect */
}

/* Remove unused styles */
.card-body {
    padding: 20px;
}



.modal-full-height {
    height: 100vh; /* Set height to full viewport height */
}

.modal-content {
    max-height: 90vh; /* Set a max height for the modal content */
}

.modal-body {
    padding: 15px; /* Add some padding */
}



/* Media query for responsive design */
@media (max-width: 768px) {
    .chart-entry img {
        width: 100%; /* Ensure images are responsive */
    }
}


    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row g-0 h-100">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
                <a class="navbar-brand ps-2" href="#">
                    <img src="../../assets/img/SAS_LOGO.png" style="height: 0.3in;">
                    ESAS - Admin</a>
                </button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="true">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse hide" id="main_nav">
                    <div class="navbar-collapse flex-grow-1 text-right" id="sampleid" style="padding-left: 20px">
                        <?php include '../nav/nav_main.php' ?>
                    </div>
                </div>
            </nav>
            <!-- LEFT SIDEBAR -->
            <div class="col-12 col-md-2 pt-3 border-end">

            <div class="d-flex flex-column flex-shrink-0 px-2 bg-body-tertiary">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li>
                        <a href="../../esas_admin/public/dashboard.php" class="nav-link left-sidebar text-dark" id="dashboard">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/all_clubs.php" class="nav-link left-sidebar text-dark" id="all-clubs">
                            <i class="fas fa-university"></i> All Clubs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/moderators.php" class="nav-link left-sidebar text-dark" id="moderators">
                            <i class="fa fa-user-shield"></i> Moderators
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../esas_admin/public/students.php" class="nav-link left-sidebar text-dark" id="students">
                            <i class="fas fa-users"></i> Students
                        </a>
                    </li>
                    <li>
                        <a href="../../esas_admin/public/club_requests.php" class="nav-link left-sidebar text-dark" id="club-requests">
                            <i class="fas fa-envelope"></i> Club Requests
                        </a>
                    </li>
                    <li>
                        <a href="../../esas_admin/public/reports.php" class="nav-link left-sidebar text-dark" id="reports">
                            <i class="fas fa-file-alt"></i> Reports
                        </a>
                    </li>
                    <br>
                    Others
                    <li>
                        <a href="../../esas_admin/public/officers_charts.php" class="nav-link left-sidebar text-dark active" aria-current="page" id="officers_charts">
                            <i class="fas fa-user-tie"></i> CSG & SBO Officers
                        </a>
                    </li>
                    <li>
                        <a href="../../esas_admin/public/accomplishment_reports.php" class="nav-link left-sidebar text-dark" id="accomplishment_reports" 
                            style="display: flex; gap: 7px; align-items: flex-start;">
                        
                            <span class="icon-column" style="flex-shrink: 0;">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            
                            <span class="text-column" style="line-height: 1.2;">
                                Accomplishment Reports
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            </div>
            <!-- LEFT SIDEBAR END -->


            
            
            <!-- MAINPAGE BAR -->  
            <div class="col-12 col-md-10 bg-lgrey auto-scroll">
                <div class="row g-0 h-100">
                    <div class="row g-0 p-4 px-2 pt-2 h-100">
                        
                        <div class="card p-2">
                            <div class="row card-row1 col-md-12 mb-1" style="border: 1px solid transparent; margin: 0;">


                                <div class="mt-1 mb-3 d-flex justify-content-between align-items-center">
                                    <!-- Officers Charts -->
                                    <h4 class="text-muted mb-3">Officers Charts</h4> <!-- This remains left-aligned -->
                                </div>

                                <div class="mt-1 mb-3 d-flex justify-content-center align-items-center">


                                    <div class="">
                                    <?php 
                                    // Loop through charts and display the latest for each organizationType and department
                                    foreach ($officer_charts as $chart) {
                                        echo "<div class='chart-entry'>";
                                        
                                        // Move the dash "-" before the department name
                                        echo "<h5>" . htmlspecialchars($chart['organizationType']) . " Officers" . 
                                            (!empty($chart['department']) ? " - " . htmlspecialchars($chart['department']) : "") . "</h5>";
                                        
                                        // Display the chart image
                                        $imageUrl = '/esas/esas_admin/images/' . htmlspecialchars($chart['chart']);
                                        echo "<img src='$imageUrl' 
                                            alt='" . htmlspecialchars($chart['organizationType']) . " Chart' class='chart-img' />";

                                        // Change the Edit link to a Change button
                                        echo "<button type='button' class='change-button' onclick='openModal(" . htmlspecialchars($chart['chart_id']) . ", \"$imageUrl\", \"" . htmlspecialchars($chart['organizationType']) . "\", \"" . htmlspecialchars($chart['department']) . "\")'>Change</button>"; // Updated button
                                        echo "</div>";
                                    }

                                    ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MAINPAGE BAR END -->


        </div>
    </div>
</div>



    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/global_script.js"></script>



<!-- Modal -->
<div id="changeModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" style="display: flex; flex-direction: column;">
                <h4 class="text-muted mb-3" id="modalHeader">Update Chart</h4>
                <div class="text-center mb-3">
                    <h5 id="organizationInfo" class="text-muted"></h5> <!-- This will be empty -->
                </div>
                <div class="row">
                    <div class="col text-center d-flex flex-column align-items-center">
                        <h5>Current Chart:</h5>
                        <img id="currentChartImage" src="" alt="Current Chart" class="img-fluid" style="border-radius: 15px; box-shadow: 0 5px 10px rgba(0, 0, 0, .5); display: none; max-width: 300px; max-height: 300px;" />
                    </div>
                    <div class="col text-center d-flex flex-column align-items-center">
                        <h5>New Chart:</h5>
                        <img id="newChartImage" src="" alt="New Chart" class="img-fluid" style="border-radius: 15px; box-shadow: 0 5px 10px rgba(0, 0, 0, .5); display: none; max-width: 300px; max-height: 300px;" />
                    </div>
                </div>
                <form id="changeForm" action="/esas/esas_admin/public/crud/officers_charts/chart_update.php" method="POST" enctype="multipart/form-data" class="mt-3">
                    <input type="hidden" name="chart_id" id="chart_id" value="" />
                    <label for="chart_image">Upload New Image:</label>
                    <input type="file" name="chart_image" id="chart_image" required onchange="previewImage(event)" />
                    <div class="modal-footer text-center align-items-center justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
function openModal(chartId, currentImage, orgType, dept) {
    document.getElementById("chart_id").value = chartId; // Set the chart_id in the modal
    const currentImgElement = document.getElementById("currentChartImage");
    const newImgElement = document.getElementById("newChartImage");
    
    // Set the current chart image
    currentImgElement.src = currentImage; 
    currentImgElement.style.display = "block"; // Show the current image
    
    // Clear the new chart image
    newImgElement.src = ""; // Reset the new chart image
    newImgElement.style.display = "none"; // Hide the new image by default
    
    // Update modal header with the specific text for organization type and department
    let modalHeaderText = `Update ${orgType}`; // Start with the organization type
    if (dept) {
        modalHeaderText += ` - ${dept}`; // Append department if it exists
    }
    modalHeaderText += " Officers Chart"; // Add "Officers Chart" at the end
    document.getElementById("modalHeader").innerText = modalHeaderText; // Set the modal header text
    
    // Remove the organization info below the header (optional, if you don't need it)
    const orgInfo = document.getElementById("organizationInfo");
    orgInfo.innerText = ""; // Clear the organization info text
    
    $('#changeModal').modal('show'); // Use jQuery to show the modal
}



function closeModal() {
    $('#changeModal').modal('hide'); // Use jQuery to hide the modal
}

// Close the modal when the user clicks outside of it
$(window).on('click', function(event) {
    if ($(event.target).is('#changeModal')) {
        closeModal();
    }
});

// Function to preview the selected image
function previewImage(event) {
    const newImgElement = document.getElementById("newChartImage");
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            newImgElement.src = e.target.result; // Set the new image as source
            newImgElement.style.display = "block"; // Show the new image
        }
        reader.readAsDataURL(file); // Read the selected file as a Data URL
    } else {
        newImgElement.style.display = "none"; // Hide the image if no file is selected
    }
}
</script>


</body>
</html>