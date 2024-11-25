                            <div class="row justify-content-between">
                                
                                <div class="col-auto">
                                    <p class="text-muted mt-3 mb-0" style="font-size: 24px;">
                                        <strong>Clubs Performance Race</strong>
                                        <i class="fa fa-trophy" style="color: gold; font-size: 30px;"></i>
                                    </p>
                                    <p class="text-muted mb-1">
                                        See what clubs are on top of excellence every S.Y.
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


                            <div class="row d-flex align-items-start justify-content-between mx-auto mb-0 p-0">

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
                                                        AND a.dateAdded BETWEEN :startDate AND :endDate
                                                        GROUP BY c.clubName
                                                        ORDER BY activity_count DESC, c.clubName";
                                                $stmt = $pdo->prepare($query);
                                                $stmt->bindParam(':startDate', $startDate);
                                                $stmt->bindParam(':endDate', $endDate);
                                                $stmt->execute();

                                                $rank = 1;
                                                $clubsFound = false; // Flag to check if any club is found
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $clubsFound = true; // Set to true if a club is found
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

                                                // If no clubs are found, display the "No clubs qualified" message
                                                if (!$clubsFound) {
                                                    echo "<tr><td colspan='2' class='text-start text-muted'>No clubs qualified</td></tr>";
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
                                                        WHERE a.dateApplied <= :endDate
                                                        GROUP BY c.clubName
                                                        ORDER BY application_count DESC, c.clubName
                                                    ";
                                                    $stmt = $pdo->prepare($query);
                                                    $stmt->bindParam(':endDate', $endDate);
                                                    $stmt->execute();

                                                    $rank = 1;
                                                    $clubsFound = false; // Flag to check if any club is found
                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        $clubsFound = true; // Set to true if a club is found
                                                        // Define gold class based on rank
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

                                                        // Display the result in a table row
                                                        echo "<tr {$goldClass}>
                                                                <td>{$rank}</td>
                                                                <td>" . htmlspecialchars($row['clubName']) . "</td>
                                                            </tr>";
                                                        $rank++;
                                                    }

                                                    // If no clubs are found, display the "No clubs qualified" message
                                                    if (!$clubsFound) {
                                                        echo "<tr><td colspan='2' class='text-start text-muted'>No clubs qualified</td></tr>";
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
                                                    // Extract selected year from GET parameter or default to the latest school year
                                                    $selectedYear = $_GET['club_performance_year'] ?? $defaultSchoolYear;
                                                    $selectedYearParts = explode('-', $selectedYear);
                                                    $startYear = $selectedYearParts[0];
                                                    $endYear = $selectedYearParts[1];

                                                    // Convert school year range to date range (August of start year to July of next year)
                                                    $startDate = "$startYear-08-01";
                                                    $endDate = "$endYear-07-31";

                                                    // Query to fetch cumulative member counts up to the selected school year's end date
                                                    $stmt = $pdo->prepare("
                                                        SELECT c.clubName, COUNT(a.application_id) AS member_count
                                                        FROM tbl_application a
                                                        INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                                                        WHERE a.status = 'active' AND a.dateDecided <= :endDate
                                                        AND c.dateAdded <= :endDate
                                                        GROUP BY c.club_id
                                                        ORDER BY member_count DESC, c.clubName
                                                    ");
                                                    $stmt->bindParam(':endDate', $endDate);
                                                    $stmt->execute();

                                                    // Fetch clubs data
                                                    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    
                                                    if (empty($clubs)) {
                                                        echo "<tr><td colspan='2' class='text-start text-muted'>No clubs qualified</td></tr>";
                                                    } else {
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
                                                    // Get the selected school year from the query parameter or default to the latest school year
                                                    $selectedSchoolYear = $_GET['club_performance_year'] ?? $defaultSchoolYear;
                                                    $yearRange = explode('-', $selectedSchoolYear);
                                                    $startYear = $yearRange[0];
                                                    $endYear = $yearRange[1];

                                                    // Modify the query to filter based on the selected school year
                                                    $sql = "
                                                        SELECT tbl_clubs.clubName,
                                                            (SELECT COUNT(application_id) 
                                                                FROM tbl_application 
                                                                WHERE club_id = tbl_clubs.club_id 
                                                                AND status = 'active' 
                                                                AND YEAR(dateApplied) = :year) AS current_year_members,

                                                            (SELECT COUNT(application_id) 
                                                                FROM tbl_application 
                                                                WHERE club_id = tbl_clubs.club_id 
                                                                AND status = 'active' 
                                                                AND YEAR(dateApplied) = :prev_year) AS previous_year_members,

                                                            (SELECT COUNT(post_id) 
                                                                FROM tbl_posts 
                                                                WHERE club_id = tbl_clubs.club_id 
                                                                AND YEAR(dateAdded) = :year) AS current_year_posts,

                                                            (SELECT COUNT(post_id) 
                                                                FROM tbl_posts 
                                                                WHERE club_id = tbl_clubs.club_id 
                                                                AND YEAR(dateAdded) = :prev_year) AS previous_year_posts,

                                                            (SELECT COUNT(event_id) 
                                                                FROM tbl_events 
                                                                WHERE club_id = tbl_clubs.club_id 
                                                                AND YEAR(dateAdded) = :year) AS current_year_events,

                                                            (SELECT COUNT(event_id) 
                                                                FROM tbl_events 
                                                                WHERE club_id = tbl_clubs.club_id 
                                                                AND YEAR(dateAdded) = :prev_year) AS previous_year_events
                                                        FROM tbl_clubs
                                                        WHERE EXISTS (
                                                            SELECT 1
                                                            FROM tbl_application
                                                            WHERE tbl_application.club_id = tbl_clubs.club_id
                                                            AND YEAR(tbl_application.dateApplied) = :year
                                                        )
                                                        ORDER BY 
                                                            GREATEST(current_year_members - previous_year_members, current_year_posts - previous_year_posts, current_year_events - previous_year_events) DESC
                                                    ";

                                                    $stmt = $pdo->prepare($sql);

                                                    // Bind the year variables
                                                    $previousYear = $startYear - 1;
                                                    $stmt->bindValue(':year', $startYear, PDO::PARAM_INT);
                                                    $stmt->bindValue(':prev_year', $previousYear, PDO::PARAM_INT);
                                                    $stmt->execute();

                                                    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    $rank = 1;
                                                    $clubsFound = false; // Flag to check if any club is found

                                                    foreach ($clubs as $club) {
                                                        $clubsFound = true; // Set to true if a club is found
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

                                                    // If no clubs are found, display the "No clubs found" message
                                                    if (!$clubsFound) {
                                                        echo "<tr><td colspan='2' class='text-center text-muted'>No clubs qualified</td></tr>";
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

                            
                            </div>




                            
                            <div class="row d-flex align-items-start justify-content-center mx-auto mt-0 p-0">
                                <div class="row col-auto">
                                    <p class="text-muted text-center mb-0" style="font-size: 24px;">
                                        <strong>Outstanding Club Moderators</strong>
                                        <i class="fa fa-medal" style="color: gold; font-size: 30px;"></i>
                                    </p>
                                    <p class="text-muted text-center mb-1">
                                        See who's on top in leadership every S.Y.
                                    </p>
                                </div>
                                <!-- Top Moderators Section -->
                                <div class="top-moderators-section col-md-8 m-0 p-3" style="position: relative; z-index: 1;">
                                    <!-- <p>Selected School Year: <?php echo htmlspecialchars($endDate); ?></p> -->
                                    <div class="auto-scroll" style="max-height: 555px;">
                                        <table class="table table-sm">
                                            <tbody>
                                            <?php
                                            try {
                                                // Extract school year from GET parameter or default to the latest year
                                                $selectedYear = $_GET['club_performance_year'] ?? $defaultSchoolYear;
                                                $selectedYearParts = explode('-', $selectedYear);
                                                $startYear = $selectedYearParts[0];
                                                $endYear = $selectedYearParts[1];

                                                // Convert school year range to a date range (August of start year to July of next year)
                                                $startDate = "$startYear-08-01";
                                                $endDate = "$endYear-07-31";

                                                // Fetch moderators and calculate their ratings, filtered by the selected school year
                                                $sql = "
                                                    SELECT 
                                                        tbl_moderators.moderator_id,
                                                        CONCAT(tbl_moderators.firstName, ' ', tbl_moderators.lastName) AS fullName,
                                                        tbl_moderators.profilePic,
                                                        COUNT(tbl_clubs_and_moderators.club_id) AS clubCount,
                                                        (
                                                            (SELECT COUNT(*) 
                                                            FROM tbl_activity_logs 
                                                            WHERE tbl_activity_logs.moderator_id = tbl_moderators.moderator_id 
                                                            AND tbl_activity_logs.dateAdded <= :endDate) * 0.25 +
                                                            (SELECT COUNT(*) 
                                                            FROM tbl_posts 
                                                            WHERE tbl_posts.moderator_id = tbl_moderators.moderator_id 
                                                            AND tbl_posts.dateAdded <= :endDate) * 0.25 +
                                                            (SELECT COUNT(*) 
                                                            FROM tbl_events 
                                                            WHERE tbl_events.moderator_id = tbl_moderators.moderator_id 
                                                            AND tbl_events.dateAdded <= :endDate) * 0.25 +
                                                            (SELECT COUNT(*) 
                                                            FROM tbl_application 
                                                            WHERE tbl_application.moderator_id = tbl_moderators.moderator_id 
                                                            AND tbl_application.dateDecided <= :endDate) * 0.10 +
                                                            (SELECT COUNT(*) 
                                                            FROM tbl_chats 
                                                            WHERE (tbl_chats.sender_id = tbl_moderators.moderator_id OR tbl_chats.recipient_id = tbl_moderators.moderator_id)
                                                            AND tbl_chats.dateAdded <= :endDate) * 0.15
                                                        ) AS moderatorRatingSchoolYear
                                                    FROM 
                                                        tbl_moderators
                                                    LEFT JOIN 
                                                        tbl_clubs_and_moderators 
                                                    ON 
                                                        tbl_moderators.moderator_id = tbl_clubs_and_moderators.moderator_id
                                                    WHERE
                                                        tbl_clubs_and_moderators.dateAdded <= :endDate
                                                    GROUP BY 
                                                        tbl_moderators.moderator_id
                                                    ORDER BY 
                                                        moderatorRatingSchoolYear DESC, clubCount DESC
                                                    LIMIT 30;
                                                ";

                                                $stmt = $pdo->prepare($sql);
                                                $stmt->bindParam(':startDate', $startDate);
                                                $stmt->bindParam(':endDate', $endDate);
                                                $stmt->execute();
                                                $moderators = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                if ($moderators) {
                                                    $rank = 1;
                                                    foreach ($moderators as $moderator) {
                                                        $goldClass = '';
                                                        if ($rank <= 5) {
                                                            $opacity = 0.8 - (($rank - 1) * 0.15); // Adjust gold opacity
                                                            $goldClass = 'style="background-color: rgba(255, 215, 0, ' . $opacity . '); font-size: 16px;"'; // Add font size here
                                                        }

                                                        echo "<tr {$goldClass} class='align-middle' style='font-size: 16px;'>"; // Fallback font size for all rows
                                                        echo "<td><img src='/esas/esas_moderator/images/" . htmlspecialchars($moderator['profilePic']) . "' alt='Profile Picture' style='width: 50px; height: 50px; border-radius: 50%;'></td>";
                                                        echo "<td><strong class='text-dark'>" . htmlspecialchars($moderator['fullName']) . "</strong></td>";
                                                        echo "<td><strong class='text-dark'>" . htmlspecialchars($moderator['clubCount']) . " " . ($moderator['clubCount'] == 1 ? "Club" : "Clubs") . "</strong></td>";
                                                        echo "<td><strong class='text-dark'>" . htmlspecialchars(min(max(round($moderator['moderatorRatingSchoolYear'] / 10, 2), 0), 10)) . "/10</strong></td>";
                                                        echo "</tr>";

                                                        $rank++; 
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4' class='text-center text-muted'>No moderators found</td></tr>";
                                                }
                                            } catch (PDOException $e) {
                                                echo "<tr><td colspan='4' class='text-danger'>Error fetching top moderators: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

