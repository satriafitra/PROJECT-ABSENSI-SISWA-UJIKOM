@extends('layouts.admin')

@section('title', 'Scan QR')

@section('content')
<div class="max-w-5xl mx-auto p-6">

    <h1 class="text-3xl font-bold text-orange-600 mb-6">
        📷 Scan QR Absensi Siswa
    </h1>

    <div class="grid md:grid-cols-3 gap-6">



        <!-- QR SISWA -->
        <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">
                QR Code Siswa
            </h2>

            <div class="flex justify-center mb-4">
                {!! QrCode::size(180)->generate($qrData) !!}
            </div>

            <p class="text-sm text-gray-600">
                Scan QR ini untuk absensi
            </p>

            <a href="{{ route('guru.scan.export') }}"
               class="mt-5 inline-flex w-full justify-center
                      bg-orange-500 hover:bg-orange-600
                      text-white font-semibold
                      px-5 py-3 rounded-xl transition">
                ⬇ Export Absensi
            </a>
        </div>

    </div>
</div>
@endsection
