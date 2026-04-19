@extends('layouts.admin')

@section('content')
<div class="px-4 py-6 md:px-8 md:py-8 bg-[#f8fafc] min-h-screen">

    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="p-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg shadow-orange-200/50 text-white flex-shrink-0">
                <i data-lucide="users" class="w-8 h-8"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-none mb-2">
                    Data Siswa
                </h1>
                <p class="text-slate-500 text-sm font-medium tracking-wide">
                    Kelola data, NISN, dan kelas seluruh siswa terdaftar.
                </p>
            </div>
        </div>

        <a href="{{ route('admin.siswa.create') }}"
            class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 hover:bg-orange-500 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-orange-200/50 transition-all transform hover:-translate-y-1 active:scale-95 group">
            <i data-lucide="plus-circle" class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
            Tambah Siswa Baru
        </a>
    </div>

    {{-- Filter & Search Section --}}
    <div class="bg-white p-5 rounded-3xl shadow-sm border border-slate-100 mb-8 flex flex-col xl:flex-row gap-6 justify-between xl:items-center">
        
        {{-- Filter Jurusan --}}
        @php
        $jurusans = [
            ['code' => '', 'name' => 'Semua'],
            ['code' => 'MPLB', 'name' => 'MPLB'],
            ['code' => 'PPLG', 'name' => 'PPLG'],
            ['code' => 'AKKUL', 'name' => 'AKKUL'],
            ['code' => 'TJKT', 'name' => 'TJKT'],
            ['code' => 'PS', 'name' => 'PS'],
        ];
        @endphp

        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-2">Filter Jurusan:</span>
            @foreach($jurusans as $j)
            <a href="{{ route('admin.siswa.index', ['major' => $j['code'], 'search' => request('search')]) }}"
                class="px-4 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all duration-200
                {{ request('major') == $j['code'] || (request('major') == null && $j['code'] == '')
                    ? 'bg-orange-500 text-white shadow-md shadow-orange-200/50 border border-orange-500'
                    : 'bg-slate-50 text-slate-500 hover:bg-orange-50 border border-slate-200 hover:border-orange-200 hover:text-orange-600' }}">
                {{ $j['name'] }}
            </a>
            @endforeach
        </div>

        {{-- Search --}}
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="w-full xl:w-80 relative group">
            @if(request('major'))
                <input type="hidden" name="major" value="{{ request('major') }}">
            @endif
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                <i data-lucide="search" class="w-5 h-5"></i>
            </div>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari Nama atau NISN..."
                class="w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-400 transition-all outline-none">
            @if(request('search'))
                <a href="{{ route('admin.siswa.index', ['major' => request('major')]) }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500">
                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-500 text-xs uppercase tracking-[0.15em] font-bold">
                        <th class="px-6 py-5 w-16 text-center">No</th>
                        <th class="px-6 py-5">Informasi Siswa</th>
                        <th class="px-6 py-5">NISN</th>
                        <th class="px-6 py-5 text-center">Kelas / Jurusan</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($students as $student)
                    <tr class="hover:bg-slate-50/80 transition-colors duration-200 group">
                        <td class="px-6 py-5 text-center text-slate-400 font-semibold text-sm">
                            {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-slate-100 to-slate-200 border-2 border-white shadow-sm flex items-center justify-center text-slate-500 font-bold text-lg group-hover:from-orange-100 group-hover:to-orange-200 group-hover:text-orange-600 transition-all duration-300">
                                    {{ strtoupper(substr($student->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm group-hover:text-orange-600 transition-colors">
                                        {{ $student->name }}
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Siswa Aktif</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg">
                                <i data-lucide="id-card" class="w-4 h-4 text-slate-400"></i>
                                <span class="font-mono text-sm font-semibold text-slate-600 tracking-wider">
                                    {{ $student->nis ?? 'BELUM DIATUR' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @php
                                $className = $student->class->name ?? 'Tanpa Kelas';
                                $badgeStyle = 'bg-slate-100 text-slate-600 border-slate-200';
                                
                                if(str_contains($className,'MPLB')) $badgeStyle='bg-rose-50 text-rose-600 border-rose-100';
                                elseif(str_contains($className,'PPLG')) $badgeStyle='bg-emerald-50 text-emerald-600 border-emerald-100';
                                elseif(str_contains($className,'AKKUL')) $badgeStyle='bg-sky-50 text-sky-600 border-sky-100';
                                elseif(str_contains($className,'TJKT')) $badgeStyle='bg-amber-50 text-amber-600 border-amber-100';
                                elseif(str_contains($className,'PS')) $badgeStyle='bg-pink-50 text-pink-600 border-pink-100';
                            @endphp
                            <div class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest border shadow-sm {{ $badgeStyle }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current mr-2 opacity-75"></span>
                                {{ $className }}
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.siswa.edit', $student->id) }}"
                                    class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all"
                                    title="Edit Data Siswa">
                                    <i data-lucide="edit" class="w-5 h-5"></i>
                                </a>
                                
                                <form action="{{ route('admin.siswa.destroy', $student->id) }}" method="POST" class="inline delete-form">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all btn-delete"
                                        title="Hapus Data Siswa">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="inbox" class="w-10 h-10 text-slate-300"></i>
                                </div>
                                <h3 class="text-slate-500 font-bold text-lg mb-1">Data Siswa Kosong</h3>
                                <p class="text-slate-400 text-sm max-w-sm mx-auto">
                                    Belum ada data siswa yang sesuai dengan kriteria pencarian atau filter yang Anda pilih.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($students->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $students->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Delete Button
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Hapus data siswa?',
                    text: "Tindakan ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    borderRadius: '1.25rem'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Handle Session Flash Messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#f97316',
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Ups!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444'
            });
        @endif
    });
</script>
@endsection