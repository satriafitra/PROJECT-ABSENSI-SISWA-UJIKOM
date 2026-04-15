@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    :root {
        --primary: #FF8C00; 
        --primary-soft: #fff7ed;
        --secondary: #64748b;
        --success: #10b981;
        --danger: #ef4444;
        --info: #3b82f6;
        --bg-body: #f1f5f9;
        --radius-card: 24px;
        --radius-inner: 16px;
        --shadow-lux: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }

    body { 
        font-family: 'Poppins', sans-serif; 
        background-color: var(--bg-body); 
        color: #1e293b;
        line-height: 1.6;
    }

    .content-wrapper { padding: 3rem; max-width: 1440px; margin: 0 auto; }

    /* --- Luxury Input Design --- */
    .input-group-custom {
        margin-bottom: 1.5rem;
    }

    .input-group-custom label {
        font-weight: 700;
        font-size: 0.75rem;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.6rem;
        display: block;
        padding-left: 4px;
    }

    .premium-input {
        width: 100%;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: var(--radius-inner);
        padding: 0.85rem 1.25rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #1e293b;
    }

    .premium-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-soft);
        transform: translateY(-1px);
    }

    /* --- Enhanced Voucher Design --- */
    .voucher-card {
        display: flex;
        width: 100%;
        max-width: 420px;
        height: 160px;
        filter: drop-shadow(0 20px 30px rgba(0,0,0,0.07));
        transition: all 0.4s ease;
    }

    .voucher-card:hover { transform: translateY(-8px) rotate(1deg); }

    .v-left {
        flex: 2.2;
        background: white;
        border-radius: var(--radius-card) 0 0 var(--radius-card);
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        /* Sobekan Voucher Kiri */
        clip-path: polygon(0 0, 100% 0, 100% 35%, 94% 42%, 94% 58%, 100% 65%, 100% 100%, 0 100%);
    }

    .v-right {
        flex: 1;
        border-radius: 0 var(--radius-card) var(--radius-card) 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        position: relative;
        /* Sobekan Voucher Kanan */
        clip-path: polygon(0 0, 0 35%, 6% 42%, 6% 58%, 0 65%, 0 100%, 100% 100%, 100% 0);
    }

    .v-bg-reward { background: linear-gradient(135deg, #FF8C00, #FFA500); }
    .v-bg-izin { background: linear-gradient(135deg, #ef4444, #b91c1c); }
    .v-bg-fasilitas { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }

    /* --- Table Styling (Floating Card Style) --- */
    .table-container { margin-top: 4rem; }
    
    .table-luxury {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 1.25rem;
    }

    .table-luxury thead th {
        padding: 0 1.5rem;
        color: var(--secondary);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        border: none;
    }

    .table-luxury tbody tr {
        background: white;
        border-radius: var(--radius-inner);
        box-shadow: var(--shadow-lux);
        transition: all 0.3s ease;
    }

    .table-luxury tbody tr:hover {
        transform: scale(1.01);
    }

    .table-luxury td {
        padding: 1.5rem;
        border: none;
        vertical-align: middle;
    }

    .table-luxury td:first-child { border-radius: var(--radius-inner) 0 0 var(--radius-inner); }
    .table-luxury td:last-child { border-radius: 0 var(--radius-inner) var(--radius-inner) 0; }

    /* --- Action Buttons --- */
    .action-btn-group {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
    }

    .btn-action {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
    }

    .btn-edit { background: #eff6ff; color: #2563eb; }
    .btn-edit:hover { background: #2563eb; color: white; }

    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-delete:hover { background: #dc2626; color: white; }

    /* --- Other UI Elements --- */
    .glass-card {
        background: white;
        border-radius: var(--radius-card);
        padding: 2.5rem;
        box-shadow: var(--shadow-lux);
    }

    .btn-save {
        background: var(--primary);
        color: white;
        font-weight: 700;
        border: none;
        border-radius: var(--radius-inner);
        padding: 1.1rem;
        width: 100%;
        margin-top: 1rem;
        box-shadow: 0 10px 20px -5px rgba(255, 140, 0, 0.4);
        transition: all 0.3s;
    }

    .btn-save:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px -5px rgba(255, 140, 0, 0.5);
    }
</style>

<div class="content-wrapper">
    <header class="mb-5">
        <h1 style="font-weight: 800; font-size: 2.25rem; color: #0f172a; margin-bottom: 0.5rem;">Inventory Voucher 🎫</h1>
        <p class="text-muted fw-500">Kustomisasi item penukaran poin AkvaScan dengan tampilan mewah.</p>
    </header>

    <div class="row g-5"> <div class="col-lg-5">
            <div class="d-flex flex-column align-items-center justify-content-center h-100" 
                 style="background: rgba(255,255,255,0.4); border: 2px dashed #cbd5e1; border-radius: var(--radius-card); padding: 3rem;">
                <span class="small fw-800 text-muted mb-5" style="letter-spacing: 2px;">LIVE PREVIEW MODE</span>
                
                <div id="voucher-preview" class="voucher-card">
                    <div class="v-left">
                        <div>
                            <span class="badge mb-2" id="preview-category" 
                                  style="background: var(--primary-soft); color: var(--primary); font-weight: 800; font-size: 0.65rem; padding: 6px 12px; border-radius: 50px;">REWARD</span>
                            <h4 class="fw-800 mb-1" id="preview-name" style="color: #0f172a;">Nama Voucher</h4>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div style="padding: 8px; background: #f8fafc; border-radius: 10px;">
                                <i id="preview-icon" data-lucide="ticket" class="text-primary" style="width: 20px; height: 20px;"></i>
                            </div>
                            <span class="small text-muted fw-500" id="preview-desc">Tulis deskripsi menarik di sini...</span>
                        </div>
                    </div>
                    <div class="v-right v-bg-reward" id="preview-right-bg">
                        <span style="font-size: 0.6rem; font-weight: 600; opacity: 0.8; letter-spacing: 1px;">POINT COST</span>
                        <h2 class="fw-800 mb-0" id="preview-points" style="font-size: 2.2rem;">0</h2>
                        <span style="font-size: 0.75rem; font-weight: 700;">PTS</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="glass-card">
                <form action="{{ route('admin.siswa-shop.store') }}" method="POST">
                    @csrf
                    <div class="row g-4"> <div class="col-md-8">
                            <div class="input-group-custom">
                                <label>Nama Item</label>
                                <input type="text" name="item_name" id="in-name" class="premium-input" placeholder="Voucher Makan Siang..." required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group-custom">
                                <label>Kategori</label>
                                <select name="category" id="in-category" class="premium-input">
                                    <option value="Reward">Reward</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Fasilitas">Fasilitas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group-custom">
                                <label>Harga Poin</label>
                                <div class="position-relative">
                                    <input type="number" name="point_cost" id="in-points" class="premium-input" placeholder="Contoh: 500" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group-custom">
                                <label>Stok Maksimal</label>
                                <input type="number" name="stock_limit" class="premium-input" placeholder="Ketik 0 jika tak terbatas">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group-custom">
                                <label>Deskripsi Manfaat</label>
                                <textarea name="description" id="in-desc" class="premium-input" rows="2" placeholder="Jelaskan apa yang didapatkan siswa..."></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-save">
                        <i data-lucide="sparkles" style="width: 18px; margin-right: 8px; vertical-align: middle;"></i> Simpan Voucher Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div style="width: 4px; height: 24px; background: var(--primary); border-radius: 10px;"></div>
            <h3 class="fw-800 m-0" style="font-size: 1.5rem;">Katalog Voucher</h3>
        </div>

        <div class="table-responsive">
            <table class="table-luxury">
                <thead>
                    <tr>
                        <th>Visual Item</th>
                        <th>Info Stok</th>
                        <th class="text-center">Nilai Tukar</th>
                        <th class="text-end">Aksi Pengelolaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td width="380px">
                            <div class="d-flex align-items-center gap-3">
                                <div class="voucher-card" style="height: 90px; width: 220px; font-size: 0.7rem; filter: none;">
                                    <div class="v-left p-2 px-3" style="border: 1px solid #f1f5f9; border-right: none;">
                                        <div class="fw-800 text-dark text-truncate">{{ $item->item_name }}</div>
                                        <div class="text-muted" style="font-size: 0.6rem;">{{ $item->category }}</div>
                                    </div>
                                    <div class="v-right {{ $item->category == 'Reward' ? 'v-bg-reward' : ($item->category == 'Izin' ? 'v-bg-izin' : 'v-bg-fasilitas') }}">
                                        <div class="fw-800" style="font-size: 1rem;">{{ $item->point_cost }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($item->stock_limit === null || $item->stock_limit == 0)
                                <div class="d-flex align-items-center gap-2 text-success fw-600">
                                    <i data-lucide="infinity" size="16"></i> Unlimited
                                </div>
                            @else
                                <div class="fw-600 {{ $item->stock_limit < 5 ? 'text-danger' : 'text-slate-700' }}">
                                    {{ $item->stock_limit }} Tersedia
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark fw-800 p-2 px-3 rounded-pill" style="font-size: 0.8rem;">
                                {{ number_format($item->point_cost) }} Poin
                            </span>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                <a href="#" class="btn-action btn-edit" title="Edit Data">
                                    <i data-lucide="file-text" size="20"></i>
                                </a>
                                <form action="{{ route('admin.siswa-shop.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn-action btn-delete" title="Hapus Permanen">
                                        <i data-lucide="trash-2" size="20"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted fw-500">Katalog masih kosong, silakan tambah voucher baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Real-time Live Preview Script
    const inName = document.getElementById('in-name');
    const inDesc = document.getElementById('in-desc');
    const inPoints = document.getElementById('in-points');
    const inCategory = document.getElementById('in-category');

    const updatePreview = () => {
        document.getElementById('preview-name').innerText = inName.value || "Nama Voucher";
        document.getElementById('preview-desc').innerText = inDesc.value || "Tulis deskripsi menarik di sini...";
        document.getElementById('preview-points').innerText = inPoints.value || "0";
        document.getElementById('preview-category').innerText = inCategory.value.toUpperCase();
        
        const rightBg = document.getElementById('preview-right-bg');
        rightBg.className = 'v-right'; // Reset classes
        if(inCategory.value === 'Reward') rightBg.classList.add('v-bg-reward');
        if(inCategory.value === 'Izin') rightBg.classList.add('v-bg-izin');
        if(inCategory.value === 'Fasilitas') rightBg.classList.add('v-bg-fasilitas');

        lucide.createIcons();
    };

    [inName, inDesc, inPoints, inCategory].forEach(el => {
        el.addEventListener('input', updatePreview);
    });

    // Inisialisasi awal icon
    lucide.createIcons();
</script>
@endsection