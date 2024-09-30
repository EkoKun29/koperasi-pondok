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
use App\Models\PengajuanPo;

class ExportDataController extends Controller
{
    public function exportPiutang($startDate, $endDate, $id){
        $detailPenjualanPiutang = DetailPenjualanPiutang::where('PengajuanPo')->whereHas('PengajuanPo', function ($q) use ($id, $startDate, $endDate) {
            $q->where('id_user', $id)->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);
        })->get();

            return response()->json($detailPenjualanPiutang);
    }
}
