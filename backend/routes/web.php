<?php

use App\Http\Controllers\Api\RekapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    echo bcrypt('rsgs@321');
    return view('welcome');
});

Route::get('/rekap/linen/excel', [RekapController::class, 'harianLinen'])->name('rekap.harian_linen');
Route::get('/rekap/mutu/excel', [RekapController::class, 'mutuLinen'])->name('rekap.mutu_linen');
