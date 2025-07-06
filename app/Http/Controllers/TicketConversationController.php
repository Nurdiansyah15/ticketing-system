<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketConversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TicketConversationController extends Controller
{
    /**
     * Menyimpan percakapan baru di tiket (user atau admin).
     */
    public function store(Request $request, Ticket $ticket)
    {
        // // Otorisasi akses tiket
        // Gate::authorize('view', $ticket);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'message' => 'required|string',
        ]);

        // Simpan percakapan
        TicketConversation::create([
            'ticket_id'   => $ticket->id,
            'user_id'     => $user->id,
            'message'     => $request->message,
            'sender_type' => $user->role,
        ]);

        // Update status tiket berdasarkan role
        if ($user->role === 'admin') {
            $ticket->update(['status' => 'waiting_for_answer']);
        } else {
            $ticket->update(['status' => 'queue']);
        }

        $openTicketExists = Ticket::where('status', 'open')->exists();

        if (!$openTicketExists) {
            Ticket::where('status', 'queue')
                ->orderBy('created_at')
                ->first()?->update(['status' => 'open']);
        }

        return back()->with('success', 'Pesan berhasil dikirim.');
    }

    public function start(Request $request, Ticket $ticket)
    {
        // Gate::authorize('update', $ticket);

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'on_going']);
        }

        return back()->with('success', 'Percakapan dimulai.');
    }



    /**
     * Admin: Ajukan resolusi ke user
     */
    public function askResolved(Request $request, Ticket $ticket)
    {
        // Gate::authorize('update', $ticket);

        $user = Auth::user();

        TicketConversation::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => 'Apakah masalah ini sudah selesai?',
            'sender_type' => 'admin',
        ]);

        $ticket->update(['status' => 'waiting_for_resolved']);

        if ($user->role === 'admin') {
            Ticket::where('status', 'queue')
                ->orderBy('created_at')
                ->first()?->update(['status' => 'open']);
        }

        return back()->with('success', 'Permintaan penyelesaian telah dikirim.');
    }


    /**
     * User: Menyatakan tiket telah selesai
     */
    public function markResolved(Request $request, Ticket $ticket)
    {
        // Gate::authorize('update', $ticket);

        $ticket->update(['status' => 'resolved']);

        return redirect()->route('tickets.rating.show', $ticket->id)
            ->with('success', 'Tiket ditandai telah selesai. Silakan beri penilaian.');
    }

    /**
     * User: Belum selesai, berikan feedback tambahan
     */
    public function notResolved(Request $request, Ticket $ticket)
    {
        // Gate::authorize('update', $ticket);

        $request->validate([
            'message' => 'required|string',
        ]);

        TicketConversation::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Kembalikan status ke queue
        $ticket->update(['status' => 'queue']);

        return back()->with('success', 'Masukan tambahan telah dikirim.');
    }

    public function showRating(Ticket $ticket)
    {
        // Misal teknisi yang menangani tiket disimpan di kolom `handled_by`
        $user = User::find($ticket->user_id); // sesuaikan nama field-nya

        return view('tickets.rating', compact('ticket', 'user'));
    }

    public function submitRating(Request $request, Ticket $ticket)
    {
        // Validasi input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Simpan rating ke kolom admin_rating atau rating lain
        $ticket->update([
            'admin_rating' => $validated['rating'],
        ]);

        // Hitung ulang average rating untuk admin ini
        $adminId = $ticket->admin_id;

        $adminTickets = \App\Models\Ticket::where('admin_id', $adminId)
            ->where('admin_rating', '>', 0)
            ->get();

        $totalRating = $adminTickets->sum('admin_rating');
        $totalRatedTickets = $adminTickets->count();

        $averageRating = $totalRatedTickets > 0 ? $totalRating / $totalRatedTickets : 0;

        // Update rating di tabel user
        \App\Models\User::where('id', $adminId)->update([
            'rating' => round($averageRating, 2), // Bisa dibulatkan jika perlu
        ]);

        // Redirect kembali atau ke halaman lain
        return redirect()
            ->route('tickets.index') // atau route lain seperti 'tickets.index'
            ->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
