<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\SatisfactionRating;
use App\Models\Student;
use App\Models\PointLedger;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Get list of tickets for a student
     */
    public function index(Request $request)
    {
        $studentId = $request->input('student_id');
        
        $tickets = Ticket::where('reporter_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $tickets
        ]);
    }

    /**
     * Check for duplicate tickets before creating
     */
    public function checkDuplicate(Request $request)
    {
        $subject = $request->input('subject');
        $description = $request->input('description');
        
        // Simple full text search implementation
        $duplicates = Ticket::where('status', 'Open')
            ->whereRaw("MATCH(subject, description) AGAINST(? IN NATURAL LANGUAGE MODE)", [$subject . ' ' . $description])
            ->get();
            
        return response()->json([
            'status' => 'success',
            'has_duplicate' => $duplicates->count() > 0,
            'duplicates' => $duplicates
        ]);
    }

    /**
     * Create a new ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Mid,High',
        ]);
        
        $ticket = Ticket::create([
            'reporter_id' => $request->student_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Open',
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Tiket berhasil dibuat',
            'data' => $ticket
        ]);
    }

    /**
     * Get ticket detail and responses
     */
    public function show($id)
    {
        $ticket = Ticket::with(['responses' => function($q) {
            $q->orderBy('created_at', 'asc');
        }, 'rating'])->find($id);
        
        if (!$ticket) {
            return response()->json(['status' => 'error', 'message' => 'Tiket tidak ditemukan'], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $ticket
        ]);
    }

    /**
     * Student replies to ticket
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'message' => 'required|string'
        ]);
        
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['status' => 'error', 'message' => 'Tiket tidak ditemukan'], 404);
        }
        
        $response = TicketResponse::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $request->student_id,
            'sender_type' => 'student',
            'message' => $request->message,
            'is_auto_reply' => false
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Balasan terkirim',
            'data' => $response
        ]);
    }

    /**
     * Rate a ticket and add points
     */
    public function rate(Request $request, $id)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string'
        ]);
        
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['status' => 'error', 'message' => 'Tiket tidak ditemukan'], 404);
        }
        
        if ($ticket->status !== 'Closed') {
            return response()->json(['status' => 'error', 'message' => 'Tiket belum selesai'], 400);
        }
        
        // Cek jika sudah ada rating
        if ($ticket->rating) {
            return response()->json(['status' => 'error', 'message' => 'Tiket sudah dinilai'], 400);
        }
        
        DB::beginTransaction();
        try {
            // 1. Simpan Rating
            $rating = SatisfactionRating::create([
                'ticket_id' => $ticket->id,
                'score' => $request->score,
                'feedback' => $request->feedback
            ]);
            
            // 2. Beri Poin +5
            $student = Student::find($ticket->reporter_id);
            $pointsToAdd = 5;
            $student->points += $pointsToAdd;
            $student->save();
            
            // 3. Catat di PointLedger
            PointLedger::create([
                'student_id' => $student->id,
                'transaction_type' => 'EARN',
                'amount' => $pointsToAdd,
                'current_balance' => $student->points,
                'description' => "Bonus feedback tiket aduan #{$ticket->id}"
            ]);
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Terima kasih atas feedback Anda! Anda mendapatkan 5 poin.',
                'points_earned' => 5
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Rating Error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
