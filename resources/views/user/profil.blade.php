@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Profil Pengguna</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Nama Lengkap:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>

        </div>
    </div>
</div>
@endsection
