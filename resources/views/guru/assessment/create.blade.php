@extends('layouts.admin')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Styling Dasar & Font */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    
    :root {
        --primary-orange: #f97316;
        --soft-orange: #fff7ed;
        --slate-900: #0f172a;
    }

    /* Override Select2 - Modern Design */
    .select2-container--default .select2-selection--single, 
    .select2-container--default .select2-selection--multiple {
        border: 2px solid #f1f5f9 !important;
        border-radius: 1.25rem !important;
        min-height: 54px !important;
        padding: 8px 15px !important;
        background-color: #f8fafc !important;
        transition: all 0.3s ease;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: var(--primary-orange) !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1) !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: var(--slate-900) !important;
        border: none !important;
        color: white !important;
        border-radius: 0.75rem !important;
        padding: 4px 12px !important;
        margin-top: 4px !important;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fca5a5 !important;
        margin-right: 8px !important;
    }

    /* Custom Slider Thumb */
    input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        height: 22px;
        width: 22px;
        border-radius: 50%;
        background: var(--primary-orange);
        cursor: pointer;
        border: 4px solid white;
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.4);
    }

    /* Category Card Animation */
    .category-card { 
        display: none; 
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .category-card.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    /* Badge Label */
    .badge-category {
        background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
    }
</style>

<div class="min-h-screen bg-[#f8fafc] py-10 px-4 font-['Plus_Jakarta_Sans']">
    <div class="w-full max-w-5xl mx-auto">
        
        {{-- Tombol Kembali --}}
        <div class="mb-8">
            <a href="{{ route('guru.assessment.index') }}" class="inline-flex items-center text-slate-400 hover:text-orange-600 transition-all font-bold group text-[11px] uppercase tracking-[0.2em]">
                <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center mr-3 group-hover:bg-orange-50 group-hover:text-orange-600 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </div>
                Kembali ke Dashboard
            </a>
        </div>

        <form action="{{ route('guru.assessment.store') }}" method="POST" id="assessmentForm">
            @csrf
            
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200 border border-white overflow-hidden transition-all duration-500">
                
                {{-- Header Section --}}
                <div class="bg-slate-900 p-10 relative overflow-hidden">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <span class="inline-block px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-500 text-[10px] font-black uppercase tracking-widest mb-4">
                                Form Penilaian Karakter
                            </span>
                            <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight flex items-center">
                                Input Evaluasi Siswa
                            </h2>
                            <p class="text-slate-400 mt-2 font-medium">Berikan penilaian objektif berdasarkan perilaku harian siswa.</p>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-20 h-20 bg-orange-500 rounded-3xl rotate-12 flex items-center justify-center shadow-lg shadow-orange-500/20">
                                <i data-lucide="star" class="w-10 h-10 text-white -rotate-12 fill-white"></i>
                            </div>
                        </div>
                    </div>
                    {{-- Decorative Background --}}
                    <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                </div>

                <div class="p-8 md:p-12 space-y-10">
                    
                    {{-- Seleksi Utama --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end bg-slate-50/50 p-8 rounded-[2.5rem] border border-slate-100">
                        
                        {{-- Siswa --}}
                        <div class="lg:col-span-4 space-y-3">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1 flex items-center">
                                <i data-lucide="users" class="w-4 h-4 mr-2 text-orange-500"></i> Pilih Nama Siswa
                            </label>
                            <select name="evaluatee_id" class="select2-search w-full" required>
                                <option value="">Cari Siswa...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Periode --}}
                        <div class="lg:col-span-3 space-y-3">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1 flex items-center">
                                <i data-lucide="calendar" class="w-4 h-4 mr-2 text-orange-500"></i> Periode Akademik
                            </label>
                            <select name="period" class="w-full bg-[#f8fafc] border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold text-slate-700 focus:border-orange-500 focus:bg-white outline-none transition-all text-sm h-[54px]">
                                <option value="Semester Genap 2026">Semester Genap 2026</option>
                            </select>
                        </div>

                        {{-- Kategori Multiple --}}
                        <div class="lg:col-span-5 space-y-3">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1 flex items-center">
                                <i data-lucide="layers" class="w-4 h-4 mr-2 text-orange-500"></i> Kategori yang Dinilai
                            </label>
                            <select id="categorySelector" class="select2-category w-full" multiple="multiple">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Empty State --}}
                    <div id="empty-state" class="py-24 text-center border-4 border-dashed border-slate-50 rounded-[3rem] transition-all">
                        <div class="relative inline-block mb-6">
                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto">
                                <i data-lucide="mouse-pointer-click" class="w-10 h-10 text-slate-300"></i>
                            </div>
                            <div class="absolute -top-2 -right-2 w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center animate-bounce">
                                <i data-lucide="sparkles" class="w-4 h-4 text-orange-500"></i>
                            </div>
                        </div>
                        <h3 class="text-slate-800 text-xl font-black">Mulai Penilaian</h3>
                        <p class="text-slate-400 mt-2 max-w-sm mx-auto font-medium">Silahkan pilih siswa dan kategori penilaian di atas untuk menampilkan indikator evaluasi.</p>
                    </div>

                    {{-- Indikator Container --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8" id="indicators-container">
                        @foreach($categories as $category)
                        <div id="card-{{ $category->id }}" class="category-card bg-white rounded-[2rem] p-8 border-2 border-slate-100 hover:border-orange-200 transition-all duration-300 shadow-sm hover:shadow-xl hover:shadow-orange-500/5">
                            <div class="flex justify-between items-start mb-8">
                                <div class="space-y-1">
                                    <h4 class="font-black text-slate-900 text-xl tracking-tight leading-tight">{{ $category->name }}</h4>
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Performance Score</span>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <div id="avg-{{ $category->id }}" class="bg-slate-100 text-slate-700 text-2xl font-black w-14 h-14 flex items-center justify-center rounded-2xl transition-all duration-500 shadow-inner">
                                        0
                                    </div>
                                    <div id="icon-container-{{ $category->id }}" class="flex items-center gap-1.5 text-slate-300">
                                        <i data-lucide="meh" id="icon-{{ $category->id }}" class="w-4 h-4"></i>
                                        <span class="text-[9px] font-black uppercase tracking-tighter" id="label-{{ $category->id }}">N/A</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-8">
                                @forelse($category->questions as $question)
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center px-1">
                                        <p class="text-sm font-bold text-slate-600 max-w-[80%] leading-relaxed">{{ $question->question_text }}</p>
                                        <span class="text-lg font-black text-orange-500 bg-orange-50 px-3 py-1 rounded-xl" id="val-q-{{ $question->id }}">0</span>
                                    </div>
                                    <div class="relative flex items-center group">
                                        <input type="range" 
                                               name="scores[{{ $question->id }}]" 
                                               min="0" max="100" step="1" value="0" 
                                               data-category="{{ $category->id }}"
                                               oninput="updateSingleScore(this, '{{ $question->id }}', '{{ $category->id }}')"
                                               class="w-full h-2 bg-slate-100 rounded-full appearance-none cursor-pointer accent-orange-500 transition-all">
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                    <p class="text-slate-400 text-xs italic font-medium">Belum ada indikator tersedia.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer Section --}}
                    <div id="footer-section" class="space-y-8 pt-6" style="display: none;">
                        <div class="space-y-4">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider ml-1 flex items-center">
                                <i data-lucide="message-circle" class="w-4 h-4 mr-2 text-orange-500"></i> Catatan Evaluasi Akhir
                            </label>
                            <textarea name="general_notes" class="w-full bg-[#f8fafc] border-2 border-slate-100 rounded-[1.5rem] px-8 py-6 focus:border-orange-500 focus:bg-white outline-none transition-all font-semibold text-slate-700 text-sm shadow-inner" rows="4" placeholder="Berikan feedback positif atau hal yang perlu ditingkatkan oleh siswa..."></textarea>
                        </div>

                        <div class="flex flex-col md:flex-row gap-5">
                            <button type="submit" class="flex-[3] bg-slate-900 hover:bg-orange-600 text-white font-black py-6 rounded-2xl shadow-2xl shadow-slate-300 hover:shadow-orange-500/40 transition-all duration-300 flex justify-center items-center uppercase tracking-[0.2em] text-[11px] group">
                                <i data-lucide="save" class="w-5 h-5 mr-3 group-hover:scale-125 transition-transform"></i> Finalisasi & Simpan Nilai
                            </button>
                            <a href="{{ route('guru.assessment.index') }}" class="flex-1 py-6 bg-white text-slate-400 border-2 border-slate-100 font-bold rounded-2xl hover:bg-slate-50 hover:text-slate-600 transition-all text-center uppercase tracking-widest text-[11px] flex items-center justify-center">
                                Batalkan
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function updateSingleScore(el, qId, catId) {
        // 1. Update text per pertanyaan
        document.getElementById('val-q-' + qId).innerText = el.value;

        // 2. Kalkulasi Rata-rata
        const allInCat = document.querySelectorAll(`input[data-category="${catId}"]`);
        let total = 0;
        allInCat.forEach(input => total += parseInt(input.value));
        const average = Math.round(total / allInCat.length);
        
        // 3. UI Update Kategori Card
        const avgBox = document.getElementById('avg-' + catId);
        const icon = document.getElementById('icon-' + catId);
        const label = document.getElementById('label-' + catId);
        
        avgBox.innerText = average;
        
        let config = { icon: 'meh', color: '#94a3b8', label: 'CUKUP' };

        if (average <= 20) config = { icon: 'frown', color: '#ef4444', label: 'BURUK' };
        else if (average <= 45) config = { icon: 'meh', color: '#f59e0b', label: 'KURANG' };
        else if (average <= 75) config = { icon: 'smile', color: '#f97316', label: 'BAIK' };
        else if (average <= 90) config = { icon: 'laugh', color: '#10b981', label: 'HEBAT' };
        else config = { icon: 'award', color: '#6366f1', label: 'TELADAN' };

        avgBox.style.backgroundColor = config.color;
        avgBox.style.color = "#ffffff";
        avgBox.style.boxShadow = `0 10px 20px -5px ${config.color}66`;
        
        icon.setAttribute('data-lucide', config.icon);
        icon.parentElement.style.color = config.color;
        label.innerText = config.label;
        
        lucide.createIcons();
    }

    $(document).ready(function() {
        lucide.createIcons();

        $('.select2-search').select2({ placeholder: "Cari Siswa..." });
        
        $('.select2-category').select2({
            placeholder: "Pilih Kategori Penilaian",
            allowClear: true,
            closeOnSelect: false
        });

        // Toggle Tampilan Berdasarkan Pilihan Kategori
        $('#categorySelector').on('change', function() {
            const selected = $(this).val() || [];
            
            $('.category-card').removeClass('active');
            
            if (selected.length > 0) {
                $('#empty-state').fadeOut(300, function() {
                    $('#footer-section').fadeIn();
                    selected.forEach(id => {
                        $(`#card-${id}`).addClass('active');
                    });
                });
            } else {
                $('#footer-section').fadeOut();
                $('#empty-state').fadeIn();
            }
            lucide.createIcons();
        });
    });
</script>
@endsection