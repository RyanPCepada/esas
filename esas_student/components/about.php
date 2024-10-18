    <div class="about-section">
        <!-- Club information will be dynamically inserted here -->
    </div>

    <style>
        .club-info {
            margin: 20px;
        }

        .club-info h3 {
            margin-top: 30px;
            color: #333;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0px;
            padding: 0px;
        }

        .card {
            border: 1px solid #ddd; /* Border around the card */
            border-radius: 8px; /* Rounded corners */
            padding: 10px; /* Padding inside the card */
            display: flex;
            align-items: center; /* Center items vertically */
            margin: 3px; /* Space between rows */
        }

        .card img {
            width: 30px; /* Set the width of the profile picture */
            height: 30px; /* Set the height of the profile picture */
            object-fit: cover; /* Maintain aspect ratio */
            border-radius: 50%; /* Circular image */
        }

        .col-2, .col-10 {
            margin: 0px;
            padding: 0px;
        }

        /* Flex alignment for the text and image */
        .card-body {
            display: flex;
            align-items: center;
        }

        .card-body .col-10 h6 {
            margin: 0;
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
                        const aboutSection = $('.about-section');

                        // Format the registration date
                        const formattedDate = new Date(club.registrationDate).toLocaleDateString('en-US', {
                            year: 'numeric', month: 'long', day: 'numeric'
                        });

                        // Build the club information HTML
                        aboutSection.append(`
                            <div class="club-info">
                                <p><strong>Date of Creation:</strong> ${formattedDate}</p>
                                <p><strong>Information:</strong> ${club.information}</p>
                                <hr>
                                <p class="my-2"><strong>Club Moderators:</strong></p>
                                <div class="row">
                                    ${buildCards(club.moderators, 'moderator')}
                                </div>
                                <p class="my-2"><strong>Members:</strong></p>
                                <div class="row">
                                    ${buildCards(club.members, 'member')}
                                </div>
                            </div>
                        `);
                    } else {
                        $('.about-section').append(`<p>${response.message}</p>`);
                    }
                },
                error: function() {
                    $('.about-section').append('<p>An error occurred while fetching club information.</p>');
                }
            });
        } else {
            $('.about-section').append('<p>Club ID is required to display information.</p>');
        }

        // Function to build cards for moderators and members
function buildCards(data, type) {
    if (!data) return '<p>No data available.</p>';

    const members = data.split(', ');
    return members.map(member => {
        const details = member.split('|');
        
        if (type === 'moderator') {
    // Moderator details
    const [fullName, profilePic, dateAssigned] = details; // Make sure dateAssigned is being set from the SQL
    const [firstName, middleName, lastName] = fullName.split(' ');

    const profilePicUrl = profilePic ? `/esas/esas_moderator/images/${profilePic}` : '/esas/esas_student/PROF_PIC.png'; // Default image

    return `
        <div class="col-md-6 p-0">
            <div class="card" style="background-color: ${getColor('', type)}">
                <div class="row col-12 text-start d-flex align-items-start justify-content-start">
                    <div class="col-2">
                        <img src="${profilePicUrl}" alt="${firstName} ${lastName}" class="img-fluid">
                    </div>
                    <div class="col-10">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <h6 class="card-title mb-1">${firstName} ${middleName} ${lastName}</h6>
                                <p class="mb-0">Date Assigned: ${new Date(dateAssigned).toLocaleDateString('en-US', {
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
    // Student details
    const [fullName, profilePic, department, yearLevel, dateApproved] = details; // Make sure dateApproved is being set from the SQL
    const [firstName, middleName, lastName] = fullName.split(' ');

    const profilePicUrl = profilePic ? `/esas/esas_student/images/${profilePic}` : '/esas/esas_student/PROF_PIC.png'; // Default image

    return `
        <div class="col-md-6 p-0">
            <div class="card" style="background-color: ${getColor(department, type)}">
                <div class="row col-12 text-start d-flex align-items-start justify-content-start">
                    <div class="col-2">
                        <img src="${profilePicUrl}" alt="${firstName} ${lastName}" class="img-fluid">
                    </div>
                    <div class="col-10">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <h6 class="card-title mb-1">${firstName} ${middleName} ${lastName}</h6>
                                <p class="mb-1"><strong>${department} ${yearLevel}</strong></p>
                                <p class="mb-0">Member since: ${new Date(dateApproved).toLocaleDateString('en-US', {
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


    <!-- HERE -->