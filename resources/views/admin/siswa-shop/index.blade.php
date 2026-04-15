@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    :root {
        --primary: #FF8C00;
        --primary-hover: #e67e00;
        --primary-soft: #fff7ed;
        --background: #f8fafc;
        --surface: #ffffff;
        --border: #e2e8f0;
        --text-main: #0f172a;
        --text-muted: #64748b;
    }

    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: var(--background);
        color: var(--text-main);
    }

    .content-wrapper {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* --- Header Section --- */
    .page-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .page-title {
        font-weight: 800;
        font-size: 1.75rem;
        letter-spacing: -0.03em;
        margin-bottom: 0.25rem;
    }

    /* --- Action Card (Compact Form) --- */
    .action-card {
        background: var(--surface);
        border-radius: 20px;
        border: 1px solid var(--border);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        padding: 1.5rem;
        margin-bottom: 2.5rem;
        position: relative;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
        gap: 1rem;
        align-items: flex-end;
    }

    .input-group-custom {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .input-label-mini {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .custom-input {
        background: #fcfcfd;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 0.65rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        width: 100%;
    }

    .custom-input:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(255, 140, 0, 0.1);
        outline: none;
    }

    .btn-submit {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0 1.5rem;
        font-weight: 700;
        font-size: 0.85rem;
        height: 42px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 140, 0, 0.3);
    }

    /* --- Table Styling (Modern & Clean) --- */
    .table-container {
        background: var(--surface);
        border-radius: 24px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-table thead th {
        background: #f8fafc;
        padding: 1.25rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border);
        text-align: left;
    }

    .custom-table tbody tr {
        transition: background 0.2s;
    }

    .custom-table tbody tr:hover {
        background: #fafafa;
    }

    .custom-table tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-main);
        font-size: 0.9rem;
    }

    /* Column Specific Styles */
    .item-display {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: var(--primary-soft);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .item-name {
        font-weight: 700;
        display: block;
        margin-bottom: 0.1rem;
    }

    .item-desc {
        font-size: 0.75rem;
        color: var(--text-muted);
        display: block;
    }

    /* Status Badges */
    .badge-pill {
        padding: 6px 14px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .bg-indigo-soft { background: #eef2ff; color: #4338ca; }
    .bg-orange-soft { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .bg-green-soft { background: #f0fdf4; color: #15803d; }
    .bg-red-soft { background: #fef2f2; color: #b91c1c; }

    .action-btn {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        background: white;
        color: var(--text-muted);
        transition: all 0.2s;
        cursor: pointer;
    }

    .action-btn:hover {
        background: #fff1f2;
        color: #e11d48;
        border-color: #fda4af;
        transform: scale(1.05);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .form-grid {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }
</style>

<div class="content-wrapper">
    <header class="page-header">
        <div>
            <h1 class="page-title">Inventory Voucher</h1>
            <p class="text-muted">Kelola katalog item penukaran poin siswa secara real-time.</p>
        </div>
        <div class="badge-pill bg-indigo-soft">
            <i data-lucide="layers" size="14"></i>
            {{ $items->count() }} Total Item
        </div>
    </header>

    <div class="action-card">
        <form action="{{ route('admin.siswa-shop.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="input-group-custom">
                    <label class="input-label-mini">Nama Item</label>
                    <input type="text" name="item_name" class="custom-input" placeholder="Contoh: Tiket Kantin" required>
                </div>

                <div class="input-group-custom">
                    <label class="input-label-mini">Kategori</label>
                    <select name="category" class="custom-input">
                        <option value="Izin">Izin</option>
                        <option value="Reward" selected>Reward</option>
                        <option value="Fasilitas">Fasilitas</option>
                    </select>
                </div>

                <div class="input-group-custom">
                    <label class="input-label-mini">Icon</label>
                    <select name="icon" class="custom-input">
                        <option value="ticket">Ticket</option>
                        <option value="gift">Gift</option>
                        <option value="zap">Fast Pass</option>
                        <option value="coffee">Food</option>
                    </select>
                </div>

                <div class="input-group-custom">
                    <label class="input-label-mini">Poin</label>
                    <input type="number" name="point_cost" class="custom-input" placeholder="0" required>
                </div>

                <div class="input-group-custom">
                    <label class="input-label-mini">Stok</label>
                    <input type="number" name="stock_limit" class="custom-input" placeholder="∞">
                </div>

                <div class="input-group-custom">
                    <button type="submit" class="btn-submit">
                        <i data-lucide="plus-circle" size="18"></i>
                        Tambah
                    </button>
                </div>
            </div>
            
            <div class="input-group-custom mt-3">
                <label class="input-label-mini">Deskripsi</label>
                <input type="text" name="description" class="custom-input" placeholder="Jelaskan detail item di sini...">
            </div>
        </form>
    </div>

    <div class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th style="width: 40%">Detail Item</th>
                    <th>Kategori</th>
                    <th>Harga Tukar</th>
                    <th>Ketersediaan</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        <div class="item-display">
                            <div class="icon-wrapper">
                                <i data-lucide="{{ $item->icon }}" size="20"></i>
                            </div>
                            <div>
                                <span class="item-name">{{ $item->item_name }}</span>
                                <span class="item-desc">{{ Str::limit($item->description, 50) }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-pill bg-indigo-soft">{{ $item->category }}</span>
                    </td>
                    <td>
                        <span class="badge-pill bg-orange-soft">
                            <i data-lucide="database" size="12"></i>
                            {{ number_format($item->point_cost) }} Poin
                        </span>
                    </td>
                    <td>
                        @if($item->stock_limit === null)
                            <span class="text-muted fw-bold small" style="letter-spacing: 0.05em;">UNLIMITED</span>
                        @elseif($item->stock_limit > 0)
                            <span class="badge-pill bg-green-soft">
                                <i data-lucide="check-circle" size="12"></i>
                                {{ $item->stock_limit }} Stok
                            </span>
                        @else
                            <span class="badge-pill bg-red-soft">Habis</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <form action="{{ route('admin.siswa-shop.destroy', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn" onclick="return confirm('Hapus item ini?')">
                                <i data-lucide="trash-2" size="16"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <i data-lucide="inbox" size="40" class="mb-2" style="opacity: 0.2;"></i>
                            <p class="small">Belum ada item tersedia dalam katalog.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    // Inisialisasi icon lucide
    lucide.createIcons();
</script>
@endsection