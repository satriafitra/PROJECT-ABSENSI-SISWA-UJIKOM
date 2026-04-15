<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PointLedger;
use App\Models\FlexibilityItem;
use App\Models\UserToken;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GamificationApiController extends Controller
{
    // Lihat Katalog Shop
    public function getShopItems()
    {
        return response()->json([
            'status' => true,
            'data' => FlexibilityItem::all()
        ]);
    }

    // Tukar Poin (Beli Voucher)
    public function purchaseItem(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'item_id' => 'required|exists:flexibility_items,id',
        ]);

        $student = Student::find($request->student_id);
        $item = FlexibilityItem::find($request->item_id);

        // Hitung saldo terakhir dari Ledger
        $lastLedger = PointLedger::where('student_id', $student->id)->latest('id')->first();
        $currentBalance = $lastLedger ? $lastLedger->current_balance : 0;

        if ($currentBalance < $item->point_cost) {
            return response()->json(['status' => false, 'message' => 'Poin tidak mencukupi'], 400);
        }

        return DB::transaction(function () use ($student, $item, $currentBalance) {
            // 1. Kurangi poin di Ledger
            PointLedger::create([
                'student_id' => $student->id,
                'transaction_type' => 'SPEND',
                'amount' => -$item->point_cost,
                'current_balance' => $currentBalance - $item->point_cost,
                'description' => "Menukarkan poin dengan " . $item->item_name
            ]);

            // 2. Tambah Token ke Inventory Siswa
            UserToken::create([
                'student_id' => $student->id,
                'item_id' => $item->id,
                'status' => 'AVAILABLE'
            ]);

            return response()->json(['status' => true, 'message' => 'Voucher berhasil ditukarkan!']);
        });
    }

    // Riwayat Mutasi Poin
    public function getPointHistory($student_id)
    {
        $history = PointLedger::where('student_id', $student_id)->latest()->get();
        return response()->json(['status' => true, 'data' => $history]);
    }
}