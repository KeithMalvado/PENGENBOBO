@extends('layouts.app') {{-- Menggunakan layout utama --}}

@section('title', 'Beranda | EvenTara') {{-- Mengatur judul halaman --}}

@section('content')

<section class="vh-100 d-flex align-items-center position-relative" style="background: url('{{ asset('images/tarii.png') }}') center center / cover no-repeat">
    <div style="
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to bottom, rgba(162, 140, 74, 0.5), rgba(36, 30, 1, 0.5));
        z-index: 1;">
    </div>
    <div class="container text-center text-white position-relative" style="z-index: 2;">
        <h1 class="fw-bold display-5 mb-3">Temukan Makna dan Cerita di <br> Balik Setiap Perayaan Budaya.</h1>
        <p class="lead">Mulai perjalanan budaya kamu dengan pesan tiket mudah secara online.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4">Upcoming Events</h2>
        {{-- Tambahkan konten untuk upcoming events di sini --}}
    </div>
</section>

<section class="py-5" style="background-color: #4b2e18;">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between text-white">
        <div class="mb-4 mb-md-0">
            <img src="{{ asset('images/gambar1.png') }}" alt="Buat Event" class="img-fluid" style="max-height: 400px;">
        </div>
        <div class="text-md-start text-center ms-md-5">
            <h3 class="fw-bold mb-3">Buat Event</h3>
            <p class="mb-4">Kamu bisa mendaftar sebagai penyelenggara event kebudayaan.</p>
            <a href="{{ route('admin.register') }}" class="btn btn-warning text-white fw-bold px-4 py-2">Create Events</a>
        </div>
    </div>
</section>

{{-- Script untuk navbar scroll effect sudah dipindahkan ke layouts/app.blade.php --}}

@endsection

{{-- Notifikasi logout berhasil --}}
@if (session('logout_success'))
    <script>
        // Pakai SweetAlert kalau tersedia
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: 'Logout Berhasil!',
                text: '{{ session('logout_success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        } else {
            // Fallback ke alert biasa
            alert('{{ session('logout_success') }}');
        }
    </script>
@endif
