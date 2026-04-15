@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
    :root {
        --premium-orange: linear-gradient(135deg, #FF8C00 0%, #F27121 100%);
        --orange-glow: 0 10px 20px rgba(242, 113, 33, 0.2);
        --glass-bg: rgba(255, 255, 255, 0.9);
        --surface-color: #ffffff;
    }

    body {
        background-color: #fcfcfd;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #2D3748;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        letter-spacing: -0.02em;
    }

    /* Card Styling */
    .card-premium {
        background: var(--surface-color);
        border-radius: 24px;
        border: 1px solid rgba(0, 0, 0, 0.04);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
    }

    .card-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
    }

    /* Input & Form Styling */
    .form-custom-input {
        background: #F7FAFC;
        border: 2px solid #EDF2F7;
        border-radius: 16px;
        padding: 14px 18px;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .form-custom-input:focus {
        background: #fff;
        border-color: #FF8C00;
        box-shadow: 0 0 0 4px rgba(255, 140, 0, 0.1);
        outline: none;
    }

    label {
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #718096;
        margin-bottom: 8px;
    }

    /* Button Styling */
    .btn-premium {
        background: var(--premium-orange);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 16px 20px;
        font-weight: 700;
        box-shadow: var(--orange-glow);
        transition: 0.3s;
    }

    .btn-premium:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(242, 113, 33, 0.4);
        color: white;
    }

    /* Leaderboard Design */
    .rank-row {
        background: #fff;
        border-radius: 20px;
        margin-bottom: 12px;
        transition: 0.2s;
        border: 1px solid #F1F5F9;
    }

    .rank-row:hover {
        border-color: #FF8C00;
        background: #FFFBF7;
    }

    .avatar-main {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: #FFF5EB;
        color: #FF8C00;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .text-gradient-orange {
        background: var(--premium-orange);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    .clock-badge {
        background: white;
        padding: 10px 20px;
        border-radius: 100px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #f1f1f1;
        font-weight: 700;
        color: #4A5568;
    }

    .icon-shape {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
</style>

<div class="container-fluid py-5 px-lg-5 animate__animated animate__fadeIn">
    <div class="row align-items-center mb-5">
        <div class="col-md-7">
            <h1 class="display-4 font-weight-bold mb-2">
                <span class="text-gradient-orange">Gamification</span> Console
            </h1>
            <p class="text-muted" style="font-size: 1.1rem;">Ubah rutinitas menjadi pencapaian yang kompetitif.</p>
        </div>
        <div class="col-md-5 text-md-right mt-3 mt-md-0">
            <div class="d-inline-flex align-items-center clock-badge">
                <i data-lucide="clock" class="text-warning mr-2" style="width: 18px;"></i>
                <span id="realtime-clock">00:00:00</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card card-premium h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-shape bg-warning text-white mr-3 shadow-sm">
                            <i data-lucide="zap" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h4 class="font-weight-bold mb-0">Rule Engine</h4>
                            <p class="small text-muted mb-0">Konfigurasi otomatisasi poin</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.gamifikasi.rule.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label>Nama Aturan</label>
                            <input type="text" name="rule_name" class="form-control form-custom-input" placeholder="Contoh: Sang Pagi Hari" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <label>Kondisi</label>
                                <select name="condition_operator" class="form-control form-custom-input">
                                    <option value="<">Datang Sebelum</option>
                                    <option value=">">Terlambat Setelah</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label>Waktu</label>
                                <input type="time" name="condition_value" class="form-control form-custom-input" required>
                            </div>
                        </div>

                        <div class="form-group mb-5">
                            <label>Reward / Penalty (PTS)</label>
                            <div class="input-group">
                                <input type="number" name="point_modifier" class="form-control form-custom-input" placeholder="10" style="border-radius: 16px 0 0 16px;" required>
                                <div class="input-group-append">
                                    <span class="input-group-text px-4 border-0" style="background: #2D3748; color: white; border-radius: 0 16px 16px 0; font-weight: 700; font-size: 0.8rem;">PTS</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-premium btn-block py-3">
                            Terapkan Aturan Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card card-premium">
                <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-light text-dark mr-3">
                            <i data-lucide="award" class="text-warning"></i>
                        </div>
                        <h4 class="font-weight-bold mb-0">Top Integrity</h4>
                    </div>
                    <a href="{{ route('admin.gamifikasi.leaderboard') }}" class="btn btn-link font-weight-bold text-decoration-none" style="color: #FF8C00;">
                        Lihat Semua <i data-lucide="chevron-right" class="ml-1" style="width: 16px;"></i>
                    </a>
                </div>

                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                @forelse($leaderboard as $index => $data)
                                <tr class="rank-row animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.05 }}s;">
                                    <td class="align-middle py-3 px-3" width="70">
                                        <div class="position-relative">
                                            <div class="avatar-main">
                                                {{ substr($data->student->name ?? '?', 0, 1) }}
                                            </div>
                                            @if($index < 3)
                                            <div class="position-absolute" style="top: -10px; left: -10px;">
                                                <span class="badge badge-pill {{ $index == 0 ? 'badge-warning' : ($index == 1 ? 'badge-secondary' : 'badge-danger') }} shadow-sm">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">{{ $data->student->name ?? 'Unknown' }}</div>
                                        <div class="progress mt-2" style="height: 4px; width: 100px; border-radius: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 90%;"></div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-right px-4">
                                        <span class="h5 font-weight-bold mb-0 text-orange d-block">{{ number_format($data->total_points) }}</span>
                                        <small class="text-uppercase text-muted font-weight-bold" style="font-size: 0.65rem; letter-spacing: 1px;">Points</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">Belum ada aktivitas poin hari ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateClock() {
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        document.getElementById('realtime-clock').innerText = now.toLocaleTimeString('id-ID', options);
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Initialize Lucide
    lucide.createIcons();
</script>
@endsection