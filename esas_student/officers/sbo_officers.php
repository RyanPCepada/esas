<div class="officers-div pt-2">
                        <div class="row g-0 p-1 px-2 pt-1">
                            <h5 class="ms-2">CSG Officers</h5>
                            <?php foreach ($csgOfficers as $officer): ?>
                                <div class="csg-officers-row col-md-2 p-1 text-center align-items-center justify-content-center">
                                    <div class="card card-csg-officer d-flex flex-row align-items-center p-2" style="width: auto; height: 70px; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <!-- Profile Picture -->
                                        <div class="profile-pic" style="margin-right: 10px;">
                                            <img src="/esas/esas_admin/images/<?php echo $officer['profilePic']; ?>" alt="Profile Pic" style="width: 50px; height: 50px; border-radius: 50%;">
                                        </div>
                                        <!-- Officer Details (Name and Position) -->
                                        <div class="officer-details text-left" style="line-height: 1.2; max-width: 80px;">
                                            <h6 style="font-size: 12px; margin-bottom: 2px;"><?php echo $officer['firstName'] . ' ' . $officer['lastName']; ?></h6>
                                            <p style="font-size: 10px; margin: 0;"><?php echo $officer['position']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-2"></div>

                        <div class="row g-0 p-1 px-2 pt-1">
                            <h5 class="ms-2">SBO Officers</h5>
                            <?php foreach ($sboCCSOfficers as $CCSofficer): ?>
                                <div class="sbo-officers-row col-md-2 p-1 text-center align-items-center justify-content-center">
                                    <div class="card card-sbo-officer d-flex flex-row align-items-center p-2" style="width: auto; height: 70px; background-color: #A6E22E; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <!-- Profile Picture -->
                                        <div class="profile-pic" style="margin-right: 10px;">
                                            <img src="/esas/esas_admin/images/<?php echo $CCSofficer['profilePic']; ?>" alt="Profile Pic" style="width: 50px; height: 50px; border-radius: 50%;">
                                        </div>
                                        <!-- Officer Details (Name and Position) -->
                                        <div class="officer-details text-left" style="line-height: 1.2; max-width: 80px;">
                                            <h6 style="font-size: 12px; margin-bottom: 2px;"><?php echo $CCSofficer['firstName'] . ' ' . $CCSofficer['lastName']; ?></h6>
                                            <p style="font-size: 10px; margin: 0;"><?php echo $CCSofficer['position']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php foreach ($sboTEPOfficers as $TEPofficer): ?>
                                <div class="sbo-officers-row col-md-2 p-1 text-center align-items-center justify-content-center">
                                    <div class="card card-sbo-officer d-flex flex-row align-items-center p-2" style="width: auto; height: 70px; background-color: #6A8CCF; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <!-- Profile Picture -->
                                        <div class="profile-pic" style="margin-right: 10px;">
                                            <img src="/esas/esas_admin/images/<?php echo $TEPofficer['profilePic']; ?>" alt="Profile Pic" style="width: 50px; height: 50px; border-radius: 50%;">
                                        </div>
                                        <!-- Officer Details (Name and Position) -->
                                        <div class="officer-details text-left" style="line-height: 1.2; max-width: 80px;">
                                            <h6 style="font-size: 12px; margin-bottom: 2px;"><?php echo $TEPofficer['firstName'] . ' ' . $TEPofficer['lastName']; ?></h6>
                                            <p style="font-size: 10px; margin: 0;"><?php echo $TEPofficer['position']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php foreach ($sboBSBAOfficers as $BSBAofficer): ?>
                                <div class="sbo-officers-row col-md-2 p-1 text-center align-items-center justify-content-center">
                                    <div class="card card-sbo-officer d-flex flex-row align-items-center p-2" style="width: auto; height: 70px; background-color: #FFF176; background-color: white; box-shadow: 0 5px 10px rgba(0, 0, 0, .3);">
                                        <!-- Profile Picture -->
                                        <div class="profile-pic" style="margin-right: 10px;">
                                            <img src="/esas/esas_admin/images/<?php echo $BSBAofficer['profilePic']; ?>" alt="Profile Pic" style="width: 50px; height: 50px; border-radius: 50%;">
                                        </div>
                                        <!-- Officer Details (Name and Position) -->
                                        <div class="officer-details text-left" style="line-height: 1.2; max-width: 80px;">
                                            <h6 style="font-size: 12px; margin-bottom: 2px;"><?php echo $BSBAofficer['firstName'] . ' ' . $BSBAofficer['lastName']; ?></h6>
                                            <p style="font-size: 10px; margin: 0;"><?php echo $BSBAofficer['position']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>