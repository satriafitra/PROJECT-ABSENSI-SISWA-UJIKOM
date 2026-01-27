@extends('layouts.admin')

@section('content')
<div class="p-6 min-h-screen">

    <h1 class="text-3xl font-bold text-orange-600 mb-6">
        Dashboard Guru
    </h1>

    <!-- CARD AKSI GURU -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

        <!-- SCAN QR -->
        <a href="#"
           class="bg-gradient-to-br from-orange-400 to-orange-600
                  text-white p-6 rounded-2xl shadow-md
                  hover:scale-[1.02] transition">
            <h2 class="text-xl font-semibold mb-2">Scan QR Absensi</h2>
            <p class="text-sm opacity-90">
                Lakukan absensi siswa hari ini
            </p>
        </a>

        <!-- ABSENSI HARI INI -->
        <a href="#"
           class="bg-white border-l-8 border-orange-400
                  p-6 rounded-2xl shadow-md
                  hover:translate-x-1 transition">
            <h2 class="text-xl font-semibold text-orange-600 mb-2">
                Absensi Hari Ini
            </h2>
            <p class="text-sm text-gray-500">
                Lihat rekap absensi siswa
            </p>
        </a>

    </div>

    <!-- SECTION CHART -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- DOUGHNUT CHART -->
        <div class="bg-white rounded-2xl shadow-md p-6 lg:col-span-1">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Statistik Absensi Hari Ini
            </h3>

            <div class="flex justify-center">
                <canvas id="absensiChart" class="max-w-[260px]"></canvas>
            </div>
        </div>

        <!-- BAR CHART -->
        <div class="bg-white rounded-2xl shadow-md p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                Absensi Mingguan
            </h3>

            <canvas id="mingguanChart" height="120"></canvas>
        </div>

    </div>

</div>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Doughnut Chart
    new Chart(document.getElementById('absensiChart'), {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin', 'Alpha'],
            datasets: [{
                data: [105, 10, 5], // dummy
                backgroundColor: [
                    '#f97316',
                    '#fdba74',
                    '#fb923c'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#000',
                        font: {
                            size: 13,
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });

    // Bar Chart
    new Chart(document.getElementById('mingguanChart'), {
        type: 'bar',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
            datasets: [{
                label: 'Jumlah Hadir',
                data: [100, 102, 98, 105, 103],
                backgroundColor: '#fb923c',
                borderRadius: 8
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

});
</script>
@endsection
