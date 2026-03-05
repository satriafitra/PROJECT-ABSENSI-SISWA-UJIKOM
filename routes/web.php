<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\LokasiController;
use App\Http\Controllers\Admin\JadwalGuruController;

use App\Http\Controllers\Guru\ScanQrController;
use App\Http\Controllers\Guru\RekapAbsensiController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Redirect dashboard sesuai role
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'guru') {
            return redirect()->route('guru.dashboard');
        }

        abort(403, 'Role tidak dikenali');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:isAdmin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Master Data
            Route::view('/tahun-ajar', 'admin.tahunajar')->name('tahunajar');
            Route::view('/rombel', 'admin.rombel')->name('rombel');

            Route::get('/rekap-absensi', [DashboardController::class, 'rekapAbsensi'])->name('rekapabsensi');

            // Lokasi
            Route::resource('lokasi', LokasiController::class);

            // Siswa
            Route::resource('siswa', SiswaController::class);

            // Guru
            Route::resource('guru', GuruController::class);

            // Jadwal Guru (CRUD)
            Route::get('jadwal', [JadwalGuruController::class, 'index'])->name('jadwal.index');
            Route::get('jadwal/create', [JadwalGuruController::class, 'create'])->name('jadwal.create');
            Route::post('jadwal', [JadwalGuruController::class, 'store'])->name('jadwal.store');
            Route::get('jadwal/{id}/edit', [JadwalGuruController::class, 'edit'])->name('jadwal.edit');
            Route::put('jadwal/{id}', [JadwalGuruController::class, 'update'])->name('jadwal.update');
            Route::delete('jadwal/{id}', [JadwalGuruController::class, 'destroy'])->name('jadwal.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | GURU ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('can:isGuru')
        ->prefix('guru')
        ->name('guru.')
        ->group(function () {

            Route::get('/dashboard', function () {
                return view('guru.dashboard');
            })->name('dashboard');

            Route::get('/scan-qr', [ScanQrController::class, 'index'])->name('scan.qr');
            Route::get('/scan-qr/export', [ScanQrController::class, 'export'])->name('scan.export');

            Route::get('/rekap-absensi', [RekapAbsensiController::class, 'index'])->name('rekap.absensi');
        });

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';