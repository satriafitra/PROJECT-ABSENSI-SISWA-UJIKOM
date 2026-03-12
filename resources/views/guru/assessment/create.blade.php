@extends('layouts.admin')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    
    :root {
        --primary-orange: #f97316;
        --slate-900: #0f172a;
    }

    body { font-family: 'Plus_Jakarta_Sans', sans-serif; }

    /* Modern Scrollbar */
    .custom-scroll::-webkit-scrollbar { width: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #f97316; }

    /* Likert Circle Styling */
    .likert-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }

    .likert-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        flex: 1;
    }

    .likert-circle {
        width: 14px;
        height: 14px;
        border: 3px solid #e2e8f0;
        border-radius: 50%;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        background: white;
    }

    /* Ukuran Bergradasi sesuai saran: Bulat Gede -> Kecil -> Gede */
    .size-1 { width: 32px; height: 32px; } /* Sangat Buruk */
    .size-2 { width: 22px; height: 22px; } /* Buruk */
    .size-3 { width: 16px; height: 16px; } /* Cukup */
    .size-4 { width: 22px; height: 22px; } /* Baik */
    .size-5 { width: 32px; height: 32px; } /* Sangat Baik */

    .likert-item input:checked + .likert-circle.color-1 { background: #ef4444; border-color: #fca5a5; transform: scale(1.1); box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); }
    .likert-item input:checked + .likert-circle.color-2 { background: #f59e0b; border-color: #fcd34d; transform: scale(1.1); box-shadow: 0 0 15px rgba(245, 158, 11, 0.4); }
    .likert-item input:checked + .likert-circle.color-3 { background: #94a3b8; border-color: #cbd5e1; transform: scale(1.1); box-shadow: 0 0 15px rgba(148, 163, 184, 0.4); }
    .likert-item input:checked + .likert-circle.color-4 { background: #f97316; border-color: #fdba74; transform: scale(1.1); box-shadow: 0 0 15px rgba(249, 115, 22, 0.4); }
    .likert-item input:checked + .likert-circle.color-5 { background: #10b981; border-color: #6ee7b7; transform: scale(1.1); box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }

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

    /* Select2 Styling */
    .select2-container--default .select2-selection--single, 
    .select2-container--default .select2-selection--multiple {
        border: 2px solid #f1f5f9 !important;
        border-radius: 1.25rem !important;
        min-height: 54px !important;
        background-color: #f8fafc !important;
    }
</style>

<div class="min-h-screen bg-[#f8fafc] py-10 px-4">
    <div class="w-full max-w-6xl mx-auto">
        
        <div class="mb-8 flex justify-between items-center">
            <a href="{{ route('guru.assessment.index') }}" class="inline-flex items-center text-slate-400 hover:text-orange-600 transition-all font-bold group text-[11px] uppercase tracking-widest">
                <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center mr-3">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </div>
                Kembali
            </a>
        </div>

        <form action="{{ route('guru.assessment.store') }}" method="POST" id="assessmentForm">
            @csrf
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-slate-200 border border-white overflow-hidden">
                
                {{-- Header --}}
                <div class="bg-slate-900 p-10 relative">
                    <div class="relative z-10 flex justify-between items-center">
                        <div>
                            <span class="inline-block px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-500 text-[10px] font-black uppercase tracking-widest mb-4">Assessment System v2</span>
                            <h2 class="text-4xl font-black text-white tracking-tight tracking-tighter">Evaluasi Karakter <span class="text-orange-500">Siswa</span></h2>
                        </div>
                        <i data-lucide="clipboard-check" class="w-16 h-16 text-slate-800"></i>
                    </div>
                </div>

                <div class="p-8 md:p-12 space-y-10">
                    {{-- Input Area --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 bg-slate-50 p-8 rounded-[2.5rem]">
                        <div class="lg:col-span-4 space-y-3">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Pilih Siswa</label>
                            <select name="evaluatee_id" class="select2-search w-full" required>
                                <option value="">Cari Nama...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="lg:col-span-3 space-y-3">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Periode</label>
                            <select name="period" class="w-full bg-white border-2 border-slate-100 rounded-2xl px-5 py-3 font-bold text-sm h-[54px]">
                                <option value="Semester Genap 2026">Semester Genap 2026</option>
                            </select>
                        </div>
                        <div class="lg:col-span-5 space-y-3">
                            <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Kategori Penilaian</label>
                            <select id="categorySelector" class="select2-category w-full" multiple="multiple">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Empty State --}}
                    <div id="empty-state" class="py-20 text-center border-4 border-dashed border-slate-50 rounded-[3rem]">
                        <i data-lucide="layers" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
                        <p class="text-slate-400 font-bold">Pilih kategori untuk memulai penilaian</p>
                    </div>

                    {{-- Main Assessment Area --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" id="indicators-container">
                        @foreach($categories as $category)
                        <div id="card-{{ $category->id }}" class="category-card bg-white rounded-[2.5rem] border-2 border-slate-100 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                            {{-- Card Header --}}
                            <div class="p-8 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                                <div>
                                    <h4 class="font-black text-slate-900 text-2xl tracking-tight">{{ $category->name }}</h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Total Indikator: {{ $category->questions->count() }}</p>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <div id="avg-{{ $category->id }}" class="text-3xl font-black text-slate-300 mb-1">0</div>
                                    <div class="flex items-center gap-2" id="status-container-{{ $category->id }}">
                                        <i data-lucide="minus" id="icon-{{ $category->id }}" class="w-4 h-4 text-slate-300"></i>
                                        <span class="text-[10px] font-black text-slate-300 uppercase" id="label-{{ $category->id }}">Belum Dinilai</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Questions Area (SCROLLABLE) --}}
                            <div class="p-8 space-y-10 max-h-[500px] overflow-y-auto custom-scroll">
                                @forelse($category->questions as $question)
                                <div class="space-y-6 pb-6 border-b border-slate-50 last:border-0">
                                    <p class="text-sm font-extrabold text-slate-700 leading-relaxed">{{ $question->question_text }}</p>
                                    
                                    <div class="likert-container">
                                        {{-- Sangat Buruk (20) --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="20" class="hidden" 
                                                   onclick="updateScore('{{ $category->id }}', '{{ $question->id }}', 20)">
                                            <div class="likert-circle size-1 color-1"></div>
                                            <span class="text-[8px] font-black text-slate-400 uppercase">Sangat Buruk</span>
                                        </label>

                                        {{-- Buruk (40) --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="40" class="hidden"
                                                   onclick="updateScore('{{ $category->id }}', '{{ $question->id }}', 40)">
                                            <div class="likert-circle size-2 color-2"></div>
                                            <span class="text-[8px] font-black text-slate-400 uppercase text-center">Buruk</span>
                                        </label>

                                        {{-- Cukup (60) --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="60" class="hidden"
                                                   onclick="updateScore('{{ $category->id }}', '{{ $question->id }}', 60)">
                                            <div class="likert-circle size-3 color-3"></div>
                                            <span class="text-[8px] font-black text-slate-400 uppercase">Cukup</span>
                                        </label>

                                        {{-- Baik (80) --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="80" class="hidden"
                                                   onclick="updateScore('{{ $category->id }}', '{{ $question->id }}', 80)">
                                            <div class="likert-circle size-4 color-4"></div>
                                            <span class="text-[8px] font-black text-slate-400 uppercase">Baik</span>
                                        </label>

                                        {{-- Sangat Baik (100) --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="100" class="hidden"
                                                   onclick="updateScore('{{ $category->id }}', '{{ $question->id }}', 100)">
                                            <div class="likert-circle size-5 color-5"></div>
                                            <span class="text-[8px] font-black text-slate-400 uppercase">Sangat Baik</span>
                                        </label>
                                    </div>
                                </div>
                                @empty
                                <p class="text-slate-400 text-center italic">Tidak ada pertanyaan.</p>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer --}}
                    <div id="footer-section" class="pt-10 space-y-6" style="display: none;">
                        <textarea name="general_notes" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] p-8 focus:bg-white focus:border-orange-500 outline-none transition-all font-bold text-slate-700" rows="3" placeholder="Tambahkan catatan untuk siswa..."></textarea>
                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <button type="submit" class="flex-[3] bg-slate-900 hover:bg-orange-600 text-white font-black py-7 rounded-2xl transition-all uppercase tracking-widest text-xs flex justify-center items-center gap-3">
                                <i data-lucide="save"></i> Simpan Penilaian Siswa
                            </button>
                            <button type="button" onclick="location.reload()" class="flex-1 bg-white text-slate-400 border-2 border-slate-100 py-7 rounded-2xl font-black uppercase text-[10px] tracking-widest">Reset Form</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function updateScore(catId, qId, value) {
        // Ambil semua input yang sudah terisi di kategori ini
        const card = document.getElementById(`card-${catId}`);
        const selectedInputs = card.querySelectorAll('input[type="radio"]:checked');
        const totalQuestions = card.querySelectorAll('.likert-container').length;

        let sum = 0;
        selectedInputs.forEach(input => sum += parseInt(input.value));
        const average = Math.round(sum / selectedInputs.length);

        // Update UI
        const avgDisplay = document.getElementById('avg-' + catId);
        const icon = document.getElementById('icon-' + catId);
        const label = document.getElementById('label-' + catId);
        const container = document.getElementById('status-container-' + catId);

        avgDisplay.innerText = average;
        avgDisplay.classList.remove('text-slate-300');
        avgDisplay.classList.add('text-slate-900');

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

    $(document).ready(function() {
        lucide.createIcons();

        $('.select2-search').select2({ placeholder: "Pilih Siswa" });
        $('.select2-category').select2({ placeholder: "Pilih Kategori", closeOnSelect: false });

        $('#categorySelector').on('change', function() {
            const selected = $(this).val() || [];
            $('.category-card').removeClass('active');
            
            if (selected.length > 0) {
                $('#empty-state').hide();
                $('#footer-section').show();
                selected.forEach(id => {
                    $(`#card-${id}`).addClass('active');
                });
            } else {
                $('#empty-state').show();
                $('#footer-section').hide();
            }
        });
    });
</script>
@endsection