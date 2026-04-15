<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FlexibilityItem;
use App\Models\UserToken;
use App\Models\Student;
use Illuminate\Http\Request;

class ShopManagerController extends Controller
{
    // ================= GET MARKETPLACE =================
    public function apiIndex()
    {
        $items = FlexibilityItem::latest()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->item_name,
                'price' => $item->point_cost,
                'category' => $item->category,
                'description' => $item->description,

                // ICON DARI CATEGORY
                'icon' => match ($item->category) {
                    'Reward' => 'gift',
                    'Izin' => 'log-out',
                    'Fasilitas' => 'bolt',
                    default => 'ticket'
                },
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    // ================= REDEEM VOUCHER =================
    public function redeem(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'item_id' => 'required|exists:flexibility_items,id',
        ]);

        $item = FlexibilityItem::findOrFail($request->item_id);
        $student = Student::findOrFail($request->student_id);

        // ❌ CEK POIN
        if ($student->points < $item->point_cost) {
            return response()->json([
                'success' => false,
                'message' => 'Poin tidak cukup'
            ]);
        }

        // ❌ CEK STOK
        if ($item->stock_limit !== null && $item->stock_limit <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok habis'
            ]);
        }

        // ✅ KURANGI POIN
        $student->points -= $item->point_cost;
        $student->save();

        // ✅ KURANGI STOK (jika ada limit)
        if ($item->stock_limit !== null) {
            $item->stock_limit -= 1;
            $item->save();
        }

        // ✅ SIMPAN TRANSAKSI
        UserToken::create([
            'student_id' => $student->id,
            'item_id' => $item->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dibeli',
            'points' => $student->points,
            'item' => [
                'id' => $item->id,
                'name' => $item->item_name,
            ]
        ]);
    }
}