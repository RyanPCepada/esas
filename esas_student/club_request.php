<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Sample Template</title>
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="../assets/js/all.js" crossorigin="anonymous"></script>
    <script src="../assets/js/jquery-3.6.0.js"></script>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/img/nbsclogo.png" rel="icon">
    <style>
        .card-body {
            width: 100%;
            /* max-width: 600px; */
            margin: 0 auto;
            padding: 50px;
        }
        .card-img-only {
            position: relative;
            width: 95%;
            /* width: 1280px;
            height: 720px; */
            height: auto;
            border: solid 3px transparent;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .5);
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-img-only:hover {
            transform: scale(1.03);
            border: solid 3px lightblue;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
        }
        .card-img-only img {
            width: 100%;
            height: auto;
            display: block;
            background: linear-gradient(to top right, rgba(0,0,0,0.6), rgba(255,255,255,0.2));
        }
        .card small {
            position: absolute;
            top: 5px;
            right: 5px;
            color: white;
            padding: 8px;
            font-size: 14px;
            cursor: pointer;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
        }
        .overlay-text {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            background: linear-gradient(to top, rgba(0, 0, 0, .7), rgba(0, 0, 0, 0));
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6); /* Shadow effect */
            text-align: left;
            line-height: 1.2; /* Adjust line height for closer spacing */
        }
        .overlay-text h4 {
            margin: 0;
            font-size: 20px;
        }
        .overlay-text p {
            margin: 5px 0 0;
            font-size: 14px;
        }

    </style>
</head>
<body>
<div class="row g-0 h-100">
    <div class="col-2 ps-0 pt-3 border-end ">
        <div class="pe-2">
            <h5>Your Club Requests</h5>
            <button class="btn btn-primary btn-sm py-1  mt-1 rounded-3 w-100">Create New Request</button>
        </div>
        <div class="row g-0 mt-2">
            <nav>
                <div class="nav nav-tabs n" role="tablist">
                    <button title="New" class="ms-2 px-2 nav-link active" id="nav-prnew-tab" data-bs-toggle="tab" data-bs-target="#nav-prnew" type="button" role="tab" aria-controls="nav-prnew" aria-selected="true"><i class="fa-regular fa-file"></i></button>
                    <button title="Ongoing" class="px-2 nav-link" id="nav-prongoing-tab" data-bs-toggle="tab" data-bs-target="#nav-prongoing" type="button" role="tab" aria-controls="nav-prongoing" aria-selected="false"><i class="fa-regular fa-clock"></i></button>
                    <button title="Approved" class="px-2 nav-link" id="nav-prapproved-tab" data-bs-toggle="tab" data-bs-target="#nav-prapproved" type="button" role="tab" aria-controls="nav-prapproved" aria-selected="false"><i class="fa-regular fa-thumbs-up"></i></button>
                    <button title="Rejected" class="px-2 nav-link" id="nav-prreject-tab" data-bs-toggle="tab" data-bs-target="#nav-prreject" type="button" role="tab" aria-controls="nav-prreject" aria-selected="false"><i class="fa-regular fa-thumbs-down"></i></button>
                    <button title="Filter" class="px-1 btn ms-auto" tabindex="-1" type="button" style="box-shadow: none !important;"><i class="fa-solid fa-sliders"></i></button>
                </div>
            </nav>
            <div class="col-12 pe-2 py-1">
                <input id="inprsearchfilter" class="form-control form-control-sm me-1" placeholder="Search...">
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="nav-prnew" role="tabpanel" aria-labelledby="nav-prnew-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprnewsumm" class="tblprfilter table table-sm table-hover">
                            <!-- ALL STUDENT CLUB REQUESTS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-prongoing" role="tabpanel" aria-labelledby="nav-prongoing-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprongoingsumm" class="tblprfilter table table-sm table-hover">
                            <!-- ALL STUDENT PENDING CLUB REQUESTS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-prapproved" role="tabpanel" aria-labelledby="nav-prapproved-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprapprovedsumm" class="tblprfilter table table-sm table-hover">
                            <!-- ALL STUDENT APPROVED CLUB REQUESTS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-prreject" role="tabpanel" aria-labelledby="nav-prreject-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprrejectedsumm" class="tblprfilter table table-sm table-hover">
                            <!-- ALL STUDENT DISAPPROVED CLUB REQUESTS HERE -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-8 bg-lgrey">
        <div class="row g-0 h-100">
            <div id="divpr_requesdetails" class="table-responsive px-0 auto-scroll">
                <div class="row g-0 p-4 px-2 pt-3 h-100">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="mt-3 mb-4">Request for a New Club</h2>
                            <p class="py-2">Please fill out this form and submit your request for a new club.</p>
                            <form id="clubRequestForm" action="../esas_student/actions/club_request_action.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group mb-3">
                                    <label for="clubName">Club Name</label>
                                    <input type="text" name="clubName" class="form-control" id="clubName" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">What is the primary goal of this club?</label>
                                    <textarea name="description" class="form-control" id="description" rows="4" required></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="activities">Proposed Activities</label>
                                    <textarea name="activities" class="form-control" id="activities" rows="3"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="coverPhoto">Club Cover Photo</label>
                                    <input type="file" name="coverPhoto" class="form-control" id="coverPhoto" required onchange="previewImage(event)">
                                </div>
                                
                                <!-- Preview Container -->
                                <div class="form-group mb-3">
                                    <img id="coverPhotoPreview" src="#" alt="Cover Photo Preview" style="display:none; width: 50%; object-fit: cover;" />
                                </div>
                                
                                <a href="#" onclick="history.back(); return false;" class="btn btn-secondary float-end">Cancel</a>
                            </form>

                            <script>
                                function previewImage(event) {
                                    var reader = new FileReader();
                                    reader.onload = function(){
                                        var output = document.getElementById('coverPhotoPreview');
                                        output.src = reader.result;
                                        output.style.display = 'block';
                                    }
                                    reader.readAsDataURL(event.target.files[0]);
                                }
                            </script>
                        </div>
                    </div>
                </div>
                <div style="height: 150px;"></div>
            </div>
        </div>
    </div>
    <div id="divprstatussection" class="col-2 border-start">
        <div class="row mt-3 g-0">
            <div class="col-12">
                <div class="ps-2">
                    <button id="prbtnsubmitforapproval" onclick="submitClubRequest()" class="btn btn-sm btn-outline-primary rounded-3 w-100 mb-1"><i class="fa fa-plane" aria-hidden="true"></i> Submit for Approval</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitClubRequest() {
            document.getElementById('clubRequestForm').submit();
        }

        // Fetch all club requests
fetch('/esas/esas_student/apis/club-request-all-api.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const table = document.getElementById('tblprnewsumm');
        if (data && data.length > 0) {
            data.forEach(request => {
                const cardHTML = `
                    <div class="col-md-12">
                        <div class="card card-img-only">
                            <a href="#">
                                <img src="/esas/esas_student/images/${request.coverPhoto}" alt="Cover Photo">
                                <div class="overlay-text">
                                    <h4>${request.clubName}</h4>
                                    <p>${request.description}</p>
                                    <p>Status: ${request.status}</p>
                                    <p>Requested on: ${new Date(request.dateRequested).toLocaleDateString()}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                `;
                table.innerHTML += cardHTML;
            });
        } else {
            table.innerHTML = '<p>No club requests found.</p>';
        }
    })
    .catch(error => {
        console.error('Error fetching club requests:', error);
        const table = document.getElementById('tblprnewsumm');
        table.innerHTML = '<p>Failed to fetch club requests. Please try again later.</p>';
    });


    </script>

</div>

<!-- <?php include 'assets/components/modals.php' ?> -->
<script src="../assets/js/jquery.dataTables.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/global_script.js"></script>
<script>
    $(document).ready(function() {
        $('.delprreq').click(function(e) {
            e.stopPropagation();
        });
    });
</script>
</body>
</html>
