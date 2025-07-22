<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailBarangMasuk;
use App\Models\DetailBarangMasukProduksi;
use Illuminate\Http\Request;
use App\Models\DetailBarangTerjual;
use App\Models\DetailHutangNonProduksi;
use App\Models\DetailNonProduksi;
use App\Models\DetailPembelianCash;
use App\Models\DetailPembelianPerKampus;
use App\Models\DetailPembelianTitipan;
use App\Models\DetailPengajuanPo;
use App\Models\DetailPenjualanAcara;
use App\Models\DetailPenjualanProduksiTitipan;
use App\Models\DetailPenjualanPiutang;
use App\Models\Pelunasan;
use App\Models\PelunasanPembelian;
use App\Models\Setoran;
use App\Models\DetailReturPenjualan;

class ExportDataController extends Controller
{
    public function exportPiutang($startDate, $endDate, $nama_koperasi)
{
    $detailPenjualanPiutang = DetailPenjualanPiutang::with('penjualanPiutang')->whereHas('penjualanPiutang', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailPenjualanPiutang);
}

    public function exportProduksiTitipan($startDate, $endDate, $nama_koperasi)
{
    $detailProduksiTitipan = DetailPenjualanProduksiTitipan::with('titipan')->whereHas('titipan', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailProduksiTitipan);
}

public function exportNonProduksi($startDate, $endDate, $nama_koperasi)
{
    $detailNonProduksi = DetailNonProduksi::with('penjualanNonProduksi')->whereHas('penjualanNonProduksi', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailNonProduksi);
}

public function exportHutangNonProduksi($startDate, $endDate, $nama_koperasi)
{
    $detailHutangNonProduksi = DetailHutangNonProduksi::with('pembelianHutangNonProduksi')->whereHas('pembelianHutangNonProduksi', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailHutangNonProduksi);
}

public function exportCash($startDate, $endDate, $nama_koperasi)
{
    $detailCash = DetailPembelianCash::with('pembelianCash')->whereHas('pembelianCash', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailCash);
}

public function exportPembelianTitipan($startDate, $endDate, $nama_koperasi)
{
    $detailTitipan = DetailPembelianTitipan::with('pembelianTitipan')->whereHas('pembelianTitipan', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailTitipan);
}

public function exportDataTerjual($startDate, $endDate, $nama_koperasi)
{
    $detailTerjual = DetailBarangTerjual::with('barangTerjual')->whereHas('barangTerjual', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailTerjual);
}

public function exportPO($startDate, $endDate, $nama_koperasi)
{
    $detailPO = DetailPengajuanPo::with('pengajuanPO')->whereHas('pengajuanPO', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailPO);
}


public function exportSetoran($startDate, $endDate, $nama_koperasi)
{
    $setoran = Setoran::where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate)->get();

    return response()->json($setoran);
}

public function exportPelunasan($startDate, $endDate, $nama_koperasi)
{
    $pelunasan = Pelunasan::where('nama_koperasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate)->get();

    return response()->json($pelunasan);
}

public function exportPembelianNew($startDate, $endDate)
{
    $detailPembelianNew = DetailPembelianPerKampus::with('pembelianPerKampus')->whereHas('pembelianPerKampus', function ($q) use ($startDate, $endDate) {
        $q->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);})->get();

    return response()->json($detailPembelianNew );
}

public function exportBarangMasuk($startDate, $endDate)
{
    $detailBarangMasuk = DetailBarangMasuk::with('barangMasuk')->whereHas('barangMasuk', function ($q) use ($startDate, $endDate) {
        $q->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);})->get();

    return response()->json($detailBarangMasuk);
}

public function exportBarangMasukProduksi($startDate, $endDate)
{
    $detailBarangMasukProduksi = DetailBarangMasukProduksi::with('barangMasukProduksi')->whereHas('barangMasukProduksi', function ($q) use ($startDate, $endDate) {
        $q->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);})->get();

    return response()->json($detailBarangMasukProduksi);

}

public function exportReturPenjualan($startDate, $endDate, $nama_koperasi)
{
    $detailReturPenjualan = DetailReturPenjualan::with('returPenjualan')->whereHas('returPenjualan', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_kampus', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailReturPenjualan);
}

public function exportPelunasanPembelian($startDate, $endDate)
{
    $pelunasanPembelian = PelunasanPembelian::whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate)->get();

    return response()->json($pelunasanPembelian);
}

public function exportPenjualanAcara($startDate, $endDate)
{
    $detailPenjualanAcara = DetailPenjualanAcara::with('penjualanAcara')->whereHas('penjualanAcara', function ($q) use ($startDate, $endDate) {
        $q->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailPenjualanAcara);
}
}
