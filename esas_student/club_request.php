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
</head>
<body>
<div class="row g-0 h-100">
    <div class="col-2 ps-0 pt-3 border-end ">
        <div class="pe-2">
            <h5>Activity Design</h5>
            <button class="btn btn-primary btn-sm py-1  mt-1 rounded-3 w-100">Create PR</button>
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
                            <tr class="border-bottom highlight" onclick="loadPrDetailHeader('49',this, 'Purchase Request')">
                                <td class="py-0 cursor-pointer">
                                    <div class="d-flex bd-highlight">
                                        <div class="p-2 py-1 w-100 bd-highlight">
                                            <div style="width: 150px;">
                                                <p class="mb-0 lh-3" style="font-size: small; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">wew</p>
                                            </div>
                                            <p class="mb-0 lh-2" style="font-size: x-small;">Date created: 2024/4/08 4:58pm
                                            </p>
                                            <p class="mb-0 lh-2" style="font-size: x-small;">By: PALabastida
                                            </p>
                                            <p class="mb-0 lh-2" style="font-size: x-small;">Information Management Office
                                            </p>
                                        </div>
                                        <div class="p-1 py-1 pt-2 flex-shrink-1 bd-highlight">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                            <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-prongoing" role="tabpanel" aria-labelledby="nav-prongoing-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprongoingsumm" class="tblprfilter table table-sm table-hover">
                            <!-- LOAD DATA -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-prapproved" role="tabpanel" aria-labelledby="nav-prapproved-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprapprovedsumm" class="tblprfilter table table-sm table-hover">
                            <!-- LOAD DATA -->
                        </table>
                        <div style="height: 150px;"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-prreject" role="tabpanel" aria-labelledby="nav-prreject-tab">
                    <div class="table-responsive auto-scroll" style="height: 400px">
                        <table id="tblprrejectedsumm" class="tblprfilter table table-sm table-hover">
                            <!-- LOAD DATA -->
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
                    <div class="card ">
                        <div class="card-body">

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
                    <button id="prbtnsubmitforapproval" onclick="modal_confirm('submitPrSummary()', '', 'Are you sure you want to submit this <b>Purchase Request (PR)</b>?')" class="btn btn-sm btn-outline-primary rounded-3 w-100 mb-1"><i class="fa fa-plane" aria-hidden="true"></i> Submit for Approval</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.delprreq').click(function(e) {
            e.stopPropagation();
        });
    });
</script>
</body>
</html>
