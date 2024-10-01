<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailBarangTerjual;
use App\Models\DetailHutangNonProduksi;
use App\Models\DetailNonProduksi;
use App\Models\DetailPembelianCash;
use App\Models\DetailPembelianTitipan;
use App\Models\DetailPengajuanPo;
use App\Models\DetailPenjualanProduksiTitipan;
use App\Models\DetailPenjualanPiutang;
use App\Models\Setoran;

class ExportDataController extends Controller
{
    public function exportPiutang($startDate, $endDate, $nama_koperasi)
{
    $detailPenjualanPiutang = DetailPenjualanPiutang::with('penjualanPiutang')->whereHas('penjualanPiutang', function ($q) use ($nama_koperasi, $startDate, $endDate) {
        $q->where('nama_operasi', $nama_koperasi)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailPenjualanPiutang);
}

    public function exportProduksiTitipan($startDate, $endDate, $id)
{
    $detailProduksiTitipan = DetailPenjualanProduksiTitipan::with('titipan')->whereHas('titipan', function ($q) use ($id, $startDate, $endDate) {
        $q->where('id_user', $id)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailProduksiTitipan);
}

public function exportNonProduksi($startDate, $endDate, $id)
{
    $detailNonProduksi = DetailNonProduksi::with('penjualanNonProduksi')->whereHas('penjualanNonProduksi', function ($q) use ($id, $startDate, $endDate) {
        $q->where('id_user', $id)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailNonProduksi);
}

public function exportHutangNonProduksi($startDate, $endDate, $id)
{
    $detailHutangNonProduksi = DetailHutangNonProduksi::with('pembelianHutangNonProduksi')->whereHas('pembelianHutangNonProduksi', function ($q) use ($id, $startDate, $endDate) {
        $q->where('id_user', $id)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailHutangNonProduksi);
}

public function exportCash($startDate, $endDate, $id)
{
    $detailCash = DetailPembelianCash::with('pembelianCash')->whereHas('pembelianCash', function ($q) use ($id, $startDate, $endDate) {
        $q->where('id_user', $id)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailCash);
}

public function exportPembelianTitipan($startDate, $endDate, $id)
{
    $detailTitipan = DetailPembelianTitipan::with('pembelianTitipan')->whereHas('pembelianTitipan', function ($q) use ($id, $startDate, $endDate) {
        $q->where('id_user', $id)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailTitipan);
}

public function exportDataTerjual($startDate, $endDate, $id)
{
    $detailTerjual = DetailBarangTerjual::with('barangTerjual')->whereHas('barangTerjual', function ($q) use ($id, $startDate, $endDate) {
        $q->where('id_user', $id)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailTerjual);
}

public function exportPO($startDate, $endDate, $id)
{
    $detailPO = DetailPengajuanPo::with('pengajuanPO')->whereHas('pengajuanPO', function ($q) use ($id, $startDate, $endDate) {
        $q->where('id_user', $id)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate);
    })->get();

    return response()->json($detailPO);
}


public function exportSetoran($startDate, $endDate, $id)
{
    $setoran = Setoran::where('id_user', $id)
          ->whereDate('created_at', '>=', $startDate)
          ->whereDate('created_at', '<=', $endDate)->get();

    return response()->json($setoran);
}


}
