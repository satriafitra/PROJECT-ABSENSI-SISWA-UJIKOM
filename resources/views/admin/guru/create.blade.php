@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-2xl mx-auto">

    <h1 class="text-2xl font-bold text-orange-600 mb-6">
        ➕ Tambah Guru
    </h1>

    <div class="bg-white rounded-2xl shadow p-6">

        <form action="{{ route('admin.guru.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- NAMA -->
            <div>
                <label class="block text-sm font-medium mb-1">Nama Guru</label>
                <input type="text" nama="nama"
                       value="{{ old('nama') }}"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                       required>
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" nama="email"
                       value="{{ old('email') }}"
                       class="w-full border rounded-lg px-3 py-2">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NIP -->
            <div>
                <label class="block text-sm font-medium mb-1">NIP</label>
                <input type="text" nama="nip"
                       value="{{ old('nip') }}"
                       class="w-full border rounded-lg px-3 py-2">
                @error('nip')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- STATUS -->
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select nama="status"
                        class="w-full border rounded-lg px-3 py-2">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.guru.index') }}"
                   class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
