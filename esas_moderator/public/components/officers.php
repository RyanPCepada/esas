<div class="officers-section">
    <div class="row d-flex align-items-center">
        <div class="col-12 d-flex justify-content-start">
            <h5 class="text-muted mb-3" style="font-size: 1.2em;">Club Officers</h5>
        </div>
    </div>
    <div class="officers-list auto-scroll" id="officersList">
        <div class="officers-info">
            <div class="officer-row top-row"></div>
            <div class="officer-row bottom-row"></div>
            <div class="text-end mt-3">
                <a href="../settings.php">Edit Officers in Settings</a>
            </div>
        </div>
    </div>
</div>

<style>
    .officers-info {
        /* margin: 20px; */
        padding: 20px;
        /* background-color: #f9f9f9;  */
        /* border-radius: 10px;  */
        /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
        background-color: #f9f9f9; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .officer-card {
        min-width: 180px;
        border: 1px solid #ddd; 
        border: 1px solid #fff; 
        border-radius: 8px; 
        padding: 10px; 
        display: flex;
        flex-direction: column; 
        align-items: center; 
        margin: 10px; 
        text-align: center; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Box shadow for officer cards */
        transition: box-shadow 0.3s ease; /* Smooth transition for hover effect */
    }

    .officer-row.top-row {
        display: flex;
        justify-content: center; /* Center both officers in the row */
        margin-bottom: 10px; /* Add spacing below the top row */
    }

    .officer-row.top-row .officer-card {
        margin: 0 10px; /* Space between President and Vice President */
    }

    .officer-card img {
        width: 80px; 
        height: 80px; 
        object-fit: cover; 
        border-radius: 50%; 
    }

    .officer-card h6 {
        margin: 5px 0; 
        font-size: 1.1em; 
        color: #007bff; 
    }

    .officer-card p {
        margin: 0; 
        font-size: 0.9em; 
        color: #666; 
    }

    .officer-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center; 
    }

    .officer-row.bottom-row {
        justify-content: center; /* Centering for single officer */
    }

    .officers-info {
        position: relative; /* Make it relative for absolute positioning of the pseudo-element */
        padding: 20px;
        background-color: #f9f9f9; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden; /* Hide any overflow from shapes */
    }

    .officers-info::before {
        content: "";
        position: absolute;
        top: 10%;  /* Adjust to position above the container */
        left: -10%; /* Adjust to position left of the container */
        width: 120%; /* Make it larger than the container */
        height: 120%; /* Make it larger than the container */
        background: rgba(0, 98, 204, 0.1); /* Light background color */
        border-radius: 50%; /* Rounded shape */
        z-index: 0; /* Behind the content */
        transform: rotate(-30deg); /* Rotate for a unique effect */
    }

    .officers-info > div {
        position: relative; /* Ensure this is on top of the pseudo-element */
        z-index: 1; /* Stack above the pseudo-element */
    }
</style>

<script>
$(document).ready(function() {
    const clubId = getQueryParameter('club_id');

    // Define the officer positions in the required order
    const positions = ['President', 'Vice President', 'Secretary', 'Treasurer', 'P.I.O.', 'Sergeant at Arms'];

    if (clubId) {
        $.ajax({
            url: `/esas/esas_moderator/apis/club-officers-api.php?club_id=${clubId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const officersSection = $('.officers-list');
                
                // Create officers info section
                // officersSection.append('<div class="officers-info"><div class="officer-row top-row"></div><div class="officer-row bottom-row"></div><div class="text-end mt-3"><a href="../settings.php">Edit Officers in Settings</a></div>');
                const topRow = $('.officer-row.top-row');
                const bottomRow = $('.officer-row.bottom-row');

                // Initialize an empty array to track existing officer positions
                const existingPositions = {};

                if (response.success) {
                    const officers = response.officers;

                    // Loop through existing officers and store their information based on position
                    officers.forEach(officer => {
                        const profilePicPath = officer.profilePic ? `/esas/esas_student/images/${officer.profilePic}` : '/esas/esas_student/images/PROF_PIC.png';
                        existingPositions[officer.position] = officer;  // Store the officer by position
                    });
                }

                // Render officers in the specified order
                positions.forEach(position => {
                    if (existingPositions[position]) {
                        const officer = existingPositions[position];
                        const profilePicPath = officer.profilePic ? `/esas/esas_student/images/${officer.profilePic}` : '/esas/esas_student/images/PROF_PIC.png';
                        
                        if (position === 'President' || position === 'Vice President') {
                            topRow.append(`
                                <div class="officer-card">
                                    <img src="${profilePicPath}" alt="${officer.fullName}">
                                    <h6>${officer.fullName}</h6>
                                    <p>${officer.position}</p>
                                </div>
                            `);
                        } else {
                            bottomRow.append(`
                                <div class="officer-card">
                                    <img src="${profilePicPath}" alt="${officer.fullName}">
                                    <h6>${officer.fullName}</h6>
                                    <p>${officer.position}</p>
                                </div>
                            `);
                        }
                    } else {
                        // If no officer is found for this position, add a placeholder
                        if (position === 'President' || position === 'Vice President') {
                            topRow.append(`
                                <div class="officer-card">
                                    <img src="/esas/esas_student/images/PROF_PIC.png" alt="No ${position} selected">
                                    <h6>None</h6>
                                    <p>${position}</p>
                                </div>
                            `);
                        } else {
                            bottomRow.append(`
                                <div class="officer-card">
                                    <img src="/esas/esas_student/images/PROF_PIC.png" alt="No ${position} selected">
                                    <h6>None</h6>
                                    <p>${position}</p>
                                </div>
                            `);
                        }
                    }
                });

                // Display a message if there are no officers and placeholders are shown
                if (officers.length === 0) {
                    officersSection.append('<p>No officers have been assigned to this club.</p>');
                }
            },
            error: function() {
                $('.officers-section').append('<p>An error occurred while fetching officers information.</p>');
            }
        });
    } else {
        $('.officers-section').append('<p>Club ID is required to display officers information.</p>');
    }

    function getQueryParameter(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }
});
</script>
