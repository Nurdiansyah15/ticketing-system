<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketConversation;
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

        return back()->with('success', 'Tiket ditandai telah selesai.');
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
}
