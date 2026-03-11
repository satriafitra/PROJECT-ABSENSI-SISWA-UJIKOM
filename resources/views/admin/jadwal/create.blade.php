@extends('layouts.admin')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* 1. Custom Scrollbar Modern */
    .select2-results__options::-webkit-scrollbar { width: 6px; }
    .select2-results__options::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }

    /* 2. Transformasi Select2 menjadi Modern Input */
    .select2-container--default .select2-selection--single {
        border: 2px solid #f1f5f9 !important;
        border-radius: 1rem !important;
        height: 56px !important;
        padding: 14px 16px !important;
        background-color: #f8fafc !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #f97316 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.08);
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #334155 !important;
        font-weight: 500;
        padding-left: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 54px !important;
        right: 12px !important;
    }

    /* Dropdown Styling */
    .select2-dropdown {
        border: 1px solid #f1f5f9 !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        border-radius: 1.25rem !important;
        margin-top: 8px;
        overflow: hidden;
        padding: 4px;
    }

    .select2-results__option--highlighted[aria-selected] {
        background-color: #f97316 !important;
        border-radius: 0.75rem;
    }

    .select2-results__option {
        padding: 10px 15px !important;
        margin: 2px 0;
    }

    /* 3. Input Jam Aesthetic */
    input[type="time"] {
        position: relative;
    }
    input[type="time"]::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }
</style>

<div class="min-h-[90vh] bg-[#f8fafc] flex flex-col items-center py-8 px-4 sm:px-6">
    <div class="w-full max-w-3xl">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('admin.jadwal.index') }}" class="flex items-center text-gray-500 hover:text-orange-600 transition-colors font-medium group">
                <div class="p-2 rounded-lg group-hover:bg-orange-50 transition-colors mr-2">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </div>
                Kembali
            </a>
            <div class="text-right">
                <span class="text-xs font-bold text-orange-500 uppercase tracking-widest">Panel Akademik</span>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-[0_15px_50px_-12px_rgba(0,0,0,0.08)] border border-gray-100 overflow-hidden">
            <div class="relative bg-slate-900 p-10 overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-orange-500 rounded-xl shadow-lg shadow-orange-500/30">
                            <i data-lucide="plus-circle" class="w-6 h-6 text-white"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white tracking-tight">Buat Jadwal Baru</h2>
                    </div>
                    <p class="text-slate-400">Atur alokasi waktu guru dan mata pelajaran dengan presisi.</p>
                </div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-500/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
            </div>

            <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-8 lg:p-12 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="flex items-center text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-3 ml-1">
                            <i data-lucide="users" class="w-4 h-4 mr-2 text-orange-500"></i> Guru Pengampu
                        </label>
                        <select name="guru_id" class="select2-search w-full">
                            <option value="">-- Cari Nama Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                        @error('guru_id')<p class="text-red-500 text-xs mt-2 ml-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center text-[13px] font-bold text-slate-500 uppercase tracking-wider ml-1">
                            <i data-lucide="book-open" class="w-4 h-4 mr-2 text-orange-500"></i> Mata Pelajaran
                        </label>
                        <select name="mata_pelajaran" class="select2-search w-full">
                            <option value="">-- Cari Mapel --</option>
                            @foreach(App\Enums\Mapel::all() as $mapel)
                                <option value="{{ $mapel }}">{{ $mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center text-[13px] font-bold text-slate-500 uppercase tracking-wider ml-1">
                            <i data-lucide="calendar-days" class="w-4 h-4 mr-2 text-orange-500"></i> Hari Belajar
                        </label>
                        <select name="hari" class="select2-search w-full">
                            <option value="">-- Pilih Hari --</option>
                            @foreach(App\Enums\Hari::all() as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="relative bg-slate-50 rounded-[2rem] p-8 border border-slate-100">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="h-6 w-1 bg-orange-500 rounded-full"></div>
                        <h3 class="font-bold text-slate-700">Durasi Pembelajaran</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="group">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1 group-focus-within:text-orange-500 transition-colors">Waktu Mulai</label>
                            <div class="relative">
                                <input type="time" name="jam_mulai" class="w-full bg-white border-2 border-slate-100 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-500/5 focus:border-orange-500 outline-none transition-all font-semibold text-slate-700">
                                <i data-lucide="play" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 group-focus-within:text-orange-500 transition-colors"></i>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1 group-focus-within:text-orange-500 transition-colors">Waktu Selesai</label>
                            <div class="relative">
                                <input type="time" name="jam_selesai" class="w-full bg-white border-2 border-slate-100 rounded-xl px-5 py-4 focus:ring-4 focus:ring-orange-500/5 focus:border-orange-500 outline-none transition-all font-semibold text-slate-700">
                                <i data-lucide="square" class="absolute right-4 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-300 group-focus-within:text-orange-500 transition-colors"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="flex items-center text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-3 ml-1">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-orange-500"></i> Ruangan / Kelas
                    </label>
                    <input type="text" name="ruangan" 
                           class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-orange-500/5 focus:border-orange-500 outline-none transition-all placeholder:text-slate-400 font-medium text-slate-700" 
                           placeholder="Misal: Laboratorium Fisika lt. 2">
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                    <button type="submit" class="w-full sm:flex-[2] bg-orange-600 hover:bg-orange-700 text-white font-bold py-5 rounded-2xl shadow-xl shadow-orange-600/20 hover:shadow-orange-600/40 hover:-translate-y-1 transition-all active:scale-95 flex justify-center items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                        Simpan Jadwal Baru
                    </button>
                    <a href="{{ route('admin.jadwal.index') }}" class="w-full sm:flex-1 py-5 px-6 bg-slate-100 text-slate-500 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        lucide.createIcons();

        // Inisialisasi Select2 untuk semua select
        $('.select2-search').select2({
            width: '100%',
            placeholder: "-- Pilih --",
            allowClear: true,
            selectionCssClass: 'modern-select2'
        });

        // Trigger ulang ikon lucide jika ada perubahan pada select2
        $('.select2-search').on('change', function() {
            lucide.createIcons();
        });
    });
</script>
@endsection