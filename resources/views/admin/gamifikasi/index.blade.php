@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #FF8C00 0%, #F27121 100%);
        --secondary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --glass-white: rgba(255, 255, 255, 0.85);
        --bg-soft: #f8fafc;
        --text-main: #1e293b;
        --orange-glow: 0 10px 25px rgba(242, 113, 33, 0.25);
    }

    body {
        background: radial-gradient(circle at top right, #fff5eb, transparent), 
                    radial-gradient(circle at bottom left, #f0f4ff, transparent),
                    var(--bg-soft);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
    }

    /* Page Header */
    .header-section {
        margin-bottom: 2.5rem;
    }

    .text-gradient-orange {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    /* Card Premium Styling */
    .card-premium {
        background: var(--glass-white);
        backdrop-filter: blur(10px);
        border-radius: 28px;
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        overflow: hidden;
    }

    .card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
    }

    /* Form Design */
    .form-group label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748b;
        margin-left: 4px;
    }

    .form-custom-input {
        background: #ffffff;
        border: 2px solid #f1f5f9;
        border-radius: 16px;
        padding: 12px 16px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .form-custom-input:focus {
        border-color: #FF8C00;
        box-shadow: 0 0 0 4px rgba(255, 140, 0, 0.1);
        outline: none;
        background: #fff;
    }

    /* Modern Button */
    .btn-premium {
        background: var(--primary-gradient);
        border: none;
        border-radius: 18px;
        padding: 16px;
        font-weight: 700;
        color: white;
        box-shadow: var(--orange-glow);
        transition: all 0.3s;
        letter-spacing: 0.5px;
    }

    .btn-premium:hover {
        transform: scale(1.02);
        box-shadow: 0 15px 30px rgba(242, 113, 33, 0.4);
        color: #fff;
    }

    /* Leaderboard Items */
    .rank-row {
        background: #fff;
        border-radius: 20px;
        margin-bottom: 12px;
        transition: all 0.3s;
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        padding: 12px 20px;
    }

    .rank-row:hover {
        border-color: #FF8C00;
        background: #fffaf5;
        transform: scale(1.01);
    }

    .avatar-wrapper {
        position: relative;
        margin-right: 15px;
    }

    .avatar-main {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: #fff5eb;
        color: #FF8C00;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .badge-rank {
        position: absolute;
        top: -8px;
        left: -8px;
        padding: 5px 8px;
        font-size: 0.65rem;
        border-radius: 8px;
        font-weight: 800;
    }

    .progress-custom {
        height: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 8px;
    }

    .points-text {
        font-size: 1.2rem;
        font-weight: 800;
        color: #FF8C00;
        line-height: 1;
    }

    .clock-container {
        background: white;
        padding: 12px 24px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(255,255,255,0.8);
        font-weight: 800;
        color: #1e293b;
        letter-spacing: 1px;
    }

    .icon-box {
        width: 45px;
        height: 45px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .bg-soft-orange { background: #fff5eb; color: #FF8C00; }
    .bg-soft-blue { background: #eef2ff; color: #6366f1; }
</style>

<div class="container-fluid py-5 px-lg-5 animate__animated animate__fadeIn">
    <div class="row header-section align-items-center">
        <div class="col-md-8">
            <h1 class="display-4 font-weight-bold mb-1">
                <span class="text-gradient-orange">Gamification</span> Console
            </h1>
            <p class="text-muted" style="font-size: 1.15rem;">Pantau performa dan atur mekanisme poin secara real-time.</p>
        </div>
        <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <div class="d-inline-flex align-items-center clock-container">
                <i data-lucide="clock" class="mr-2 text-warning"></i>
                <span id="realtime-clock">00:00:00</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card card-premium h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box bg-soft-orange shadow-sm">
                            <i data-lucide="zap" size="22"></i>
                        </div>
                        <div>
                            <h4 class="font-weight-bold mb-0">Rule Engine</h4>
                            <p class="small text-muted mb-0">Automated Logic</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.gamifikasi.rule.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label><i data-lucide="tag" size="14" class="mr-1"></i> Nama Aturan</label>
                            <input type="text" name="rule_name" class="form-control form-custom-input" placeholder="Misal: Early Bird Reward" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12 mb-3">
                                <label><i data-lucide="settings-2" size="14" class="mr-1"></i> Kondisi Kehadiran</label>
                                <select name="condition_operator" class="form-control form-custom-input">
                                    <option value="<">Check-in Sebelum</option>
                                    <option value=">">Terlambat Setelah</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label><i data-lucide="clock" size="14" class="mr-1"></i> Target Waktu</label>
                                <input type="time" name="condition_value" class="form-control form-custom-input" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label><i data-lucide="sparkles" size="14" class="mr-1"></i> Nominal Poin</label>
                            <div class="input-group">
                                <input type="number" name="point_modifier" class="form-control form-custom-input" placeholder="10" style="border-radius: 16px 0 0 16px;" required>
                                <div class="input-group-append">
                                    <span class="input-group-text px-4" style="background: #1e293b; color: white; border: none; border-radius: 0 16px 16px 0; font-weight: 700;">PTS</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-premium btn-block shadow-lg mt-2">
                            <i data-lucide="plus-circle" class="mr-2" size="18"></i> Simpan Aturan Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card card-premium">
                <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="icon-box bg-soft-blue shadow-sm">
                            <i data-lucide="trophy" size="22"></i>
                        </div>
                        <h4 class="font-weight-bold mb-0">Top Integrity Leaderboard</h4>
                    </div>
                    <a href="{{ route('admin.gamifikasi.leaderboard') }}" class="btn btn-light rounded-pill px-4 font-weight-bold">
                        Full Ranking <i data-lucide="arrow-right" class="ml-1" size="16"></i>
                    </a>
                </div>

                <div class="card-body p-4 pt-0">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                @forelse($leaderboard as $index => $data)
                                <tr class="animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s;">
                                    <td>
                                        <div class="rank-row">
                                            <div class="avatar-wrapper">
                                                <div class="avatar-main">
                                                    {{ substr($data->student->name ?? '?', 0, 1) }}
                                                </div>
                                                @if($index < 3)
                                                <span class="badge-rank shadow-sm {{ $index == 0 ? 'bg-warning text-white' : ($index == 1 ? 'bg-secondary text-white' : 'bg-danger text-white') }}">
                                                    #{{ $index + 1 }}
                                                </span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div>
                                                        <h6 class="font-weight-bold mb-0 text-dark">{{ $data->student->name ?? 'Student' }}</h6>
                                                        <small class="text-muted">Grade Achievement</small>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="points-text">{{ number_format($data->total_points) }}</div>
                                                        <small class="text-uppercase font-weight-bold text-muted" style="font-size: 0.6rem;">Total Points</small>
                                                    </div>
                                                </div>
                                                <div class="progress progress-custom">
                                                    <div class="progress-bar {{ $index == 0 ? 'bg-warning' : 'bg-success' }}" 
                                                         role="progressbar" 
                                                         style="width: {{ 100 - ($index * 10) }}%; transition: width 1.5s ease;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="text-center py-5">
                                        <div class="opacity-50 mb-3">
                                            <i data-lucide="ghost" size="48"></i>
                                        </div>
                                        <p class="text-muted">Belum ada aktivitas poin yang tercatat.</p>
                                    </td>
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
    // Live Clock Function
    function updateClock() {
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        document.getElementById('realtime-clock').innerText = now.toLocaleTimeString('id-ID', options);
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Lucide Icons Initialization
    lucide.createIcons();
</script>
@endsection