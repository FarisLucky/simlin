<?php

use App\Http\Controllers\Api\DaftarController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\HDaftarController;
use App\Http\Controllers\Api\MAlatController;
use App\Http\Controllers\Api\MBundleController;
use App\Http\Controllers\Api\MBundleDetailController;
use App\Http\Controllers\Api\MKategoriController;
use App\Http\Controllers\Api\MLinenController;
use App\Http\Controllers\Api\MLinenUnitController;
use App\Http\Controllers\Api\MUnitController;
use App\Http\Controllers\Api\MutuController;
use App\Http\Controllers\Api\PinjamAlatController;
use App\Http\Controllers\Api\PinjamAlatDetailController;
use App\Http\Controllers\Api\TransLinenController;
use App\Http\Controllers\Api\TransLinenDetailController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    /**
     * Unit route
     **/
    Route::apiResource('m-unit', MUnitController::class)->names('units');
    Route::get('m-unit/data/all', [MUnitController::class, 'data'])->name('units.data');
    /**
     * Linen route
     **/
    Route::apiResource('m-linen', MLinenController::class)->names('linens');
    Route::get('m-linen-data', [MLinenController::class, 'data'])->name('m_linen.data');
    /**
     * Linen Unit route
     **/
    Route::apiResource('m-linen-unit', MLinenUnitController::class)->names('linen_unit.data');
    Route::get('m-linen-unit/data/all', [MLinenUnitController::class, 'data'])->name('linen_unit.data');
    /**
     * Alat route
     **/
    Route::apiResource('m-bundle', MBundleController::class)->names('mbundle');
    Route::get('m-bundle/data/all', [MBundleController::class, 'data'])->name('mbundle.data');
    /**
     * Alat Bundle route
     **/
    Route::apiResource('m-bundle-detail', MBundleDetailController::class)->names('bundle-detail');
    Route::get('m-bundle-detail/data/all', [MBundleDetailController::class, 'data'])->name('bundle-detail.data');
    /**
     * Alat route
     **/
    Route::apiResource('m-alat', MAlatController::class)->names('alat');
    Route::get('m-alat/data/all', [MAlatController::class, 'data'])->name('alat.line');
    /**
     * Alat route
     **/
    Route::apiResource('m-kategori', MKategoriController::class)->names('kategori');
    Route::get('m-kategori/data/all', [MKategoriController::class, 'data'])->name('kategori.line');

    /**
     * Alat route
     **/
    Route::apiResource('m-alat', MAlatController::class)->names('alat');
    Route::get('m-alat/data/all', [MAlatController::class, 'data'])->name('alat.line');

    /**
     * Daftar route
     **/
    Route::apiResource('daftar', DaftarController::class)->names('daftar');
    Route::delete('daftar-all', [DaftarController::class, 'destroyAll'])->name('daftar.destroy_all');
    Route::get('daftar/show/by', [DaftarController::class, 'showBy'])->name('daftar.show_by');
    Route::put('daftar/ajukan/{kode}', [DaftarController::class, 'updateAjukan'])->name('daftar.update_ajukan');
    Route::get('daftar-grafik/line', [DaftarController::class, 'grafik'])->name('daftar.grafik');
    Route::get('daftar-statistik', [DaftarController::class, 'statistik'])->name('daftar.statistik');
    Route::get('daftar-statistik/trend-kasa', [DaftarController::class, 'trendKasa'])->name('daftar.statistik-trend-kasa');

    /**
     * Trans Linen route
     **/
    Route::apiResource('trans-linen', TransLinenController::class)->names('trans_linen');

    /**
     * Trans Linen route
     **/
    Route::apiResource('trans-linen-detail', TransLinenDetailController::class)
        ->only(['store', 'destroy'])
        ->names('trans_linen_detail');
    Route::get('trans-linen-detail/kode-daftar/{kode}', [TransLinenDetailController::class, 'showByKodeDaftar'])->name('trans_linen_detail.show_by_kode_daftar');
    Route::put('trans-linen-detail/{id}/qty', [TransLinenDetailController::class, 'updateQty'])->name('trans_linen_detail.update_qty');

    /**
     * Pinjam Alat route
     **/
    Route::apiResource('pinjam-alat', PinjamAlatController::class)->names('pinjam_alat');
    Route::get('pinjam-alat/kode-daftar/{kode}', [PinjamAlatController::class, 'showByKodeDaftar'])->name('pinjam_alat.show_by_kode_daftar');
    Route::put('pinjam-alat/{id}/qty', [PinjamAlatController::class, 'updateQty'])->name('pinjam_alat.update_qty');
    Route::delete('pinjam-alat-all', [PinjamAlatController::class, 'destroyAll'])->name('pinjam_alat.destroy_all');
    Route::put('pinjam-alat-catatan/{id}', [PinjamAlatController::class, 'catatan'])->name('pinjam_alat.catatan');

    /**
     * Pinjam route
     **/
    Route::apiResource('pinjam-detail', PinjamAlatDetailController::class)
        ->only(['store', 'destroy'])
        ->names('pinjam_detail');
    Route::get('pinjam-detail/kode-daftar/{kode}', [PinjamAlatDetailController::class, 'showByKodeDaftar'])->name('trans_linen_detail.show_by_kode_daftar');
    Route::put('pinjam-detail/{id}/qty', [PinjamAlatDetailController::class, 'updateQty'])->name('trans_linen_detail.update_qty');

    /**
     * Logout
     */
    Route::delete('logout', [LoginController::class, 'logout'])->name('auth.logout');

    /**
     * History Daftar route
     **/
    Route::get('history/daftar', [HDaftarController::class, 'index'])->name('history_daftar.index');

    /**
     * Kontrol route
     *
     **/
    Route::prefix('/dashboard')->name('dashboard.')->group(function () {
        Route::get('stat', [DashboardController::class, 'index'])
            ->name('index');
        Route::get('line', [DashboardController::class, 'line'])
            ->name('line');
    });
});

/**
 * Authentication route
 *
 **/
Route::post('login', [LoginController::class, 'login'])->name('auth.login');

/**
 * Mutu route
 *
 **/
Route::apiResource('mutu', MutuController::class)->names('mutu');
Route::get('mutu/{kode}/daftar', [MutuController::class, 'showByDaftar'])->name('mutu.show-by-daftar');

Route::get('daftar-statistik/trend-kasa', [DaftarController::class, 'trendKasa'])->name('daftar.statistik-trend-kasa');
