@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                📁 Laporan Absensi Manual 
            </h1>
            <p class="text-gray-500 mt-1">
                Manajemen data siswa yang melakukan pelaporan <span class="text-orange-600 font-semibold">Sakit</span> atau <span class="text-blue-600 font-semibold">Izin</span>.
            </p>
        </div>
        
        <div class="mt-4 md:mt-0">
            <div class="flex bg-white shadow-sm border border-gray-200 rounded-xl p-1">
                <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition">
                    <i class="fas fa-download mr-2"></i> Export PDF
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-procedures fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Sakit</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $manualAbsences->where('status', 'sakit')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-envelope-open-text fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Izin</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $manualAbsences->where('status', 'izin')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-calendar-check fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Hari Ini</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ now()->translatedFormat('d M') }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-700 text-lg">Daftar Laporan Terkini</h3>
            <span class="text-xs font-semibold bg-gray-100 text-gray-500 px-3 py-1 rounded-full uppercase">
                Auto-Refresh Active
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 text-xs uppercase tracking-widest font-bold">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Informasi Siswa</th>
                        <th class="px-6 py-4">Waktu Laporan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Alasan / Keterangan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($manualAbsences as $index => $row)
                    <tr class="group hover:bg-orange-50/30 transition-all duration-200">
                        <td class="px-6 py-4 text-sm text-gray-400 font-medium">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-400 to-orange-600 flex items-center justify-center text-white font-bold text-sm mr-3 shadow-md">
                                    {{ strtoupper(substr($row->student->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 group-hover:text-orange-700 transition">
                                        {{ $row->student->name ?? 'Siswa Terhapus' }}
                                    </p>
                                    <p class="text-xs text-gray-400">NISN: {{ $row->student->nisn ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700 font-medium">
                                {{ \Carbon\Carbon::parse($row->date)->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-xs text-gray-400">Pukul: {{ $row->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($row->status == 'sakit')
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-600 uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-600 mr-2 animate-pulse"></span>
                                    Sakit
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-600 uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-2 animate-pulse"></span>
                                    Izin
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-[200px]">
                                <p class="text-sm text-gray-600 italic line-clamp-2" title="{{ $row->keterangan }}">
                                    "{{ $row->keterangan ?? '-' }}"
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <button class="p-2 text-blue-500 hover:bg-blue-50 rounded-xl transition shadow-sm" title="Lihat Detail">
                                    <i class="fas fa-external-link-alt"></i>
                                </button>
                                <form action="#" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-2 text-red-400 hover:bg-red-50 rounded-xl transition shadow-sm" onclick="return confirm('Hapus data ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-folder-open text-gray-200 text-3xl"></i>
                                </div>
                                <p class="text-gray-400 font-medium">Belum ada laporan absen manual hari ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($manualAbsences, 'links'))
        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $manualAbsences->links() }}
        </div>
        @endif
    </div>
</div>
@endsection