<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;

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
    |--------------------------------------------------
    | DASHBOARD REDIRECT BY ROLE
    |--------------------------------------------------
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
    |--------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Placeholder halaman (biar tombol bisa diklik)
    Route::view('/tahun-ajar', 'admin.tahunajar')->name('tahunajar');
    Route::view('/rombel', 'admin.rombel')->name('rombel');
    Route::view('/guru', 'admin.guru')->name('guru');
    Route::view('/siswa', 'admin.siswa')->name('siswa');
    Route::view('/rekap-absensi', 'admin.rekapabsensi')->name('rekapabsensi');
    });

    /*
    |--------------------------------------------------
    | GURU ROUTES
    |--------------------------------------------------
    */
    Route::get('/guru/dashboard', function () {
        return view('guru.dashboard');
    })->name('guru.dashboard');

    /*
    |--------------------------------------------------
    | PROFILE
    |--------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
