@extends('layouts.app')

@section('content')
<style>
    .tickets-page {
        padding: 130px 0 50px 0;
    }

    .ticket-card {
        border: 1px solid #e0e0e0;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        background: #fff;
    }

    .ticket-name {
        font-weight: 600;
        font-size: 16px;
    }

    .ticket-price {
        font-size: 14px;
        font-weight: bold;
        color: #2e7d32;
    }

    .ticket-desc {
        font-size: 13px;
        color: #555;
        margin-top: 8px;
    }

    .btn-habis {
        background-color: #ddd;
        color: #777;
        font-weight: bold;
        border: none;
        padding: 5px 15px;
        border-radius: 8px;
    }

    .btn-tambah {
        color: #1976d2;
        border: 1px solid #1976d2;
        background: white;
        padding: 4px 12px;
        font-weight: 600;
        border-radius: 8px;
    }

    .right-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
    }

    .checkout-btn {
        background-color: #1f45fc;
        color: white;
        font-weight: bold;
        border: none;
        width: 100%;
        padding: 12px;
        border-radius: 10px;
    }

    .checkout-btn:hover {
        background-color: #0035b4;
    }

    .quantity-box {
        display: flex;
        align-items: center;
    }

    .quantity-box button {
        border: 1px solid #aaa;
        background: white;
        font-weight: bold;
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }

    .quantity-box input {
        width: 50px;
        text-align: center;
        border: none;
        font-weight: bold;
        background: transparent;
    }
</style>

<div class="container tickets-page">
    <form id="checkoutForm" action="{{ route('user.checkout') }}" method="GET">
        @csrf
        <div class="row">
            <!-- LEFT COLUMN (TIKET) -->
            <div class="col-md-8">
                <h5 class="fw-bold mb-4">Kategori Tiket</h5>

                @foreach($event->tickets as $ticket)
                    <div class="ticket-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="ticket-name">{{ $ticket->nama }}</div>
                                <div class="ticket-price">Rp {{ number_format($ticket->harga, 0, ',', '.') }}</div>
                            </div>

                            @if($ticket->stok == 0)
                                <button type="button" class="btn btn-habis" disabled>Habis</button>
                            @else
                                <div class="quantity-box">
                                    <button type="button" onclick="decreaseQty({{ $ticket->id }})">−</button>
                                    <input type="number" name="tickets[{{ $ticket->id }}]" id="ticket-{{ $ticket->id }}" value="0" readonly>
                                    <button type="button" onclick="increaseQty({{ $ticket->id }})">+</button>
                                </div>
                            @endif
                        </div>
                        @if($ticket->deskripsi)
                            <div class="ticket-desc">{{ $ticket->deskripsi }}</div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- RIGHT COLUMN (DETAIL PESANAN) -->
            <div class="col-md-4">
                <div class="right-card">
                    <h6 class="fw-bold">Detail Pesanan</h6>
                    <img src="{{ $event->gambar ? asset('storage/' . $event->gambar) : asset('images/default-event.png') }}" class="img-fluid mb-2 rounded" style="height: 120px; object-fit: cover;">

                    <div class="fw-bold">{{ $event->nama }}</div>
                    <div class="text-muted" style="font-size: 13px;">{{ \Carbon\Carbon::parse($event->tanggal)->format('d F Y') }}</div>
                    <div class="text-muted" style="font-size: 13px;">{{ $event->lokasi }}</div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span><strong>Total</strong></span>
                        <span id="totalAmount" class="fw-bold">Rp 0</span>
                    </div>

                    <button type="submit" class="checkout-btn mt-3">Checkout</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const ticketData = @json($event->tickets);

    function increaseQty(id) {
        const input = document.getElementById('ticket-' + id);
        input.value = parseInt(input.value) + 1;
        updateTotal();
    }


    function decreaseQty(id) {
        const input = document.getElementById('ticket-' + id);
        if (parseInt(input.value) > 0) {
            input.value = parseInt(input.value) - 1;
            updateTotal();
        }
    }

    function updateTotal() {
        let total = 0;
        ticketData.forEach(ticket => {
            const qty = parseInt(document.getElementById('ticket-' + ticket.id).value);
            total += qty * ticket.harga;
        });
        document.getElementById('totalAmount').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endsection
