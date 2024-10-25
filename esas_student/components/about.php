<div class="about-section">
    <div class="row d-flex align-items-center">
        <div class="col-12 d-flex justify-content-start">
            <h5 class="text-muted mb-3" style="font-size: 1.2em;">Club Details</h5>
        </div>
    </div>
    <div class="about-list" id="aboutList">
        <!-- Club description will be dynamically inserted here -->
    </div>
</div>


<style>
    .club-info {
        /* margin: 20px;
        padding: 20px;
        background-color: #f9f9f9; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); Subtle shadow for elevation */
    }
    .club-info-top {
        padding: 20px;
        background-color: #f9f9f9; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .club-info h3 {
        margin-top: 30px;
        color: #333;
    }

    .club-info .date-created,
    .club-info .description {
        margin-bottom: 20px; /* Space between sections */
    }

    .club-info .date-created {
        /* font-size: 1.1em; */
        color: #666; /* Softer color for the date */
        font-style: italic; /* Italic for date */
    }

    .club-info .description {
        /* font-size: 1.1em; Slightly larger font for club description */
        color: #333; /* Darker color for better readability */
        line-height: 1.5; /* Improved line spacing for readability */
    }

    .club-info .section-title {
        font-size: 1.3em;
        font-weight: bold;
        color: #007bff; /* Primary color for section titles */
        margin-bottom: 10px; /* Spacing after title */
        border-bottom: 2px solid #007bff; /* Underline effect for section title */
        padding-bottom: 5px;
    }

    .club-info .club_moderators, .club-info .members {
        font-size: 1em;
        font-weight: bold;
        color: #007bff; /* Primary color for section titles */
    }

    .about-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0px;
        padding: 0px;
    }

    .about-card {
        border: 1px solid #ddd; /* Border around the card */
        border-radius: 8px; /* Rounded corners */
        padding: 12px 0px; /* Padding inside the card */
        display: flex;
        align-items: center; /* Center items vertically */
        margin: 3px; /* Space between rows */
    }

    .about-card img {
        width: 35px; /* Set the width of the profile picture */
        height: 35px; /* Set the height of the profile picture */
        object-fit: cover; /* Maintain aspect ratio */
        border-radius: 50%; /* Circular image */
    }

    .col-2, .col-10 {
        margin: 0px;
        padding: 0px;
    }

    /* Flex alignment for the text and image */
    .about-card-body {
        display: flex;
        align-items: center;
    }

    .about-card-body .col-10 h6 {
        margin: 0;
    }

    .description, .mission, .vision, .history {
        text-align: justify;     /* Center-align text horizontally */
        text-indent: 2em;
    }
    .mission-container, .vision-container {
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    }
    .dashed-border {
        height: 2px; /* Thickness of the border */
        border-top: 2px dashed #ccc; /* Dashed line */
        margin: 30px 0; /* Space above and below */
    }

    

    /* Responsive Design */
    @media (max-width: 768px) {
        .about-card-body {
            margin-left: 10px;
        }
    }

</style>

<script>
$(document).ready(function() {
    const clubId = getQueryParameter('club_id');

    if (clubId) {
        $.ajax({
            url: `/esas/esas_student/apis/club-about-api.php?club_id=${clubId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const club = response.club;
                    const aboutSection = $('.about-list');

                    // Format the registration date
                    const formattedDate = new Date(club.registrationDate).toLocaleDateString('en-US', {
                        year: 'numeric', month: 'long', day: 'numeric'
                    });

                    // Calculate counts
                    const moderatorsCount = club.moderators ? club.moderators.split(', ').length : 0;
                    const membersCount = club.members ? club.members.split(', ').length : 0;

                    // Build the club description HTML
                    aboutSection.append(`
                        <div class="club-info mb-5">
                            <div class="club-info-top">
                                <p class="date-created"><strong>Date of Creation: </strong>${formattedDate}</p>
                                <p><i class="fas fa-lightbulb text-warning" style="font-size: 35px;"></i><strong>Why ${club.clubName}?</strong></p>
                                <p class="description">${club.description}</p>
                                <!-- <div class="dashed-border"></div> -->
                                <div class="row" style="margin-top: 30px; margin-bottom: 30px;">
                                    <div class="col-md-6">
                                        <div class="mission-container text-center p-3 mb-3 border rounded bg-light">
                                            <em><p><strong>Mission</strong></p></em>
                                            <em><p class="mission">${club.mission}</p></em>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="vision-container text-center p-3 mb-3 border rounded bg-light">
                                            <em><p><strong>Vision</strong></p></em>
                                            <em><p class="vision">${club.vision}</p></em>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="dashed-border"></div> -->
                                <p><i class="fas fa-history text-warning" style="font-size: 30px;"></i><strong> History</strong></p>
                                <p class="history">${club.history}</p>

                                <div class="dashed-border"></div>
                                <p class="club_moderators text-muted my-2">${moderatorsCount} Club Moderators <i class="fas fa-shield-alt text-danger" style="font-size: 20px;"></i></p>
                                <div class="about-row">
                                    ${buildCards(club.moderators, 'moderator')}
                                </div>
                                <p class="members text-muted my-2">${membersCount} Students <i class="fas fa-users text-primary" style="font-size: 20px;"></i></p>
                                <div class="about-row">
                                    ${buildCards(club.members, 'member')}
                                </div>
                            </div>
                        </div>
                    `);
                } else {
                    $('.about-section').append(`<p>${response.message}</p>`);
                }
            },
            error: function() {
                $('.about-section').append('<p>An error occurred while fetching club details.</p>');
            }
        });
    } else {
        $('.about-section').append('<p>Club ID is required to display details.</p>');
    }

    // Function to build cards for moderators and members
    function buildCards(data, type) {
        if (!data) return '<p>No data available.</p>';

        const members = data.split(', ');
        return members.map(member => {
            const details = member.split('|');

            if (type === 'moderator') {
                const [fullName, profilePic, department, dateAssigned] = details;
                const nameParts = fullName.split(' ');
                const firstName = nameParts[0];
                const lastName = nameParts.length > 1 ? nameParts[nameParts.length - 1] : '';
                const middleName = nameParts.length > 2 ? nameParts.slice(1, -1).join(' ') : '';

                const profilePicUrl = profilePic ? `/esas/esas_moderator/images/${profilePic}` : '/esas/esas_student/PROF_PIC.png';

                return `
                    <div class="col-md-6 p-0">
                        <div class="about-card" style="background-color: ${getColor('', type)}">
                            <div class="row col-12 m-0 text-start d-flex align-items-start justify-content-start">
                                <div class="col-2">
                                    <img src="${profilePicUrl}" alt="${firstName} ${lastName}" class="img-fluid">
                                </div>
                                <div class="col-10">
                                    <div class="about-card-body p-0">
                                        <div class="d-flex flex-column" style="line-height: 1em;">
                                            <h7 class="card-title mb-1"><strong>${firstName} ${middleName} ${lastName}</strong></h7>
                                            <p class="text-muted mb-0">Assigned: ${new Date(dateAssigned).toLocaleDateString('en-US', {
                                                year: 'numeric', month: 'long', day: 'numeric'
                                            })}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                const [fullName, profilePic, department, yearLevel, dateApproved] = details;
                const nameParts = fullName.split(' ');
                const firstName = nameParts[0];
                const lastName = nameParts.length > 1 ? nameParts[nameParts.length - 1] : '';
                const middleName = nameParts.length > 2 ? nameParts.slice(1, -1).join(' ') : '';

                const profilePicUrl = profilePic ? `/esas/esas_student/images/${profilePic}` : '/esas/esas_student/PROF_PIC.png';

                return `
                    <div class="col-md-6 p-0">
                        <div class="about-card" style="background-color: ${getColor(department, type)}">
                            <div class="row col-12 m-0 text-start d-flex align-items-start justify-content-center">
                                <div class="col-2">
                                    <img src="${profilePicUrl}" alt="${firstName} ${lastName}" class="img-fluid">
                                </div>
                                <div class="col-10">
                                    <div class="about-card-body p-0">
                                        <div class="d-flex flex-column" style="line-height: 1em;">
                                            <h7 class="card-title mb-1"><strong>${firstName} ${middleName} ${lastName}</strong></h7>
                                            <p class="mb-1 text-muted"><strong>${department} ${yearLevel}</strong></p>
                                            <p class="text-muted mb-0">Member since: ${new Date(dateApproved).toLocaleDateString('en-US', {
                                                year: 'numeric', month: 'long', day: 'numeric'
                                            })}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

        }).join('');
    }

    // Function to get the card color based on department
    function getColor(department, type) {
        if (type === 'member') {
            if (department === 'TEP') return '#cce5ff'; // Blue for TEP
            if (department === 'BSBA') return '#fff3cd'; // Yellow for BSBA
            if (department === 'CCS') return '#d4edda'; // Green for CCS
        } else if (type === 'moderator') {
            return '#f8d7da'; // Reddish color for moderators
        }
        return '#ffffff'; // Default color
    }

    // Function to get URL parameters
    function getQueryParameter(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }
});
</script>
