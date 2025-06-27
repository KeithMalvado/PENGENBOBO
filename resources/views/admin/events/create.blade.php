@extends('layouts.admin')

@section('content')
<style>
    .event-form-wrapper {
        background-color: #f9fafb;
        padding: 40px;
        max-width: 1000px;
        margin: auto;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        font-family: 'Poppins', sans-serif;
    }

    .form-title {
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 30px;
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .form-group {
        flex: 1 1 48%;
    }

    .form-group-full {
        flex: 1 1 100%;
    }

    label {
        font-weight: 500;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }

    input, textarea, select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 15px;
        background-color: #ffffff;
        color: #1e293b;
    }

    .ticket-group {
        border: 1px solid #e2e8f0;
        padding: 20px;
        border-radius: 10px;
        position: relative;
        background-color: #f8fafc;
        margin-bottom: 15px;
    }

    .remove-ticket-btn {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .preview-img {
        max-height: 200px;
        margin-top: 10px;
        border-radius: 8px;
    }

    .btn {
        font-weight: 500;
        border-radius: 6px;
    }

    .btn-success {
        width: 100%;
    }
</style>

<div class="event-form-wrapper">
    <h3 class="form-title">Tambah Event Baru</h3>

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-row">
            <div class="form-group">
                <label for="nama">Nama Event</label>
                <input type="text" name="nama" id="nama" placeholder="Contoh: Tech Expo 2025" required>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" required>
            </div>

            <div class="form-group">
                <label for="jam_mulai">Jam Mulai</label>
                <input type="time" name="jam_mulai" id="jam_mulai" required>
            </div>

            <div class="form-group">
                <label for="jam_selesai">Jam Selesai</label>
                <input type="time" name="jam_selesai" id="jam_selesai" required>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi" placeholder="Gedung Aula Universitas" required>
            </div>

            <div class="form-group-full">
                <label for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" placeholder="Deskripsi event..."></textarea>
            </div>
        </div>

        <div class="form-group-full mt-4">
            <label>Daftar Tiket</label>
            <div id="ticket-section">
                <div class="ticket-group">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-ticket-btn d-none" onclick="removeTicket(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                    <input type="text" name="tickets[0][nama]" placeholder="Nama Tiket (contoh: VIP)" class="form-control mb-2" required>
                    <input type="number" name="tickets[0][stok]" placeholder="Stok" class="form-control mb-2" required>
                    <input type="number" step="0.01" name="tickets[0][harga]" placeholder="Harga (contoh: 100000)" class="form-control" required>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addTicket()">+ Tambah Tipe Tiket</button>
        </div>

        <div class="form-group-full mt-4">
            <label for="gambar">Upload Gambar</label>
            <input type="file" name="gambar" id="gambar" accept="image/*">
            <img id="preview" class="preview-img d-none"/>
        </div>

        <button type="submit" class="btn btn-success mt-4">
            Simpan Event
        </button>
    </form>
</div>

<script>
    let ticketIndex = 1;

    function addTicket() {
        const section = document.getElementById('ticket-section');
        const group = document.createElement('div');
        group.classList.add('ticket-group');
        group.innerHTML = `
            <button type="button" class="btn btn-outline-danger btn-sm remove-ticket-btn" onclick="removeTicket(this)">
                <i class="bi bi-trash"></i>
            </button>
            <input type="text" name="tickets[${ticketIndex}][nama]" placeholder="Nama Tiket" class="form-control mb-2" required>
            <input type="number" name="tickets[${ticketIndex}][stok]" placeholder="Stok" class="form-control mb-2" required>
            <input type="number" step="0.01" name="tickets[${ticketIndex}][harga]" placeholder="Harga" class="form-control" required>
        `;
        section.appendChild(group);
        ticketIndex++;
    }

    function removeTicket(button) {
        button.parentElement.remove();
    }

    document.getElementById("gambar").addEventListener("change", function(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById("preview");
            preview.src = e.target.result;
            preview.classList.remove("d-none");
        };
        reader.readAsDataURL(event.target.files[0]);
    });


    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const localToday = `${year}-${month}-${day}`;
    document.getElementById("tanggal").setAttribute('min', localToday);
</script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
