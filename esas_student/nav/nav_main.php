<style>
    .nav-link {
        position: relative; /* Ensure the parent is positioned to handle the absolute position of the red dot */
    }
    
    .panel-notification-badge {
        position: absolute;
        top: 0px;
        left: 35px;
        width: 10px;
        height: 10px;
        background-color: red;
        border-radius: 50%;
    }

    @media (max-width: 767px) {
        .panel-notification-badge {
            left: 27px;
        }
    }
</style>

<ul class="navbar-nav ms-auto mb-2 mb-lg-0 flex-nowrap">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" active="false" aria-selected="false">
            Panel
            <span id="panel-notification-count" class="panel-notification-badge" style="display: none;"></span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end shadow-sm">
            <div class="d-md-flex align-items-start justify-content-start">
                <div>
                    <a class="dropdown-item py-1" href="index.php">Dashboard</a>
                    <a class="dropdown-item py-1">Profile</a>
                    <a class="dropdown-item py-1">Certificate of Registration</a>
                    <a class="dropdown-item py-1">Grade Slip</a>
                    <a class="dropdown-item py-1">Permanent Records</a>
                    <a class="dropdown-item py-1">Assessment</a>
                    <a class="dropdown-item py-1">Course Evaluation</a>
                    <a class="dropdown-item py-1">Admission</a>
                    <a class="dropdown-item py-1">Library Resource</a>
                    <a class="dropdown-item py-1">Election Voting</a>
                    <a class="dropdown-item py-1" href="dashboard.php">Club Registration</a>
                    <!-- <a class="dropdown-item py-1" href="clubs_old_v2.php">Club Registration Old</a> -->
                </div>
                <!-- <div>
                    <div class="dropdown-header py-0">Settings</div>
                    <a class="dropdown-item py-0" onclick="clickSubModule('nav/userreg/userreg_main.php')">User Registry</a>
                    <a class="dropdown-item py-0">Department Registry</a>
                    <a class="dropdown-item py-0">Approval Settings</a>
                </div>
                <div>
                    <div class="dropdown-header py-0">Activity Design</div>
                    <a class="dropdown-item py-0">Administrator</a>
                    <a class="dropdown-item py-0" onclick="clickSubModule('nav/ad_portal/ad_portal_main.php')">Activity Design Portal</a>
                </div>
                <div>
                    <div class="dropdown-header py-0">Misc</div>
                    <a class="dropdown-item py-0">Module 3</a>
                    <a class="dropdown-item py-0">Module 4</a>
                    <a class="dropdown-item py-0">Module 5</a>
                    <a class="dropdown-item py-0">Module 6</a>
                </div> -->
            </div>
        </div>
    </li>

    <li class="nav-item dropdown" style="padding-top: 0px;">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li>
                <div class="dropdown-account"><?php echo "$lastName, $firstName $middleName"; ?></div>
            </li>
            <li>
                <div class="dropdown-account">ID: <?php echo $student_id; ?></div>
            </li>
            <li>
                <hr class="dropdown-divider" />
            </li>
            <li><a class="dropdown-item" href="/esas/esas_student/logout.php">Logout</a></li>
        </ul>
    </li>
</ul>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Fetch and display notification dot
    function fetchNotificationCount() {
        $.ajax({
            url: '/esas/esas_student/apis/notifications/panel-notifications-api.php',
            method: 'GET',
            success: function(response) {
                const data = JSON.parse(response);
                if (data.unread_count > 0) {
                    $('#panel-notification-count').show(); // Show red dot
                } else {
                    $('#panel-notification-count').hide(); // Hide red dot
                }
            }
        });
    }

    // Fetch notifications every 10 seconds
    setInterval(fetchNotificationCount, 10000);
    fetchNotificationCount();
</script>


<script>
    function clickSubModule(filepath) {
        // console.log(filepath)
        $.post(filepath, {
            // name: 'PAUL',
            // lastname: 'labastida',
            // firstName: 'sample',


        }, function(data) {
            // console.log(0)
            $('#maincontent').html(data)
        });
        // console.log(1)
    }
</script>