@extends('layouts.admin')

@section('content')
<div class="p-6  min-h-screen">

    <h1 class="text-3xl font-bold text-orange-600 mb-6">
        Dashboard Admin
    </h1>

    <!-- Card Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @php
        $cards = [
        ['title'=>'Siswa', 'value'=>$totalSiswa],
        ['title'=>'Guru', 'value'=>$totalGuru],
        ['title'=>'Kelas', 'value'=>$totalKelas],
        ['title'=>'Hadir Hari Ini', 'value'=>$hadir],
        ];
        @endphp

        @foreach ($cards as $card)
        <div class="bg-white rounded-2xl shadow-md p-5 border-l-8 border-orange-400">
            <h2 class="text-gray-500 text-sm">{{ $card['title'] }}</h2>
            <p class="text-3xl font-bold text-orange-600 mt-2">
                {{ $card['value'] }}
            </p>
        </div>
        @endforeach
    </div>

    <!-- Grafik Kehadiran -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- CHART CARD -->
        <div class="bg-white rounded-2xl shadow-md p-6 h-full">
            <h2 class="text-lg font-semibold text-orange-600 mb-4">
                Statistik Absensi Hari Ini
            </h2>
            <canvas id="absensiChart"></canvas>
        </div>

        <!-- QUICK ACTION -->
        <div class="bg-gradient-to-br from-orange-400 to-orange-600
                text-white rounded-2xl shadow-md p-6
                h-full flex flex-col justify-start">
            <h2 class="text-xl font-semibold mb-4">
                Quick Action
            </h2>

            <div class="grid grid-cols-2 gap-4 mt-2">
                <a href="#" class="bg-white text-orange-600 py-3 rounded-xl text-center font-semibold hover:bg-orange-100 transition">
                    Data Siswa
                </a>
                <a href="#" class="bg-white text-orange-600 py-3 rounded-xl text-center font-semibold hover:bg-orange-100 transition">
                    Data Guru
                </a>
                <a href="#" class="bg-white text-orange-600 py-3 rounded-xl text-center font-semibold hover:bg-orange-100 transition">
                    Data Kelas
                </a>
                <a href="#" class="bg-white text-orange-600 py-3 rounded-xl text-center font-semibold hover:bg-orange-100 transition">
                    Rekap Absen
                </a>
            </div>
        </div>

    </div>


</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('absensiChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Alpha'],
                datasets: [{
                    data: [120, 15, 8],
                    backgroundColor: [
                        '#fb923c',
                        '#fdba74',
                        '#f97316'
                    ],
                    borderWidth: 0,
                    hoverOffset: 12
                }]
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#000000', // ✅ TIDAK OPACITY
                            padding: 16,
                            font: {
                                size: 13,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8
                    }
                }
            }
        });
    });
</script>




@endsection