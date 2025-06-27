<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Midtrans\Transaction as MidtransTransaction;

class PurchaseController extends Controller
{
    public function form($ticket_id)
    {
        $selectedTicket = Ticket::with('event')->findOrFail($ticket_id);
        $event = $selectedTicket->event;
        $tickets = $event->tickets;

        return view('user.purchase', compact('event', 'tickets', 'selectedTicket'));
    }
public function pay(Request $request)
{
    try {
        $tickets = $request->input('tickets');

        if (!$tickets || !is_array($tickets)) {
            return response()->json(['message' => 'Invalid ticket data'], 400);
        }

        $items = [];
        $total = 0;
        $order_id = 'ORDER-' . uniqid();

        foreach ($tickets as $ticketId => $qty) {
            if ($qty <= 0) continue;
            $ticket = Ticket::findOrFail($ticketId);

            $items[] = [
                'id' => $ticket->id,
                'price' => $ticket->harga,
                'quantity' => $qty,
                'name' => $ticket->nama,
            ];

            $total += $ticket->harga * $qty;

            Transaction::create([
                'user_id' => auth()->id(),
                'event_id' => $ticket->event_id,
                'ticket_id' => $ticket->id,
                'order_id' => $order_id,
                'quantity' => $qty,
                'total_amount' => $ticket->harga * $qty,
                'status' => 'pending',
                'payment_method' => null,
                'transaction_id' => null,
                'snap_token' => null,
            ]);
        }

        if ($total <= 0) {
            return response()->json(['message' => 'Total invalid'], 400);
        }

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'item_details' => $items,
        ]);

      // Setelah Snap::getSnapToken(...)
Log::info('Snap Token Generated', [
    'snapToken' => $snapToken,
    'order_id' => $order_id,
    'total' => $total,
    'items' => $items
]);


        Transaction::where('order_id', $order_id)->update([
            'snap_token' => $snapToken
        ]);

        return response()->json(['snapToken' => $snapToken, 'order_id' => $order_id]);

    } catch (\Exception $e) {
        Log::error('Gagal membuat Snap Token Midtrans: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


public function notificationHandler(Request $request)
{
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';

    try {
        $notif = new notification();
        Log::info('🔥 Notifikasi diterima', (array) $notif);

        $transaction = Transaction::where('order_id', $notif->order_id)->first();
        if (!$transaction) {
            Log::warning('‼️ Transaksi tidak ditemukan', ['order_id' => $notif->order_id]);
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $status = $notif->transaction_status;
        $type = $notif->payment_type;
        $transaction_id = $notif->transaction_id;

        $status_update = match ($status) {
            'settlement', 'capture' => 'paid',
            'pending' => 'pending',
            'expire', 'cancel', 'deny' => 'cancelled',
            default => 'pending',
        };

        Transaction::where('order_id', $notif->order_id)->update([
            'status' => $status_update,
            'transaction_id' => $transaction_id,
            'payment_method' => $type,
        ]);

        Log::info('✅ Transaksi berhasil diupdate', [
            'order_id' => $notif->order_id,
            'status' => $status_update
        ]);

        return response()->json(['message' => 'Notification processed']);

    } catch (\Exception $e) {
        Log::error('❌ Gagal memproses notifikasi Midtrans: ' . $e->getMessage());
        return response()->json(['error' => 'Internal error'], 500);
    }
}



    public function checkout(Request $request)
    {
        $tickets = $request->input('tickets');

        if (!$tickets || !is_array($tickets)) {
            return redirect()->back()->with('error', 'Tidak ada tiket yang dipilih');
        }

        $selectedTickets = [];

        foreach ($tickets as $ticketId => $qty) {
            if ($qty <= 0) continue;

            $ticket = Ticket::findOrFail($ticketId);
            $ticket->jumlah = $qty;
            $selectedTickets[] = $ticket;
        }

        if (count($selectedTickets) === 0) {
            return redirect()->back()->with('error', 'Jumlah tiket tidak boleh kosong.');
        }

        return view('user.purchase', compact('selectedTickets'));
    }

    public function selectTicket($event_id)
    {
        $event = \App\Models\Event::with('tickets')->findOrFail($event_id);
        return view('user.tickets', compact('event'));
    }

public function detail($orderId)
{
    $transactions = Transaction::where('order_id', $orderId)
        ->where('user_id', auth()->id())
        ->get();

    if ($transactions->isEmpty()) {
        abort(404, 'Transaksi tidak ditemukan.');
    }

    $transactionForView = $transactions->first();

    try {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';

        $status = MidtransTransaction::status($orderId);

        if (!is_object($status)) {
            throw new \Exception('Respon Midtrans tidak valid');
        }

        $newStatus = match($status->transaction_status ?? 'pending') {
            'settlement', 'capture' => 'paid',
            'pending' => 'pending',
            'expire', 'cancel', 'deny' => 'cancelled',
            default => 'pending',
        };

        $paymentInfo = [];
        $paymentType = $status->payment_type ?? null;

        if ($paymentType === 'bank_transfer' && isset($status->va_numbers[0])) {
            $paymentInfo = [
                'bank' => $status->va_numbers[0]->bank ?? null,
                'va_number' => $status->va_numbers[0]->va_number ?? null,
            ];
        } elseif ($paymentType === 'qris' && isset($status->actions)) {
            $qrAction = collect($status->actions)->firstWhere('name', 'generate-qr-code');
            if ($qrAction) {
                $paymentInfo = ['qr_url' => $qrAction->url ?? null];
            }
        } elseif ($paymentType === 'echannel' && isset($status->bill_key)) {
            $paymentInfo = [
                'bank' => 'mandiri',
                'bill_key' => $status->bill_key ?? null,
                'biller_code' => $status->biller_code ?? null,
            ];
        } elseif (in_array($paymentType, ['gopay', 'shopeepay'])) {
            $paymentInfo = ['ewallet_type' => $paymentType];
        }

foreach ($transactions as $transaction) {
    $oldStatus = $transaction->status;

    // Paksa update walau status belum berubah
    $transaction->status = $newStatus;
    $transaction->payment_method = $paymentType;
    $transaction->transaction_id = $status->transaction_id ?? null;
    $transaction->payment_info = json_encode($paymentInfo);
    $transaction->save();

    // Kurangi stok hanya jika baru pertama kali dibayar
    if ($newStatus === 'paid' && $oldStatus !== 'paid') {
        $ticket = Ticket::find($transaction->ticket_id);
        if ($ticket && $ticket->stok >= $transaction->quantity) {
            $ticket->decrement('stok', $transaction->quantity);
        }
    }
}


    } catch (\Exception $e) {
        Log::error("Gagal ambil status Midtrans: " . $e->getMessage(), [
            'order_id' => $orderId,
        ]);
    }

    $transactionForView = Transaction::where('order_id', $orderId)
        ->where('user_id', auth()->id())
        ->first();

    return view('user.paymentdetail', [
        'transaction' => $transactionForView
    ]);
}

public function checkMidtransStatus($orderId)
{
    try {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';

        $status = MidtransTransaction::status($orderId);

        if (!is_object($status)) {
            Log::error("Status bukan object", ['response' => $status]);
            return response()->json(['error' => 'Status tidak valid'], 400);
        }

        $newStatus = match($status->transaction_status ?? 'pending') {
            'settlement', 'capture' => 'paid',
            'pending' => 'pending',
            'expire', 'cancel', 'deny' => 'cancelled',
            default => 'pending',
        };

        $paymentType = $status->payment_type ?? null;
        $paymentInfo = [];

        if ($paymentType === 'bank_transfer' && isset($status->va_numbers[0])) {
            $paymentInfo = [
                'bank' => $status->va_numbers[0]->bank ?? null,
                'va_number' => $status->va_numbers[0]->va_number ?? null,
            ];
        } elseif ($paymentType === 'qris' && isset($status->actions)) {
            $qr = collect($status->actions)->firstWhere('name', 'generate-qr-code');
            if ($qr) {
                $paymentInfo = ['qr_url' => $qr->url ?? null];
            }
        } elseif ($paymentType === 'echannel' && isset($status->bill_key)) {
            $paymentInfo = [
                'bank' => 'mandiri',
                'bill_key' => $status->bill_key ?? null,
                'biller_code' => $status->biller_code ?? null,
            ];
        } elseif (in_array($paymentType, ['gopay', 'shopeepay'])) {
            $paymentInfo = ['ewallet_type' => $paymentType];
        }

        $transactions = Transaction::where('order_id', $orderId)->get();

        foreach ($transactions as $transaction) {
            $transaction->status = $newStatus;
            $transaction->payment_method = $paymentType;
            $transaction->transaction_id = $status->transaction_id ?? null;
            $transaction->payment_info = json_encode($paymentInfo);
            $transaction->save();

            Log::info("✅ Updated transaction", [
                'id' => $transaction->id,
                'status' => $newStatus,
                'payment_type' => $paymentType,
            ]);
        }

        return response()->json([
            'message' => 'Status berhasil diperbarui.',
            'status' => $newStatus,
        ]);

    } catch (\Exception $e) {
        Log::error("Gagal checkMidtransStatus: " . $e->getMessage());
        return response()->json(['error' => 'Gagal ambil status'], 500);
    }
}


}
