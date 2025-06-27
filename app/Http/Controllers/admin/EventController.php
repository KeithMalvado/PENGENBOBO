<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {

        $events = Event::with('tickets')
            ->where('admin_id', Auth::guard('admin')->id())
            ->latest()
            ->get();
            
        return view('admin.events.kelolaevent', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            'tickets' => 'required|array|min:1',
            'tickets.*.nama' => 'required|string|max:255',
            'tickets.*.stok' => 'required|integer|min:1',
            'tickets.*.harga' => 'required|numeric|min:0',
        ]);


        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('public/gambar-event');

            $gambarPath = str_replace('public/', '', $gambarPath);
        }

        $event = Event::create([
            'admin_id' => Auth::guard('admin')->id(), 
            'nama' => $request->nama,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath ?? null,
        ]);

        foreach ($request->tickets as $ticket) {
            $event->tickets()->create($ticket);
        }

        return redirect()->route('admin.events.kelolaevent')
            ->with('success', 'Event dan tiket berhasil ditambahkan.');
    }

    public function show($id)
    {
        $event = Event::with('tickets')
            ->where('admin_id', Auth::guard('admin')->id())
            ->findOrFail($id);
            
        return view('admin.events.detail', compact('event'));
    }

    public function edit($id)
    {
        $event = Event::with('tickets')->findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

public function update(Request $request, $id)
{
    $event = Event::where('admin_id', Auth::guard('admin')->id())
        ->findOrFail($id);

    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
        'lokasi' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'tickets' => 'nullable|array',
        'tickets.*.id' => 'nullable|exists:tickets,id',
        'tickets.*.nama' => 'required|string|max:255',
        'tickets.*.stok' => 'required|integer|min:1',
        'tickets.*.harga' => 'required|numeric|min:0',
        'new_tickets' => 'nullable|array',
        'new_tickets.*.nama' => 'required|string|max:255',
        'new_tickets.*.stok' => 'required|integer|min:1',
        'new_tickets.*.harga' => 'required|numeric|min:0',
    ]);

    if ($request->hasFile('gambar')) {
        if ($event->gambar) {
            Storage::disk('public')->delete($event->gambar);
        }
        $gambarPath = $request->file('gambar')->store('public/gambar-event');
        $gambarPath = str_replace('public/', '', $gambarPath);
        $event->gambar = $gambarPath;
    }

    $event->update([
        'nama' => $validated['nama'],
        'tanggal' => $validated['tanggal'],
        'jam_mulai' => $validated['jam_mulai'],
        'jam_selesai' => $validated['jam_selesai'],
        'lokasi' => $validated['lokasi'],
        'deskripsi' => $validated['deskripsi'] ?? null,
    ]);

    if ($request->filled('tickets')) {
        foreach ($request->tickets as $ticketData) {
            if (isset($ticketData['id'])) {
                $ticket = Ticket::where('event_id', $event->id)->find($ticketData['id']);
                if ($ticket) {
                    $ticket->update([
                        'nama' => $ticketData['nama'],
                        'stok' => $ticketData['stok'],
                        'harga' => $ticketData['harga'],
                    ]);
                }
            }
        }
    }


    if ($request->filled('new_tickets')) {
        foreach ($request->new_tickets as $newTicket) {
            $event->tickets()->create($newTicket);
        }
    }

    return redirect()->route('admin.events.kelolaevent')
        ->with('success', 'Event dan tiket berhasil diperbarui.');
}


    public function destroy($id)
    {
        $event = Event::where('admin_id', Auth::guard('admin')->id())
            ->findOrFail($id);

        if ($event->gambar) {
            Storage::disk('public')->delete($event->gambar);
        }

        $event->delete();

        return redirect()->route('admin.events.kelolaevent')
            ->with('success', 'Event berhasil dihapus.');
    }
}
