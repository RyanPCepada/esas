<div class="row justify-content-between">
                                
                                <div class="col-auto">
                                    <p class="text-muted mt-3 mb-0" style="font-size: 24px;">
                                        <strong>Clubs Performance Race</strong>
                                        <i class="fa fa-trophy" style="color: gold; font-size: 30px;"></i>
                                    </p>
                                    <p class="text-muted mb-1">
                                        See what clubs are on top of excellence this S.Y.
                                    </p>
                                </div>

                                <!-- DROPDOWN START -->
                                <div class="col-auto ml-auto">
                                    <div class="row align-items-center mt-2 mb-2">
                                        <label for="clubPerformanceYearDropdown" class="col-auto col-form-label">School Year:</label>
                                        <div class="col-auto">
                                            <select id="clubPerformanceYearDropdown" class="form-select form-select-sm" style="width: 150px;" onchange="filterClubPerformanceRace()">
                                                <?php
                                                try {
                                                    // Fetch distinct years where clubs were added
                                                    $sql = "SELECT DISTINCT YEAR(dateAdded) as year FROM tbl_clubs ORDER BY year DESC";
                                                    $stmt = $pdo->prepare($sql);
                                                    $stmt->execute();
                                                    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    $latestYear = $years[0]['year'];
                                                    $defaultSchoolYear = $latestYear . '-' . ($latestYear + 1);
                                                    $selectedSchoolYear = $_GET['club_performance_year'] ?? $defaultSchoolYear;

                                                    foreach ($years as $year) {
                                                        $startYear = $year['year'];
                                                        $endYear = $startYear + 1;
                                                        $schoolYear = $startYear . '-' . $endYear;

                                                        echo "<option value=\"" . htmlspecialchars($schoolYear) . "\"";
                                                        if ($selectedSchoolYear === $schoolYear) {
                                                            echo " selected";
                                                        }
                                                        echo ">" . htmlspecialchars($schoolYear) . "</option>";
                                                    }
                                                } catch (PDOException $e) {
                                                    echo "Error: " . htmlspecialchars($e->getMessage());
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <script>
                                        function filterClubPerformanceRace() {
                                            var school_year = document.getElementById('clubPerformanceYearDropdown').value;
                                            var queryParams = new URLSearchParams(window.location.search);
                                            queryParams.set('club_performance_year', school_year);
                                            window.location.search = queryParams.toString();
                                        }
                                    </script>
                                </div>
                                <!-- DROPDOWN END -->

                            </div>



                            <!-- COL-MD-3 MOST ACTIVE CLUB START -->
                            <div class="most-active-club-section col-md-3 m-0 p-3" style="position: relative; z-index: 1;">
                                <p class="text-muted"><strong>Most Active</strong> <i class="fas fa-fire text-warning"></i></p>
                                <div class="auto-scroll" style="max-height: 555px;">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php
                                            // Extract school year from GET parameter or default to the latest year
                                            $selectedYear = $_GET['club_performance_year'] ?? $defaultSchoolYear;
                                            $selectedYearParts = explode('-', $selectedYear);
                                            $startYear = $selectedYearParts[0];
                                            $endYear = $selectedYearParts[1];

                                            // Convert school year range to a date range (August of start year to July of next year)
                                            $startDate = "$startYear-08-01";
                                            $endDate = "$endYear-07-31";

                                            // Modify the query to accumulate activity data from all years, but filter by the selected school year
                                            $query = "SELECT c.clubName, COUNT(a.activity_id) AS activity_count
                                                    FROM tbl_activity_logs a
                                                    INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                                                    WHERE a.club_id IS NOT NULL
                                                    AND c.dateAdded <= :endDate
                                                    GROUP BY c.clubName
                                                    ORDER BY activity_count DESC";
                                            $stmt = $pdo->prepare($query);
                                            $stmt->bindParam(':endDate', $endDate);
                                            $stmt->execute();

                                            $rank = 1;
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $goldClass = '';
                                                if ($rank == 1) {
                                                    $goldClass = 'style="background-color: rgba(255, 215, 0, .8);"'; // Pure Gold
                                                } elseif ($rank == 2) {
                                                    $goldClass = 'style="background-color: rgba(255, 215, 0, 0.65);"'; // Light Pure Gold
                                                } elseif ($rank == 3) {
                                                    $goldClass = 'style="background-color: rgba(255, 215, 0, 0.5);"'; // Lighter Pure Gold
                                                } elseif ($rank == 4) {
                                                    $goldClass = 'style="background-color: rgba(255, 215, 0, 0.35);"'; // Slightly Fading Pure Gold
                                                } elseif ($rank == 5) {
                                                    $goldClass = 'style="background-color: rgba(255, 215, 0, 0.2);"'; // Faded Gold
                                                } else {
                                                    $goldClass = 'style="background-color: rgba(255, 215, 0, 0);"'; // White
                                                }

                                                echo "<tr {$goldClass}>
                                                        <td>{$rank}</td>
                                                        <td>{$row['clubName']}</td>
                                                    </tr>";
                                                $rank++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- COL-MD-3 MOST ACTIVE CLUB END -->

                            <!-- COL-MD-3 MOST APPLIED CLUB START -->
                            <div class="most-applied-club-section col-md-3 m-0 p-3" style="position: relative; z-index: 1;">
                                <p class="text-muted"><strong>Most Applied</strong> <i class="fas fa-file-text text-secondary"></i></p>
                                <div class="auto-scroll" style="max-height: 555px;">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php
                                            try {
                                                // Extract selected year from GET parameter or use default
                                                $selectedYear = $_GET['club_performance_year'] ?? $defaultSchoolYear;
                                                $selectedYearParts = explode('-', $selectedYear);
                                                $startYear = $selectedYearParts[0];
                                                $endYear = $selectedYearParts[1];

                                                // Convert school year range to a date range (August of start year to July of next year)
                                                $startDate = "$startYear-08-01";
                                                $endDate = "$endYear-07-31";

                                                // Query to get the most applied clubs within the selected school year
                                                $query = "
                                                    SELECT c.clubName, COUNT(a.application_id) AS application_count
                                                    FROM tbl_application a
                                                    INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                                                    WHERE c.dateAdded <= :endDate
                                                    GROUP BY c.clubName
                                                    ORDER BY application_count DESC
                                                ";
                                                $stmt = $pdo->prepare($query);
                                                $stmt->bindParam(':endDate', $endDate);
                                                $stmt->execute();

                                                $rank = 1;
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $goldClass = '';
                                                    if ($rank == 1) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, .8);"'; // Pure Gold
                                                    } elseif ($rank == 2) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.65);"'; // Light Pure Gold
                                                    } elseif ($rank == 3) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.5);"'; // Lighter Pure Gold
                                                    } elseif ($rank == 4) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.35);"'; // Slightly Fading Pure Gold
                                                    } elseif ($rank == 5) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.2);"'; // Faded Gold
                                                    } else {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0);"'; // White
                                                    }

                                                    echo "<tr {$goldClass}>
                                                            <td>{$rank}</td>
                                                            <td>" . htmlspecialchars($row['clubName']) . "</td>
                                                        </tr>";
                                                    $rank++;
                                                }
                                            } catch (PDOException $e) {
                                                echo "Error fetching most applied clubs: " . htmlspecialchars($e->getMessage());
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- COL-MD-3 MOST APPLIED CLUB END -->

                            <!-- COL-MD-3 HIGHEST MEMBERS START -->
                            <div class="highest-in-members-section col-md-3 m-0 p-3 auto-scroll" style="position: relative; z-index: 1;">
                                <p class="text-muted"><strong>Highest Members</strong> <i class="fas fa-users text-primary"></i></p>
                                <div class="auto-scroll" style="max-height: 555px;">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php
                                            try {
                                                $stmt = $pdo->prepare("
                                                    SELECT tbl_clubs.clubName, COUNT(tbl_application.application_id) AS active_member_count 
                                                    FROM tbl_application 
                                                    INNER JOIN tbl_clubs ON tbl_clubs.club_id = tbl_application.club_id
                                                    WHERE tbl_application.status = 'active' 
                                                    GROUP BY tbl_clubs.club_id 
                                                    ORDER BY active_member_count DESC
                                                ");
                                                $stmt->execute();
                                                $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $rank = 1;

                                                foreach ($clubs as $club) {

                                                    $goldClass = '';
                                                    if ($rank == 1) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, .8);"'; // Pure Gold
                                                    } elseif ($rank == 2) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.65);"'; // Light Pure Gold
                                                    } elseif ($rank == 3) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.5);"'; // Lighter Pure Gold
                                                    } elseif ($rank == 4) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.35);"'; // Slightly Fading Pure Gold
                                                    } elseif ($rank == 5) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.2);"'; // Faded Gold
                                                    } else {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0);"'; // White
                                                    }
                                                    
                                                    echo "<tr {$goldClass}>
                                                            <td>{$rank}</td>
                                                            <td>" . htmlspecialchars($club['clubName']) . "</td>
                                                        </tr>";
                                                    $rank++;
                                                }
                                            } catch (PDOException $e) {
                                                echo "Error fetching highest member clubs: " . $e->getMessage();
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- COL-MD-3 HIGHEST MEMBERS END -->

                            <!-- COL-MD-3 FASTEST GROWING CLUB START -->
                            <div class="fastest-growing-club-section col-md-3 m-0 p-3" style="position: relative; z-index: 1;">
                                <p class="text-muted"><strong>Fastest Growing</strong> <i class="fas fa-bolt text-warning"></i></p>
                                <div class="auto-scroll" style="max-height: 555px;">
                                    <table class="table table-sm">
                                        <tbody>
                                            <?php
                                            try {
                                                $stmt = $pdo->prepare("
                                                    SELECT tbl_clubs.clubName,
                                                        (SELECT COUNT(application_id) 
                                                            FROM tbl_application 
                                                            WHERE club_id = tbl_clubs.club_id 
                                                            AND status = 'active' 
                                                            AND YEAR(dateApplied) = YEAR(CURDATE())) AS current_year_members,

                                                        (SELECT COUNT(application_id) 
                                                            FROM tbl_application 
                                                            WHERE club_id = tbl_clubs.club_id 
                                                            AND status = 'active' 
                                                            AND YEAR(dateApplied) = YEAR(CURDATE()) - 1) AS previous_year_members,

                                                        (SELECT COUNT(post_id) 
                                                            FROM tbl_posts 
                                                            WHERE club_id = tbl_clubs.club_id 
                                                            AND YEAR(dateAdded) = YEAR(CURDATE())) AS current_year_posts,

                                                        (SELECT COUNT(post_id) 
                                                            FROM tbl_posts 
                                                            WHERE club_id = tbl_clubs.club_id 
                                                            AND YEAR(dateAdded) = YEAR(CURDATE()) - 1) AS previous_year_posts,

                                                        (SELECT COUNT(event_id) 
                                                            FROM tbl_events 
                                                            WHERE club_id = tbl_clubs.club_id 
                                                            AND YEAR(dateAdded) = YEAR(CURDATE())) AS current_year_events,

                                                        (SELECT COUNT(event_id) 
                                                            FROM tbl_events 
                                                            WHERE club_id = tbl_clubs.club_id 
                                                            AND YEAR(dateAdded) = YEAR(CURDATE()) - 1) AS previous_year_events
                                                    FROM tbl_clubs
                                                    ORDER BY 
                                                        GREATEST(current_year_members - previous_year_members, current_year_posts - previous_year_posts, current_year_events - previous_year_events) DESC
                                                ");
                                                $stmt->execute();
                                                $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $rank = 1;

                                                foreach ($clubs as $club) {

                                                    $goldClass = '';
                                                    if ($rank == 1) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, .8);"'; // Pure Gold
                                                    } elseif ($rank == 2) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.65);"'; // Light Pure Gold
                                                    } elseif ($rank == 3) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.5);"'; // Lighter Pure Gold
                                                    } elseif ($rank == 4) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.35);"'; // Slightly Fading Pure Gold
                                                    } elseif ($rank == 5) {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0.2);"'; // Faded Gold
                                                    } else {
                                                        $goldClass = 'style="background-color: rgba(255, 215, 0, 0);"'; // White
                                                    }
                                                    
                                                    echo "<tr {$goldClass}>
                                                            <td>{$rank}</td>
                                                            <td>" . htmlspecialchars($club['clubName']) . "</td>
                                                        </tr>";
                                                    $rank++;
                                                }
                                            } catch (PDOException $e) {
                                                echo "Error fetching fastest growing clubs: " . $e->getMessage();
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- COL-MD-3 FASTEST GROWING CLUB END -->