@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    #vouch-page-wrapper {
        --primary: #FF8C00;
        --primary-soft: #fff7ed;
        --secondary: #64748b;
        --bg-site: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        background-color: var(--bg-site);
        min-height: 100vh;
        color: #1e293b;
        padding: 1.5rem;
    }

    #vouch-page-wrapper h1, #vouch-page-wrapper h3, #vouch-page-wrapper h4 {
        margin: 0; line-height: 1.2; color: #0f172a;
    }

    .v-grid-main {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 2.5rem;
        margin-bottom: 3rem;
    }

    @media (max-width: 992px) {
        .v-grid-main { grid-template-columns: 1fr; }
    }

    .lux-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
    }

    /* VOUCHER DESIGN WITH TICKET PUNCH EFFECT */
    .preview-zone {
        background: #ffffff;
        border: 2px dashed #e2e8f0;
        border-radius: 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        position: sticky;
        top: 20px;
    }

    .voucher-main {
        width: 100%;
        max-width: 420px;
        height: 160px;
        border-radius: 20px;
        display: flex;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        color: white;
    }

    /* Bulat Potongan (Ticket Cut) */
    .voucher-main::before, .voucher-main::after {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        background: #ffffff00; /* Harus sama dengan background preview-zone */
        border-radius: 50%;
        left: 50px; /* Sesuai lebar v-side-tab */
        margin-left: -15px;
        z-index: 2;
    }
    .voucher-main::before { top: -15px; } /* Potongan Atas */
    .voucher-main::after { bottom: -15px; } /* Potongan Bawah */

    .v-side-tab {
        width: 50px;
        background: rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 2px dashed rgba(255,255,255,0.3);
        position: relative;
    }

    .v-side-text {
        transform: rotate(-90deg);
        white-space: nowrap;
        font-weight: 800;
        font-size: 0.7rem;
        letter-spacing: 3px;
        opacity: 0.8;
    }

    .v-body {
        flex: 1;
        padding: 1.5rem 1.5rem 1.5rem 2rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Themes */
    .theme-reward { background: linear-gradient(135deg, #FF8C00, #F59E0B); }
    .theme-izin { background: linear-gradient(135deg, #ef4444, #991b1b); }
    .theme-fasilitas { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }

    /* Form & Table Style tetap sama tapi dipoles sedikit */
    .input-box { margin-bottom: 1.2rem; }
    .input-box label { display: block; font-weight: 700; font-size: 0.75rem; color: var(--secondary); margin-bottom: 0.4rem; text-transform: uppercase; }
    
    .lux-input {
        width: 100%; border: 2px solid #f1f5f9; border-radius: 12px; padding: 0.7rem 1rem;
        background: #fcfcfd; transition: 0.3s;
    }
    .lux-input:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px var(--primary-soft); }

    .btn-save-lux {
        background: var(--primary); color: white; border: none; width: 100%; padding: 1rem;
        border-radius: 14px; font-weight: 700; transition: 0.3s; cursor: pointer;
    }
    .btn-save-lux:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -5px rgba(255,140,0,0.4); }

    /* Table Design */
    .lux-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
    .lux-table tr { background: white; }
    .lux-table td, .lux-table th { padding: 1rem; vertical-align: middle; }
    .lux-table th { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
    .lux-table td:first-child { border-radius: 16px 0 0 16px; }
    .lux-table td:last-child { border-radius: 0 16px 16px 0; }

    .v-mini-icon {
        width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;
    }

    .action-btn { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: none; }
    .edit-bg { background: #eff6ff; color: #2563eb; }
    .delete-bg { background: #fef2f2; color: #dc2626; }
</style>

<div id="vouch-page-wrapper">
    <header class="mb-5">
        <h1 style="font-weight: 800; font-size: 2rem;">Inventory Voucher 🎫</h1>
        <p style="color: #64748b; margin-top: 0.3rem;">Desain eksklusif untuk reward siswa.</p>
    </header>

    <div class="v-grid-main">
        <div class="preview-zone">
            <span style="font-size: 0.6rem; font-weight: 800; letter-spacing: 2px; color: #cbd5e1; margin-bottom: 2rem;">LIVE PREVIEW</span>
            
            <div id="v-card" class="voucher-main theme-reward">
                <div class="v-side-tab">
                    <span class="v-side-text" id="p-tab">REWARD</span>
                </div>
                <div class="v-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h4 id="p-name" style="font-weight: 800; font-size: 1.1rem; color: white;">Nama Voucher</h4>
                            <p id="p-desc" style="font-size: 0.7rem; opacity: 0.8; margin-top: 4px; max-width: 170px;">Deskripsi singkat...</p>
                        </div>
                        <div style="text-align: right;">
                            <div id="p-points" style="font-size: 2.2rem; font-weight: 900; line-height: 0.9;">0</div>
                            <small style="font-size: 0.55rem; font-weight: 700; opacity: 0.8;">POINTS</small>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed rgba(255,255,255,0.2); padding-top: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="background: rgba(255,255,255,0.2); padding: 6px; border-radius: 8px;">
                                <i data-lucide="ticket" style="width: 16px; height: 16px;"></i>
                            </div>
                            <span style="font-size: 0.6rem; font-weight: 700; letter-spacing: 1px;">OFFICIAL VOUCHER</span>
                        </div>
                        <i data-lucide="qr-code" style="width: 20px; height: 20px; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="lux-card">
            <form action="{{ route('admin.siswa-shop.store') }}" method="POST">
                @csrf
                <div class="input-box">
                    <label>Nama Item</label>
                    <input type="text" name="item_name" id="in-name" class="lux-input" placeholder="Misal: Sarapan Gratis" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="input-box">
                        <label>Kategori</label>
                        <select name="category" id="in-cat" class="lux-input">
                            <option value="Reward">Reward</option>
                            <option value="Izin">Izin</option>
                            <option value="Fasilitas">Fasilitas</option>
                        </select>
                    </div>
                    <div class="input-box">
                        <label>Harga Poin</label>
                        <input type="number" name="point_cost" id="in-pts" class="lux-input" placeholder="0" required>
                    </div>
                </div>

                <div class="input-box">
                    <label>Stok Maksimal</label>
                    <input type="number" name="stock_limit" class="lux-input" placeholder="Kosongkan jika tak terbatas">
                </div>

                <div class="input-box">
                    <label>Deskripsi Manfaat</label>
                    <textarea name="description" id="in-desc" class="lux-input" rows="2" placeholder="Apa yang didapat siswa?"></textarea>
                </div>

                <button type="submit" class="btn-save-lux">
                    <i data-lucide="plus-circle" style="width: 18px; vertical-align: middle; margin-right: 8px;"></i> Simpan Data
                </button>
            </form>
        </div>
    </div>

    <div class="table-section">
        <h3 style="font-weight: 800; margin-bottom: 1rem;">Katalog Voucher</h3>
        <div style="overflow-x: auto;">
            <table class="lux-table">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Info Voucher</th>
                        <th>Harga</th>
                        <th style="text-align: center;">Terpakai</th>
                        <th style="text-align: center;">Sisa</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td width="60px">
                            <div class="v-mini-icon theme-{{ strtolower($item->category) }}">
                                <i data-lucide="{{ $item->category == 'Reward' ? 'gift' : ($item->category == 'Izin' ? 'log-out' : 'bolt') }}" size="18"></i>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700;">{{ $item->item_name }}</div>
                            <div style="font-size: 0.65rem; color: #94a3b8;">{{ Str::limit($item->description, 40) }}</div>
                        </td>
                        <td>
                            <span style="font-weight: 800; color: var(--primary);">{{ number_format($item->point_cost) }}</span>
                            <small style="font-size: 0.6rem; color: #94a3b8;">PTS</small>
                        </td>
                        <td style="text-align: center; font-weight: 700;">{{ $item->total_redeemed ?? 0 }}x</td>
                        <td style="text-align: center;">
                            {!! $item->stock_limit ? '<b>'.$item->stock_limit.'</b>' : '<span style="color:#10b981">∞</span>' !!}
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="#" class="action-btn edit-bg"><i data-lucide="pencil" size="14"></i></a>
                                <form action="{{ route('admin.siswa-shop.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="action-btn delete-bg"><i data-lucide="trash" size="14"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 2rem;">Data masih kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const nameIn = document.getElementById('in-name');
    const descIn = document.getElementById('in-desc');
    const ptsIn = document.getElementById('in-pts');
    const catIn = document.getElementById('in-cat');
    const vCard = document.getElementById('v-card');

    const sync = () => {
        document.getElementById('p-name').innerText = nameIn.value || "Nama Voucher";
        document.getElementById('p-desc').innerText = descIn.value || "Deskripsi singkat...";
        document.getElementById('p-points').innerText = ptsIn.value || "0";
        document.getElementById('p-tab').innerText = catIn.value.toUpperCase();
        vCard.className = 'voucher-main theme-' + catIn.value.toLowerCase();
    };

    [nameIn, descIn, ptsIn, catIn].forEach(el => el.addEventListener('input', sync));
    lucide.createIcons();
</script>
@endsection