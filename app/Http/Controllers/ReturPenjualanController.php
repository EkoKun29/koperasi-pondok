<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\BarangMasukProduksi;
use App\Models\ReturPenjualan;
use App\Models\NamaBarang;
use App\Models\PenjualanPiutang;
use App\Models\PenjualanProduksiTitipan;
use App\Models\PenjualanNonProduksi;
use App\Models\BarangTerjual;
use App\Models\DetailReturPenjualan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;

use Illuminate\Http\Request;

class ReturPenjualanController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $retur = ReturPenjualan::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $retur = ReturPenjualan::whereIn('id_user', $usersWithRole1)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $retur = ReturPenjualan::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $retur = ReturPenjualan::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $retur = ReturPenjualan::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }

        $data=NamaBarang::all();
        
        $barangMasuk = BarangMasuk::all();
        $barangMasukProduksi = BarangMasukProduksi::all();

        // saya ingin mengambail data no_nota dari penjualanPiutang, penjualanProduksi, penjualanNonProduksi, baranTerjual
        $dataNoNota = $barangMasuk->pluck('nota')
            ->merge($barangMasukProduksi->pluck('nota'))
            ->unique();

        return view('retur_penjualan.index',compact('retur', 'data', 'dataNoNota'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = ReturPenjualan::where('nota_retur', 'like', 'RP' . $inisial . '%')
                                    ->orderBy('id', 'desc')
                                    ->first();
    
        if ($lastNote) {
            // Ekstrak bagian numerik dari no_nota
            $parts = explode('-', $lastNote->nota_retur);
            $numericPart = (int)end($parts);
            $numericPart++; // Increment bagian numerik
        } else {
            $numericPart = 1; // Mulai dari 1 jika tidak ada record sebelumnya
        }

        return 'RP' . $inisial . '-' . $numericPart;
    }

    public function create(){

        try {
            $client = new Client();
            $user = Auth::user()->role;
            $urlDB= "https://script.google.com/macros/s/AKfycbxkPyYzkbcPMICgq1NDGmOQGGILgIDI-iWNxofklBA1jS14eM8HGOEOmRWH7KuNm1um/exec";

            $responseDB = $client->request('GET', $urlDB, [
                'verify'  => false,
            ]);

            $dataDB = json_decode($responseDB->getBody());

            $db = collect($dataDB); // Change to collection

        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Gagal Mengambil Data Supplier!.');
        }
        $data = NamaBarang::all();
        
        $barangMasuk = BarangMasuk::all();
        $barangMasukProduksi = BarangMasukProduksi::all();
        $dataNoNota = $barangMasuk->pluck('nota')
            ->merge($barangMasukProduksi->pluck('nota'))
            ->unique();
        return view('retur_penjualan.create', compact('data', 'dataNoNota', 'db'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required',
            'nota_barang_masuk' => 'required',
            'tgl_barang_masuk' => 'required',
            'nama_personil' => 'required',
            'nama_supplier' => 'required',
            'data' => 'required|array',
        ]);

        $retur = ReturPenjualan::create([
            'id_user' => Auth::user()->id,
            'nota_retur' => $this->generateNota(),
            'tanggal' => $request->tanggal,
            'nota_barang_masuk' => $request->nota_barang_masuk,
            'tgl_barang_masuk' => $request->tgl_barang_masuk,
            'nama_personil' => $request->nama_personil,
            'nama_kampus' => 'KAMPUS ' . Auth::user()->role,
            'nama_supplier' => $request->nama_supplier,
        ]);
        
        foreach ($request->data as $item) {
            DetailReturPenjualan::create([
                'uuid_retur_penjualan' => $retur->uuid,
                'nama_barang' => $item['nama_barang'],
                'qty' => $item['qty'],
                'satuan' => $item['satuan'],
            ]);
        }

        return response()->json(['success' => true, 'uuid' => $retur->uuid]);

    }

    public function storeDetail(Request $request, $uuid)
    {
        $request->validate([
            'nama_barang' => 'required',
            'qty' => 'required',
            'satuan' => 'required',
        ]);

    try{
            $detail = DetailReturPenjualan::create([
            'uuid_retur_penjualan' => $uuid,
            'nama_barang' => $request->nama_barang,
            'qty' => $request->qty,
            'satuan' => $request->satuan,
        ]);

        // $retur = ReturPenjualan::where('uuid', $uuid)->first();
        // $retur->total += $request->subtotal;
        // $retur->save();

        return response()->json(['success' => true, 'detail' => $detail]);
    }
    catch(\Exception $e){
        return response()->json(['success' => false, 'message' => 'Gagal menyimpan detail retur: ' . $e->getMessage()], 500);
        }
        return response()->json(['success' => true]);
    }

    public function edit($uuid)
    {
        $retur = ReturPenjualan::where('uuid', $uuid)->first();
        if (!$retur) {
            return redirect()->route('retur_penjualan.index')->with('error', 'Retur Penjualan tidak ditemukan.');
        }

        $data = NamaBarang::all();
        
        $barangMasuk = BarangMasuk::all();
        $barangMasukProduksi = BarangMasukProduksi::all();
        $dataNoNota = $barangMasuk->pluck('nota')
            ->merge($barangMasukProduksi->pluck('nota'))
            ->unique();

        return response()->json([
        'tanggal' => $retur->tanggal,
        'nota_barang_masuk' => $retur->nota_barang_masuk,
        'tgl_barang_masuk' => $retur->tgl_barang_masuk,
        'nama_supplier' => $retur->nama_supplier,
        'nama_personil' => $retur->nama_personil,
    ]);
    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'tanggal' => 'required',
            'nota_barang_masuk' => 'required',
            'tgl_barang_masuk' => 'required',
            'nama_personil' => 'required',
            'nama_supplier' => 'required',
        ]);

        $retur = ReturPenjualan::where('uuid', $uuid)->first();
        if (!$retur) {
            return redirect()->route('retur_penjualan.index')->with('error', 'Retur Penjualan tidak ditemukan.');
        }

        $retur->update([
            'tanggal' => $request->tanggal,
            'nota_barang_masuk' => $request->nota_barang_masuk,
            'tgl_barang_masuk' => $request->tgl_barang_masuk,
            'nama_personil' => $request->nama_personil,
            'nama_supplier' => $request->nama_supplier,
        ]);

        return redirect()->route('retur-penjualan.index')->with('success', 'Retur Penjualan berhasil diperbarui.');

    }   

    public function show($uuid)
    {

        try {
            $client = new Client();
            $user = Auth::user()->role;
            $urlDB= "https://script.google.com/macros/s/AKfycbxkPyYzkbcPMICgq1NDGmOQGGILgIDI-iWNxofklBA1jS14eM8HGOEOmRWH7KuNm1um/exec";

            $responseDB = $client->request('GET', $urlDB, [
                'verify'  => false,
            ]);

            $dataDB = json_decode($responseDB->getBody());

            $db = collect($dataDB); // Change to collection

        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Gagal Mengambil Data Supplier!.');
        }
        $retur = ReturPenjualan::where('uuid', $uuid)->first();
        if (!$retur) {
            return redirect()->route('retur_penjualan.index')->with('error', 'Retur Penjualan tidak ditemukan.');
        }

        $detailRetur = DetailReturPenjualan::where('uuid_retur_penjualan', $uuid)->get();
        $data = NamaBarang::all();
        return view('retur_penjualan.detail', compact('retur', 'detailRetur', 'data', 'db'));
    }

    public function editDetail($uuid)
    {
        try {
            $client = new Client();
            $user = Auth::user()->role;
            $urlDB= "https://script.google.com/macros/s/AKfycbxkPyYzkbcPMICgq1NDGmOQGGILgIDI-iWNxofklBA1jS14eM8HGOEOmRWH7KuNm1um/exec";

            $responseDB = $client->request('GET', $urlDB, [
                'verify'  => false,
            ]);

            $dataDB = json_decode($responseDB->getBody());

            $db = collect($dataDB); // Change to collection

        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Gagal Mengambil Data Supplier!.');
        }
        $retur = ReturPenjualan::where('uuid', $uuid)->first();
        $detail = DetailReturPenjualan::where('uuid_retur_penjualan', $retur->uuid)->get();

        $data = NamaBarang::all();

        return view('retur_penjualan.edit_detail', compact('retur', 'detail', 'data', 'db'));
    }

    public function updateDetail(Request $request, $uuid)
    {
        $request->validate([
            'id' => 'required|exists:detail_retur_penjualans,id',
            'nama_barang' => 'required',
            'qty' => 'required',
            'satuan' => 'required',
        ]);

        
            $retur = ReturPenjualan::where('uuid', $uuid)->firstOrFail();

            $detail = DetailReturPenjualan::findOrFail($request->id);
            $oldSubtotal = $detail->subtotal;

            $detail->update([
                'nama_barang' => $request->nama_barang,
                'qty' => $request->qty,
                'satuan' => $request->satuan,
            ]);

            // if ($retur) {
            //     $newSubtotal = $request->harga * $request->qty;
            //     $retur->total += ($newSubtotal - $oldSubtotal);
            //     $retur->save();
            // } 

        return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil diperbarui',
                'detail' => $detail // Returning the updated detail
            ]);
    }

    public function destroy($uuid)
    {
        $retur = ReturPenjualan::where('uuid', $uuid)->first();
        if ($retur) {
            $retur->detailReturPenjualans()->delete();
            $retur->delete();
            return redirect()->route('retur-penjualan.index')->with('success', 'Retur Penjualan berhasil dihapus.');
        }else{
            return redirect()->back()->with('error', 'Pembelian tidak ditemukan');
        }

        
    }


    public function destroyDetail($uuid)
    {
        $detail = DetailReturPenjualan::with('returPenjualan')->where('uuid', $uuid)->first();
        if (!$detail) {
            return redirect()->back()->with('error', 'Detail retur penjualan tidak ditemukan.');
        }

        $jumlahDetail = DetailReturPenjualan::where('uuid_retur_penjualan', $detail->uuid_retur_penjualan)->count();

        if ($jumlahDetail == 1) {
        $detail->delete();
        $detail->returPenjualan?->delete();
        return redirect()->route('retur-penjualan.index')->with('success', 'Data retur penjualan & detail terakhir berhasil dihapus');
        }
        
        // if ($detail->returPenjualan) {
        //     $detail->returPenjualan->total -= $detail->subtotal;
        //     $detail->returPenjualan->save();
        // }

        // Hapus detail retur penjualan
        $detail->delete();

        return redirect()->back()->with('success', 'Detail retur penjualan berhasil dihapus.');
    }

    public function print($uuid)
    {
        $retur = ReturPenjualan::where('uuid', $uuid)->firstOrFail();
        $detailRetur = DetailReturPenjualan::where('uuid_retur_penjualan', $retur->uuid)->get();

        return view('retur_penjualan.print', compact('retur', 'detailRetur'));
    }

}
