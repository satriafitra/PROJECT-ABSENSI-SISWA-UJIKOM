<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlexibilityItem;
use App\Models\UserToken;
use Illuminate\Http\Request;
use App\Models\Student;

class ShopManagerController extends Controller
{
    public function index()
    {
        $items = FlexibilityItem::latest()->get();

        // Statistik untuk Dashboard Shop
        $stats = [
            'total_items'    => $items->count(),
            'out_of_stock'   => $items->where('stock_limit', 0)->count(),
            'total_redeemed' => UserToken::count(), // Total voucher yang sudah dibeli siswa
        ];

        return view('admin.siswa-shop.index', compact('items', 'stats'));
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'item_name'   => 'required|string|max:255',
            'category'    => 'required|string',
            'point_cost'  => 'required|integer|min:1',
            'stock_limit' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        FlexibilityItem::create($validData);

        return back()->with('success', 'Voucher berhasil dipublikasi ke marketplace!');
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'item_id' => 'required',
        ]);

        $item = FlexibilityItem::findOrFail($request->item_id);
        $student = Student::findOrFail($request->student_id);

        if ($student->points < $item->point_cost) {
            return response()->json([
                'success' => false,
                'message' => 'Poin tidak cukup'
            ]);
        }

        // Kurangi poin
        $student->points -= $item->point_cost;
        $student->save();

        // Simpan transaksi (opsional tapi bagus)
        UserToken::create([
            'student_id' => $student->id,
            'item_id' => $item->id,
            'status' => 'AVAILABLE'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dibeli',
            'points' => $student->points
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = FlexibilityItem::findOrFail($id);

        $validData = $request->validate([
            'item_name'   => 'required|string|max:255',
            'point_cost'  => 'required|integer|min:1',
            'stock_limit' => 'nullable|integer',
        ]);

        $item->update($validData);

        return back()->with('success', 'Data voucher berhasil diperbarui!');
    }

    public function destroy($id)
    {
        FlexibilityItem::findOrFail($id)->delete();
        return back()->with('success', 'Voucher telah dihapus dari katalog.');
    }
}
