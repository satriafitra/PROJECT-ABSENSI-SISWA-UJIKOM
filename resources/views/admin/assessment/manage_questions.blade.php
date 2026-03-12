@extends('layouts.admin')
@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="mb-8">
        <a href="{{ route('admin.assessment-category.index') }}" class="text-indigo-600 font-bold flex items-center gap-2 mb-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Kategori
        </a>
        <h1 class="text-3xl font-black text-slate-800">Indikator: {{ $category->name }}</h1>
        <p class="text-slate-500 italic">Pertanyaan yang Anda masukkan di sini akan tampil di form penilaian Guru.</p>
    </div>

    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm mb-8">
        <form action="{{ route('admin.assessment-category.questions.store', $category->id) }}" method="POST" class="flex flex-col md:flex-row gap-4">
            @csrf
            <div class="flex-1 relative">
                <i data-lucide="help-circle" class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
                <input type="text" name="question_text" placeholder="Ketik pertanyaan penilaian di sini..." 
                       class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium" required>
            </div>
            <button type="submit" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold hover:bg-indigo-600 transition-all">
                Tambah Indikator
            </button>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($category->questions as $index => $q)
        <div class="bg-white p-5 rounded-3xl border border-slate-100 flex justify-between items-center group hover:border-indigo-200 transition-all">
            <div class="flex items-center gap-4">
                <span class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center font-black">
                    {{ $index + 1 }}
                </span>
                <p class="font-bold text-slate-700">{{ $q->question_text }}</p>
            </div>
            <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-50 text-red-400 p-3 rounded-xl opacity-0 group-hover:opacity-100 hover:bg-red-500 hover:text-white transition-all">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
        @empty
        <div class="text-center py-20 bg-slate-100 rounded-[3rem] border-2 border-dashed border-slate-200">
            <div class="inline-flex p-6 bg-white rounded-full mb-4 text-slate-300">
                <i data-lucide="database-zap" class="w-12 h-12"></i>
            </div>
            <p class="text-slate-400 font-bold">Belum ada pertanyaan. Silakan tambah indikator di atas.</p>
        </div>
        @endforelse
    </div>
</div>

<script>lucide.createIcons();</script>
@endsection