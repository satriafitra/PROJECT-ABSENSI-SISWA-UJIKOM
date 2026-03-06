@extends('layouts.admin')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen flex justify-center items-start">
    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-orange-500 to-amber-600 p-6">
            <h2 class="text-2xl font-bold text-white">Tambah Jadwal Baru</h2>
            <p class="text-orange-100">Lengkapi formulir di bawah untuk menambah jadwal guru.</p>
        </div>

        <form action="{{ route('admin.jadwal.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Guru</label>
                    <select name="guru_id" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                        @endforeach
                    </select>
                    @error('guru_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Hari</label>
                    <input type="text" name="hari" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all" placeholder="Contoh: Senin">
                    @error('hari')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all" placeholder="Contoh: Matematika">
                    @error('mata_pelajaran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ruangan (Opsional)</label>
                    <input type="text" name="ruangan" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all" placeholder="Contoh: Ruang Lab 1">
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-bold py-4 rounded-xl shadow-lg hover:opacity-90 transition-opacity">
                    Simpan Jadwal
                </button>
                <a href="{{ route('admin.jadwal.index') }}" class="px-8 py-4 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection