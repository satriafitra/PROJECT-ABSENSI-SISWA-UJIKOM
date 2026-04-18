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

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-procedures fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Sakit (Disetujui)</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $approvedAbsences->where('status', 'sakit')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-envelope-open-text fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Izin (Disetujui)</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $approvedAbsences->where('status', 'izin')->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-hourglass-half fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Menunggu Validasi</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $pendingAbsences->count() }}</h3>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 border-l-4 border-green-500 rounded-lg shadow-sm font-semibold">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Tabel Menunggu Validasi -->
    @if($pendingAbsences->count() > 0)
    <div class="bg-white rounded-3xl shadow-xl shadow-yellow-100/50 border border-yellow-200 overflow-hidden mb-8">
        <div class="p-6 border-b border-yellow-100 flex justify-between items-center bg-yellow-50/50">
            <h3 class="font-bold text-yellow-700 text-lg">
                <i class="fas fa-bell mr-2 animate-bounce"></i> Menunggu Validasi ({{ $pendingAbsences->count() }})
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-yellow-100/50 text-yellow-700 text-xs uppercase tracking-widest font-bold">
                        <th class="px-6 py-4">Informasi Siswa</th>
                        <th class="px-6 py-4">Waktu Laporan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Alasan / Keterangan</th>
                        <th class="px-6 py-4 text-center">Bukti Foto</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pendingAbsences as $row)
                    <tr class="group hover:bg-yellow-50/30 transition-all duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-sm mr-3 shadow-md">
                                    {{ strtoupper(substr($row->student->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $row->student->name ?? 'Siswa Terhapus' }}
                                    </p>
                                    <p class="text-xs text-gray-500">NISN: {{ $row->student->nisn ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700 font-medium">
                                {{ \Carbon\Carbon::parse($row->date)->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-xs text-gray-500">Pukul: {{ $row->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($row->status == 'sakit')
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-orange-100 text-orange-600 uppercase">
                                    Sakit
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-600 uppercase">
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
                        <td class="px-6 py-4 text-center">
                            @if($row->image)
                                <a href="{{ asset('storage/' . $row->image) }}" target="_blank" class="inline-block relative group">
                                    <img src="{{ asset('storage/' . $row->image) }}" alt="Bukti {{ $row->status }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-sm group-hover:scale-105 transition-transform duration-200">
                                    <div class="absolute inset-0 bg-black/40 hidden group-hover:flex items-center justify-center rounded-lg">
                                        <i class="fas fa-search-plus text-white text-sm"></i>
                                    </div>
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <form action="{{ route('admin.manual.verify', $row->id) }}" method="POST" class="inline form-verify-terima">
                                    @csrf
                                    <input type="hidden" name="action" value="approved">
                                    <button type="button" class="px-3 py-2 text-white bg-green-500 hover:bg-green-600 rounded-xl transition shadow-sm font-semibold text-xs flex items-center btn-verify-terima">
                                        <i class="fas fa-check mr-1"></i> Terima
                                    </button>
                                </form>
                                <form action="{{ route('admin.manual.verify', $row->id) }}" method="POST" class="inline form-verify-tolak">
                                    @csrf
                                    <input type="hidden" name="action" value="rejected">
                                    <button type="button" class="px-3 py-2 text-white bg-red-500 hover:bg-red-600 rounded-xl transition shadow-sm font-semibold text-xs flex items-center btn-verify-tolak">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Tabel Daftar Laporan Terkini -->
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-700 text-lg">Daftar Laporan Terkini (Sudah Disetujui)</h3>
            <span class="text-xs font-semibold bg-green-100 text-green-600 px-3 py-1 rounded-full uppercase">
                <i class="fas fa-check-circle mr-1"></i> Terverifikasi
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
                        <th class="px-6 py-4 text-center">Bukti</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($approvedAbsences as $index => $row)
                    <tr class="group hover:bg-gray-50/30 transition-all duration-200">
                        <td class="px-6 py-4 text-sm text-gray-400 font-medium">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-gray-400 to-gray-600 flex items-center justify-center text-white font-bold text-sm mr-3 shadow-md">
                                    {{ strtoupper(substr($row->student->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">
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
                                    Sakit
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-600 uppercase">
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
                        <td class="px-6 py-4 text-center">
                            @if($row->image)
                                <a href="{{ asset('storage/' . $row->image) }}" target="_blank" class="inline-block relative group">
                                    <img src="{{ asset('storage/' . $row->image) }}" alt="Bukti {{ $row->status }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-sm group-hover:scale-105 transition-transform duration-200">
                                    <div class="absolute inset-0 bg-black/40 hidden group-hover:flex items-center justify-center rounded-lg">
                                        <i class="fas fa-search-plus text-white text-sm"></i>
                                    </div>
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <form action="{{ route('admin.siswa.destroy', $row->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="p-2 text-red-400 hover:bg-red-50 rounded-xl transition shadow-sm btn-delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-folder-open text-gray-200 text-3xl"></i>
                                </div>
                                <p class="text-gray-400 font-medium">Belum ada laporan absen manual yang disetujui hari ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($approvedAbsences, 'links'))
        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $approvedAbsences->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert untuk Terima
        const btnTerima = document.querySelectorAll('.btn-verify-terima');
        btnTerima.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.form-verify-terima');
                
                Swal.fire({
                    title: 'Terima Laporan?',
                    text: "Siswa ini akan terhitung masuk absen (Sakit/Izin) pada rekap.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#22c55e', // Green 500
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Terima',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2rem] p-6 shadow-2xl',
                        confirmButton: 'rounded-xl px-6 py-3 font-bold uppercase text-xs tracking-widest',
                        cancelButton: 'rounded-xl px-6 py-3 font-bold uppercase text-xs tracking-widest'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // SweetAlert untuk Tolak
        const btnTolak = document.querySelectorAll('.btn-verify-tolak');
        btnTolak.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.form-verify-tolak');
                
                Swal.fire({
                    title: 'Tolak Laporan?',
                    text: "Siswa ini tidak akan mendapatkan keterangan Sakit/Izin.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', // Red 500
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-[2rem] p-6 shadow-2xl border-t-4 border-red-500',
                        confirmButton: 'rounded-xl px-6 py-3 font-bold uppercase text-xs tracking-widest',
                        cancelButton: 'rounded-xl px-6 py-3 font-bold uppercase text-xs tracking-widest'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

@endsection