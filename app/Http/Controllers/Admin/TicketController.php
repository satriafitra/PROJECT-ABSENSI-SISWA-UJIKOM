<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the tickets.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        $query = Ticket::with('reporter');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        // Urutkan berdasarkan prioritas (High > Mid > Low) dan tanggal
        $tickets = $query->orderByRaw("FIELD(priority, 'High', 'Mid', 'Low')")
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);
                         
        // Analytics
        $totalOpen = Ticket::where('status', 'Open')->count();
        $totalInProgress = Ticket::where('status', 'In-Progress')->count();
        $totalClosed = Ticket::where('status', 'Closed')->count();
        
        return view('admin.tickets.index', compact('tickets', 'totalOpen', 'totalInProgress', 'totalClosed', 'status'));
    }

    /**
     * Display the specified ticket.
     */
    public function show($id)
    {
        $ticket = Ticket::with(['reporter', 'responses', 'rating'])->findOrFail($id);
        
        // Saran balasan otomatis jika masih open
        $suggestedReply = '';
        if ($ticket->status !== 'Closed') {
            $text = strtolower($ticket->subject . ' ' . $ticket->description);
            if (strpos($text, 'poin') !== false) {
                $suggestedReply = 'Halo, terkait masalah poin Anda sedang kami tindak lanjuti ke sistem pusat.';
            } elseif (strpos($text, 'scan') !== false || strpos($text, 'qr') !== false) {
                $suggestedReply = 'Halo, pastikan pencahayaan saat melakukan scan cukup terang dan lensa kamera bersih.';
            } else {
                $suggestedReply = 'Halo, keluhan Anda sudah kami terima dan sedang dalam pemeriksaan. Mohon menunggu informasi selanjutnya.';
            }
        }

        return view('admin.tickets.show', compact('ticket', 'suggestedReply'));
    }

    /**
     * Reply to the ticket
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'status' => 'nullable|in:Open,In-Progress,Closed'
        ]);

        $ticket = Ticket::findOrFail($id);
        
        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'sender_id' => Auth::id() ?? 1, // fallback ke 1 jika tidak ada auth
            'sender_type' => 'user', // admin/guru
            'message' => $request->message,
            'is_auto_reply' => false
        ]);
        
        if ($request->has('status') && $request->status != $ticket->status) {
            $ticket->update(['status' => $request->status]);
        } elseif ($ticket->status === 'Open') {
            // Otomatis pindah ke In-Progress kalau admin membalas
            $ticket->update(['status' => 'In-Progress']);
        }

        return redirect()->route('admin.tickets.show', $ticket->id)->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Update the status of the ticket
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Open,In-Progress,Closed'
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        return redirect()->route('admin.tickets.show', $ticket->id)->with('success', 'Status tiket berhasil diperbarui.');
    }
}
