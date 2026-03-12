@extends('layouts.admin')
@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800">Kategori Penilaian</h1>
            <p class="text-slate-500">Kelola kategori dan indikator pertanyaan penilaian karakter.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah').classList.toggle('hidden')" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-5 h-5"></i> Tambah Kategori
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $cat)
        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6">
                <div class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest">
                    {{ $cat->questions_count }} Indikator
                </div>
            </div>
            
            <h3 class="text-xl font-black text-slate-800 mb-2">{{ $cat->name }}</h3>
            <p class="text-slate-500 text-sm mb-6 line-clamp-2">{{ $cat->description ?? 'Tidak ada deskripsi.' }}</p>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.assessment-category.questions', $cat->id) }}" class="flex-1 bg-slate-900 text-white text-center py-3 rounded-xl font-bold text-sm hover:bg-indigo-600 transition-all">
                    Kelola Pertanyaan
                </a>
                <form action="{{ route('admin.assessment-category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button class="bg-red-50 text-red-500 p-3 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div id="modal-tambah" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[3rem] p-8 w-full max-w-md">
        <h2 class="text-2xl font-black mb-6">Tambah Kategori</h2>
        <form action="{{ route('admin.assessment-category.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Nama Kategori (Contoh: Kedisiplinan)" class="w-full px-6 py-4 bg-slate-100 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500" required>
            <textarea name="description" placeholder="Deskripsi singkat..." class="w-full px-6 py-4 bg-slate-100 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500"></textarea>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="flex-1 py-4 font-bold text-slate-500">Batal</button>
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-indigo-200">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>lucide.createIcons();</script>
@endsection