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
    
    /* Frame QR dengan animasi pulse lembut */
    .qr-frame { position: relative; padding: 20px; background: white; border-radius: 24px; display: inline-block; }
    .qr-frame::before { 
        content: ''; position: absolute; inset: 0;
        border: 4px solid #FF6B35; border-radius: 24px;
        clip-path: polygon(
            0 0, 20% 0, 20% 5%, 5% 5%, 5% 20%, 0 20%,
            0 0, 100% 0, 100% 20%, 95% 20%, 95% 5%, 80% 5%, 80% 0,
            100% 0, 100% 100%, 80% 100%, 80% 95%, 95% 95%, 95% 80%, 100% 80%,
            100% 100%, 0 100%, 0 80%, 5% 80%, 5% 95%, 20% 95%, 20% 100%, 0 100%
        );
    }
    
    .status-badge { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="max-w-6xl mx-auto p-6 md:p-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                <span class="text-orange-600"><i class="fa-solid fa-qrcode mr-3"></i>QR</span> Absensi Guru
            </h1>
            <p class="text-gray-500 mt-2 flex items-center">
                <i class="fa-solid fa-circle-info mr-2 text-orange-400"></i>
                QR aktif otomatis berdasarkan jadwal mata pelajaran Anda.
            </p>
        </div>
        <button onclick="location.reload()" class="flex items-center gap-2 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-2xl font-semibold hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-rotate"></i> Perbarui QR
        </button>
    </div>

    <div class="grid lg:grid-cols-5 gap-8">
        {{-- PROFIL & JADWAL --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Card Profil --}}
            <div class="bg-white qr-card rounded-3xl p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 text-xl">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Identitas Pengajar</h2>
                        <p class="text-xs text-gray-500 italic">Sesuai data Dapodik/Sistem</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                        <p class="text-md font-semibold text-gray-800">{{ $guru->nama }}</p>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NIP / Kode Guru</label>
                        <p class="text-md font-semibold text-gray-800">{{ $guru->nip ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Card Status Jadwal Aktif --}}
            <div class="bg-white qr-card rounded-3xl p-8 shadow-sm border-l-4 border-orange-500">
                <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">
                    <i class="fa-solid fa-calendar-check text-orange-500 mr-2"></i>Status Jadwal Saat Ini
                </h3>
                
                @if($jadwalSekarang->isEmpty())
                    <div class="p-4 bg-red-50 rounded-2xl border border-red-100 status-badge">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation text-red-500 mt-1"></i>
                            <div>
                                <p class="text-sm font-bold text-red-700">Tidak Ada Jadwal</p>
                                <p class="text-xs text-red-600 mt-1">QR Code tidak akan menerima absensi di luar jam mengajar.</p>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach($jadwalSekarang as $j)
                    <div class="p-4 bg-green-50 rounded-2xl border border-green-100 mb-2 status-badge">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2 animate-ping"></div>
                            <div>
                                <p class="text-xs font-bold text-green-700 uppercase">{{ $j->mata_pelajaran }}</p>
                                <p class="text-sm font-black text-gray-800">{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</p>
                                <p class="text-[10px] text-green-600 font-medium mt-1">
                                    <i class="fa-solid fa-location-dot"></i> Ruangan: {{ $j->ruangan ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- QR CODE DISPLAY --}}
        <div class="lg:col-span-3">
            <div class="bg-white qr-card rounded-3xl p-10 shadow-sm border-2 border-dashed border-orange-100 text-center relative overflow-hidden">
                {{-- Watermark Background --}}
                <i class="fa-solid fa-qrcode absolute -bottom-10 -right-10 text-9xl text-gray-50 opacity-50"></i>

                @if(!$jadwalSekarang->isEmpty())
                    <h2 class="text-2xl font-extrabold text-gray-800 mb-2">QR Siap Scan</h2>
                    <p class="text-gray-500 mb-8">Siswa silakan scan untuk mencatat kehadiran</p>

                    <div class="qr-frame shadow-2xl shadow-orange-100 mb-4 bg-white">
                        {!! QrCode::size(300)
                            ->gradient(185, 65, 22, 255, 107, 53, 'diagonal')
                            ->margin(1)
                            ->generate($qrData) !!}
                    </div>
                @else
                    <div class="py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <i class="fa-solid fa-lock text-3xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-400">QR Terkunci</h2>
                        <p class="text-gray-400 text-sm max-w-xs mx-auto">QR hanya akan muncul dan dapat digunakan saat memasuki jam mengajar Anda.</p>
                    </div>
                @endif

                <div class="mt-8 grid grid-cols-2 gap-4 max-w-md mx-auto relative z-10">
                    <div class="p-4 bg-white rounded-2xl border shadow-sm">
                        <i class="fa-solid fa-clock text-orange-500 mb-1"></i>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Waktu Server</p>
                        <p id="current-time" class="text-md font-bold text-gray-700 tracking-wider">00:00:00</p>
                    </div>
                    <div class="p-4 bg-white rounded-2xl border shadow-sm">
                        <i class="fa-solid fa-calendar-day text-orange-500 mb-1"></i>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Hari Ini</p>
                        <p class="text-md font-bold text-gray-700">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</p>
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                    Sistem Absensi AkvaScan Terenkripsi
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi Update Jam Realtime
    function updateClock() {
        const now = new Date();
        document.getElementById('current-time').textContent =
            now.toLocaleTimeString('id-ID', { hour12: false });
            
        // Logika Refresh Otomatis:
        // Jika menit adalah 00, 15, 30, atau 45 (Asumsi pergantian jam pelajaran biasanya di kelipatan ini)
        // Halaman akan refresh untuk mengecek jadwal terbaru
        const seconds = now.getSeconds();
        const minutes = now.getMinutes();
        if ((minutes % 15 === 0) && seconds === 0) {
            location.reload();
        }
    }
    
    setInterval(updateClock, 1000);
    updateClock();

    // Animasi Pulse pada Frame QR jika ada
    const frame = document.querySelector('.qr-frame');
    if (frame) {
        setInterval(() => {
            frame.style.transform = 'scale(1.01)';
            setTimeout(() => frame.style.transform = 'scale(1)', 500);
        }, 2000);
    }
</script>
@endsection