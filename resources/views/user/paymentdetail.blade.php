@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-4">Detail Pembayaran</h3>

    <div class="card">
        <div class="card-body">
            <p><strong>Order ID:</strong> {{ $transaction->order_id }}</p>
            <p><strong>Nama Event:</strong> {{ $transaction->event->nama ?? '-' }}</p>
            <p><strong>Nama Tiket:</strong> {{ $transaction->ticket->nama ?? '-' }}</p>
            <p><strong>Jumlah:</strong> {{ $transaction->quantity }}</p>
            <p><strong>Total Bayar:</strong> Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
            <p><strong>Status:</strong>
                @if($transaction->status === 'paid')
                    <span class="badge bg-success">Sudah Dibayar</span>
                @elseif($transaction->status === 'pending')
                    <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                @else
                    <span class="badge bg-danger">Dibatalkan</span>
                @endif
            </p>
            <p><strong>Metode Pembayaran:</strong> {{ ucfirst($transaction->payment_method ?? '-') }}</p>

            {{-- Informasi tambahan --}}
            @if(is_array($transaction->payment_info))
                @if(isset($transaction->payment_info['bank']))
                    <p><strong>Bank:</strong> {{ strtoupper($transaction->payment_info['bank']) }}</p>
                @endif
                @if(isset($transaction->payment_info['va_number']))
                    <p><strong>Nomor Virtual Account:</strong> {{ $transaction->payment_info['va_number'] }}</p>
                @endif
                @if(isset($transaction->payment_info['bill_key']))
                    <p><strong>Bill Key:</strong> {{ $transaction->payment_info['bill_key'] }}</p>
                @endif
                @if(isset($transaction->payment_info['biller_code']))
                    <p><strong>Biller Code:</strong> {{ $transaction->payment_info['biller_code'] }}</p>
                @endif
                @if(isset($transaction->payment_info['qr_url']))
                    <p><strong>QR Code:</strong><br>
                        <img src="{{ $transaction->payment_info['qr_url'] }}" alt="QRIS" width="200">
                    </p>
                @endif
            @endif
        </div>
    </div>

    @if($transaction->status === 'pending')
        <div class="alert alert-info mt-4">
            <h5 class="mb-2">Silakan selesaikan pembayaran Anda.</h5>
            <p>Waktu pembayaran tersisa: <span id="countdown">15:00</span></p>
            <p><strong>Catatan:</strong> Jika waktu habis dan pembayaran tidak dilakukan, transaksi akan otomatis dibatalkan.</p>
        </div>

        <form method="GET" action="{{ route('user.purchase.detail', $transaction->order_id) }}">
            <button type="submit" class="btn btn-primary mt-2">Cek Status Pembayaran</button>
        </form>
    @endif

    <a href="{{ route('user.events') }}" class="btn btn-secondary mt-3">Kembali ke Event</a>
</div>

@if($transaction->status === 'pending')
<script>
    let countdown = 15 * 60;
    const countdownElement = document.getElementById('countdown');
    const interval = setInterval(() => {
        const minutes = Math.floor(countdown / 60);
        const seconds = countdown % 60;
        countdownElement.textContent =
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        if (countdown <= 0) {
            clearInterval(interval);
            countdownElement.textContent = "00:00";
        }
        countdown--;
    }, 1000);
</script>
@endif
@endsection
