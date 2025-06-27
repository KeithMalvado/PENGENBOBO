@extends('layouts.app')

@section('content')
<style>
    .checkout-section {
        padding: 40px 0;
    }
    .checkout-box {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 30px;
    }
</style>

<div class="container checkout-section">
    <div class="checkout-box">
        <h3 class="mb-4">Checkout Tiket</h3>

        <form id="checkoutForm">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Nama</label>
                <div>{{ auth()->user()->name }}</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <div>{{ auth()->user()->email }}</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Tiket Dipilih</label>
                <ul class="list-group">
                    @php $total = 0; @endphp
                    @foreach ($selectedTickets as $ticket)
                        @php
                            $subtotal = $ticket->harga * $ticket->jumlah;
                            $total += $subtotal;
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $ticket->nama }} (x{{ $ticket->jumlah }})
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-3 fw-bold">
                Total Harga: Rp {{ number_format($total, 0, ',', '.') }}
            </div>

            @php
                $ticketMap = collect($selectedTickets)->mapWithKeys(fn($t) => [$t->id => $t->jumlah]);
            @endphp
            <input type="hidden" id="ticketData" value='@json($ticketMap)'>

            <button type="button" class="btn btn-primary mt-4" onclick="goToPayment()">Bayar Sekarang</button>
        </form>

        <div id="payment-frame" class="mt-4"></div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
function goToPayment() {
    const tickets = JSON.parse(document.getElementById("ticketData").value);
    const token = document.querySelector('input[name="_token"]').value;

    fetch("{{ route('user.purchase.pay') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": token,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ tickets: tickets })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.snapToken && data.order_id) {
            snap.pay(data.snapToken, {
                onSuccess: function (result) {
                    checkStatusAndRedirect(result.order_id);
                },
                onPending: function (result) {
                    checkStatusAndRedirect(result.order_id);
                },
                onError: function (result) {
                    checkStatusAndRedirect(result.order_id);
                },
                onClose: function () {
                    alert("Transaksi belum selesai.");
                }
            });
        } else {
            document.getElementById('payment-frame').innerHTML =
                `<div class='text-danger'>Gagal mendapatkan token Snap: ${data.error || 'Tidak diketahui'}</div>`;
        }
    })
    .catch((err) => {
        console.error('Gagal fetch Snap Token:', err);
        document.getElementById('payment-frame').innerHTML =
            `<div class='text-danger'>${err.error || 'Kesalahan koneksi ke server.'}</div>`;
    });
}

function checkStatusAndRedirect(orderId) {
    const callbackUrl = "{{ env('NGROK_URL') }}/midtrans/notification-handler";
    fetch(callbackUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({ order_id: orderId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "paid") {
            window.location.href = "{{ route('user.events') }}";
        } else {
            document.getElementById('payment-frame').innerHTML =
                `<div class='text-warning'>Status Transaksi: ${data.status}</div>`;
        }
    })
    .catch(err => {
        console.error("Error checking payment status:", err);
        document.getElementById('payment-frame').innerHTML =
            `<div class='text-danger'>Kesalahan saat memeriksa status transaksi.</div>`;
    });
}
</script>
@endsection
