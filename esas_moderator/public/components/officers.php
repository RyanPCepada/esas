OFFICERS

<div class="officers-section auto-scroll">
    <!-- Officers information will be dynamically inserted here -->
</div>

<style>
    .officers-info {
        margin: 20px;
        padding: 20px;
        background-color: #f9f9f9; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .officers-info h3 {
        margin-top: 30px;
        color: #333;
    }

    .officer-card {
        border: 1px solid #ddd; 
        border-radius: 8px; 
        padding: 10px; 
        display: flex;
        flex-direction: column; 
        align-items: center; 
        margin: 10px; 
        text-align: center; 
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
                if (response.success) {
                    const officersSection = $('.officers-section');
                    const officers = response.officers;

                    officersSection.append('<div class="officers-info"><h3>Club Officers</h3><div class="officer-row"></div></div>');
                    
                    const officerRow = $('.officer-row');
                    officers.forEach(officer => {
                        officerRow.append(`
                            <div class="officer-card">
                                <img src="${officer.profilePic || '/esas/esas_student/PROF_PIC.png'}" alt="${officer.fullName}">
                                <h6>${officer.fullName}</h6>
                                <p>${officer.position}</p>
                            </div>
                        `);
                    });
                } else {
                    $('.officers-section').append(`<p>${response.message}</p>`);
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
