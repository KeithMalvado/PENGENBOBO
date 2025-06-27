@extends('layouts.admin')

@section('content')
<style>
    .event-admin-wrapper {
        padding-top: 40px;
        padding-bottom: 40px;
    }

    .card-event-admin {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform 0.2s;
        height: 100%;
    }

    .card-event-admin:hover {
        transform: translateY(-4px);
    }

    .event-img-admin {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .event-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 4px 10px;
        font-size: 13px;
    }

</style>

<div class="container event-admin-wrapper">
    <h3 class="mb-4">Kelola Event</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            + Tambah Event
        </a>
    </div>

    <div class="row">
        @forelse($events as $event)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card card-event-admin">
                    @if($event->gambar)
                        <img src="{{ asset('storage/' . $event->gambar) }}" 
                             alt="{{ $event->nama }}"
                             class="event-img-admin">
                    @else
                        <img src="{{ asset('images/default-event.png') }}" 
                             alt="Default Event"
                             class="event-img-admin">
                    @endif

                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $event->nama }}</h5>
                        <p class="mb-1 text-muted">
                            {{ $event->tanggal }} | {{ $event->jam_mulai }} - {{ $event->jam_selesai }}
                        </p>
                        <p class="mb-3 text-muted"><i class="bi bi-geo-alt"></i> {{ $event->lokasi }}</p>

                        <div class="event-actions">
                            <a href="{{ route('admin.events.detail', $event->id) }}" class="btn btn-info btn-sm">Detail</a>
                            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus event ini?')" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Tidak ada event yang tersedia.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
