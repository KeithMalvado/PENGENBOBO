<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get counts for the logged-in admin
        $adminId = Auth::guard('admin')->id();

        $data = [
            'totalUsers' => User::count(),
            'totalEvents' => Event::where('admin_id', $adminId)->count(),
            'totalTransactions' => Transaction::whereHas('event', function($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })->count(),
            'totalRevenue' => Transaction::whereHas('event', function($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })->sum('total_amount')
        ];

        return view('admin.dashboard', $data);
    }
}