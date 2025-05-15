<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    // Method untuk User

    /**
     * Menampilkan daftar tiket user.
     */
    public function index(Request $request)
    {
        // Query dasar
        $tickets = Ticket::where('user_id', Auth::user()->id);

        // Search global (judul dan status)
        if ($request->has('search')) {
            $search = $request->search;
            $tickets->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        // Sorting per kolom
        if ($request->has('sort')) {
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';
            $tickets->orderBy($request->sort, $direction);
        } else {
            $tickets->orderBy('created_at', 'desc'); // Default sorting
        }

        // Pagination
     $perPage = $request->has('perPage') ? $request->perPage : 10;
     $tickets = $tickets->paginate($perPage);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Menampilkan form pembuatan tiket.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Menyimpan tiket baru.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Simpan tiket baru
        Auth::user()->tickets()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'open', // Status default
        ]);

        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil dibuat!');
    }

    /**
     * Menampilkan detail tiket.
     */
    public function show(Ticket $ticket)
    {
        // Pastikan user hanya bisa melihat tiketnya sendiri
        Gate::authorize('view', $ticket);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Menampilkan form edit tiket.
     */
    public function edit(Ticket $ticket)
    {
        // Pastikan user hanya bisa mengedit tiketnya sendiri
        Gate::authorize('update', $ticket);
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Mengupdate tiket.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Pastikan user hanya bisa mengedit tiketnya sendiri
        Gate::authorize('update', $ticket);

        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Update tiket
        $ticket->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil diperbarui!');
    }

    // Method untuk Admin

    /**
     * Menampilkan daftar semua tiket (hanya admin).
     */
    public function adminIndex(Request $request)
    {
        // Gunakan query builder agar bisa mengelola sorting dan filtering dengan lebih fleksibel
        $query = Ticket::query()->with('user');

        // Search global (judul dan status)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        // Sorting per kolom
        if ($request->has('sort')) {
            $sortColumn = $request->sort;
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';

            // Cek apakah user mencoba sorting berdasarkan kolom di tabel `users`
            if ($sortColumn === 'username') {
                $query->join('users', 'tickets.user_id', '=', 'users.id')
                      ->select('tickets.*', 'users.name as user_name')
                      ->orderBy('users.name', $direction);
            } else {
                $query->orderBy($sortColumn, $direction);
            }
        } else {
            $query->orderBy('created_at', 'desc'); // Default sorting
        }

        // Pagination
        $perPage = $request->get('perPage', 10);
        $tickets = $query->paginate($perPage);

        return view('admin.tickets.index', compact('tickets'));
    }


      /**
     * Menampilkan detail tiket.
     */
    public function adminShow(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    /**
     * Mengupdate status tiket (hanya admin).
     */
    public function adminUpdate(Request $request, Ticket $ticket)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        // Update status tiket
        $ticket->update(['status' => $request->status]);

        return redirect()->route('admin.tickets.index')->with('success', 'Status tiket berhasil diperbarui!');
    }

    /**
     * Menghapus tiket (hanya admin).
     */
    public function adminDestroy(Ticket $ticket)
    {
        // Hapus tiket
        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Tiket berhasil dihapus!');
    }
}
