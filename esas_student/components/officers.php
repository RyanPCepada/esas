<div class="officers-section">
    <div class="row d-flex align-items-center">
        <div class="col-12 d-flex justify-content-start">
            <h5 class="text-muted mb-3" style="font-size: 1.2em;">Club Officers</h5>
        </div>
    </div>
    <div class="officers-list" id="officersList">
        <div class="officers-info">
            <div class="officer-row top-row"></div>
            <div class="officer-row bottom-row"></div>
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
        width: 180px;
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

    if (clubId) {
        $.ajax({
            url: `/esas/esas_student/apis/club-officers-api.php?club_id=${clubId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const officersSection = $('.officers-list');
                const topRow = $('.officer-row.top-row');
                const bottomRow = $('.officer-row.bottom-row');

                // Clear previous entries in case of re-rendering
                topRow.empty();
                bottomRow.empty();

                if (response.success) {
                    const officers = response.officers;

                    if (officers.length > 0) {
                        officers.forEach((officer, index) => {
                            const profilePicPath = officer.profilePic ? `/esas/esas_student/images/${officer.profilePic}` : '/esas/esas_student/images/PROF_PIC.png';
                            const officerCard = `
                                <div class="officer-card">
                                    <img src="${profilePicPath}" alt="${officer.fullName}">
                                    <h6>${officer.fullName}</h6>
                                    <p>${officer.position}</p>
                                </div>
                            `;

                            // Place the first two officers in the top row, others in the bottom row
                            if (index < 2) {
                                topRow.append(officerCard);
                            } else {
                                bottomRow.append(officerCard);
                            }
                        });
                    } else {
                        // Display message if no officers are elected
                        officersSection.append('<p>No officers have been assigned to this club.</p>');
                    }
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
