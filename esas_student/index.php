<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to login page
    header("Location: /esas/esas_student/login.php");
    exit(); // Ensure no further code is executed
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
    <title>eSAS - Index</title>
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
</head>

<body class="sb-nav-fixed">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-2">
        <a class="navbar-brand ps-2" href="#">
            <img src="../assets/img/nbsclogo.png" style="height: 0.3in;">
            NBSC SIS</a>
        </button>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="true">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse hide" id="main_nav">
            <div class="navbar-collapse flex-grow-1 text-right" id="sampleid" style="padding-left: 20px">
                <?php include 'nav/nav_main.php' ?>
            </div>
        </div>
    </nav>

    <div>
        <div>
            <main>
                <div id="maincontent" class="container-fluid px-2">
                    <!-- LOAD MAIN DATA HERE -->
                    <div class="row">
                        <div class="col-12" style="text-align: -webkit-center;">
                            <img src="../assets/img/nbsclogo.png" style="height: 1.5in; margin-top:100px; font-family: fantasy;color: #002d54 !important;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <h3 class="mb-0" style="font-size: 50px;font-family: fantasy;color: #002d54 !important;">Northern Bukidnon State College</h3>
                            <h5 class="mb-0" style="color: #d18b00;">Creando Futura, Transformationis Vitae, Ductae a Deo</h5>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- <?php include 'assets/components/modals.php' ?> -->
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/global_script.js"></script>
    <script>
        // clickSubModule('nav/userreg/userreg_main.php')
        // clickSubModule('nav/purchaserequest/pr_main.php');
        // clickSubModule('nav/purchaserequest/pr_main.php')
    </script>
   

</body>

</html>