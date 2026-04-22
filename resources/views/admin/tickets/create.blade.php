@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfd; }
    .fade-up {
        animation: fadeUp 0.6s ease-out forwards;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="p-8 min-h-screen">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.tickets.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-slate-400 hover:text-orange-500 hover:border-orange-200 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Tambah Tiket Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Buat tiket aduan atas nama siswa</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-600">
        <ul class="list-disc pl-5 text-sm font-medium">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/40 border border-slate-100 max-w-3xl fade-up">
        <form action="{{ route('admin.tickets.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                {{-- Pemilihan Siswa --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Siswa (Pelapor)</label>
                    <select name="reporter_id" required class="select2-student w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->nis }} - {{ $student->name }} (Kelas: {{ $student->class->name ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Subjek --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Subjek Aduan</label>
                    <input type="text" name="subject" required placeholder="Contoh: Kesulitan saat scan QR code" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition">
                </div>

                {{-- Prioritas --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tingkat Prioritas</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="Low" class="peer sr-only" checked>
                            <div class="text-center px-4 py-3 rounded-2xl border-2 border-slate-100 text-slate-500 peer-checked:border-slate-500 peer-checked:bg-slate-50 peer-checked:text-slate-700 font-bold transition">
                                Rendah (Low)
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="Mid" class="peer sr-only">
                            <div class="text-center px-4 py-3 rounded-2xl border-2 border-slate-100 text-slate-500 peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-600 font-bold transition">
                                Sedang (Mid)
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="High" class="peer sr-only">
                            <div class="text-center px-4 py-3 rounded-2xl border-2 border-slate-100 text-slate-500 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-600 font-bold transition">
                                Tinggi (High)
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Detail</label>
                    <textarea name="description" rows="5" required placeholder="Jelaskan secara detail kendala yang dialami..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition resize-none"></textarea>
                </div>

                {{-- Submit --}}
                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.tickets.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">Batal</a>
                    <button type="submit" class="px-8 py-3 rounded-2xl bg-orange-500 text-white font-bold shadow-xl shadow-orange-500/30 hover:bg-orange-600 transition hover:-translate-y-1 flex items-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i> Buat Tiket
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    /* Styling khusus agar Select2 sesuai dengan tema AkvaScan */
    .select2-container--default .select2-selection--single {
        background-color: #f8fafc; /* bg-slate-50 */
        border: 1px solid #e2e8f0; /* border-slate-200 */
        border-radius: 1rem; /* rounded-2xl */
        height: 3.25rem;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #fb923c; /* border-orange-400 */
        box-shadow: 0 0 0 2px #ffedd5; /* ring-orange-100 */
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 3rem;
        right: 1rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #334155;
        font-size: 0.875rem; /* text-sm */
        font-weight: 500;
        padding-left: 0;
    }
    .select2-dropdown {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    $(document).ready(function() {
        $('.select2-student').select2({
            placeholder: "-- Cari & Pilih Siswa --",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection
