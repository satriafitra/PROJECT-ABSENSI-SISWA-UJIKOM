@extends('layouts.app')

@section('title', 'Scan QR')

@section('content')
<h1 class="text-xl font-bold mb-4">Scan QR Siswa</h1>

<div class="bg-white p-6 rounded shadow">
    <p class="mb-4 text-gray-600">
        Arahkan kamera ke QR Code siswa
    </p>

    <div class="border-2 border-dashed h-64 flex items-center justify-center">
        <span class="text-gray-400">Camera Preview</span>
    </div>
</div>
@endsection
