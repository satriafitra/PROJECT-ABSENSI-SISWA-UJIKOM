@extends('layouts.admin')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
    
    /* Likert Scales */
    .likert-container { display: flex; justify-content: space-between; align-items: center; padding: 20px 0; gap: 10px; }
    .likert-item { display: flex; flex-direction: column; align-items: center; gap: 12px; cursor: pointer; flex: 1; transition: all 0.2s ease; }
    .likert-circle { border: 3px solid #e2e8f0; border-radius: 50%; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); background: white; }
    
    .size-1 { width: 36px; height: 36px; } 
    .size-2 { width: 28px; height: 28px; } 
    .size-3 { width: 20px; height: 20px; } 
    .size-4 { width: 28px; height: 28px; } 
    .size-5 { width: 36px; height: 36px; } 

    .likert-item input:checked + .likert-circle.color-1 { background: #ef4444; border-color: #fee2e2; transform: scale(1.15); box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
    .likert-item input:checked + .likert-circle.color-2 { background: #f59e0b; border-color: #fef3c7; transform: scale(1.15); box-shadow: 0 0 20px rgba(245, 158, 11, 0.4); }
    .likert-item input:checked + .likert-circle.color-3 { background: #94a3b8; border-color: #f1f5f9; transform: scale(1.15); box-shadow: 0 0 20px rgba(148, 163, 184, 0.4); }
    .likert-item input:checked + .likert-circle.color-4 { background: #f97316; border-color: #ffedd5; transform: scale(1.15); box-shadow: 0 0 20px rgba(249, 115, 22, 0.4); }
    .likert-item input:checked + .likert-circle.color-5 { background: #10b981; border-color: #d1fae5; transform: scale(1.15); box-shadow: 0 0 20px rgba(16, 185, 129, 0.4); }

    .likert-label { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; text-align: center; }
    .likert-item input:checked ~ .likert-label { color: #0f172a; }

    /* Category Page Animation */
    .category-page { display: none; opacity: 0; transform: translateY(15px); transition: all 0.4s ease; }
    .category-page.active { display: block; opacity: 1; transform: translateY(0); }

    /* Sidebar Item */
    .cat-tab { cursor: pointer; transition: all 0.3s ease; }
    .cat-tab.active { background: white; border-color: #f97316; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
    .cat-tab.active .cat-icon { color: #f97316; background: #fff7ed; }
    .cat-tab.active .cat-text { color: #f97316; font-weight: 800; }
    
    .select2-container--default .select2-selection--single {
        border: 2px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        height: 54px !important;
        display: flex;
        align-items: center;
    }
</style>

<div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8 font-jakarta">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <a href="{{ route('guru.assessment.index') }}" class="inline-flex items-center text-slate-400 hover:text-orange-500 font-bold text-xs uppercase tracking-widest mb-4 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali ke Riwayat
                </a>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Penilaian <span class="text-orange-500">Siswa</span>
                </h1>
                <p class="text-slate-500 font-medium mt-2 text-lg">Halaman khusus evaluasi karakter dan sikap siswa secara detail.</p>
            </div>
            <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3">
                <i data-lucide="calendar" class="text-orange-500 w-5 h-5"></i>
                <span class="font-bold text-slate-700">Semester Genap 2026</span>
            </div>
        </div>

        <form action="{{ route('guru.assessment.store') }}" method="POST" id="assessmentForm">
            @csrf
            
            {{-- Form Target Siswa --}}
            <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 mb-8 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-64 h-64 bg-orange-50 rounded-full blur-3xl -z-10 translate-x-1/2 -translate-y-1/2"></div>
                
                <div class="w-16 h-16 rounded-2xl bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0">
                    <i data-lucide="user-check" class="w-8 h-8"></i>
                </div>
                
                <div class="flex-grow w-full md:w-auto">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-2">Pilih Siswa yang Akan Dinilai</label>
                    <select name="evaluatee_id" class="select2-search w-full" required>
                        <option value="">Cari Nama Siswa...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} - {{ $student->nis ?? 'NISN Kosong' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Layout Utama: Sidebar & Area Soal --}}
            <div class="flex flex-col lg:flex-row gap-8">
                
                {{-- Sidebar Kategori --}}
                <div class="w-full lg:w-1/3 xl:w-1/4 flex-shrink-0">
                    <div class="bg-slate-50 rounded-[2rem] p-4 border border-slate-200 sticky top-8">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest px-4 mb-4 mt-2">Daftar Kategori</h3>
                        <div class="space-y-2">
                            @foreach($categories as $index => $category)
                            <div class="cat-tab p-4 rounded-2xl border-2 border-transparent flex items-center justify-between {{ $index == 0 ? 'active' : '' }}" onclick="switchCategory('{{ $category->id }}', this)">
                                <div class="flex items-center gap-4">
                                    <div class="cat-icon w-10 h-10 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center font-bold transition-colors">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="cat-text font-bold text-slate-600 transition-colors">{{ $category->name }}</div>
                                </div>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
                            </div>
                            @endforeach
                            <div class="cat-tab p-4 rounded-2xl border-2 border-transparent flex items-center justify-between" onclick="switchCategory('final', this)">
                                <div class="flex items-center gap-4">
                                    <div class="cat-icon w-10 h-10 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center font-bold transition-colors">
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    </div>
                                    <div class="cat-text font-bold text-slate-600 transition-colors">Selesai</div>
                                </div>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Area Soal (Halaman Sendiri) --}}
                <div class="w-full lg:w-2/3 xl:w-3/4">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 min-h-[600px] relative overflow-hidden">
                        
                        @foreach($categories as $index => $category)
                        <div id="page-{{ $category->id }}" class="category-page {{ $index == 0 ? 'active' : '' }} p-8 md:p-12">
                            
                            {{-- Header Halaman Kategori --}}
                            <div class="border-b-2 border-slate-100 pb-8 mb-8 flex justify-between items-end">
                                <div>
                                    <span class="px-4 py-1.5 rounded-xl bg-orange-100 text-orange-600 text-[10px] font-black uppercase tracking-widest mb-4 inline-block">Kategori {{ $index + 1 }}</span>
                                    <h2 class="text-3xl md:text-4xl font-black text-slate-800">{{ $category->name }}</h2>
                                    <p class="text-slate-500 font-medium mt-2">Berikan penilaian yang objektif berdasarkan pengamatan Anda.</p>
                                </div>
                                <div class="hidden sm:block text-right">
                                    <div id="avg-{{ $category->id }}" class="text-5xl font-black text-slate-200">0</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Skor Rata-rata</div>
                                </div>
                            </div>

                            {{-- Daftar Pertanyaan --}}
                            <div class="space-y-12">
                                @forelse($category->questions as $qIndex => $question)
                                <div class="bg-slate-50/50 rounded-3xl p-6 md:p-8 border border-slate-100 hover:border-orange-200 transition-colors">
                                    <div class="flex gap-4 mb-8">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-sm font-bold shadow-sm">
                                            {{ $qIndex + 1 }}
                                        </div>
                                        <h4 class="text-lg font-bold text-slate-700 leading-relaxed">{{ $question->question_text }}</h4>
                                    </div>
                                    
                                    {{-- Likert Scale Bulat --}}
                                    <div class="likert-container px-0 sm:px-8">
                                        {{-- Sangat Buruk --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="20" class="hidden" onclick="updateScore('{{ $category->id }}')">
                                            <div class="likert-circle size-1 color-1"></div>
                                            <span class="likert-label">S. Buruk</span>
                                        </label>

                                        {{-- Buruk --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="40" class="hidden" onclick="updateScore('{{ $category->id }}')">
                                            <div class="likert-circle size-2 color-2"></div>
                                            <span class="likert-label">Buruk</span>
                                        </label>

                                        {{-- Cukup --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="60" class="hidden" onclick="updateScore('{{ $category->id }}')">
                                            <div class="likert-circle size-3 color-3"></div>
                                            <span class="likert-label">Cukup</span>
                                        </label>

                                        {{-- Baik --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="80" class="hidden" onclick="updateScore('{{ $category->id }}')">
                                            <div class="likert-circle size-4 color-4"></div>
                                            <span class="likert-label">Baik</span>
                                        </label>

                                        {{-- Sangat Baik --}}
                                        <label class="likert-item">
                                            <input type="radio" name="scores[{{ $question->id }}]" value="100" class="hidden" onclick="updateScore('{{ $category->id }}')">
                                            <div class="likert-circle size-5 color-5"></div>
                                            <span class="likert-label">S. Baik</span>
                                        </label>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-20">
                                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i data-lucide="folder-open" class="text-slate-300 w-10 h-10"></i>
                                    </div>
                                    <p class="text-slate-500 font-bold text-lg">Belum Ada Indikator</p>
                                    <p class="text-slate-400 text-sm">Kategori ini belum memiliki pertanyaan penilaian.</p>
                                </div>
                                @endforelse
                            </div>

                        </div>
                        @endforeach
                        
                        {{-- Halaman Finalisasi --}}
                        <div id="page-final" class="category-page p-8 md:p-12 text-center">
                            <div class="py-12">
                                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8">
                                    <i data-lucide="check-circle-2" class="text-green-500 w-12 h-12"></i>
                                </div>
                                <h2 class="text-4xl font-black text-slate-800 mb-4">Selesai Menilai?</h2>
                                <p class="text-slate-500 font-medium text-lg mb-10 max-w-xl mx-auto">Pastikan Anda telah mengisi seluruh indikator pertanyaan. Tambahkan catatan opsional di bawah ini jika diperlukan, lalu klik simpan.</p>
                                
                                <div class="text-left mb-10">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4 ml-4">Catatan Feedback Guru</label>
                                    <textarea name="general_notes" class="w-full bg-slate-50 border-2 border-slate-100 rounded-[2rem] p-8 focus:bg-white focus:border-orange-500 outline-none transition-all font-semibold text-slate-700 shadow-inner" rows="4" placeholder="Tuliskan perkembangan positif siswa atau masukan khusus..."></textarea>
                                </div>

                                <button type="submit" class="w-full bg-slate-900 hover:bg-orange-600 text-white font-black py-6 rounded-2xl shadow-xl hover:shadow-orange-500/40 transition-all duration-300 uppercase tracking-[0.2em] text-sm flex justify-center items-center gap-3">
                                    <i data-lucide="save" class="w-5 h-5"></i> Simpan Penilaian Siswa
                                </button>
                            </div>
                        </div>

                    </div>
                    
                    {{-- Navigasi Bawah --}}
                    <div class="mt-6 flex justify-between items-center bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
                        <button type="button" id="btn-prev" class="px-6 py-4 md:px-8 md:py-4 rounded-2xl font-bold text-slate-500 hover:bg-slate-100 transition-colors flex items-center gap-2 opacity-50 cursor-not-allowed" disabled>
                            <i data-lucide="arrow-left" class="w-5 h-5"></i> Sebelumnya
                        </button>
                        <button type="button" id="btn-next" class="px-6 py-4 md:px-8 md:py-4 rounded-2xl font-bold text-white bg-orange-500 hover:bg-orange-600 transition-all shadow-md flex items-center gap-2">
                            Selanjutnya <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </button>
                    </div>

                </div>
            </div>

        </form>
    </div>
</div>

<script>
    const categoryIds = [
        @foreach($categories as $category) "{{ $category->id }}", @endforeach
        "final"
    ];
    let currentIndex = 0;

    function switchCategory(id, element) {
        // Update Sidebar UI
        document.querySelectorAll('.cat-tab').forEach(el => el.classList.remove('active'));
        if(element) {
            element.classList.add('active');
        } else {
            const tabIndex = categoryIds.indexOf(id);
            const tabs = document.querySelectorAll('.cat-tab');
            if(tabs[tabIndex]) tabs[tabIndex].classList.add('active');
        }

        // Hide all pages
        document.querySelectorAll('.category-page').forEach(page => page.classList.remove('active'));
        
        // Show target page
        document.getElementById('page-' + id).classList.add('active');
        
        // Update index
        currentIndex = categoryIds.indexOf(id);
        updateNavButtons();
        window.scrollTo({ top: 300, behavior: 'smooth' });
    }

    function updateNavButtons() {
        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');

        if (currentIndex === 0) {
            btnPrev.disabled = true;
            btnPrev.classList.add('opacity-50', 'cursor-not-allowed');
            btnPrev.classList.remove('hover:bg-slate-100');
        } else {
            btnPrev.disabled = false;
            btnPrev.classList.remove('opacity-50', 'cursor-not-allowed');
            btnPrev.classList.add('hover:bg-slate-100');
        }

        if (currentIndex === categoryIds.length - 1) {
            btnNext.style.display = 'none'; // Hide next button on final page
        } else {
            btnNext.style.display = 'flex';
        }
    }

    document.getElementById('btn-prev').addEventListener('click', () => {
        if (currentIndex > 0) {
            switchCategory(categoryIds[currentIndex - 1]);
        }
    });

    document.getElementById('btn-next').addEventListener('click', () => {
        if (currentIndex < categoryIds.length - 1) {
            switchCategory(categoryIds[currentIndex + 1]);
        }
    });

    function updateScore(catId) {
        const page = document.getElementById(`page-${catId}`);
        const checkedRadios = page.querySelectorAll('input[type="radio"]:checked');
        
        if (checkedRadios.length > 0) {
            let total = 0;
            checkedRadios.forEach(radio => total += parseInt(radio.value));
            const average = Math.round(total / checkedRadios.length);

            // Update Angka Rata-rata
            const avgDisplay = document.getElementById('avg-' + catId);
            if(avgDisplay) {
                avgDisplay.innerText = average;
                avgDisplay.className = "text-5xl font-black text-orange-500 transition-colors";
            }
        }
    }

    $(document).ready(function() {
        lucide.createIcons();
        $('.select2-search').select2({ placeholder: "Cari Nama Siswa..." });
        updateNavButtons();
    });
</script>
@endsection