<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class ReportController extends Controller
{
   /**
     * Menampilkan halaman laporan pengaduan.
     */
    public function index(Request $request)
    {
        $query = Ticket::with('user');

        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $tickets = $query->get();

        return view('admin.reports.index', compact('tickets'));
    }


    /**
     * Mencetak laporan pengaduan dalam format PDF.
     */
    public function print(Request $request)
    {
        // Validasi input range tanggal
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Ambil data pengaduan berdasarkan range tanggal
        $tickets = Ticket::with('user')
            ->whereBetween('created_at', [$request->start_date, $request->end_date])
            ->get();

        // Tanggal export
        $exportDate = now()->format('d M Y H:i');

        // Load view untuk PDF dengan tambahan startDate dan endDate
        $pdf = Pdf::loadView('admin.reports.print', [
            'tickets' => $tickets,
            'exportDate' => $exportDate,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date
        ]);

        // Download PDF
        return $pdf->download('laporan-pengaduan.pdf');
    }
}
