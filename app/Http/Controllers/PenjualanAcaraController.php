<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualanAcara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PenjualanAcara;
use App\Models\NamaBarang;
use GuzzleHttp\Client;

class PenjualanAcaraController extends Controller
{
    public function index(){
        $penjualanAcara = PenjualanAcara::orderBy('uuid', 'desc')->get();
        $data = NamaBarang::all();
        return view('penjualan_acara.index', compact('penjualanAcara', 'data'));
    }

    private function generateNota()
    {
        // $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = PenjualanAcara::where('no_nota', 'like', 'PLA' . '%')
                                    ->orderBy('id', 'desc')
                                    ->first();
    
        if ($lastNote) {
            // Ekstrak bagian numerik dari no_nota
            $parts = explode('-', $lastNote->no_nota);
            $numericPart = (int)end($parts);
            $numericPart++; // Increment bagian numerik
        } else {
            $numericPart = 1; // Mulai dari 1 jika tidak ada record sebelumnya
        }
    
        return 'PLA' . '-' . $numericPart;
    }

    public function create()
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
        $data = NamaBarang::all();
        return view('penjualan_acara.create', compact('data', 'db'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_personil' => 'required|string',
            'tanggal' => 'required|date',
            'shift' => 'required|string',
            'total' => 'required|numeric',
            'data' => 'required|array',
        ]);

        $penjualanAcara = PenjualanAcara::create([
            'no_nota' => $this->generateNota(),
            'tanggal' => $request->tanggal,
            'id_user' => Auth::user()->id,
            'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
            'nama_personil' => $request->nama_personil,
            'shift' => $request->shift,
            'total' => $request->total,
    ]);

    // Simpan detail barang
    foreach ($request->data as $item) {
        DetailPenjualanAcara::create([
            'uuid_penjualan_acara' => $penjualanAcara->uuid,
            'nama_barang' => $item['nama_barang'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            'keterangan' => $item['keterangan'],
            'subtotal' => $item['subtotal'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $penjualanAcara->uuid]);
    }

    public function storeDetail(Request $request, $uuid)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'harga' => 'required|numeric',
            'qty' => 'required|integer',
            'keterangan' => 'nullable|string',
            'subtotal' => 'required|numeric',
        ]);

    try {
        $detail = DetailPenjualanAcara::create([
            'uuid_penjualan_acara' => $uuid,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'keterangan' => $request->keterangan,
            'subtotal' => $request->subtotal,
        ]);

        $penjualanAcara = PenjualanAcara::where('uuid', $uuid)->first();
        $penjualanAcara->total += $request->subtotal;
        $penjualanAcara->save();

        return response()->json(['success' => true, 'detail' => $detail, 'total' => $penjualanAcara->total]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }

    }

    public function detail($uuid)
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
        $data = NamaBarang::all();
        $penjualanAcara = PenjualanAcara::where('uuid', $uuid)->first();

        if (!$penjualanAcara) {
            return redirect()->back()->with('error', 'Penjualan Acara tidak ditemukan');
        }

        $details = DetailPenjualanAcara::where('uuid_penjualan_acara', $uuid)->get();

        if ($details->isEmpty()) {
            return redirect()->back()->with('error', 'Detail Penjualan Acara tidak ditemukan');
        }

        return view('penjualan_acara.detail', compact('penjualanAcara', 'details', 'db'));
    }

    public function edit($uuid)
    {
        $penjualanAcara = PenjualanAcara::where('uuid', $uuid)->first();
       
        return response()->json([
            'tanggal' => $penjualanAcara->tanggal,
            'nama_personil' => $penjualanAcara->nama_personil,
            'shift' => $penjualanAcara->shift,
            'total' => $penjualanAcara->total,
        ]);

    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_personil' => 'required|string',
            'shift' => 'required|string',
            'total' => 'required|numeric',
        ]);

        $penjualanAcara = PenjualanAcara::where('uuid', $uuid)->first();

        $penjualanAcara->update([
            'tanggal' => $request->tanggal,
            'nama_personil' => $request->nama_personil,
            'shift' => $request->shift,
            'total' => $request->total,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diubah');
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
        $data = NamaBarang::all();
        $penjualanAcara = PenjualanAcara::where('uuid', $uuid)->first();
        $detail = DetailPenjualanAcara::where('uuid_penjualan_acara', $penjualanAcara->uuid)->get();

        return view('penjualan_acara.edit_detail', compact('penjualanAcara', 'detail', 'data', 'db'));
    }

    public function updateDetail(Request $request, $uuid)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'harga' => 'required|numeric',
            'qty' => 'required|integer',
            'keterangan' => 'nullable|string',
            'subtotal' => 'required|numeric',
        ]);

        $detail = DetailPenjualanAcara::where('uuid_penjualan_acara', $uuid)->firstOrFail();

        $detail->update([
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'keterangan' => $request->keterangan,
            'subtotal' => $request->subtotal,
        ]);

        // Update total in PenjualanAcara
        $penjualanAcara = PenjualanAcara::where('uuid', $detail->uuid_penjualan_acara)->first();
        $penjualanAcara->total = DetailPenjualanAcara::where('uuid_penjualan_acara', $detail->uuid_penjualan_acara)->sum('subtotal');
        $penjualanAcara->save();

        return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil diperbarui',
                'detail' => $detail // Returning the updated detail
            ]);
    }

    public function delete($uuid)
    {
        $penjualanAcara = PenjualanAcara::where('uuid', $uuid)->first();

        if (!$penjualanAcara) {
            return redirect()->back()->with('error', 'Penjualan Acara tidak ditemukan');
        }

        // Delete all details associated with this penjualan acara
        DetailPenjualanAcara::where('uuid_penjualan_acara', $uuid)->delete();

        // Delete the penjualan acara itself
        $penjualanAcara->delete();

        return redirect()->back()->with('success', 'Penjualan Acara berhasil dihapus');
    }

    public function deleteDetail($uuid)
{
    $details = DetailPenjualanAcara::where('uuid', $uuid)->first();

    if (!$details) {
        return redirect()->back()->with('error', 'Detail Penjualan Acara tidak ditemukan');
    }

    $pAcara = PenjualanAcara::where('uuid', $details->uuid_penjualan_acara)->first();
    if ($pAcara) {
        $pAcara->total -= $details->subtotal;
        $pAcara->save();
    }

    $details->delete();

    return response()->json(['message' => 'Detail berhasil dihapus']);
}



    public function print($uuid)
    {
        $penjualanAcara = PenjualanAcara::where('uuid', $uuid)->firstOrFail();
        $details = DetailPenjualanAcara::where('uuid_penjualan_acara', $uuid)->get();

        return view('penjualan_acara.print', compact('penjualanAcara', 'details'));
    }

        
}




