@extends('layouts.app')

@section('content')
<style>
    .event-wrapper {
        padding-top: 120px;
        padding-bottom: 40px;
        background-color: #f9f9f9;
        min-height: 100vh;
    }

    .event-card {
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
        transition: transform 0.2s ease;
        background-color: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .event-card:hover {
        transform: translateY(-5px);
    }

    .event-img {
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        width: 100%;
    }

    .event-date-box {
        text-align: center;
        margin-right: 12px;
    }

    .event-month {
        text-transform: uppercase;
        font-size: 12px;
        font-weight: 600;
        color: #8B5E3C;
    }

    .event-day {
        font-size: 22px;
        font-weight: bold;
        color: #333;
        line-height: 1;
    }

    .filter-section {
        position: sticky;
        top: 100px;
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .event-info {
        flex-grow: 1;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c2c2c;
        margin-bottom: 4px;
    }

    .card-text {
        font-size: 13px;
        color: #555;
    }

    .event-location {
        font-size: 13px;
        color: #777;
    }

    .pagination {
        justify-content: end;
    }

        .btn-terapkan {
        background-color: #A28C4A;
        color: #fff;
        border: none;
        transition: background-color 0.2s ease;
    }

    .btn-terapkan:hover {
        background-color: #8e793f;
        color: #fff;
    }



</style>

<div class="container-fluid event-wrapper">
    <div class="row">

        <div class="col-md-3 col-12 mb-4">
            <div class="filter-section">
                <h5 class="fw-bold mb-3"><i class="bi bi-sliders"></i> Filter</h5>
                <form action="{{ route('user.events') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Lokasi</label>
                        <select class="form-select" name="lokasi">
                            <option value="">Semua Lokasi</option>
                            @foreach($lokasi as $loc)
                                <option value="{{ $loc }}" {{ request('lokasi') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="{{ request('tanggal') }}">
                    </div>
                <button type="submit" class="btn btn-terapkan w-100">Terapkan Filter</button>

                </form>
            </div>
        </div>

        <div class="col-md-9 col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-muted">Menampilkan {{ $events->firstItem() }}–{{ $events->lastItem() }} dari {{ $totalEvents }} event</h6>
                <form action="{{ route('user.events') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari Event" name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>

            <div class="row g-4">
                @forelse($events as $event)
                    <div class="col-md-4 col-sm-6">
<a href="{{ route('user.events.show', $event->id) }}" class="text-decoration-none text-dark">
    <div class="card event-card h-100">
        <img src="{{ $event->gambar ? asset('storage/' . $event->gambar) : asset('images/default-event.png') }}" class="event-img" alt="{{ $event->nama }}">
        <div class="card-body d-flex gap-3">
            <div class="event-date-box">
                <div class="event-month">{{ \Carbon\Carbon::parse($event->tanggal)->format('M') }}</div>
                <div class="event-day">{{ \Carbon\Carbon::parse($event->tanggal)->format('d') }}</div>
            </div>
            <div class="event-info">
                <h6 class="card-title">{{ $event->nama }}</h6>
                <p class="card-text">{{ Str::limit($event->deskripsi, 90) }}</p>
                <div class="event-location">
                    <i class="bi bi-geo-alt"></i> {{ $event->lokasi }}
                </div>
            </div>
        </div>
    </div>
</a>

                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            Tidak ada event yang tersedia.
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <p class="text-muted small mb-0">
                    Menampilkan {{ $events->firstItem() }}–{{ $events->lastItem() }} dari {{ $totalEvents }}
                </p>
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
