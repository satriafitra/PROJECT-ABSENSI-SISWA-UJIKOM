@extends('layouts.admin')

@section('content')
<div class="p-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Guru</h1>
            <p class="text-sm text-gray-500">Daftar seluruh jadwal mengajar guru</p>
        </div>
    </div>

    <!-- Card -->
    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Guru</th>
                        <th class="px-6 py-3">Kelas</th>
                        <th class="px-6 py-3">Hari</th>
                        <th class="px-6 py-3">Mata Pelajaran</th>
                        <th class="px-6 py-3">Jam</th>
                        <th class="px-6 py-3">Ruangan</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schedules as $index => $schedule)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-6 py-4 font-semibold text-gray-700">
                            {{ $schedule->guru->nama ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $schedule->class->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                {{ $schedule->day }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            {{ $schedule->subject }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                {{ \Carbon\Carbon::parse($schedule->time_start)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($schedule->time_end)->format('H:i') }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            {{ $schedule->room ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @if($schedule->is_break)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                    Istirahat
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                    Mengajar
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-6 text-center text-gray-500">
                            Data jadwal belum tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection