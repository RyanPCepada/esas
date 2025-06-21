<?php


    // Fetch the default club_id from tbl_clubs_and_moderators
    $sqlClub = "
    SELECT club_id 
    FROM tbl_clubs_and_moderators 
    WHERE moderator_id = :moderator_id 
    ORDER BY dateAdded ASC 
    LIMIT 1
";
$stmtClub = $pdo->prepare($sqlClub);
$stmtClub->bindParam(':moderator_id', $moderator_id, PDO::PARAM_INT);
$stmtClub->execute();
$clubResult = $stmtClub->fetch(PDO::FETCH_ASSOC);

if ($clubResult) {
    $defaultClubId = $clubResult['club_id'];
} else {
    $defaultClubId = null; // No clubs assigned to this moderator
}

// Set the club_id from the query or default to the first club if not in URL
if (isset($_GET["club_id"])) {
    $club_id = trim($_GET["club_id"]);
} else {
    $club_id = $defaultClubId ?: 'no_club_assigned'; // Fallback if no default club found
}


?>


<p class="text-muted mt-0" style="font-size: 24px; margin-bottom: 15px;">
                                    <strong style="margin-left: -6px;">Achievements & Awards</strong>
                                    <i class="fa fa-trophy" style="color: gold; font-size: 30px;"></i>
                                </p>
                                <p class="text-muted mb-0 p-0" style="margin-top: -15px; margin-left: 7px;">
                                    Your club's performances that reached top 10
                                </p>

                                <div class="row col-md-12 justify-content-between m-0 p-3" style="max-height: auto; gap: 1px;">
                                    <?php

                                    if ($club_id === null) {
                                        // Handle error: club_id not passed
                                        echo "Club ID is required.";
                                        exit;
                                    }

                                    // Initialize achievements array
                                    $achievements = [];

                                    // Get the current year
                                    $currentYear = date("Y");

                                    // Define the start and end years for each school year
                                    $startYear = 2022;

                                    // Loop through each school year from 2022-2023 up to the current school year
                                    while ($startYear < $currentYear) { // Exclude current school year
                                        // Define the start and end dates of the school year
                                        $schoolYearStart = "{$startYear}-08-01"; // August 1st of the starting year
                                        $schoolYearEnd = ($startYear + 1) . "-07-31"; // July 31st of the next year
                                        $schoolYear = "{$startYear}-" . ($startYear + 1); // Format the school year (e.g., "2022-2023")

                                        // Query for Most Active Clubs
                                        $sql = "SELECT c.club_id, c.clubName, COUNT(a.activity_id) AS activity_count
                                                FROM tbl_activity_logs a
                                                INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                                                WHERE a.dateAdded BETWEEN :startDate AND :endDate
                                                GROUP BY c.club_id, c.clubName
                                                ORDER BY activity_count DESC, c.clubName";

                                        $stmt = $pdo->prepare($sql);
                                        $stmt->bindParam(':startDate', $schoolYearStart);
                                        $stmt->bindParam(':endDate', $schoolYearEnd);
                                        $stmt->execute();

                                        $rank = 1;
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            if ($row['club_id'] == $club_id) {
                                                $achievements[] = [
                                                    'clubName' => $row['clubName'],
                                                    'rank' => $rank,
                                                    'category' => 'Most Active Club',
                                                    'schoolYear' => $schoolYear
                                                ];
                                            }
                                            $rank++;
                                        }

                                        // Query for Most Applied Clubs
                                        $mostAppliedSql = "
                                            SELECT c.club_id, c.clubName, COUNT(a.application_id) AS application_count
                                            FROM tbl_application a
                                            INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                                            WHERE a.dateApplied <= :endDate
                                            GROUP BY c.club_id, c.clubName
                                            ORDER BY application_count DESC, c.clubName";

                                        $mostAppliedStmt = $pdo->prepare($mostAppliedSql);
                                        $mostAppliedStmt->bindParam(':endDate', $schoolYearEnd);
                                        $mostAppliedStmt->execute();

                                        $rank = 1;
                                        while ($row = $mostAppliedStmt->fetch(PDO::FETCH_ASSOC)) {
                                            if ($row['club_id'] == $club_id) {
                                                $achievements[] = [
                                                    'clubName' => $row['clubName'],
                                                    'rank' => $rank,
                                                    'category' => 'Most Applied Club',
                                                    'schoolYear' => $schoolYear
                                                ];
                                            }
                                            $rank++;
                                        }

                                        // Query for Highest Members
                                        $highestMembersSql = "
                                            SELECT c.club_id, c.clubName, COUNT(a.application_id) AS member_count
                                            FROM tbl_application a
                                            INNER JOIN tbl_clubs c ON a.club_id = c.club_id
                                            WHERE a.status = 'active' AND a.dateDecided <= :endDate
                                            AND c.dateAdded <= :endDate
                                            GROUP BY c.club_id
                                            ORDER BY member_count DESC, c.clubName";

                                        $highestMembersStmt = $pdo->prepare($highestMembersSql);
                                        $highestMembersStmt->bindParam(':endDate', $schoolYearEnd);
                                        $highestMembersStmt->execute();

                                        $rank = 1;
                                        while ($row = $highestMembersStmt->fetch(PDO::FETCH_ASSOC)) {
                                            if ($row['club_id'] == $club_id) {
                                                $achievements[] = [
                                                    'clubName' => $row['clubName'],
                                                    'rank' => $rank,
                                                    'category' => 'Highest Members',
                                                    'schoolYear' => $schoolYear
                                                ];
                                            }
                                            $rank++;
                                        }

                                        // Query for Fastest Growing Club
                                        $fastestGrowingSql = "
                                            SELECT c.club_id, c.clubName,
                                                (SELECT COUNT(a.application_id) 
                                                    FROM tbl_application a 
                                                    WHERE a.club_id = c.club_id AND a.status = 'active' 
                                                    AND YEAR(a.dateApplied) = :currentYear) - 
                                                (SELECT COUNT(a.application_id) 
                                                    FROM tbl_application a 
                                                    WHERE a.club_id = c.club_id AND a.status = 'active' 
                                                    AND YEAR(a.dateApplied) = :previousYear) AS growth_members,

                                                (SELECT COUNT(p.post_id) 
                                                    FROM tbl_posts p 
                                                    WHERE p.club_id = c.club_id 
                                                    AND YEAR(p.dateAdded) = :currentYear) - 
                                                (SELECT COUNT(p.post_id) 
                                                    FROM tbl_posts p 
                                                    WHERE p.club_id = c.club_id 
                                                    AND YEAR(p.dateAdded) = :previousYear) AS growth_posts,

                                                (SELECT COUNT(e.event_id) 
                                                    FROM tbl_events e 
                                                    WHERE e.club_id = c.club_id 
                                                    AND YEAR(e.dateAdded) = :currentYear) - 
                                                (SELECT COUNT(e.event_id) 
                                                    FROM tbl_events e 
                                                    WHERE e.club_id = c.club_id 
                                                    AND YEAR(e.dateAdded) = :previousYear) AS growth_events

                                            FROM tbl_clubs c
                                            WHERE EXISTS (SELECT 1 FROM tbl_application a WHERE a.club_id = c.club_id 
                                                AND YEAR(a.dateApplied) = :currentYear)
                                            ORDER BY 
                                                GREATEST(growth_members, growth_posts, growth_events) DESC
                                        ";

                                        $stmt = $pdo->prepare($fastestGrowingSql);
                                        $stmt->bindValue(':currentYear', $startYear, PDO::PARAM_INT);
                                        $stmt->bindValue(':previousYear', $startYear - 1, PDO::PARAM_INT);
                                        $stmt->execute();

                                        $rank = 1;
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            if ($row['club_id'] == $club_id) {
                                                $achievements[] = [
                                                    'clubName' => $row['clubName'],
                                                    'rank' => $rank,
                                                    'category' => 'Fastest Growing Club',
                                                    'schoolYear' => $schoolYear
                                                ];
                                            }
                                            $rank++;
                                        }

                                        // Move to the next school year
                                        $startYear++;
                                    }

                                    // Check if there are any achievements
                                    if (empty($achievements)) {
                                        echo '<p class="text-center text-muted mt-5">Nothing for now. Just keep everything going!</p>';
                                    } else {
                                        $displayedRanks = 0; // Counter to track how many achievements to display (top 5 only)
                                        
                                        foreach ($achievements as $achievement) {
                                            // Check if the rank is between 1 and 5 (inclusive)
                                            if ($achievement['rank'] <= 10) {
                                                ?>
                                                <div class="row card col-md-3 mb-3 p-0" style="background-color: #F1F3F5; border: none; border-radius: 15px; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);">
                                                    <div class="card-body text-center p-3">
                                                        <img src="/esas/esas_admin/icons/ICON_TROPHEE.png" style="width: 70px; height: 70px;" alt="Trophy" class="trophy-img">
                                                        <p class="rank" style="font-size: 1.5rem; font-weight: bold; margin: 0;">
                                                            <?php 
                                                            // Combine rank and suffix directly without spaces
                                                            echo $achievement['rank'] . (
                                                                $achievement['rank'] == 1 ? 'st' :
                                                                ($achievement['rank'] == 2 ? 'nd' :
                                                                ($achievement['rank'] == 3 ? 'rd' : 'th'))
                                                            ); 
                                                            ?>
                                                        </p>
                                                        <p class="text-muted m-0 p-0" style="margin: 0;"><strong><?php echo $achievement['category']; ?></strong></p>
                                                        <small style="display: block; margin-top: 0.5rem;">S.Y. <?php echo $achievement['schoolYear']; ?></small>
                                                    </div>
                                                </div>
                                                <?php
                                                $displayedRanks++; // Increment the counter
                                            }
                                            
                                            // Break the loop if we've displayed 5 achievements
                                            // if ($displayedRanks >= 5) {
                                            //     break;
                                            // }
                                        }
                                    }
                                    ?>
                                </div>