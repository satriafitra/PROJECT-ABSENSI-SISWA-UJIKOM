@extends('layouts.admin')

@section('content')
<div class="p-6 bg-slate-50 min-h-screen">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                👨‍🎓 Data <span class="text-orange-500">Siswa</span>
            </h1>
            <p class="text-gray-500 text-sm">Kelola informasi siswa dan filter cepat berdasarkan jurusan.</p>
        </div>

        <a href="{{ route('admin.siswa.create') }}"
            class="inline-flex items-center px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-200 transition-all duration-300 transform hover:-translate-y-1">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Siswa
        </a>
    </div>

    <div class="flex flex-wrap gap-3 mb-8">
        @php
        $jurusans = [
        ['code' => '', 'name' => 'Semua Siswa', 'color' => 'orange'],
        ['code' => 'MPLB', 'name' => 'MPLB', 'color' => 'blue'],
        ['code' => 'PPLG', 'name' => 'PPLG', 'color' => 'purple'],
        ['code' => 'AKKUL', 'name' => 'AKKUL', 'color' => 'green'],
        ['code' => 'TJKT', 'name' => 'TJKT', 'color' => 'red'],
        ['code' => 'BDPS', 'name' => 'BDPS', 'color' => 'amber'],
        ];
        $currentSearch = request('search');
        @endphp

        @foreach($jurusans as $j)
        <a href="{{ route('admin.siswa.index', [
            'major' => $j['code'],
            'search' => request('search')
        ]) }}"
            class="px-6 py-2.5 rounded-2xl border-2 font-bold text-sm transition-all
       {{ request('major') == $j['code'] 
          ? 'border-orange-500 bg-orange-500 text-white shadow-lg' 
          : 'border-white bg-white text-gray-600 hover:bg-orange-50' }}">
            {{ $j['name'] }}
        </a>
        @endforeach

    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 items-center">
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="relative w-full md:w-1/2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama, NISN, atau jurusan (contoh: PPLG)..."
                class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-orange-400 text-sm transition-all shadow-inner">
            <div class="absolute left-4 top-3.5 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            @if(request('search'))
            <a href="{{ route('admin.siswa.index') }}" class="absolute right-4 top-3.5 text-gray-400 hover:text-red-500">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </a>
            @endif
        </form>
    </div>

    @if(session('success'))
    <div class="animate-fade-in-down bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                        <th class="p-6 text-xs font-bold uppercase tracking-widest">No</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest">NISN</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest">Nama Lengkap</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-center">Kelas & Jurusan</th>
                        <th class="p-6 text-xs font-bold uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($students as $student)
                    <tr class="hover:bg-orange-50/30 transition-all duration-200 group">
                        <td class="p-6">
                            <span class="text-gray-400 font-bold text-sm group-hover:text-orange-500 transition-colors">
                                {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                            </span>
                        </td>
                        <td class="p-6">
                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-slate-600 font-mono text-xs font-bold border border-slate-200">
                                {{ $student->nis ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="font-bold text-gray-800 tracking-wide group-hover:text-orange-600 transition-colors capitalize">
                                {{ $student->name }}
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            @php
                            $className = $student->class->name ?? '-';
                            // Logika penentuan warna badge berdasarkan teks jurusan
                            $badgeStyle = 'bg-gray-100 text-gray-600 border-gray-200';
                            if(str_contains(strtoupper($className), 'PPLG')) $badgeStyle = 'bg-purple-100 text-purple-700 border-purple-200';
                            elseif(str_contains(strtoupper($className), 'MPLB')) $badgeStyle = 'bg-blue-100 text-blue-700 border-blue-200';
                            elseif(str_contains(strtoupper($className), 'AKKUL')) $badgeStyle = 'bg-green-100 text-green-700 border-green-200';
                            elseif(str_contains(strtoupper($className), 'TJKT')) $badgeStyle = 'bg-red-100 text-red-700 border-red-200';
                            elseif(str_contains(strtoupper($className), 'BDPS')) $badgeStyle = 'bg-amber-100 text-amber-700 border-amber-200';
                            @endphp
                            <span class="inline-block px-4 py-1.5 {{ $badgeStyle }} rounded-xl text-xs font-black border tracking-tighter shadow-sm">
                                {{ $className }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="flex justify-end gap-3 opacity-70 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.siswa.edit', $student->id) }}"
                                    class="p-2 text-blue-500 hover:bg-blue-100 rounded-xl transition-all shadow-sm bg-white border border-blue-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-100 rounded-xl transition-all shadow-sm bg-white border border-red-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-orange-50 text-orange-200 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-400">Data Tidak Ditemukan</h3>
                                <p class="text-gray-400 text-sm">Coba gunakan kata kunci lain atau pilih semua jurusan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 px-4">
        {{ $students->withQueryString()->links() }}
    </div>

</div>

<style>
    /* Animasi halus saat data muncul */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-down {
        animation: fadeInDown 0.5s ease-out forwards;
    }

    /* Scrollbar minimalis */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #fb923c;
    }
</style>
@endsection