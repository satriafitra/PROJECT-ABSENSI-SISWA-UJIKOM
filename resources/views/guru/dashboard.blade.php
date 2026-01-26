@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<h1 class="text-2xl font-bold mb-6">Dashboard Guru</h1>

<div class="grid grid-cols-2 gap-6">
    <a href="#" class="bg-green-500 text-white p-6 rounded shadow text-center">
        📷 Scan QR Absensi
    </a>

    <a href="#" class="bg-blue-500 text-white p-6 rounded shadow text-center">
        📄 Lihat Absensi Hari Ini
    </a>
</div>
@endsection
