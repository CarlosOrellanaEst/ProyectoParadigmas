document.addEventListener('DOMContentLoaded', function() {
    initializeCreateBooking();
});

function initializeCreateBooking() {
    const createBookingForm = document.getElementById('createBookingForm');
    if (createBookingForm) {
        createBookingForm.addEventListener('submit', function(event) {
            event.preventDefault();
            createBooking();
        });
    }
}

function createBooking() {
    const numPersons = document.getElementById('numPersons').value;

    fetch('../business/bookingBusiness.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            create: true,
            numPersons: numPersons
        })
    })
    .then(response => response.json())
    .then(res => {
        alert(res.message);
        if (res.status === 'success') {
            location.reload();
        }
    })
    .catch(() => {
        alert('Error creating booking.');
    });
}
