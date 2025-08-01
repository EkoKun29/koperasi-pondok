<?php

use App\Models\Setoran;
use App\Models\Pelunasan;
use App\Models\PengajuanPo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\PenjualanProduksiTitipan;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\PelunasanController;
use App\Http\Controllers\PelunasanPembelianController;
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
use App\Http\Controllers\StokController;
use App\Http\Controllers\PindahController;



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

//PembelianNew
Route::resource('pembelian-new', App\Http\Controllers\PembelianPerKampusController::class);
Route::post('pembelian-new/create', [App\Http\Controllers\PembelianPerKampusController::class, 'store'])->name('pembelian-new.store');
Route::get('/pembelian-new/detail/{uuid}', [App\Http\Controllers\PembelianPerKampusController::class, 'show'])->name('pembelian-new.detail');
// Route::get('/pembelian-new/detail/{uuid}/edit', [App\Http\Controllers\PembelianPerKampusController::class, 'showDetail'])->name('pembelian-new.detail');
Route::get('/pembelian-new/delete/{uuid}', [App\Http\Controllers\PembelianPerKampusController::class, 'destroy'])->name('delete-pembelian-new');
Route::post('/pembelian-new/store-detail/{uuid}', [App\Http\Controllers\PembelianPerKampusController::class, 'storeDetail'])->name('pembelian-new.store-detail');
Route::get('/pembelian-new/{uuid}/edit', [App\Http\Controllers\PembelianPerKampusController::class, 'edit'])->name('pembelian-new.edit');
Route::get('/pembelian-new/{uuid}/edit/detail', [App\Http\Controllers\PembelianPerKampusController::class, 'editDetail'])->name('pembelian-new.edit-detail');
Route::put('/pembelian-new/{uuid}', [App\Http\Controllers\PembelianPerKampusController::class, 'update'])->name('pembelian-new.update');
Route::put('/pembelian-new/{uuid}/detail/update', [App\Http\Controllers\PembelianPerKampusController::class, 'updateDetail'])->name('pembelian-new.update-detail');
Route::get('/pembelian-new/detail/delete/{id}', [App\Http\Controllers\PembelianPerKampusController::class, 'deleteDetail'])->name('delete-pembelian-new-detail');
Route::get('/pembelian-new/print/{uuid}', [App\Http\Controllers\PembelianPerKampusController::class, 'print'])->name('pembelian-new.print');

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
Route::get('/barang-terjual/{uuid}/edit', [BarangTerjualController::class, 'edit'])->name('barang-terjual.edit');
Route::put('/barang-terjual/{uuid}', [BarangTerjualController::class, 'update'])->name('barang-terjual.update');
Route::put('/barang-terjual/{uuid}/detail/update', [BarangTerjualController::class, 'updateDetail'])->name('barang-terjual-update-detail');




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
Route::post('/pelunasan', [PelunasanController::class, 'store'])->name('pelunasan.store');
Route::get('/pelunasan-sisa', [PelunasanController::class, 'getSisaPiutang']);
Route::get('pelunasan/{pelunasan}', [PelunasanController::class, 'show']);
Route::get('/pelunasan/{uuid}/edit', [PelunasanController::class, 'edit'])->name('pelunasan.edit');
Route::put('/pelunasan/{uuid}', [PelunasanController::class, 'update'])->name('pelunasan.update');
Route::get('/pelunasan/delete/{uuid}', [PelunasanController::class, 'Delete'])->name('delete-pelunasan');

//-----------------------------------------Pelunasan Pembelian--------------------------------------------------------------------------------------------------------------------
Route::resource('pelunasan-pembelian', PelunasanPembelianController::class);
Route::post('/pelunasan-pembelian', [ PelunasanPembelianController::class, 'store'])->name('pelunasan-pembelian.store');
Route::get('/pelunasan-pembelian-sisa', [ PelunasanPembelianController::class, 'getSisaPiutang']);
Route::get('pelunasan-pembelian/{pelunasan}', [ PelunasanPembelianController::class, 'show']);
Route::get('/pelunasan-pembelian/{uuid}/edit', [ PelunasanPembelianController::class, 'edit'])->name('pelunasan-pembelian.edit');
Route::put('/pelunasan-pembelian/{uuid}', [ PelunasanPembelianController::class, 'update'])->name('pelunasan-pembelian.update');
Route::get('/pelunasan-pembelian/delete/{uuid}', [ PelunasanPembelianController::class, 'Delete'])->name('delete-pelunasan-pembelian');

//--------------------------------------BARANG MASUK----------------------------------------------------------------------------------------------------------------------------------------------

Route::resource('barang-masuk', App\Http\Controllers\BarangMasukController::class);
Route::post('barang-masuk/create', [App\Http\Controllers\BarangMasukController::class, 'store'])->name('barang-masuk.store');
Route::post('barang-masuk/store-detail/{uuid}', [App\Http\Controllers\BarangMasukController::class, 'storeDetail'])->name('barang-masuk.store-detail');
Route::get('/barang-masuk/{uuid}/edit', [App\Http\Controllers\BarangMasukController::class, 'edit'])->name('barang-masuk.edit');
Route::put('/barang-masuk/{uuid}', [App\Http\Controllers\BarangMasukController::class, 'update'])->name('barang-masuk.update');
Route::put('/barang-masuk/{uuid}/detail/update', [App\Http\Controllers\BarangMasukController::class, 'updateDetail'])->name('barang-masuk.update-detail');
Route::get('barang-masuk/detail/{uuid}', [App\Http\Controllers\BarangMasukController::class, 'show'])->name('barang-masuk.detail');
Route::get('barang-masuk/delete/{uuid}', [App\Http\Controllers\BarangMasukController::class, 'DeleteBarangMasuk'])->name('delete-barang-masuk');
Route::get('barang-masuk/detail/delete/{uuid}', [App\Http\Controllers\BarangMasukController::class, 'DeleteDetailBarangMasuk'])->name('delete-barang-masuk-detail');
Route::get('barang-masuk/print/{uuid}', [App\Http\Controllers\BarangMasukController::class, 'print'])->name('barang-masuk.print');


//-----------------------------------------Barang Masuk Produksi--------------------------------------------------------------------------------------------------------------------
Route::resource('barang-masuk-produksi', App\Http\Controllers\BarangMasukProduksiController::class);
Route::post('barang-masuk-produksi/create', [App\Http\Controllers\BarangMasukProduksiController::class, 'store'])->name('barang-masuk-produksi.store');
Route::post('barang-masuk-produksi/store-detail/{uuid}', [App\Http\Controllers\BarangMasukProduksiController::class, 'storeDetail'])->name('barang-masuk-produksi.store-detail');
Route::get('/barang-masuk-produksi/{uuid}/edit', [App\Http\Controllers\BarangMasukProduksiController::class, 'edit'])->name('barang-masuk-produksi.edit');
Route::put('/barang-masuk-produksi/{uuid}', [App\Http\Controllers\BarangMasukProduksiController::class, 'update'])->name('barang-masuk-produksi.update');
Route::put('/barang-masuk-produksi/{uuid}/detail/update', [App\Http\Controllers\BarangMasukProduksiController::class, 'updateDetail'])->name('barang-masuk-produksi.update-detail');
Route::get('barang-masuk-produksi/detail/{uuid}', [App\Http\Controllers\BarangMasukProduksiController::class, 'show'])->name('barang-masuk-produksi.detail');
Route::get('barang-masuk-produksi/delete/{uuid}', [App\Http\Controllers\BarangMasukProduksiController::class, 'DeleteBarangMasukProduksi'])->name('delete-barang-masuk-produksi');
Route::get('barang-masuk-produksi/detail/delete/{uuid}', [App\Http\Controllers\BarangMasukProduksiController::class, 'DeleteDetailBarangMasukProduksi'])->name('delete-barang-masuk-produksi-detail');
Route::get('barang-masuk-produksi/print/{uuid}', [App\Http\Controllers\BarangMasukProduksiController::class, 'print'])->name('barang-masuk-produksi.print');

//--------------------------------------PINDAH STOK----------------------------------------------------------------------------------------------------------------------------------------------
Route::get('pindah-stok', [PindahController::class, 'index'])->name('pindah.index');
Route::get('form-pindah-stok', [PindahController::class, 'form'])->name('pindah.form');
Route::post('pindah-stok/create', [PindahController::class, 'store'])->name('pindah.store');
Route::get('pindah-stok/print/{id}', [PindahController::class, 'print'])->name('pindah.print');

//--------------------------------------RETUR PENJUALAN----------------------------------------------------------------------------------------------------------------------------------------------
Route::resource('retur-pembelian', App\Http\Controllers\ReturPenjualanController::class);
Route::post('retur-pembelian/create', [App\Http\Controllers\ReturPenjualanController::class, 'store'])->name('retur-pembelian.store');
Route::post('retur-pembelian/store-detail/{uuid}', [App\Http\Controllers\ReturPenjualanController::class, 'storeDetail'])->name('retur-pembelian.store-detail');
Route::get('/retur-pembelian/{uuid}/edit', [App\Http\Controllers\ReturPenjualanController::class, 'edit'])->name('retur-pembelian.edit');
Route::put('/retur-pembelian/{uuid}', [App\Http\Controllers\ReturPenjualanController::class, 'update'])->name('retur-pembelian.update');
Route::put('/retur-pembelian/{uuid}/detail/update', [App\Http\Controllers\ReturPenjualanController::class, 'updateDetail'])->name('retur-pembelian.detail.update');
Route::get('retur-pembelian/detail/{uuid}', [App\Http\Controllers\ReturPenjualanController::class, 'show'])->name('retur-pembelian.detail');
Route::get('retur-pembelian/delete/{uuid}', [App\Http\Controllers\ReturPenjualanController::class, 'destroy'])->name('delete-retur-pembelian');
Route::get('retur-pembelian/detail/delete/{uuid}', [App\Http\Controllers\ReturPenjualanController::class, 'destroyDetail'])->name('delete-retur-pembelian-detail');
Route::get('retur-pembelian/print/{uuid}', [App\Http\Controllers\ReturPenjualanController::class, 'print'])->name('retur-pembelian.print');





//--------------------------------------CETAK LABEL----------------------------------------------------------------------------------------------------------------------------------------------

Route::resource('cetak-label', App\Http\Controllers\CetakLabelController::class)->only(['index', 'store']);
Route::get('/cetak-label-sync', [App\Http\Controllers\CetakLabelController::class, 'sync'])->name('cetak-label.sync');
Route::get('/cetak-label/print', [App\Http\Controllers\CetakLabelController::class, 'print'])->name('cetak-label.print');



//--------------------------------------barang----------------------------------------------------------------------------------------------------------------------------------------------
Route::resource('barang', App\Http\Controllers\NamaBarangController::class);
Route::get('/barang-sync', [App\Http\Controllers\NamaBarangController::class, 'sync'])->name('barang.sync');


//--------------------------------------buku piutang----------------------------------------------------------------------------------------------------------------------------------------------
Route::resource('buku-piutang', App\Http\Controllers\BukuPiutangController::class);
Route::get('/buku-piutang-sync', [App\Http\Controllers\BukuPiutangController::class, 'sync'])->name('buku-piutang.sync');

//--------------------------------------Penjualan Acara----------------------------------------------------------------------------------------------------------------------------------------------
Route::resource('penjualan-acara', App\Http\Controllers\PenjualanAcaraController::class);
Route::post('penjualan-acara/create', [App\Http\Controllers\PenjualanAcaraController::class, 'store'])->name('penjualan-acara.store');
Route::get('penjualan-acara/detail/{uuid}', [App\Http\Controllers\PenjualanAcaraController::class, 'detail'])->name('penjualan-acara.detail');
Route::get('penjualan-acara/delete/{uuid}', [App\Http\Controllers\PenjualanAcaraController::class, 'delete'])->name('delete-penjualan-acara');
Route::delete('/penjualan-acara/detail/delete/{uuid}', [App\Http\Controllers\PenjualanAcaraController::class, 'deleteDetail'])->name('delete-penjualan-acara-detail');
Route::get('penjualan-acara/print/{uuid}', [App\Http\Controllers\PenjualanAcaraController::class, 'print'])->name('penjualan-acara.print');
Route::post('penjualan-acara-detail/{uuid}', [App\Http\Controllers\PenjualanAcaraController::class, 'storeDetail'])->name('penjualan-acara-detail-create');
Route::get('/penjualan-acara/{uuid}/edit', [App\Http\Controllers\PenjualanAcaraController::class, 'edit'])->name('penjualan-acara.edit');
Route::put('/penjualan-acara/{uuid}', [App\Http\Controllers\PenjualanAcaraController::class, 'update'])->name('penjualan-acara.update');
Route::put('/penjualan-acara/{uuid}/detail/update', [App\Http\Controllers\PenjualanAcaraController::class, 'updateDetail'])->name('penjualan-acara-update-detail'); 
Route::get('/penjualan-acara/detail/edit/{id}', [App\Http\Controllers\PenjualanAcaraController::class, 'editDetail'])->name('penjualan-acara-edit-detail-create');
});