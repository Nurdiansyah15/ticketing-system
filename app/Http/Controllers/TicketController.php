<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $tickets = Ticket::where('user_id', Auth::user()->id)->where('status', '!=', 'resolved');

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
            // Kalau kolom sorting-nya 'created_at', defaultnya ascending (terlama ke terbaru)
            if ($request->sort === 'created_at') {
                $direction = $request->direction === 'desc' ? 'desc' : 'asc';
            } else {
                // Sorting untuk kolom lain defaultnya descending
                $direction = $request->direction === 'asc' ? 'asc' : 'desc';
            }
            $tickets->orderBy($request->sort, $direction);
        } else {
            // Default sorting berdasarkan created_at ASC (terlama ke terbaru)
            $tickets->orderBy('created_at', 'asc');
        }

        // Pagination
        $perPage = $request->has('perPage') ? $request->perPage : 10;
        $tickets = $tickets->paginate($perPage);

        $resolvedTickets = Ticket::where('user_id', Auth::user()->id)
            ->where('status', 'resolved')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('tickets.index', compact('tickets', 'resolvedTickets'));
    }


    /**
     * Menampilkan form pembuatan tiket.
     */
    public function create()
    {
        $admins = User::where('role', 'admin')->get();

        // Ambil jumlah tiket queue per admin dalam bentuk array [admin_id => count]
        $queueCounts = Ticket::whereIn('status', ['queue', 'open'])
            ->select('admin_id', DB::raw('COUNT(*) as total'))
            ->groupBy('admin_id')
            ->pluck('total', 'admin_id'); // hasil: [admin_id => total_queue]

        // Tambahkan nilai queue_count ke setiap object admin
        $admins->map(function ($admin) use ($queueCounts) {
            $admin->queue_count = $queueCounts[$admin->id] ?? 0;
            return $admin;
        });

        return view('tickets.create', compact('admins'));
    }
    /**
     * Menyimpan tiket baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'admin_id' => 'required|exists:users,id',
        ]);


        $hasOpenOrOngoing = \App\Models\Ticket::where('admin_id', $request->admin_id)
            ->whereIn('status', ['open', 'on_going'])
            ->exists();

        $status = $hasOpenOrOngoing ? 'queue' : 'open';


        $ticket = Ticket::create([
            'user_id' => Auth::id(),              // User yang membuat tiket
            'admin_id' => intval($request->admin_id),     // Admin yang dipilih
            'title' => $request->title,
            'description' => $request->description,
            'status' => $status,
        ]);

        Notification::create([
            'user_id' => $request->admin_id,
            'message' => 'Tiket baru telah dibuat.',
            'ticket_id' => $ticket->id,
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
        // Query builder untuk tiket yang **belum resolved**
        $query = Ticket::query()
            ->with('user')
            ->where('status', '!=', 'resolved')
            ->where('admin_id', Auth::id());

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

            // Cek sorting berdasarkan kolom di tabel users
            if ($sortColumn === 'username') {
                $query->join('users', 'tickets.user_id', '=', 'users.id')
                    ->select('tickets.*', 'users.name as user_name')
                    ->orderBy('users.name', $direction);
            } else {
                $query->orderBy($sortColumn, $direction);
            }
        } else {
            $query->orderBy('created_at', 'asc'); // Default sorting
        }

        // Pagination
        $perPage = $request->get('perPage', 10);
        $tickets = $query->paginate($perPage);

        // Query tiket yang sudah resolved (untuk tabel baru)
        $resolvedTickets = Ticket::where('status', 'resolved')
            ->where('admin_id', Auth::id()) // Hanya tiket yang ditangani oleh admin login
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return view('admin.tickets.index', compact('tickets', 'resolvedTickets'));
    }



    /**
     * Menampilkan detail tiket.
     */
    public function adminShow(Ticket $ticket)
    {
        if ($ticket->status === 'queue') {
            abort(403, 'Tiket belum bisa dibuka karena masih dalam antrean.');
        }

        return view('admin.tickets.show', compact('ticket'));
    }


    /**
     * Mengupdate status tiket (hanya admin).
     */
    public function adminUpdate(Request $request, Ticket $ticket)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|in:open,on_going,resolved,closed',
        ]);

        // Update status tiket
        $ticket->update(['status' => $request->status]);

        return redirect()->route('admin.tickets.index')->with('success', 'Status tiket berhasil diperbarui!');
    }

    /**
     * Memulai tiket (ubah status dari open ke on_going) — hanya admin.
     */
    public function adminStart(Ticket $ticket)
    {
        // Pastikan hanya tiket dengan status 'open' yang bisa dimulai
        if ($ticket->status !== 'open') {
            return back()->with('error', 'Hanya tiket dengan status "open" yang bisa dimulai.');
        }

        // Ubah status tiket menjadi 'on_going'
        $ticket->update(['status' => 'on_going']);

        return redirect()->route('admin.tickets.index')->with('success', 'Tiket berhasil dimulai.');
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
