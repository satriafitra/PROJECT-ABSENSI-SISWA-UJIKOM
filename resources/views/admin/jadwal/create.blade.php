@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Tambah Jadwal Guru</h2>

    <form action="{{ route('admin.jadwal.store') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Pilih Guru -->
        <div>
            <label class="block mb-1 font-medium">Guru</label>
            <select name="guru_id" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">-- Pilih Guru --</option>
                @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                @endforeach
            </select>
            @error('guru_id')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        <!-- Hari -->
        <div>
            <label class="block mb-1 font-medium">Hari</label>
            <input type="text" name="hari" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Senin">
            @error('hari')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        <!-- Mata Pelajaran -->
        <div>
            <label class="block mb-1 font-medium">Mata Pelajaran</label>
            <input type="text" name="mata_pelajaran" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Matematika">
            @error('mata_pelajaran')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        </div>

        <!-- Jam Mulai & Selesai -->
        <div class="flex gap-4">
            <div class="flex-1">
                <label class="block mb-1 font-medium">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('jam_mulai')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>
            <div class="flex-1">
                <label class="block mb-1 font-medium">Jam Selesai</label>
                <input type="time" name="jam_selesai" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                @error('jam_selesai')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Ruangan -->
        <div>
            <label class="block mb-1 font-medium">Ruangan (Opsional)</label>
            <input type="text" name="ruangan" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Ruang 101">
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
            Selesai
        </button>
    </form>
</div>
@endsection