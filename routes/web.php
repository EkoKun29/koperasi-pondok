<?php

use App\Models\Setoran;
use App\Models\Pelunasan;
use App\Models\PengajuanPo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\PenjualanProduksiTitipan;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\PelunasanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NamaBarangController;
use App\Http\Controllers\PengajuanPoController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BarangTerjualController;
use App\Http\Controllers\PembelianCashController;
use App\Http\Controllers\PembelianTitipanController;
use App\Http\Controllers\PenjualanPiutangController;
use App\Http\Controllers\PenjualanNonProduksiController;
use App\Http\Controllers\PenjualanBarangTerjualController;
use App\Http\Controllers\PenjualanProduksiTitipanController;
use App\Http\Controllers\PembelianHutangNonProduksiController;

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

Route::group(['middleware' => 'auth'], function() {
//------------------------------------------------------Penjualan Piutang----------------------------------------------------------------------
Route::resource('penjualan-piutang', PenjualanPiutangController::class);
Route::post('penjualan-piutang/create', [PenjualanPiutangController::class, 'store'])->name('penjualan-piutang.store');
Route::get('/penjualan-piutang/{uuid}/edit', [PenjualanPiutangController::class, 'edit'])->name('penjualan-piutang.edit');
Route::put('/penjualan-piutang/{uuid}', [PenjualanPiutangController::class, 'update'])->name('penjualan-piutang.update');
Route::get('penjualan-piutang/detail/{uuid}', [PenjualanPiutangController::class, 'show'])->name('penjualan-piutang.detail');
Route::get('penjualan-piutang/delete/{uuid}', [PenjualanPiutangController::class, 'DeletePenjualan'])->name('delete-penjualan-piutang');Route::get('penjualan-piutang/detail/delete/{uuid}', [PenjualanPiutangController::class, 'DeleteDetailPenjualan'])->name('delete-penjualan-piutang-detail');
Route::get('penjualan-piutang/detail/delete/{id}', [PenjualanPiutangController::class, 'DeleteDetailPenjualan'])->name('delete-penjualan-piutang-detail');
Route::get('penjualan-piutang/print/{uuid}', [PenjualanPiutangController::class, 'print'])->name('penjualan-piutang.print');
Route::post('penjualan-piutang-detail/{uuid}', [PenjualanPiutangController::class, 'storeDetail'])->name('penjualan-piutang-detail-create');
Route::get('penjualan-piutang/detail/edit/{id}', [PenjualanPiutangController::class, 'editDetail'])->name('penjualan-piutang-edit-detail-create');
Route::put('/penjualan-piutang/{uuid}/detail/update', [PenjualanPiutangController::class, 'updateDetail'])->name('penjualan-piutang-detail-update');



//-------------------------------------------------------Penjualan Non Produksi---------------------------------------------------------------
Route::resource('penjualan-nonproduksi', PenjualanNonProduksiController::class);
Route::post('penjualan-nonproduksi/create', [PenjualanNonProduksiController::class, 'store'])->name('penjualan-nonproduksi.store');
Route::get('/penjualan-nonproduksi/{uuid}/edit', [PenjualanNonProduksiController::class, 'edit'])->name('penjualan-nonproduksi.edit');
Route::put('/penjualan-nonproduksi/{uuid}', [PenjualanNonProduksiController::class, 'update'])->name('penjualan-nonproduksi.update');
Route::get('penjualan-nonproduksi/detail/{uuid}', [PenjualanNonProduksiController::class, 'show'])->name('penjualan-nonproduksi.detail');
Route::get('penjualan-nonproduksi/delete/{uuid}', [PenjualanNonProduksiController::class, 'DeletePenjualan'])->name('delete-penjualan-nonproduksi');
Route::get('penjualan-nonproduksi/detail/delete/{id}', [PenjualanNonProduksiController::class, 'DeleteDetailPenjualan'])->name('delete-penjualan-nonproduksi-detail');
Route::get('penjualan-nonproduksi/print/{uuid}', [PenjualanNonProduksiController::class, 'print'])->name('penjualan-nonproduksi.print');
Route::post('penjualan-nonproduksi-detail/{uuid}', [PenjualanNonProduksiController::class, 'storeDetail'])->name('penjualan-nonproduksi-detail-create');
Route::put('/penjualan-nonproduksi/{uuid}/detail/update', [PenjualanNonProduksiController::class, 'updateDetail'])->name('penjualan-nonproduksi-update-detail');
    
//-------------------------------------------------------Penjualan Produksi Titipan----------------------------------------------------------
Route::resource('penjualan-produksititipan', PenjualanProduksiTitipanController::class);
Route::post('penjualan-produksititipan/create', [PenjualanProduksiTitipanController::class, 'store'])->name('penjualan-titipan.store');
Route::get('/penjualan-produksititipan/{uuid}/edit', [PenjualanProduksiTitipanController::class, 'edit'])->name('penjualan-produksititipan.edit');
Route::put('/penjualan-produksititipan/{uuid}', [PenjualanProduksiTitipanController::class, 'update'])->name('penjualan-produksititipan.update');
Route::get('penjualan-produksititipan/detail/{uuid}', [PenjualanProduksiTitipanController::class, 'show'])->name('penjualan-titipan.detail');
Route::get('penjualan-produksititipan/delete/{uuid}', [PenjualanProduksiTitipanController::class, 'DeletePenjualan'])->name('delete-penjualan-titipan');
Route::get('penjualan-produksititipan/detail/delete/{id}', [PenjualanProduksiTitipanController::class, 'DeleteDetailPenjualan'])->name('delete-penjualan-titipan-detail');
Route::get('penjualan-produksititipan/print/{uuid}', [PenjualanProduksiTitipanController::class, 'print'])->name('penjualan-titipan.print');
Route::post('penjualan-produksititipan-detail/{uuid}', [PenjualanProduksiTitipanController::class, 'storeDetail'])->name('penjualan-titipan-detail-create');
Route::put('/penjualan-titipan/{uuid}/detail/update', [PenjualanProduksiTitipanController::class, 'updateDetail'])->name('penjualan-titipan-detail-update');


//-------------------------------------------------------Barang Terjual--------------------------------------------------------------------
Route::resource('barang-terjual', BarangTerjualController::class);
Route::post('barang-terjual/create', [BarangTerjualController::class, 'store'])->name('barang-terjual.store');
Route::get('barang-terjual/detail/{uuid}', [BarangTerjualController::class, 'show'])->name('barang-terjual.detail');
Route::get('barang-terjual/delete/{uuid}', [BarangTerjualController::class, 'DeleteBarangTerjual'])->name('delete-barang-terjual');
Route::get('barang-terjual/detail/delete/{id}', [BarangTerjualController::class, 'DeleteDetailTerjual'])->name('delete-barang-terjual-detail');
Route::get('barang-terjual/print/{uuid}', [BarangTerjualController::class, 'print'])->name('barang-terjual.print');
Route::post('barang-terjual-detail/{uuid}', [BarangTerjualController::class, 'storeDetail'])->name('barang-terjual-detail-create');


//--------------------------------------------------------Pembelian Titipan---------------------------------------------------------------
Route::resource('pembelian-titipan', PembelianTitipanController::class);
Route::post('pembelian-titipan/create', [PembelianTitipanController::class, 'store'])->name('pembelian-titipan.store');
Route::get('/pembelian-titipan/{uuid}/edit', [PembelianTitipanController::class, 'edit'])->name('pembelian-titipan.edit');
Route::put('/pembelian-titipan/{uuid}', [PembelianTitipanController::class, 'update'])->name('pembelian-titipan.update');
Route::get('pembelian-titipan/detail/{uuid}', [PembelianTitipanController::class, 'show'])->name('pembelian-titipan.detail');
Route::get('pembelian-titipan/delete/{uuid}', [PembelianTitipanController::class, 'DeletePembelian'])->name('delete-pembelian-titipan');
Route::get('pembelian-titipan/detail/delete/{id}', [PembelianTitipanController::class, 'DeleteDetailPembelian'])->name('delete-pembelian-titipan-detail');
Route::get('pembelian-titipan/print/{uuid}', [PembelianTitipanController::class, 'print'])->name('pembelian-titipan.print');
Route::post('pembelian-titipan-detail/{uuid}', [PembelianTitipanController::class, 'storeDetail'])->name('pembelian-titipan-detail-create');
Route::post('/pembelian-titipan/{uuid}/update-detail', [PembelianTitipanController::class, 'updateDetail'])->name('pembelian-titipan.update-detail');

//--------------------------------------------------------Pembelian Cash-------------------------------------------------------- 
Route::resource('pembelian-cash', PembelianCashController::class); 
Route::post('pembelian-cash/create', [PembelianCashController::class, 'store'])->name('pembelian-cash.store'); 
Route::get('pembelian-cash/detail/{uuid}', [PembelianCashController::class, 'show'])->name('pembelian-cash.detail');
Route::get('/pembelian-cash/{uuid}/edit', [PembelianCashController::class, 'edit'])->name('pembelian-cash.edit');
Route::put('/pembelian-cash/{uuid}', [PembeliancashController::class, 'update'])->name('pembelian-cash.update');
Route::get('pembelian-cash/delete/{uuid}', [PembelianCashController::class, 'DeletePembelian'])->name('delete-pembelian-cash'); 
Route::get('pembelian-cash/detail/delete/{id}', [PembelianCashController::class, 'DeleteDetailPembelian'])->name('delete-pembelian-cash-detail'); 
Route::get('pembelian-cash/print/{uuid}', [PembelianCashController::class, 'print'])->name('pembelian-cash.print');
Route::post('pembelian-cash-detail/{uuid}', [PembelianCashController::class, 'storeDetail'])->name('pembelian-cash-detail-create');
Route::post('/pembelian-cash/{uuid}/update-detail', [PembelianCashController::class, 'updateDetail'])->name('pembelian-cash-update-detail');

//-----------------------------------------Pembelian Hutang Non Produksi--------------------------------------------------------------------------------------------------------------------
Route::resource('pembelian-hutangnonproduksi', PembelianHutangNonProduksiController::class);
Route::post('pembelian-hutangnonproduksi/create', [PembelianHutangNonProduksiController::class, 'store'])->name('pembelian-hutangnonproduksi.store');
Route::get('pembelian-hutangnonproduksi/detail/{uuid}', [PembelianHutangNonProduksiController::class, 'show'])->name('pembelian-hutangnonproduksi.detail');
Route::get('/pembelian-hutangnonproduksi/{uuid}/edit', [PembelianHutangNonProduksiController::class, 'edit'])->name('pembelian-hutangnonproduksi.edit');
Route::put('/pembelian-hutangnonproduksi/{uuid}', [PembelianHutangNonProduksiController::class, 'update'])->name('pembelian-hutangnonproduksi.update');
Route::get('pembelian-hutangnonproduksi/delete/{uuid}', [PembelianHutangNonProduksiController::class, 'DeletePembelian'])->name('delete-pembelian-hutangnonproduksi');
Route::get('pembelian-hutangnonproduksi/detail/delete/{id}', [PembelianHutangNonProduksiController::class, 'DeleteDetailPembelian'])->name('delete-pembelian-hutangnonproduksi-detail');
Route::get('pembelian-hutangnonproduksi/print/{uuid}', [PembelianHutangNonProduksiController::class, 'print'])->name('pembelian-hutangnonproduksi.print');
Route::post('pembelian-hutangnonproduksi-detail/{uuid}', [PembelianHutangNonProduksiController::class, 'storeDetail'])->name('pembelian-hutangnonproduksi-detail-create');
Route::post('/pembelian-hutangnonproduksi/{uuid}/update-detail', [PembelianHutangNonProduksiController::class, 'updateDetail'])->name('pembelian-hutangnonproduksi-update-detail');

//-----------------------------------------Pengajuan PO-----------------------------------------------------------------------------------------------------------------
Route::resource('pengajuan-po', PengajuanPoController::class);
Route::post('pengajuan-po/create', [PengajuanPoController::class, 'store'])->name('pengajuan-po.store');
Route::get('pengajuan-po/{uuid}/edit', [PengajuanPoController::class, 'edit'])->name('pengajuan-po.edit');
Route::put('pengajuan-po/{uuid}', [PengajuanPoController::class, 'update'])->name('pengajuan-po.update');
Route::get('pengajuan-po/detail/{uuid}', [PengajuanPoController::class, 'show'])->name('pengajuan-po.detail');
Route::get('pengajuan-po/delete/{uuid}', [PengajuanPoController::class, 'DeletePengajuan'])->name('delete-pengajuan-po');Route::get('pengajuan-po/detail/delete/{uuid}', [PengajuanPoController::class, 'DeleteDetailPengajuan'])->name('delete-pengajuan-po-detail');
Route::get('pengajuan-po/detail/delete/{id}', [PengajuanPoController::class, 'DeleteDetailPengajuan'])->name('delete-pengajuan-po-detail');
Route::get('pengajuan-po/print/{uuid}', [PengajuanPoController::class, 'print'])->name('pengajuan-po.print');
Route::post('pengajuan-po-detail/{uuid}', [PengajuanPoController::class, 'storeDetail'])->name('pengajuan-po-detail-create');
Route::get('pengajuan-po/detail/edit/{id}', [PengajuanPoController::class, 'editDetail'])->name('pengajuan-po-edit-detail-create');
Route::put('pengajuan-po/{uuid}/detail/update', [PengajuanPoController::class, 'updateDetail'])->name('pengajuan-po-detail-update');

//-----------------------------------------Setoran--------------------------------------------------------------------------------------------------------------------
Route::resource('setoran', SetoranController::class);
Route::get('setoran/delete/{uuid}', [SetoranController::class, 'DeleteSetoran'])->name('delete-setoran');
Route::get('setoran/print/{uuid}', [SetoranController::class, 'print'])->name('setoran.print');
Route::get('/setoran/{uuid}/edit', [SetoranController::class, 'edit'])->name('setoran.edit');
Route::put('/setoran/{uuid}', [SetoranController::class, 'update'])->name('setoran.update');

//-----------------------------------------Pelunasan--------------------------------------------------------------------------------------------------------------------
Route::resource('pelunasan', PelunasanController::class);

//--------------------------------------barang----------------------------------------------------------------------------------------------------------------------------------------------
Route::resource('barang', App\Http\Controllers\NamaBarangController::class);
Route::get('/barang-sync', [App\Http\Controllers\NamaBarangController::class, 'sync'])->name('barang.sync');

});