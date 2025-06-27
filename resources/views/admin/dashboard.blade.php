@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Dashboard Admin</h1>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background-color: #795548;">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-person"></i> Total User</h5>
                    <p class="card-text fs-4">{{ number_format($totalUsers) }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background-color: #a1887f;">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-calendar-event"></i> Total Event</h5>
                    <p class="card-text fs-4">{{ number_format($totalEvents) }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background-color: #bcaaa4;">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-receipt"></i> Total Transaksi</h5>
                    <p class="card-text fs-4">{{ number_format($totalTransactions) }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background-color: #d7ccc8;">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-currency-dollar"></i> Total Pendapatan</h5>
                    <p class="card-text fs-4">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
