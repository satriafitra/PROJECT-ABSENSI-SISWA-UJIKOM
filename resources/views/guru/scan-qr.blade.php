@extends('layouts.admin')

@section('title', 'QR Absensi Guru')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
    .qr-card { transition: all 0.3s ease; border: 1px solid rgba(255, 107, 53, 0.1); }
    .qr-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    .bg-gradient-orange { background: linear-gradient(135deg, #FF6B35 0%, #FF8E62 100%); }
    .glass-effect { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
    .qr-frame { position: relative; padding: 20px; background: white; border-radius: 24px; display: inline-block; }
    .qr-frame::before { 
        content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        border: 4px solid #FF6B35; border-radius: 24px; clip-path: polygon(0 0, 20% 0, 20% 5%, 5% 5%, 5% 20%, 0 20%, 0 0, 100% 0, 100% 20%, 95% 20%, 95% 5%, 80% 5%, 80% 0, 100% 0, 100% 100%, 80% 100%, 80% 95%, 95% 95%, 95% 80%, 100% 80%, 100% 100%, 0 100%, 0 80%, 5% 80%, 5% 95%, 20% 95%, 20% 100%, 0 100%);
    }
</style>

<div class="max-w-6xl mx-auto p-6 md:p-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                <span class="text-orange-600"><i class="fa-solid fa-qrcode mr-3"></i>QR</span> Absensi Guru
            </h1>
            <p class="text-gray-500 mt-2 flex items-center">
                <i class="fa-solid fa-circle-info mr-2 text-orange-400"></i>
                Siswa dapat melakukan scan untuk mencatat kehadiran guru mengajar.
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="location.reload()" class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-2xl font-semibold hover:bg-gray-50 transition shadow-sm">
                <i class="fa-solid fa-rotate"></i> Refresh
            </button>
        </div>
    </div>

    <div class="grid lg:grid-cols-5 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white qr-card rounded-3xl p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 text-2xl">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Profil Pengajar</h2>
                        <p class="text-sm text-gray-500">Data terverifikasi sistem</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="group">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                        <p class="text-lg font-semibold text-gray-800 group-hover:text-orange-600 transition">{{ $guru->nama }}</p>
                    </div>
                    
                    <div class="group">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Email Instansi</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $guru->email }}</p>
                    </div>

                    @if($guru->nip)
                    <div class="group">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">NIP / Identitas</label>
                        <p class="text-lg font-semibold text-gray-800">{{ $guru->nip }}</p>
                    </div>
                    @endif

                    <div class="pt-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold
                            {{ $guru->status === 'aktif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            <span class="w-2 h-2 rounded-full mr-2 {{ $guru->status === 'aktif' ? 'bg-green-600' : 'bg-red-600' }} animate-pulse"></span>
                            {{ strtoupper($guru->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('guru.scan.export') }}" class="block bg-gradient-orange p-6 rounded-3xl text-white shadow-lg shadow-orange-200 hover:shadow-orange-300 transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg">Rekap Absensi</h3>
                        <p class="text-orange-100 text-sm">Download laporan kehadiran (.xlsx)</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fa-solid fa-file-export text-xl text-white"></i>
                    </div>
                </div>
            </a>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white qr-card rounded-3xl p-10 shadow-sm border-2 border-dashed border-orange-100 flex flex-col items-center justify-center text-center">
                <div class="mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-800">Scan Kehadiran</h2>
                    <p class="text-gray-500 mt-1">Arahkan kamera siswa ke QR Code di bawah ini</p>
                </div>

                <div class="qr-frame my-4 shadow-2xl shadow-orange-100">
                    {!! QrCode::size(280)
                        ->gradient(255, 107, 53, 255, 142, 98, 'diagonal')
                        ->margin(1)
                        ->generate($qrData) !!}
                </div>

                <div class="mt-8 grid grid-cols-2 gap-4 w-full max-w-md">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <i class="fa-solid fa-clock text-orange-500 mb-2"></i>
                        <p class="text-xs text-gray-500 uppercase font-bold">Waktu</p>
                        <p class="text-sm font-bold text-gray-700" id="current-time">00:00:00</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <i class="fa-solid fa-calendar-day text-orange-500 mb-2"></i>
                        <p class="text-xs text-gray-500 uppercase font-bold">Tanggal</p>
                        <p class="text-sm font-bold text-gray-700">{{ date('d M Y') }}</p>
                    </div>
                </div>

                <p class="mt-8 text-xs text-gray-400 italic">
                    <i class="fa-solid fa-shield-halved mr-1 text-green-500"></i> QR Code ini aman dan diperbarui secara otomatis oleh sistem.
                </p>
            </div>
        </div>

    </div>
</div>

<script>
    // Live Clock Function
    function updateClock() {
        const now = new Date();
        const timeStr = now.getHours().toString().padStart(2, '0') + ":" + 
                        now.getMinutes().toString().padStart(2, '0') + ":" + 
                        now.getSeconds().toString().padStart(2, '0');
        document.getElementById('current-time').textContent = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

@endsection