<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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

//Penjualan Non Produksi
Route::resource('penjualan-nonproduksi', PenjualanNonProduksiController::class);

//Penjualan Produksi Titipan
Route::resource('penjualan-produksititipan', PenjualanProduksiTitipanController::class);
