@extends('layouts.admin')

@section('content')
<div class="px-4 py-6 md:px-8 md:py-8 bg-[#f8fafc] min-h-screen flex justify-center">

    <div class="w-full max-w-3xl">
        {{-- Header Section --}}
        <div class="mb-8 flex items-center gap-5">
            <div class="p-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg shadow-orange-200/50 text-white flex-shrink-0">
                <i data-lucide="user-plus" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-none mb-2">
                    Tambah Siswa Baru
                </h1>
                <p class="text-slate-500 text-sm font-medium tracking-wide">
                    Lengkapi form di bawah ini untuk mendaftarkan siswa baru.
                </p>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            <div class="p-6 md:p-8">
                <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NAMA -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap Siswa</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                </div>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-400 transition-all outline-none"
                                    placeholder="Contoh: Ahmad Rizky" required>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs font-semibold mt-2 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIS / NISN -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">NIS / NISN</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="id-card" class="w-5 h-5"></i>
                                </div>
                                <input type="text" name="nisn" value="{{ old('nisn', old('nis')) }}"
                                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-400 transition-all outline-none"
                                    placeholder="Masukkan NIS / NISN">
                            </div>
                            @error('nisn')
                                <p class="text-red-500 text-xs font-semibold mt-2 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i>{{ $message }}</p>
                            @enderror
                            @error('nis')
                                <p class="text-red-500 text-xs font-semibold mt-2 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- KELAS -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kelas & Jurusan</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                                </div>
                                <select name="class_id"
                                    class="w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-400 transition-all outline-none appearance-none cursor-pointer" required>
                                    <option value="" disabled {{ old('class_id') ? '' : 'selected' }}>Pilih Kelas...</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                                </div>
                            </div>
                            @error('class_id')
                                <p class="text-red-500 text-xs font-semibold mt-2 flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-6 mt-8 flex items-center justify-end gap-4">
                        <a href="{{ route('admin.siswa.index') }}"
                            class="px-6 py-3 rounded-xl text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-200/50 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Simpan Data Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
