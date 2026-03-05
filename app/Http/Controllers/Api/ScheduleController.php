<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function byClass($class_id)
    {
        $schedules = Schedule::with(['guru', 'class'])
            ->where('class_id', $class_id)
            ->orderBy('day')
            ->orderBy('time_start')
            ->get();

        return response()->json([
            'status' => true,
            'total'  => $schedules->count(),
            'data'   => $schedules->map(function ($item) {
                return [
                    'id'         => $item->id,
                    'day'        => $item->day,
                    'time_start' => $item->time_start,
                    'time_end'   => $item->time_end,
                    'title'      => $item->subject,
                    'teacher'    => $item->guru->nama ?? '-',
                    'room'       => $item->room,
                    'class'      => $item->class->nama ?? '-',
                    'is_break'   => $item->is_break ?? false,
                ];
            })
        ]);
    }

    // 🔥 Optional: Jadwal hari ini saja (untuk Home Flutter)
    public function todayByClass($class_id)
    {
        $today = Carbon::now()->locale('id')->isoFormat('dddd');

        $schedules = Schedule::with('guru')
            ->where('class_id', $class_id)
            ->where('day', $today)
            ->orderBy('time_start')
            ->get();

        return response()->json([
            'status' => true,
            'day'    => $today,
            'data'   => $schedules->map(function ($item) {
                return [
                    'time_start' => $item->time_start,
                    'time_end'   => $item->time_end,
                    'title'      => $item->subject,
                    'teacher'    => $item->guru->nama ?? '-',
                    'room'       => $item->room,
                ];
            })
        ]);
    }
}