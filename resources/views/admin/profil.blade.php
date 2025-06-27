@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm border-0 rounded-4 p-4">
                <div class="text-center">
                    <i class="bi bi-person-circle" style="font-size: 5rem; color: #7B5E2A;"></i>
                    <h3 class="mt-3 mb-0">{{ auth('admin')->user()->name }}</h3>
                    <p class="text-muted mb-4">Admin Eventara</p>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Email</div>
                    <div class="col-sm-8">{{ auth('admin')->user()->email }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Telepon</div>
                    <div class="col-sm-8">
                        {{ auth('admin')->user()->phone ?? 'Belum diisi' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-4 fw-semibold">Organisasi</div>
                    <div class="col-sm-8">
                        {{ auth('admin')->user()->organization ?? 'Belum diisi' }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
