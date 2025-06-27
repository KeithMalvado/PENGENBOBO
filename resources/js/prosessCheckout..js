function processCheckout() {
    const tickets = {};
    ticketData.forEach(ticket => {
        const qty = parseInt(document.getElementById('ticket-' + ticket.id).value);
        if (qty > 0) {
            tickets[ticket.id] = qty;
        }
    });

    if (Object.keys(tickets).length === 0) {
        alert('Pilih setidaknya satu tiket.');
        return;
    }

    const token = document.querySelector('input[name="_token"]').value;

    fetch("{{ route('user.checkout') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": token,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ tickets: tickets })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Kesalahan:', error);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}
