$ticket = App\Models\Ticket::find(2);
try {
    $rating = App\Models\SatisfactionRating::create([
        'ticket_id' => $ticket->id,
        'score' => 5,
        'feedback' => 'Test'
    ]);
    
    $student = App\Models\Student::find($ticket->reporter_id);
    $pointsToAdd = 5;
    $student->points += $pointsToAdd;
    $student->save();
    
    App\Models\PointLedger::create([
        'student_id' => $student->id,
        'transaction_type' => 'reward',
        'amount' => $pointsToAdd,
        'current_balance' => $student->points,
        'description' => "Bonus feedback"
    ]);
    echo "Success\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
