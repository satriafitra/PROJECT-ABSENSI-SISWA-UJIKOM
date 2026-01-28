@extends('layouts.admin')

@section('content')
<div class="p-6">

    <h1 class="text-3xl font-bold text-orange-600 mb-6">
        👨‍🏫 Data Guru
    </h1>

    <!-- SweetAlert Success Notification -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#f97316', // orange-500
            });
        });
    </script>
    @endif

    <div class="flex flex-col md:flex-row md:justify-between mb-6 gap-4">
        <p class="text-gray-600 text-lg">Daftar guru yang terdaftar</p>

        <a href="{{ route('admin.guru.create') }}"
           class="bg-orange-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-orange-700 transition duration-300">
            + Tambah Guru
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full border-collapse text-gray-700">
            <thead>
                <tr class="bg-orange-50 text-left">
                    <th class="p-3 font-medium">No</th>
                    <th class="p-3 font-medium">Nama</th>
                    <th class="p-3 font-medium">Email</th>
                    <th class="p-3 font-medium">NIP</th>
                    <th class="p-3 font-medium">Status</th>
                    <th class="p-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $guru)
                <tr class="border-b hover:bg-orange-50 transition">
                    <td class="p-3">{{ $loop->iteration }}</td>
                    <td class="p-3 font-medium">{{ $guru->nama }}</td>
                    <td class="p-3">{{ $guru->email ?? '-' }}</td>
                    <td class="p-3">{{ $guru->nip ?? '-' }}</td>
                    <td class="p-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $guru->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($guru->status) }}
                        </span>
                    </td>
                    <td class="p-3 flex gap-3">
                        <a href="{{ route('admin.guru.edit', $guru->id) }}"
                           class="text-blue-600 hover:underline font-medium">
                            Edit
                        </a>

                        <form action="{{ route('admin.guru.destroy', $guru->id) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline font-medium">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">
                        Data guru belum ada
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
    }
</script>
@endsection
