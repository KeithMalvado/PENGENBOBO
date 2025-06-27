@extends('layouts.app')

@section('content')
<style>
    .event-wrapper {
        padding: 120px 0 50px 0;
    }
    .event-title {
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 20px;
    }
    .event-img {
        width: 100%;
        max-height: 350px;
        object-fit: cover;
        border-radius: 10px;
    }
    .event-meta {
        font-size: 14px;
        color: #888;
        margin-top: 5px;
    }
    .detail-box {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 20px;
    }
    .detail-box h5 {
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 15px;
    }
    .ticket-card {
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .ticket-title {
        font-weight: 700;
        font-size: 16px;
    }
    .ticket-desc {
        font-size: 13px;
        color: #555;
        margin-top: 5px;
    }
    .ticket-price {
        font-weight: bold;
        color: green;
        margin-top: 10px;
    }
    .btn-beli {
        background: linear-gradient(to right, #f9a825, #f57f17);
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        color: white;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        transition: 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    .btn-beli:hover {
        opacity: 0.9;
    }
</style>

<div class="container event-wrapper">
    <div class="row">
        <div class="col-md-8">
            <h2 class="event-title">{{ $event->nama }}</h2>

            <img class="event-img" src="{{ $event->gambar ? asset('storage/' . $event->gambar) : asset('images/default-event.png') }}" alt="Event Image">

            <div class="event-meta mt-2">Penyelenggara</div>
            <div class="event-meta"><strong>{{ $event->admin->organization ?? '-' }}</strong></div>

            <div class="mt-4">
                <p style="font-size: 14px; color: #333;">{{ $event->deskripsi }}</p>
            </div>

            <div class="mt-5">
                @foreach($tickets as $ticket)
                    <div class="ticket-card" id="beli">
                        <div class="ticket-title">{{ strtoupper($ticket->nama) }}</div>
                        <div class="ticket-desc">{{ $ticket->deskripsi }}</div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="ticket-price">Rp {{ number_format($ticket->harga, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-4">
            <div class="detail-box">
                <h5>Detail Event</h5>
                <p><strong>Tanggal</strong><br>{{ \Carbon\Carbon::parse($event->tanggal)->format('d F Y') }}</p>
                <p><strong>Waktu</strong><br>{{ $event->jam_mulai }} - {{ $event->jam_selesai }} WIB</p>
                <p><strong>Lokasi</strong><br>{{ $event->lokasi }}</p>
                <a href="{{ route('user.tickets.select', $event->id) }}" class="btn btn-beli w-100 mt-3">Beli Tiket</a>
            </div>
        </div>
    </div>
</div>
@endsection
