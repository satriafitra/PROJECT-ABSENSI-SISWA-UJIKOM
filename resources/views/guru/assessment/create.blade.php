@extends('layouts.admin')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-orange: #f97316;
        --slate-900: #0f172a;
        --slate-50: #f8fafc;
        --font-main: 'Poppins', sans-serif;
    }

    body { 
        font-family: var(--font-main);
        background-color: #f1f5f9;
    }

    /* Override all text to Poppins */
    .font-poppins { font-family: var(--font-main); }

    /* Modern Scrollbar untuk Card Pertanyaan */
    .custom-scroll::-webkit-scrollbar { width: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: var(--primary-orange); }

    /* Likert Circle Styling - Gradasi Ukuran */
    .likert-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        gap: 10px;
    }

    .likert-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        flex: 1;
        transition: all 0.2s ease;
    }

    .likert-circle {
        border: 3px solid #e2e8f0;
        border-radius: 50%;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: white;
        position: relative;
    }

    /* Ukuran Bergradasi: Gede -> Kecil -> Gede */
    .size-1 { width: 34px; height: 34px; } /* Sangat Buruk (Gede) */
    .size-2 { width: 24px; height: 24px; } /* Buruk (Sedang) */
    .size-3 { width: 18px; height: 18px; } /* Cukup (Kecil) */
    .size-4 { width: 24px; height: 24px; } /* Baik (Sedang) */
    .size-5 { width: 34px; height: 34px; } /* Sangat Baik (Gede) */

    /* Efek Saat Diklik (Checked) */
    .likert-item input:checked + .likert-circle.color-1 { background: #ef4444; border-color: #fee2e2; transform: scale(1.15); box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); }
    .likert-item input:checked + .likert-circle.color-2 { background: #f59e0b; border-color: #fef3c7; transform: scale(1.15); box-shadow: 0 0 15px rgba(245, 158, 11, 0.4); }
    .likert-item input:checked + .likert-circle.color-3 { background: #94a3b8; border-color: #f1f5f9; transform: scale(1.15); box-shadow: 0 0 15px rgba(148, 163, 184, 0.4); }
    .likert-item input:checked + .likert-circle.color-4 { background: #f97316; border-color: #ffedd5; transform: scale(1.15); box-shadow: 0 0 15px rgba(249, 115, 22, 0.4); }
    .likert-item input:checked + .likert-circle.color-5 { background: #10b981; border-color: #d1fae5; transform: scale(1.15); box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }

    .likert-label {
        font-size: 9px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-align: center;
    }

    .likert-item input:checked ~ .likert-label { color: var(--slate-900); }

    /* Card Category Animation */
    .category-card { 
        display: none; 
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease;
    }
    .category-card.active {
        display: flex;
        flex-direction: column;
        opacity: 1;
        transform: translateY(0);
    }

    /* Select2 UI Refresh */
    .select2-container--default .select2-selection--single, 
    .select2-container--default .select2-selection--multiple {
        border: 2px solid #f1f5f9 !important;
        border-radius: 1rem !important;
        min-height: 54px !important;
        font-family: var(--font-main);
        background-color: white !important;
    }
</style>

<div class="min-h-screen py-10 px-4 font-poppins">
    <div class="w-full max-w-6xl mx-auto">
        
        {{-- Tombol Back --}}
        <div class="mb-8">
            <a href="{{ route('guru.assessment.index') }}" class="inline-flex items-center text-slate-500 hover:text-orange-600 transition-all font-bold group text-[11px] uppercase tracking-[0.2em]">
                <div class="w-9 h-9 rounded-xl bg-white shadow-sm flex items-center justify-center mr-3 group-hover:rotate-[-10deg] transition-transform">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </div>
                Kembali ke Dashboard
            </a>
        </div>

        <form action="{{ route('guru.assessment.store') }}" method="POST" id="assessmentForm">
            @csrf
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200/60 border border-white overflow-hidden">
                
                {{-- Header Section --}}
                <div class="bg-slate-900 p-10 relative overflow-hidden">
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-3 py-1 rounded-lg bg-orange-500 text-white text-[10px] font-black uppercase tracking-widest">E-Assessment</span>
                                <span class="px-3 py-1 rounded-lg bg-white/10 text-slate-400 text-[10px] font-bold uppercase tracking-widest">v2.1.0</span>
                            </div>
                            <h2 class="text-3xl md:text-5xl font-black text-white tracking-tighter">
                                Evaluasi <span class="text-orange-500 underline decoration-4 underline-offset-8">Karakter</span> Siswa
                            </h2>
                            <p class="text-slate-400 mt-4 font-medium text-sm">Input data penilaian perkembangan karakter objektif.</p>
                        </div>
                        <div class="hidden md:block">
                            <i data-lucide="shield-check" class="w-20 h-20 text-white/5"></i>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-12 space-y-10">
                    {{-- Form Filter Utama --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100">
                        <div class="lg:col-span-4 space-y-3">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Nama Siswa</label>
                            <select name="evaluatee_id" class="select2-search w-full" required>
                                <option value="">Cari Siswa...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lg:col-span-3 space-y-3">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Periode</label>
                            <select name="period" class="w-full bg-white border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold text-sm h-[54px] outline-none focus:border-orange-500 transition-all">
                                <option value="Semester Genap 2026">Semester Genap 2026</option>
                            </select>
                        </div>
                        <div class="lg:col-span-5 space-y-3">
                            <label class="text-[11px] font-extrabold text-slate-400 uppercase tracking-widest ml-1">Kategori Pertanyaan</label>
                            <select id="categorySelector" class="select2-category w-full" multiple="multiple">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Status Kosong --}}
                    <div id="empty-state" class="py-24 text-center border-4 border-dashed border-slate-50 rounded-[3rem] transition-all">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="pencil-line" class="w-10 h-10 text-slate-300"></i>
                        </div>
                        <h3 class="text-slate-800 text-xl font-bold">Siap Menilai?</h3>
                        <p class="text-slate-400 mt-2 font-medium">Pilih siswa dan kategori penilaian di atas.</p>
                    </div>

                    {{-- Container Card Pertanyaan --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" id="indicators-container">
                        @foreach($categories as $category)
                        <div id="card-{{ $category->id }}" class="category-card bg-white rounded-[2.5rem] border-2 border-slate-100 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                            
                            {{-- Header Card --}}
                            <div class="p-8 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                                <div>
                                    <h4 class="font-black text-slate-900 text-xl">{{ $category->name }}</h4>
                                    <span class="text-[10px] text-orange-500 font-bold uppercase tracking-widest">Kategori Perilaku</span>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <div id="avg-{{ $category->id }}" class="text-3xl font-black text-slate-200 leading-none">0</div>
                                    <div class="flex items-center gap-1.5 mt-2" id="status-container-{{ $category->id }}">
                                        <i data-lucide="minus-circle" id="icon-{{ $category->id }}" class="w-4 h-4 text-slate-300"></i>
                                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-tighter" id="label-{{ $category->id }}">Draft</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Area Pertanyaan dengan SCROLL --}}
                            <div class="p-8 space-y-12 max-h-[550px] overflow-y-auto custom-scroll">
                                @forelse($category->questions as $question)
                                <div class="space-y-6">
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0 w-6 h-6 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-bold">
                                            {{ $loop->iteration }}
                                        </div>
                                        <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $question->question_text }}</p>
                                    </div>
                                    
                                    {{-- Likert Scale Bulat --}}
                                    <div class="likert-container px-2">
                                        {{-- Sangat Buruk --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="20" class="hidden" 
                                                   onclick="updateScore('{{ $category->id }}', 20)">
                                            <div class="likert-circle size-1 color-1"></div>
                                            <span class="likert-label">S. Buruk</span>
                                        </label>

                                        {{-- Buruk --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="40" class="hidden"
                                                   onclick="updateScore('{{ $category->id }}', 40)">
                                            <div class="likert-circle size-2 color-2"></div>
                                            <span class="likert-label text-center">Buruk</span>
                                        </label>

                                        {{-- Cukup --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="60" class="hidden"
                                                   onclick="updateScore('{{ $category->id }}', 60)">
                                            <div class="likert-circle size-3 color-3"></div>
                                            <span class="likert-label">Cukup</span>
                                        </label>

                                        {{-- Baik --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="80" class="hidden"
                                                   onclick="updateScore('{{ $category->id }}', 80)">
                                            <div class="likert-circle size-4 color-4"></div>
                                            <span class="likert-label">Baik</span>
                                        </label>

                                        {{-- Sangat Baik --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="100" class="hidden"
                                                   onclick="updateScore('{{ $category->id }}', 100)">
                                            <div class="likert-circle size-5 color-5"></div>
                                            <span class="likert-label text-center">S. Baik</span>
                                        </label>
                                    </div>
                                    <hr class="border-slate-50">
                                </div>
                                @empty
                                <div class="text-center py-10">
                                    <p class="text-slate-400 text-sm italic">Belum ada indikator untuk kategori ini.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Form Footer --}}
                    <div id="footer-section" class="pt-10 border-t border-slate-100 space-y-8" style="display: none;">
                        <div class="space-y-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Catatan Feedback Guru</label>
                            <textarea name="general_notes" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] p-8 focus:bg-white focus:border-orange-500 outline-none transition-all font-semibold text-slate-700 shadow-inner" rows="4" placeholder="Tuliskan perkembangan positif siswa di sini..."></textarea>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-5">
                            <button type="submit" class="flex-[3] bg-slate-900 hover:bg-orange-600 text-white font-black py-7 rounded-2xl shadow-xl hover:shadow-orange-500/40 transition-all duration-300 uppercase tracking-[0.2em] text-[11px] flex justify-center items-center gap-3">
                                <i data-lucide="check-circle" class="w-5 h-5"></i> Finalisasi & Simpan Penilaian
                            </button>
                            <button type="button" onclick="window.location.reload()" class="flex-1 bg-white text-slate-400 border-2 border-slate-100 py-7 rounded-2xl font-bold uppercase tracking-widest text-[10px] hover:bg-slate-50 transition-colors">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi Hitung Skor Rata-rata Kategori
    function updateScore(catId) {
        const card = document.getElementById(`card-${catId}`);
        const checkedRadios = card.querySelectorAll('input[type="radio"]:checked');
        
        if (checkedRadios.length > 0) {
            let total = 0;
            checkedRadios.forEach(radio => total += parseInt(radio.value));
            const average = Math.round(total / checkedRadios.length);

            // Update Angka Rata-rata
            const avgDisplay = document.getElementById('avg-' + catId);
            avgDisplay.innerText = average;
            avgDisplay.className = "text-3xl font-black text-slate-900 leading-none";

            // Update Icon dan Label berdasarkan Range Nilai
            const icon = document.getElementById('icon-' + catId);
            const label = document.getElementById('label-' + catId);
            
            let config = { icon: 'meh', color: '#f59e0b', label: 'CUKUP' };
            if (average <= 25) config = { icon: 'frown', color: '#ef4444', label: 'BURUK' };
            else if (average <= 50) config = { icon: 'meh', color: '#f59e0b', label: 'KURANG' };
            else if (average <= 75) config = { icon: 'smile', color: '#f97316', label: 'BAIK' };
            else if (average <= 90) config = { icon: 'laugh', color: '#10b981', label: 'HEBAT' };
            else config = { icon: 'award', color: '#6366f1', label: 'TELADAN' };

            icon.setAttribute('data-lucide', config.icon);
            icon.style.color = config.color;
            label.innerText = config.label;
            label.style.color = config.color;
            
            lucide.createIcons();
        }
    }

    $(document).ready(function() {
        // Init Icons
        lucide.createIcons();

        // Init Select2
        $('.select2-search').select2({ placeholder: "Pilih Siswa" });
        $('.select2-category').select2({ 
            placeholder: "Pilih Kategori Penilaian",
            closeOnSelect: false,
            allowClear: true
        });

        // Toggle Tampilan Kategori
        $('#categorySelector').on('change', function() {
            const selected = $(this).val() || [];
            $('.category-card').removeClass('active');
            
            if (selected.length > 0) {
                $('#empty-state').fadeOut(200, function() {
                    $('#footer-section').fadeIn();
                    selected.forEach(id => {
                        $(`#card-${id}`).addClass('active');
                    });
                });
            } else {
                $('#footer-section').fadeOut();
                $('#empty-state').fadeIn();
            }
        });
    });
</script>
@endsection