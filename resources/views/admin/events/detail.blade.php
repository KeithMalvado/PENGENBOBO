@extends('layouts.admin')

@section('content')
<style>
    .event-detail-section {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        padding: 30px 20px;
        background-color: #f8f9fa;
        font-family: 'Segoe UI', sans-serif;
        color: #333;
    }

    .left-detail, .right-ticket {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .left-detail {
        flex: 1 1 58%;
    }

    .right-ticket {
        flex: 1 1 38%;
    }

    .left-detail h2, .right-ticket h2 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #222;
    }

    .event-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .event-info p {
        margin-bottom: 10px;
        font-size: 15px;
    }

    .ticket-table {
        margin-top: 10px;
    }

    .ticket-table table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
    }

    .ticket-table th, .ticket-table td {
        padding: 10px 14px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
        font-size: 14px;
    }

    .ticket-table thead {
        background-color: #f1f1f1;
    }

    .btn-back {
        margin-top: 20px;
        display: inline-block;
        background-color: #A28C4A;
        color: white;
        border: none;
        padding: 10px 20px;
        font-weight: 500;
        border-radius: 6px;
        text-decoration: none;
    }

    .btn-back:hover {
        background-color: #5a6268;
    }

    @media (max-width: 768px) {
        .event-detail-section {
            flex-direction: column;
        }
    }
</style>

<div class="event-detail-section">
    <!-- KIRI: Detail Event -->
    <div class="left-detail">
        <h2>Detail Event</h2>
        @if($event->gambar)
            <img src="{{ asset('storage/' . $event->gambar) }}" alt="Gambar Event" class="event-image">
        @endif
        <div class="event-info">
            <p><strong>Nama:</strong> {{ $event->nama }}</p>
            <p><strong>Tanggal:</strong> {{ $event->tanggal }}</p>
            <p><strong>Jam:</strong> {{ $event->jam_mulai }} - {{ $event->jam_selesai }}</p>
            <p><strong>Lokasi:</strong> {{ $event->lokasi }}</p>
            <p><strong>Deskripsi:</strong><br>{{ $event->deskripsi }}</p>
        </div>
        <a href="{{ route('admin.events.kelolaevent') }}" class="btn-back">← Kembali</a>
    </div>

    <!-- KANAN: Tiket -->
    <div class="right-ticket">
        <h2>Daftar Tiket</h2>
        @if($event->tickets && $event->tickets->count() > 0)
            <div class="ticket-table">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Stok</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->nama }}</td>
                                <td>{{ $ticket->stok }}</td>
                                <td>Rp {{ number_format($ticket->harga, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">Belum ada tiket untuk event ini.</p>
        @endif
    </div>
</div>
@endsection
