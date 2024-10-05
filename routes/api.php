<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExportDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('penjualan-piutang/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportPiutang']);
Route::get('penjualan-nonproduksi/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportNonProduksi']);
Route::get('penjualan-produksititipan/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportProduksiTitipan']);
Route::get('barang-terjual/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportDataTerjual']);
Route::get('pembelian-titipan/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportPembelianTitipan']);
Route::get('pembelian-cash/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportCash']);
Route::get('pembelian-hutangnonproduksi/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportHutangNonProduksi']);
Route::get('hutang-nonproduksi/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportHutangNonProduksi']);
Route::get('pengajuan-po/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportPO']);
Route::get('setoran/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportSetoran']);
Route::get('pelunasan/{startDate}/{endDate}/{nama_kampus}', [ExportDataController::class, 'exportPelunasan']);


