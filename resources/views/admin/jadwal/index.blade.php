@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Jadwal Guru</h2>

    <!-- Tombol Tambah Jadwal -->
    <a href="{{ route('admin.jadwal.create') }}" class="mb-4 inline-block bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
        + Tambah Jadwal
    </a>

    <!-- Filter Guru -->
    <form method="GET" action="{{ route('admin.jadwal.index') }}" class="flex items-center gap-3 flex-wrap mb-4">
        <div class="relative w-64">
            <select name="guru" class="w-full border border-gray-300 rounded-lg px-4 py-2 appearance-none focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                <option value="">-- Pilih Guru --</option>
                @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}" {{ request('guru') == $guru->id ? 'selected' : '' }}>
                        {{ $guru->nama }}
                    </option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>

        <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded-lg hover:bg-red-600 transition shadow">
            Filter
        </button>
    </form>

    <!-- List Jadwal -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Guru</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Hari</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Mata Pelajaran</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Jam</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Ruangan</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jadwals as $jadwal)
                    <tr>
                        <td class="px-4 py-2">{{ $jadwal->guru->nama }}</td>
                        <td class="px-4 py-2">{{ $jadwal->hari }}</td>
                        <td class="px-4 py-2">{{ $jadwal->mata_pelajaran }}</td>
                        <td class="px-4 py-2">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td class="px-4 py-2">{{ $jadwal->ruangan ?? '-' }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" class="bg-yellow-400 text-white px-2 py-1 rounded hover:bg-yellow-500">Edit</a>
                            <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-2 text-center text-gray-500">Belum ada jadwal</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $jadwals->links() }}
    </div>
</div>
@endsection