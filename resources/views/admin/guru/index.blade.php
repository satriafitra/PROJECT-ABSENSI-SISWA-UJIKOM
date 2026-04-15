@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="p-8 bg-gray-50 min-h-screen">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-800 flex items-center gap-3">
                <span class="bg-orange-500 text-white p-2 rounded-xl shadow-lg">
                    <i class="fas fa-chalkboard-teacher"></i>
                </span>
                Data Guru
            </h1>
            <p class="text-gray-500 mt-2 ml-1">Kelola informasi tenaga pendidik dengan mudah dan cepat.</p>
        </div>

        <a href="{{ route('admin.guru.create') }}"
            class="flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-orange-200 transition-all duration-300 transform hover:-translate-y-1">
            <i class="fas fa-plus"></i>
            Tambah Guru Baru
        </a>
    </div>

    {{-- SEARCH BAR --}}
    <div class="mb-6">
        <form method="GET" action="{{ route('admin.guru.index') }}">
            <div class="flex items-center gap-3 max-w-md">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama guru..."
                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                </div>
                <button
                    type="submit"
                    class="px-5 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-semibold shadow-md transition-all">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- TABLE SECTION --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider">Informasi Guru</th>
                        <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider">NIP</th>
                        <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="p-5 text-sm font-bold text-gray-600 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($gurus as $guru)
                    <tr class="hover:bg-orange-50/50 transition-colors duration-200">
                        <td class="p-5 text-gray-500 font-medium">
                            {{ $gurus->firstItem() + $loop->index }}
                        </td>
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold shadow-sm">
                                    {{ strtoupper(substr($guru->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $guru->nama }}</div>
                                    <div class="text-xs text-gray-500">{{ $guru->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-5 text-sm text-gray-600 font-mono italic">
                            {{ $guru->nip ?? 'N/A' }}
                        </td>
                        <td class="p-5">
                            @if($guru->status === 'aktif')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                Non-Aktif
                            </span>
                            @endif
                        </td>
                        <td class="p-5 text-center">
                            <div class="flex justify-center items-center gap-2">
                                {{-- BUTTON LOGIN AS (IMPERSONATE) --}}
                                <button type="button"
                                    onclick="confirmLoginAs('{{ route('admin.guru.login-as', $guru->id) }}', '{{ $guru->nama }}')"
                                    class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all duration-200 group"
                                    title="Login Sebagai {{ $guru->nama }}">
                                    <i class="fas fa-user-shield group-hover:scale-110 transition-transform"></i>
                                </button>

                                {{-- BUTTON EDIT --}}
                                <a href="{{ route('admin.guru.edit', $guru->id) }}"
                                    class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-200"
                                    title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- BUTTON DELETE --}}
                                <form action="{{ route('admin.guru.destroy', $guru->id) }}"
                                    method="POST"
                                    class="inline"
                                    onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-200"
                                        title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-folder-open text-gray-300 text-5xl mb-3"></i>
                                <p class="text-gray-500 text-lg font-medium">Belum ada data guru terdaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- PAGINATION --}}
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $gurus->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi Login Sebagai Guru
    function confirmLoginAs(url, namaGuru) {
        Swal.fire({
            title: 'Konfirmasi Login',
            html: `Apakah Anda yakin ingin login langsung sebagai <br><b>${namaGuru}</b>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981', // Emerald 600
            cancelButtonColor: '#6b7280', // Gray 500
            confirmButtonText: '<i class="fas fa-sign-in-alt mr-2"></i> Ya, Login Sekarang',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            borderRadius: '1.25rem',
            customClass: {
                title: 'text-2xl font-bold text-gray-800',
                confirmButton: 'px-6 py-3 rounded-xl font-semibold',
                cancelButton: 'px-6 py-3 rounded-xl font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Pengalihan...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                window.location.href = url;
            }
        });
    }

    // Konfirmasi Hapus Data
    function confirmDelete(event) {
        event.preventDefault();
        const form = event.target;

        Swal.fire({
            title: 'Hapus data guru?',
            text: "Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Red 500
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Sekarang',
            cancelButtonText: 'Batal',
            borderRadius: '1.25rem',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // Handle Session Flash Messages
    document.addEventListener('DOMContentLoaded', function() {
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