@extends('layouts.admin')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .select2-container--default .select2-selection--single {
        border: 2px solid #f1f5f9 !important;
        border-radius: 1.25rem !important;
        height: 60px !important;
        padding: 16px 20px !important;
        background-color: #f8fafc !important;
    }
    /* Style Slider agar lebih responsif */
    input[type=range] { cursor: pointer; }
</style>

<div class="min-h-[90vh] bg-[#f8fafc] py-8 px-4">
    <div class="w-full max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('guru.assessment.index') }}" class="flex items-center text-slate-400 hover:text-blue-600 transition-all font-bold group text-sm uppercase tracking-widest">
                <i data-lucide="arrow-left" class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-[3rem] shadow-2xl border border-white overflow-hidden">
            <div class="bg-slate-900 p-12 text-white relative">
                <div class="relative z-10">
                    <h2 class="text-3xl font-black tracking-tight">Input Penilaian</h2>
                    <p class="text-slate-400 mt-2 font-medium">Berikan skor (1-100) untuk perkembangan siswa.</p>
                </div>
                <i data-lucide="shield-check" class="absolute -right-4 -bottom-4 w-40 h-40 text-white/5 rotate-12"></i>
            </div>

            <form action="{{ route('guru.assessment.store') }}" method="POST" class="p-10 lg:p-14 space-y-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="flex items-center text-[11px] font-black text-slate-400 uppercase tracking-[2px] mb-4 ml-1">
                            <i data-lucide="user" class="w-4 h-4 mr-2 text-blue-500"></i> Pilih Siswa
                        </label>
                        <select name="evaluatee_id" class="select2-search w-full" required>
                            <option value="">-- Cari Nama Siswa --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ (isset($selectedStudent) && $selectedStudent->id == $student->id) ? 'selected' : '' }}>
                                    {{ $student->name }} | {{ $student->class->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center text-[11px] font-black text-slate-400 uppercase tracking-[2px] mb-4 ml-1">
                            <i data-lucide="calendar" class="w-4 h-4 mr-2 text-blue-500"></i> Periode
                        </label>
                        <select name="period" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 font-bold text-slate-700" required>
                            <option value="Semester Genap 2026">Semester Genap 2026</option>
                            <option value="Semester Ganjil 2025">Semester Ganjil 2025</option>
                        </select>
                    </div>
                </div>

                <div class="bg-slate-50/50 rounded-[2.5rem] p-10 border border-slate-100 space-y-12">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-6">
                        <h3 class="font-black text-slate-800 flex items-center uppercase tracking-wider text-sm">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3 text-blue-600"></i> Indikator Karakter
                        </h3>
                        <span class="bg-white px-4 py-1.5 rounded-full border border-slate-200 text-[10px] font-black text-slate-400 uppercase">Skala 1 - 100</span>
                    </div>
                    
                    @foreach($categories as $category)
                    <div class="space-y-6">
                        <div class="flex justify-between items-end">
                            <label class="font-black text-slate-700 text-lg tracking-tight">{{ $category->name }}</label>
                            <div class="flex flex-col items-end">
                                <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1" id="label-{{ $category->id }}">Cukup</span>
                                <div class="bg-blue-600 text-white text-xl font-black min-w-[3rem] h-12 px-2 flex items-center justify-center rounded-2xl shadow-lg" id="val-{{ $category->id }}">75</div>
                            </div>
                        </div>
                        <input type="range" 
                               name="scores[{{ $category->id }}]" 
                               min="1" 
                               max="100" 
                               step="1" 
                               value="75" 
                               oninput="updateScore(this, '{{ $category->id }}')"
                               class="w-full h-3 bg-slate-200 rounded-full appearance-none accent-blue-600">
                    </div>
                    @endforeach
                </div>

                <div>
                    <label class="flex items-center text-[11px] font-black text-slate-400 uppercase tracking-[2px] mb-4 ml-1">
                        <i data-lucide="message-square" class="w-4 h-4 mr-2 text-blue-500"></i> Catatan Guru
                    </label>
                    <textarea name="general_notes" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[1.5rem] px-8 py-6 focus:border-blue-500 focus:bg-white outline-none transition-all font-medium text-slate-600" rows="3" placeholder="Tuliskan catatan perkembangan spesifik..."></textarea>
                </div>

                <div class="flex flex-col md:flex-row gap-5 pt-6">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-700 hover:to-blue-600 text-white font-black py-6 rounded-2xl shadow-xl flex justify-center items-center uppercase tracking-widest text-sm group">
                        <i data-lucide="save" class="w-5 h-5 mr-3 group-hover:scale-125 transition-transform"></i> Simpan Penilaian
                    </button>
                    <a href="{{ route('guru.assessment.index') }}" class="px-12 py-6 bg-slate-100 text-slate-400 font-black rounded-2xl hover:bg-slate-200 transition-all text-center uppercase tracking-widest text-sm text-decoration-none">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk Update Angka dan Label saat slider digeser
    function updateScore(el, id) {
        const val = parseInt(el.value);
        document.getElementById('val-' + id).innerText = val;
        
        let label = "";
        if (val <= 20) label = "Sangat Kurang";
        else if (val <= 40) label = "Kurang";
        else if (val <= 60) label = "Cukup";
        else if (val <= 85) label = "Baik";
        else label = "Istimewa";
        
        document.getElementById('label-' + id).innerText = label;
    }

    $(document).ready(function() {
        // Inisialisasi Icon Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        // Inisialisasi Select2
        $('.select2-search').select2({ 
            width: '100%', 
            placeholder: "-- Pilih Siswa --", 
            allowClear: true 
        });
    });
</script>
@endsection