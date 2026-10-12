<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\JenisKejadianController;
use App\Http\Controllers\KategoriKejadianController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\PetaKerawananController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KejadianKebakaranController;
use App\Http\Controllers\KejadianPenyelamatanController;
use App\Http\Controllers\ReguController;
use App\Services\WilayahService;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AnalisisKMeansController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\LaporanController;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/peta/data', [HomeController::class, 'petaData'])->name('peta.data');
Route::get('/peta/boundary', [HomeController::class, 'boundary'])->name('peta.boundary');


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});


/*
|--------------------------------------------------------------------------
| Auth Routes (admin & petugas)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Logout (semua role)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ==================== ADMIN & PETUGAS ====================
    Route::middleware(['role:admin,petugas'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Data Kejadian
        Route::resource('kejadian-kebakarans', KejadianKebakaranController::class);
        Route::resource('kejadian-penyelamatans', KejadianPenyelamatanController::class);

        // Peta Kerawanan
        Route::get('/peta-kerawanan', [PetaKerawananController::class, 'index'])
            ->name('peta-kerawanan.index');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    });


    // ==================== ADMIN ONLY ====================
    Route::middleware(['role:admin'])->group(function () {

        // Data Master
        Route::resource('kecamatans', KecamatanController::class);
        Route::resource('kelurahans', KelurahanController::class);
        Route::resource('regus', ReguController::class);

        // Penduduk
        Route::resource('penduduk', PendudukController::class)->only(['index']);
        Route::get('/penduduk/download-template', [PendudukController::class, 'downloadTemplate'])
            ->name('penduduk.download-template');
        Route::post('/penduduk/import', [PendudukController::class, 'import'])
            ->name('penduduk.import');

        // Jenis Kejadian & Kategori Kejadian
        Route::resource('jenis-kejadians', JenisKejadianController::class);
        Route::resource('kategori-kejadians', KategoriKejadianController::class);

        // Incident (jika masih dipakai)
        Route::resource('incidents', IncidentController::class);

        // Analisis K-Means
        Route::get('/analisis-kmeans', [AnalisisKMeansController::class, 'index'])
            ->name('analisis-kmeans.index');
        Route::post('/analisis-kmeans/proses', [AnalisisKMeansController::class, 'proses'])
            ->name('analisis-kmeans.proses');
        Route::get('/analisis-kmeans/{kmeansRun}', [AnalisisKMeansController::class, 'show'])
            ->name('analisis-kmeans.show');

        // Activity Log
        Route::get('/activity-log', [ActivityLogController::class, 'index'])
            ->name('activity-log.index');

        // User Management
        Route::resource('users', UserController::class);

        // Laporan
        Route::prefix('laporan')
            ->name('laporan.')
            ->controller(LaporanController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/pdf', 'pdf')->name('pdf');
                Route::get('/excel', 'excel')->name('excel');
            });
    });


    // ==================== API ====================
    Route::get('/api/boundaries-tangsel', function () {
        $response = Http::timeout(30)
            ->get('https://wilayah.smartartstudio.my.id/api/boundaries/36.74/children');

        if ($response->failed()) {
            return response()->json([
                'message' => 'Gagal mengambil data boundary Tangerang Selatan.'
            ], 500);
        }

        return response()->json($response->json());
    })->name('api.boundaries.tangsel');

});
Route::get('/test-403', fn() => abort(403));
Route::get('/test-500', fn() => throw new \Exception('Test error'));