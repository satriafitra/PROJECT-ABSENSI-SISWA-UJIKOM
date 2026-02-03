@extends('layouts.admin')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">

    <h1 class="text-2xl font-bold mb-6 text-orange-600 flex items-center gap-2">
        👨‍🎓 Data Siswa
    </h1>

    <div class="flex flex-col md:flex-row justify-between mb-4 gap-3">
        <!-- Form Search -->
        <form method="GET" class="flex items-center gap-2 w-full md:w-1/3">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau NISN..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            <button
                type="submit"
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg shadow transition"
            >
                Cari
            </button>
        </form>

        <!-- Tombol Tambah -->
        <a
            href="{{ route('admin.siswa.create') }}"
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg shadow transition transform hover:-translate-y-0.5 hover:shadow-md text-sm font-semibold"
        >
            + Tambah Siswa
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-4 shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel -->
    <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-orange-100 text-orange-700 uppercase text-xs">
                <tr>
                    <th class="p-3 rounded-l-lg">No</th>
                    <th class="p-3">NISN</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Kelas</th>
                    <th class="p-3 rounded-r-lg">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr class="border-b border-gray-200 hover:bg-orange-50 transition">
                    <td class="p-3">
                        {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                    </td>

                    <!-- NISN -->
                    <td class="p-3 font-semibold text-gray-800">
                        {{ $student->nis ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $student->name }}
                    </td>

                    <td class="p-3">
                        {{ $student->class->name ?? '-' }}
                    </td>

                    <td class="p-3 flex gap-3">
                        <a
                            href="{{ route('admin.siswa.edit', $student->id) }}"
                            class="text-blue-500 hover:text-blue-700 font-medium"
                        >
                            Edit
                        </a>

                        <form
                            action="{{ route('admin.siswa.destroy', $student->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus siswa ini?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="text-red-500 hover:text-red-700 font-medium"
                            >
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500 italic">
                        Tidak ada data siswa.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $students->withQueryString()->links() }}
    </div>

</div>
@endsection
