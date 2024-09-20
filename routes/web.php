<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NamaBarangController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BarangTerjualController;
use App\Http\Controllers\PembelianTitipanController;
use App\Http\Controllers\PenjualanPiutangController;
use App\Http\Controllers\PenjualanNonProduksiController;
use App\Http\Controllers\PenjualanBarangTerjualController;
use App\Http\Controllers\PenjualanProduksiTitipanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Auth::routes();

//Login Page and POST request for login
Route::get('/log-in', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('_login');
Route::post('/log-in-post', [App\Http\Controllers\Auth\LoginController::class, 'posts'])->name('_postlogin');

//Register
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'create'])->name('_register');
Route::post('/input-register', [App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('_postRegister');

//Logout
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('_logout');

//Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Penjualan Piutang
Route::resource('penjualan-piutang', PenjualanPiutangController::class);
Route::post('penjualan-piutang/create', [PenjualanPiutangController::class, 'store'])->name('penjualan-piutang.store');
Route::get('penjualan-piutang/detail/{uuid}', [PenjualanPiutangController::class, 'show'])->name('penjualan-piutang.detail');
Route::get('penjualan-piutang/delete/{uuid}', [PenjualanPiutangController::class, 'DeletePenjualan'])->name('delete-penjualan-piutang');Route::get('penjualan-piutang/detail/delete/{uuid}', [PenjualanPiutangController::class, 'DeleteDetailPenjualan'])->name('delete-penjualan-piutang-detail');
Route::get('penjualan-piutang/detail/delete/{id}', [PenjualanPiutangController::class, 'DeleteDetailPenjualan'])->name('delete-penjualan-piutang-detail');
Route::get('penjualan-piutang/print/{uuid}', [PenjualanPiutangController::class, 'print'])->name('penjualan-piutang.print');


//Penjualan Non Produksi
Route::resource('penjualan-nonproduksi', PenjualanNonProduksiController::class);
Route::post('penjualan-nonproduksi/create', [PenjualanNonProduksiController::class, 'store'])->name('penjualan-nonproduksi.store');
Route::get('penjualan-nonproduksi/detail/{uuid}', [PenjualanNonProduksiController::class, 'show'])->name('penjualan-nonproduksi.detail');
Route::get('penjualan-nonproduksi/delete/{uuid}', [PenjualanNonProduksiController::class, 'DeletePenjualan'])->name('delete-penjualan-nonproduksi');
Route::get('penjualan-nonproduksi/detail/delete/{id}', [PenjualanNonProduksiController::class, 'DeleteDetailPenjualan'])->name('delete-penjualan-nonproduksi-detail');
Route::get('penjualan-nonproduksi/print/{uuid}', [PenjualanNonProduksiController::class, 'print'])->name('penjualan-nonproduksi.print');
    
//Penjualan Produksi Titipan
Route::resource('penjualan-produksititipan', PenjualanProduksiTitipanController::class);
Route::post('penjualan-produksititipan/create', [PenjualanProduksiTitipanController::class, 'store'])->name('penjualan-titipan.store');
Route::get('penjualan-produksititipan/detail/{uuid}', [PenjualanProduksiTitipanController::class, 'show'])->name('penjualan-titipan.detail');
Route::get('penjualan-produksititipan/delete/{uuid}', [PenjualanProduksiTitipanController::class, 'DeletePenjualan'])->name('delete-penjualan-titipan');
Route::get('penjualan-produksititipan/detail/delete/{id}', [PenjualanProduksiTitipanController::class, 'DeleteDetailPenjualan'])->name('delete-penjualan-titipan-detail');
Route::get('penjualan-produksititipan/print/{uuid}', [PenjualanProduksiTitipanController::class, 'print'])->name('penjualan-titipan.print');


//Barang Terjual
Route::resource('barang-terjual', BarangTerjualController::class);
Route::post('barang-terjual/create', [BarangTerjualController::class, 'store'])->name('barang-terjual.store');
Route::get('barang-terjual/detail/{uuid}', [BarangTerjualController::class, 'show'])->name('barang-terjual.detail');
Route::get('barang-terjual/delete/{uuid}', [BarangTerjualController::class, 'DeleteBarangTerjual'])->name('delete-barang-terjual');
Route::get('barang-terjual/detail/delete/{id}', [BarangTerjualController::class, 'DeleteDetailTerjual'])->name('delete-barang-terjual-detail');
Route::get('barang-terjual/print/{uuid}', [BarangTerjualController::class, 'print'])->name('barang-terjual.print');

//Pembelian Titipan
Route::resource('pembelian-titipan', PembelianTitipanController::class);
Route::post('pembelian-titipan/create', [PembelianTitipanController::class, 'store'])->name('pembelian-titipan.store');
Route::get('pembelian-titipan/detail/{uuid}', [PembelianTitipanController::class, 'show'])->name('pembelian-titipan.detail');
Route::get('pembelian-titipan/delete/{uuid}', [PembelianTitipanController::class, 'DeletePenjualan'])->name('delete-pembelian-titipan');
Route::get('pembelian-titipan/detail/delete/{id}', [PembelianTitipanController::class, 'DeleteDetailPenjualan'])->name('delete-pembelian-titipan-detail');
Route::get('pembelian-titipan/print/{uuid}', [PembelianTitipanController::class, 'print'])->name('pembelian-titipan.print');



Route::resource('barang', App\Http\Controllers\NamaBarangController::class);
Route::get('/barang-sync', [App\Http\Controllers\NamaBarangController::class, 'sync'])->name('barang.sync');
