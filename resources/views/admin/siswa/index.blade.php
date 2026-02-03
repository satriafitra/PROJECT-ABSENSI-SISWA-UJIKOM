@extends('layouts.admin')

@section('content')
<div class="px-8 py-6 bg-slate-50 min-h-screen">

    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-orange-500 rounded-2xl shadow-lg shadow-orange-200 text-white">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight leading-none">
                    Data <span class="text-orange-500">Siswa</span>
                </h1>
                <p class="text-gray-500 text-xs mt-1 font-medium tracking-wide">Kelola basis data siswa dan filter berdasarkan departemen.</p>
            </div>
        </div>

        <a href="{{ route('admin.siswa.create') }}"
            class="inline-flex items-center px-5 py-2.5 bg-gray-900 hover:bg-orange-600 text-white text-xs font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5 active:scale-95">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Tambah Siswa
        </a>
    </div>

    {{-- Filter Jurusan --}}
    @php
    $jurusans = [
        ['code' => '', 'name' => 'Semua Program'],
        ['code' => 'MPLB', 'name' => 'MPLB'],
        ['code' => 'PPLG', 'name' => 'PPLG'],
        ['code' => 'AKKUL', 'name' => 'AKKUL'],
        ['code' => 'TJKT', 'name' => 'TJKT'],
        ['code' => 'PS', 'name' => 'PS'],
    ];
    @endphp

    <div class="flex flex-wrap gap-2 mb-6">
        @foreach($jurusans as $j)
        <a href="{{ route('admin.siswa.index', ['major' => $j['code'], 'search' => request('search')]) }}"
            class="px-5 py-2 rounded-full border text-[11px] font-bold uppercase tracking-wider transition-all
            {{ request('major') == $j['code']
                ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-100'
                : 'bg-white text-gray-500 hover:bg-orange-50 border-gray-200 hover:text-orange-600' }}">
            {{ $j['name'] }}
        </a>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.siswa.index') }}" class="mb-6 w-full md:w-1/3 relative group">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari Nama atau NISN..."
            class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs focus:ring-4 focus:ring-orange-100 focus:border-orange-400 transition-all outline-none shadow-sm">
        <div class="absolute left-4 top-3.5 text-gray-400 group-focus-within:text-orange-500 transition-colors">
            <i data-lucide="search" class="w-4 h-4"></i>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-orange-500 to-orange-400 text-white">
                        <th class="px-6 py-5 font-bold uppercase tracking-widest first:rounded-tl-[1.8rem]">No</th>
                        <th class="px-6 py-5 font-bold uppercase tracking-widest">NISN</th>
                        <th class="px-6 py-5 font-bold uppercase tracking-widest">Nama Lengkap</th>
                        <th class="px-6 py-5 font-bold uppercase tracking-widest text-center">Kelas</th>
                        <th class="px-6 py-5 font-bold uppercase tracking-widest text-right last:rounded-tr-[1.8rem]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white"> {{-- Background tetap putih untuk isi --}}
                    @forelse($students as $student)
                    <tr class="hover:bg-orange-50/50 transition-colors group">
                        <td class="px-6 py-4 text-gray-400 font-medium">
                            {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 font-mono text-gray-600 tracking-tighter">
                            {{ $student->nis ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800">
                            {{ $student->name }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $className = $student->class->name ?? '-';
                                $badge = 'bg-slate-100 text-slate-600';
                                if(str_contains($className,'MPLB')) $badge='bg-rose-100 text-rose-600';
                                elseif(str_contains($className,'PPLG')) $badge='bg-emerald-100 text-emerald-600';
                                elseif(str_contains($className,'AKKUL')) $badge='bg-sky-100 text-sky-600';
                                elseif(str_contains($className,'TJKT')) $badge='bg-amber-100 text-amber-700';
                                elseif(str_contains($className,'PS')) $badge='bg-pink-100 text-pink-600';
                            @endphp
                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-tight {{ $badge }}">
                                {{ $className }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.siswa.edit',$student->id) }}"
                                    class="p-2 text-blue-600 hover:bg-blue-100 rounded-xl transition-all border border-transparent hover:border-blue-200"
                                    title="Edit Data">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                
                                <form action="{{ route('admin.siswa.destroy',$student->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-red-500 hover:bg-red-100 rounded-xl transition-all border border-transparent hover:border-red-200"
                                        title="Hapus Data">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-24 text-center bg-white">
                            <div class="flex flex-col items-center">
                                <i data-lucide="folder-open" class="w-12 h-12 text-gray-200 mb-3"></i>
                                <p class="text-gray-400 font-medium tracking-wide">Data tidak ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-8 px-2">
        {{ $students->withQueryString()->links() }}
    </div>

</div>

<script>
    lucide.createIcons();
</script>

<style>
    nav[role="navigation"] svg {
        width: 1.25rem;
        display: inline;
    }
</style>
@endsection