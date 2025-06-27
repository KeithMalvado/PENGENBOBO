<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Ticket;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $lokasi = Event::select('lokasi')
            ->distinct()
            ->whereNotNull('lokasi')
            ->pluck('lokasi');


        $query = Event::with(['tickets'])
            ->when($request->search, function($q) use ($request) {
                return $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('lokasi', 'like', '%' . $request->search . '%');
            })
            ->when($request->lokasi, function($q) use ($request) {
                return $q->where('lokasi', $request->lokasi);
            })
            ->when($request->tanggal, function($q) use ($request) {
                return $q->whereDate('tanggal', $request->tanggal);
            })
            ->orderBy('tanggal', 'asc');

        $totalEvents = $query->count();
        $events = $query->paginate(12)->withQueryString();

        return view('user.events', compact('events', 'totalEvents', 'lokasi'));
    }



    public function show($id)
    {
        $event = Event::with('admin')->findOrFail($id);
        $tickets = Ticket::where('event_id', $id)->get();

        return view('user.detailevents', compact('event', 'tickets'));
    }



}