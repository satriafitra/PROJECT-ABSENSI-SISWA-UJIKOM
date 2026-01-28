@extends('layouts.admin')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">
        📅 Data Tahun Ajar
    </h1>

    <div class="bg-white rounded-2xl shadow p-6">

        <div class="flex justify-between items-center mb-4">
            <p class="text-gray-600">
                Kelola data tahun ajar sekolah
            </p>

            <button
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl transition">
                + Tambah Tahun Ajar
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3 rounded-l-xl">No</th>
                        <th class="p-3">Tahun Ajar</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 rounded-r-xl">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="border-b">
                        <td class="p-3">1</td>
                        <td class="p-3">2024 / 2025</td>
                        <td class="p-3">
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">
                                Aktif
                            </span>
                        </td>
                        <td class="p-3 space-x-2">
                            <button class="text-blue-500 hover:underline">
                                Edit
                            </button>
                            <button class="text-red-500 hover:underline">
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td class="p-3">2</td>
                        <td class="p-3">2023 / 2024</td>
                        <td class="p-3">
                            <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-sm">
                                Tidak Aktif
                            </span>
                        </td>
                        <td class="p-3 space-x-2">
                            <button class="text-blue-500 hover:underline">
                                Edit
                            </button>
                            <button class="text-red-500 hover:underline">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
