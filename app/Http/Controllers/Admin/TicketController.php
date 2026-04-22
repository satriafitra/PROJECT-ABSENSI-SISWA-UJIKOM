<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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
        
        // KPI Average Response Time Per Operator (Sederhana)
        // Mencari selisih waktu antara pembuatan tiket dengan balasan pertama operator
        $responses = DB::table('ticket_responses')
            ->join('tickets', 'ticket_responses.ticket_id', '=', 'tickets.id')
            ->join('users', 'ticket_responses.sender_id', '=', 'users.id')
            ->select(
                'users.name as operator_name',
                DB::raw('AVG(TIMESTAMPDIFF(MINUTE, tickets.created_at, ticket_responses.created_at)) as avg_response_minutes')
            )
            ->where('ticket_responses.sender_type', 'user')
            ->whereRaw('ticket_responses.id = (SELECT MIN(id) FROM ticket_responses tr WHERE tr.ticket_id = tickets.id AND tr.sender_type = "user")')
            ->groupBy('users.id', 'users.name')
            ->get();
        // Rating Distribution
        $ratingDistribution = DB::table('satisfaction_ratings')
            ->select('score', DB::raw('count(*) as total'))
            ->groupBy('score')
            ->orderBy('score', 'desc')
            ->pluck('total', 'score')
            ->toArray();

        // Siapkan array 5 sampai 1 untuk chart
        $ratings = [];
        $totalRatings = 0;
        for ($i = 5; $i >= 1; $i--) {
            $ratings[$i] = $ratingDistribution[$i] ?? 0;
            $totalRatings += $ratings[$i];
        }
        
        $averageRating = DB::table('satisfaction_ratings')->avg('score') ?? 0;

        // Top Active Students
        $topStudents = Student::withCount('tickets')
            ->orderBy('tickets_count', 'desc')
            ->having('tickets_count', '>', 0)
            ->take(5)
            ->get();
        
        return view('admin.tickets.index', compact('tickets', 'totalOpen', 'totalInProgress', 'totalClosed', 'status', 'responses', 'ratings', 'topStudents', 'averageRating', 'totalRatings'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $students = Student::orderBy('name')->get();
        return view('admin.tickets.create', compact('students'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reporter_id' => 'required|exists:students,id',
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:Low,Mid,High',
            'description' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'reporter_id' => $request->reporter_id,
            'subject' => $request->subject,
            'priority' => $request->priority,
            'description' => $request->description,
            'status' => 'Open',
        ]);

        return redirect()->route('admin.tickets.index')->with('success', 'Tiket berhasil dibuat untuk siswa tersebut.');
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
