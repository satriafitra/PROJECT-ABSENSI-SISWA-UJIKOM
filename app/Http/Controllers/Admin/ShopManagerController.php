<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlexibilityItem; // Sesuai Skema Database
use App\Models\UserToken;      // Untuk melihat siapa saja yang punya token
use Illuminate\Http\Request;

class ShopManagerController extends Controller
{
    /**
     * Menampilkan halaman Marketplace Manager
     */
    public function index()
    {
        // Mengambil semua item katalog
        $items = FlexibilityItem::latest()->get();
        
        // Opsional: Mengambil data token yang sedang aktif digunakan siswa
        $activeTokens = UserToken::with(['item', 'student'])
            ->where('status', 'AVAILABLE')
            ->latest()
            ->paginate(10);

        return view('admin.siswa-shop.index', compact('items', 'activeTokens'));
    }

    /**
     * Menyimpan item reward baru ke katalog (Requirement No. 3)
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'point_cost' => 'required|integer|min:1',
            'stock_limit' => 'nullable|integer',
        ]);

        // Sesuai Tabel flexibility_items
        FlexibilityItem::create([
            'item_name' => $request->item_name,
            'point_cost' => $request->point_cost,
            'stock_limit' => $request->stock_limit,
        ]);

        return back()->with('success', 'Item reward berhasil ditambahkan ke marketplace!');
    }

    /**
     * Menghapus item dari katalog
     */
    public function destroy($id)
    {
        $item = FlexibilityItem::findOrFail($id);
        
        // Cek jika sudah ada siswa yang beli, sebaiknya jangan dihapus tapi di-nonaktifkan
        // Namun untuk CRUD dasar, kita gunakan delete:
        $item->delete();

        return back()->with('success', 'Item berhasil dihapus dari katalog.');
    }
}