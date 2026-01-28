<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;

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

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD REDIRECT BY ROLE
    |--------------------------------------------------------------------------
    */
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
    Route::prefix('admin')->name('admin.')->group(function () {

        /* ================= DASHBOARD ================= */
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /* ================= MASTER DATA ================= */
        Route::view('/tahun-ajar', 'admin.tahunajar')->name('tahunajar');
        Route::view('/rombel', 'admin.rombel')->name('rombel');
        Route::view('/siswa', 'admin.siswa')->name('siswa');
        Route::view('/rekap-absensi', 'admin.rekapabsensi')->name('rekapabsensi');

        /* ================= GURU ================= */

        // 👉 ALIAS AGAR route('admin.guru') TIDAK ERROR

        Route::prefix('guru')->name('guru.')->group(function () {

            Route::get('/', [GuruController::class, 'index'])->name('index');
            Route::get('/create', [GuruController::class, 'create'])->name('create');
            Route::post('/', [GuruController::class, 'store'])->name('store');

            Route::get('/{guru}/edit', [GuruController::class, 'edit'])->name('edit');
            Route::put('/{guru}', [GuruController::class, 'update'])->name('update');
            Route::delete('/{guru}', [GuruController::class, 'destroy'])->name('destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | GURU ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', function () {
            return view('guru.dashboard');
        })->name('dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';
