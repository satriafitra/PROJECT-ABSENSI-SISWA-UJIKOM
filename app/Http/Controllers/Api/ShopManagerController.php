<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FlexibilityItem;
use App\Models\UserToken;
use App\Models\Student;
use Illuminate\Http\Request;

class ShopManagerController extends Controller
{
    // ================= MARKETPLACE =================
    public function apiIndex()
    {
        $items = FlexibilityItem::latest()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->item_name,
                'price' => $item->point_cost,
                'category' => $item->category,
                'description' => $item->description,
                'theme' => strtolower($item->category),
                'is_voucher' => (bool) $item->is_voucher,
                'extra_minutes' => (int) $item->extra_minutes,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    // ================= MY VOUCHERS =================
    public function myVouchers($student_id)
    {
        $tokens = UserToken::with('item')
            ->where('student_id', $student_id)
            ->latest()
            ->get();

        $data = $tokens->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->item->item_name ?? '-',
                'category' => $t->item->category ?? '-',
                'description' => $t->item->description ?? '-',

                // 🔥 INI YANG BENAR
                'status' => $t->status,
                'attendance_id' => $t->used_at_attendance_id,

                'created_at' => optional($t->created_at)->format('d M Y'),

                'points_spent' => (int) (
                    $t->points_spent ??
                    $t->item->point_cost ??
                    0
                ),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ================= POINT HISTORY =================
    public function pointHistory($student_id)
    {
        $data = UserToken::with('item')
            ->where('student_id', $student_id)
            ->latest()
            ->get()
            ->map(function ($t) {
                return [
                    'label' => $t->item->item_name ?? '-',
                    'points' => $t->points_spent ?? $t->item->point_cost ?? 0,
                    'date' => optional($t->created_at)->format('d M'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // ================= USE VOUCHER =================
    public function useVoucher(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:user_tokens,id',
            'student_id' => 'required|exists:students,id',
        ]);

        $voucher = UserToken::where('id', $request->voucher_id)
            ->where('student_id', $request->student_id)
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan'
            ]);
        }

        // 🔥 FIX: pakai status ENUM
        if ($voucher->status !== 'AVAILABLE') {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak tersedia'
            ]);
        }

        $voucher->update([
            'status' => 'USED'
        ]);

        return response()->json([
            'success' => true,
            'message' => '1 token telah diaktifkan'
        ]);
    }

    // ================= REDEEM =================
    public function redeem(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'item_id' => 'required|exists:flexibility_items,id',
        ]);

        $item = FlexibilityItem::findOrFail($request->item_id);
        $student = Student::findOrFail($request->student_id);

        // cek poin
        if ($student->points < $item->point_cost) {
            return response()->json([
                'success' => false,
                'message' => 'Poin tidak cukup'
            ]);
        }

        // cek stok
        if ($item->stock_limit !== null && $item->stock_limit <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok habis'
            ]);
        }

        // kurangi poin
        $student->decrement('points', $item->point_cost);

        // kurangi stok
        if ($item->stock_limit !== null) {
            $item->decrement('stock_limit');
        }

        // 🔥 FIX: simpan sesuai ENUM DB
        UserToken::create([
            'student_id' => $student->id,
            'item_id' => $item->id,
            'points_spent' => $item->point_cost,
            'status' => 'AVAILABLE',
            'used_at_attendance_id' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dibeli',
            'points' => $student->points
        ]);
    }
}
