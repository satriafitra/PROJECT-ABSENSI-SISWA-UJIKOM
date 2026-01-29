@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-3xl">

    <h1 class="text-2xl font-bold text-orange-600 mb-6">
        ✏️ Edit Data Guru
    </h1>

    @if ($errors->any())
    <div class="mb-4 bg-red-100 text-red-700 p-3 rounded-xl">
        {{ $errors->first() }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow p-6">
        <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1">Nama</label>
                <input type="text" name="nama"
                    value="{{ old('nama', $guru->nama) }}"
                    class="w-full rounded-xl border-gray-300" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email"
                    value="{{ old('email', $guru->email) }}"
                    class="w-full rounded-xl border-gray-300">
            </div>

            <div class="mb-4">
                <label class="block mb-1">NIP</label>
                <input type="text" name="nip"
                    value="{{ old('nip', $guru->nip) }}"
                    class="w-full rounded-xl border-gray-300">
            </div>

            <div class="mb-6">
                <label class="block mb-1">Status</label>
                <select name="status"
                    class="w-full rounded-xl border-gray-300">
                    <option value="aktif" {{ $guru->status == 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="nonaktif" {{ $guru->status == 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('admin.guru.index') }}"
                    class="px-4 py-2 rounded-xl border">
                    Kembali
                </a>

                <div class="mb-4">
                    <label class="block mb-1">
                        Password Baru
                        <span class="text-sm text-gray-500">(opsional)</span>
                    </label>
                    <input type="password" name="password"
                        class="w-full rounded-xl border-gray-300"
                        placeholder="Kosongkan jika tidak ingin mengubah">
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-xl">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection