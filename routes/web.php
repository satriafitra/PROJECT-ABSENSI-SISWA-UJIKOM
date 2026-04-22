<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\LokasiController;
use App\Http\Controllers\Admin\JadwalGuruController;
use App\Http\Controllers\Admin\ManualAttendanceController;
// Tambahan Controller untuk Gamifikasi dan Shop
use App\Http\Controllers\Admin\PointManagerController;
use App\Http\Controllers\Admin\ShopManagerController;

use App\Http\Controllers\Guru\ScanQrController;
use App\Http\Controllers\Guru\RekapAbsensiController;
use App\Http\Controllers\Guru\AssessmentController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
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

            // Guru & Login As
            Route::resource('guru', GuruController::class);
            Route::get('guru/{id}/login-as', [GuruController::class, 'loginAs'])->name('guru.login-as');

            // Master Data & Absensi Manual
            Route::view('/tahun-ajar', 'admin.tahunajar')->name('tahunajar');
            Route::get('/total-penilaian', [DashboardController::class, 'totalPenilaian'])->name('total_penilaian');
            Route::get('/absensi-manual', [ManualAttendanceController::class, 'index'])->name('manual');
            Route::post('/absensi-manual/{id}/verify', [ManualAttendanceController::class, 'verify'])->name('manual.verify');

            // Rekap & Export
            Route::get('/rekap-absensi', [DashboardController::class, 'rekapAbsensi'])->name('rekapabsensi');
            Route::get('/admin/rekap-absensi', [AdminController::class, 'rekapAbsensi'])->name('admin.rekap');
            Route::get('/rekap-absensi/excel', [AdminController::class, 'exportExcel'])->name('rekap.excel');
            Route::get('/rekap-absensi/pdf', [AdminController::class, 'exportPdf'])->name('rekap.pdf');

            // Lokasi & Siswa
            Route::resource('lokasi', LokasiController::class);
            Route::resource('siswa', SiswaController::class);

            // Jadwal Guru
            Route::get('jadwal', [JadwalGuruController::class, 'index'])->name('jadwal.index');
            Route::get('jadwal/create', [JadwalGuruController::class, 'create'])->name('jadwal.create');
            Route::post('jadwal', [JadwalGuruController::class, 'store'])->name('jadwal.store');
            Route::get('jadwal/{id}/edit', [JadwalGuruController::class, 'edit'])->name('jadwal.edit');
            Route::put('jadwal/{id}', [JadwalGuruController::class, 'update'])->name('jadwal.update');
            Route::delete('jadwal/{id}', [JadwalGuruController::class, 'destroy'])->name('jadwal.destroy');

            // Assessment Category & Questions
            Route::resource('assessment-category', App\Http\Controllers\Admin\AssessmentCategoryController::class);
            Route::get('assessment-category/{id}/questions', [App\Http\Controllers\Admin\AssessmentCategoryController::class, 'manageQuestions'])
                ->name('assessment-category.questions');
            Route::post('assessment-category/{id}/questions', [App\Http\Controllers\Admin\AssessmentCategoryController::class, 'storeQuestion'])
                ->name('assessment-category.questions.store');
            Route::delete('questions/{id}', [App\Http\Controllers\Admin\AssessmentCategoryController::class, 'destroyQuestion'])
                ->name('questions.destroy');

            /*
            |--------------------------------------------------------------------------
            | NEW: GAMIFIKASI & SISWA SHOP
            |--------------------------------------------------------------------------
            */
            // Route Gamifikasi (Rule Manager)
            Route::prefix('gamifikasi')->name('gamifikasi.')->group(function() {
                Route::get('/', [PointManagerController::class, 'index'])->name('index');
                Route::post('/rule', [PointManagerController::class, 'storeRule'])->name('rule.store');
                Route::get('/leaderboard', [PointManagerController::class, 'leaderboard'])->name('leaderboard');
            });

            // Route Siswa Point (Shop Manager)
            Route::prefix('siswa-shop')->name('siswa-shop.')->group(function() {
                Route::get('/', [ShopManagerController::class, 'index'])->name('index');
                Route::post('/store', [ShopManagerController::class, 'store'])->name('store');
                Route::delete('/{id}', [ShopManagerController::class, 'destroy'])->name('destroy');
            });

            // Route Pusat Aduan (Ticketing System)
            Route::prefix('tickets')->name('tickets.')->group(function() {
                Route::get('/', [\App\Http\Controllers\Admin\TicketController::class, 'index'])->name('index');
                Route::get('/{id}', [\App\Http\Controllers\Admin\TicketController::class, 'show'])->name('show');
                Route::post('/{id}/reply', [\App\Http\Controllers\Admin\TicketController::class, 'reply'])->name('reply');
                Route::post('/{id}/status', [\App\Http\Controllers\Admin\TicketController::class, 'updateStatus'])->name('status');
            });

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

            Route::resource('assessment', App\Http\Controllers\Guru\AssessmentController::class);
            Route::post('/assessment/store', [AssessmentController::class, 'store']);
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