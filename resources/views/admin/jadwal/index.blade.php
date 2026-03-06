@extends('layouts.admin')

@section('content')
<div class="p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
            <div>
                <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight">Jadwal Guru</h2>
                <p class="text-slate-500 mt-1 font-medium italic">Sistem Manajemen Kurikulum <span class="text-orange-500">AkvaScan</span></p>
            </div>
            <a href="{{ route('admin.jadwal.create') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-bold rounded-2xl shadow-xl shadow-orange-200 hover:shadow-orange-300 hover:-translate-y-1 transition-all duration-300 group">
                <i data-lucide="plus" class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform"></i>
                Tambah Jadwal Baru
            </a>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 mb-8 transition-all hover:shadow-md">
            <form method="GET" action="{{ route('admin.jadwal.index') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">
                
                <div class="lg:col-span-4">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 ml-1">Pencarian Cepat</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-5 h-5 text-slate-300 group-focus-within:text-orange-500 transition-colors"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl pl-11 pr-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 focus:bg-white transition-all text-sm font-semibold"
                               placeholder="Cari nama atau mapel...">
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[2px] mb-3 ml-1">Daftar Guru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-orange-500 transition-colors border-r border-slate-200 pr-3">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <select name="guru_id" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl pl-14 pr-10 py-3.5 appearance-none focus:outline-none focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 focus:bg-white transition-all text-sm font-semibold text-slate-600">
                            <option value="">-- Semua Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-300">
                            <i data-lucide="chevron-down" class="w-5 h-5"></i>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 flex gap-3">
                    <button type="submit" class="flex-1 bg-slate-900 text-white font-bold py-4 rounded-2xl hover:bg-black transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-2 text-xs uppercase tracking-widest leading-none">
                        <i data-lucide="filter" class="w-4 h-4 text-orange-400"></i>
                        Filter
                    </button>
                    
                    @if(request('search') || request('guru_id'))
                    <a href="{{ route('admin.jadwal.index') }}" 
                       class="px-5 py-4 bg-red-50 text-red-500 rounded-2xl hover:bg-red-500 hover:text-white transition-all flex items-center justify-center group" 
                       title="Bersihkan Filter">
                        <i data-lucide="x-circle" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Data Guru</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Waktu</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Mata Pelajaran</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px]">Lokasi</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[2px] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-orange-50/30 transition-all group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white font-black text-lg mr-4 shadow-lg shadow-orange-100 group-hover:scale-110 transition-transform">
                                        {{ strtoupper(substr($jadwal->guru->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-700 leading-tight">{{ $jadwal->guru->nama }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1 italic">Professional Staff</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="block font-bold text-slate-700 mb-1">{{ $jadwal->hari }}</span>
                                <div class="flex items-center gap-1.5 text-slate-400">
                                    <i data-lucide="clock" class="w-3 h-3 text-orange-400"></i>
                                    <span class="text-xs font-semibold tracking-tighter">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-4 py-2 bg-amber-50 text-amber-600 rounded-xl text-xs font-black uppercase tracking-widest border border-amber-100">
                                    {{ $jadwal->mata_pelajaran }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-300"></i>
                                    <span class="text-sm font-bold tracking-tight">{{ $jadwal->ruangan ?? 'Ruang Umum' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('admin.jadwal.edit', $jadwal->id) }}" 
                                       class="h-11 w-11 flex items-center justify-center text-amber-500 bg-amber-50 hover:bg-amber-500 hover:text-white rounded-2xl transition-all shadow-sm">
                                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                                    </a>
                                    <form action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" class="h-11 w-11 flex items-center justify-center text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-2xl btn-delete transition-all shadow-sm">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <i data-lucide="alert-circle" class="w-10 h-10 text-slate-200"></i>
                                    </div>
                                    <h3 class="text-slate-400 font-black uppercase tracking-[3px] text-xs">Jadwal Tidak Ditemukan</h3>
                                    <p class="text-slate-300 text-sm mt-2 font-medium">Coba gunakan filter atau kata kunci yang berbeda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8">
            {{ $jadwals->links() }}
        </div>
    </div>
</div>
@endsection