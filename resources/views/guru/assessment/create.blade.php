@extends('layouts.admin')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* Styling Select2 Agar Sama dengan Tema Anda */
    .select2-container--default .select2-selection--single {
        border: 2px solid #f1f5f9 !important;
        border-radius: 1rem !important;
        height: 56px !important;
        padding: 14px 16px !important;
        background-color: #f8fafc !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #2563eb !important;
        background-color: #ffffff !important;
    }
</style>

<div class="min-h-[90vh] bg-[#f8fafc] py-8 px-4">
    <div class="w-full max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('guru.assessment.index') }}" class="flex items-center text-gray-500 hover:text-blue-600 transition-colors font-medium group">
                <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-slate-900 p-10 text-white relative">
                <h2 class="text-2xl font-bold">Input Penilaian Karakter</h2>
                <p class="text-slate-400">Pilih siswa dan berikan penilaian indikator sikap.</p>
            </div>

            <form action="{{ route('guru.assessment.store') }}" method="POST" class="p-8 lg:p-12 space-y-8">
                @csrf

                <div>
                    <label class="flex items-center text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-3 ml-1">
                        <i data-lucide="user" class="w-4 h-4 mr-2 text-blue-500"></i> Pilih Siswa
                    </label>
                    <select name="evaluatee_id" class="select2-search w-full" required>
                        <option value="">-- Cari Nama Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ (isset($selectedStudent) && $selectedStudent->id == $student->id) ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100 space-y-8">
                    <h3 class="font-bold text-slate-700 border-b pb-2 flex items-center">
                        <i data-lucide="award" class="w-5 h-5 mr-2 text-blue-500"></i> Indikator Penilaian
                    </h3>
                    
                    @foreach($categories as $category)
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="font-bold text-slate-600 text-sm">{{ $category->name }}</label>
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-bold" id="val-{{ $category->id }}">3</span>
                        </div>
                        <input type="range" name="scores[{{ $category->id }}]" min="1" max="5" step="1" value="3" 
                               oninput="document.getElementById('val-{{ $category->id }}').innerText = this.value"
                               class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                    </div>
                    @endforeach
                </div>

                <div>
                    <label class="flex items-center text-[13px] font-bold text-slate-500 uppercase tracking-wider mb-3 ml-1">
                        <i data-lucide="message-square" class="w-4 h-4 mr-2 text-blue-500"></i> Catatan Guru
                    </label>
                    <textarea name="notes" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 focus:border-blue-500 outline-none transition-all" rows="3" placeholder="Tambahkan catatan jika perlu..."></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 rounded-2xl shadow-xl shadow-blue-200 transition-all flex justify-center items-center">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i> Simpan Penilaian
                    </button>
                    <a href="{{ route('guru.assessment.index') }}" class="px-8 py-5 bg-slate-100 text-slate-500 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        lucide.createIcons();
        $('.select2-search').select2({
            width: '100%',
            placeholder: "-- Pilih Siswa --",
            allowClear: true
        });
    });
</script>
@endsection