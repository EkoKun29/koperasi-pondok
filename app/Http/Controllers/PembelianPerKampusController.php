<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembelianPerKampus;
use App\Models\DetailPembelianPerKampus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use GuzzleHttp\Client;
use Carbon\Carbon;

class PembelianPerKampusController extends Controller
{
    public function index(){
        
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

        $pembelian = PembelianPerKampus::orderBy('uuid', 'desc')->get();
        return view('pembelian_new.index', compact('pembelian', 'db'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    private function generateNota()
{
    // Temukan nota terbaru dengan prefix PPK, urutkan berdasarkan id secara menurun
    $lastNote = PembelianPerKampus::where('nota', 'like', 'PPK-%')
                                  ->orderBy('id', 'desc')->first();

    if ($lastNote) {
        // Ambil angka setelah tanda strip
        $parts = explode('-', $lastNote->nota); // HARUS nota, bukan no_nota
        $numericPart = (int)end($parts);
        $numericPart++;
    } else {
        $numericPart = 1;
    }

    return 'PPK-' . $numericPart;
}


    public function create()
    {

        // HIT API KONSUMEN DAN YANG BAWA

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
        // $data = NamaBarang::all();
        return view('pembelian_new.create', compact('db'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'pindah_barang' => 'required',
            'total' => 'required|numeric',
            'data' => 'required|array',
        ]);
        $pembelianNew = PembelianPerKampus::create([
            'id_user' => Auth::user()->id,
            'tanggal' => Carbon::now(),
            'nota' => $this->generateNota(),
            'nama_supplier' => $request->nama_supplier,
            'pindah_barang' => $request->pindah_barang,
            'total' => $request->total,
        ]);

        foreach ($request->data as $item) {
            DetailPembelianPerKampus::create([
                'uuid_pembelian' => $pembelianNew->uuid,
                'nama_barang' => $item['nama_barang'],
                'harga' => $item['harga'],
                'qty' => $item['qty'],
                'satuan' => $item['satuan'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // Kembalikan UUID untuk redirect
        return response()->json(['success' => true, 'uuid' => $pembelianNew->uuid]);
    }

    public function storeDetail (Request $request, $uuid)
    {
        // Log untuk melihat data yang masuk
        Log::info('Request Data:', $request->all());

        $request->validate([
            'barang' => 'required|string',
            'harga' => 'required|numeric',
            'qty' => 'required|numeric',
            'satuan' => 'required|string',
            'subtotal' => 'required|numeric',
        ]);

        try {
            // Buat detail baru
            $detail = DetailPembelianPerKampus::create([
                'uuid_pembelian' => $uuid,
                'nama_barang' => $request->barang,
                'harga' => $request->harga,
                'qty' => $request->qty,
                'satuan' => $request->satuan,
                'subtotal' => $request->subtotal,
            ]);

            // Update total di tabel pembelian
            $pembelian = PembelianPerKampus::where('uuid', $uuid)->first();
            $pembelian->total += $request->subtotal;
            $pembelian->save();

            return response()->json(['success' => true, 'detail' => $detail, 'total'=>$pembelian->total]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan detail pembelian.']);
        }
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
        $pembelian = PembelianPerKampus::where('uuid', $uuid)->first();
        $detail = DetailPembelianPerKampus::where('uuid_pembelian', $uuid)->get();
        if (!$pembelian) {
            return redirect()->back()->with('error', 'Pembelian tidak ditemukan');
        }
        return view('pembelian_new.detail', compact('pembelian', 'detail', 'db'));
        
    }
   
    public function update(Request $request, $uuid)
    {

        $request->validate([
            'tanggal' => 'required',
            'nama_supplier' => 'required',
            'pindah_barang' => 'required',
        ]);

        $pembelian = PembelianPerKampus::where('uuid', $uuid)->first();
        if (!$pembelian) {
            return redirect()->back()->with('error', 'Pembelian tidak ditemukan');
        }

        $pembelian->update([
            'tanggal' => $request->tanggal,
            'nama_supplier' => $request->nama_supplier,
            'pindah_barang' => $request->pindah_barang,
            
        ]);

        return redirect()->route('pembelian-new.index')->with('success', 'Data Pembelian berhasil diubah');
    }

    public function showDetail($uuid)
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

        $detail = DetailPembelianPerKampus::where('uuid', $uuid)->first();
        if (!$detail) {
            return redirect()->back()->with('error', 'Detail pembelian tidak ditemukan');
        }
        return view('pembelian_new.edit_detail', compact('detail', 'db'));
    }

    public function updateDetail(Request $request, $uuid)
{
    $request->validate([
        'nama_barang' => 'required|string',
        'harga' => 'required|numeric',
        'qty' => 'required|numeric',
        'satuan' => 'required|string',
    ]);

    $detail = DetailPembelianPerKampus::where('uuid', $uuid)->first();
    if (!$detail) {
        return redirect()->back()->with('error', 'Detail pembelian tidak ditemukan');
    }

    // Simpan subtotal lama sebelum di-update
    $oldSubtotal = $detail->subtotal;

    // Update detail
    $detail->update([
        'nama_barang' => $request->nama_barang, // ini juga perlu dicek, sebelumnya pakai $request->nama_barang
        'harga' => $request->harga,
        'qty' => $request->qty,
        'satuan' => $request->satuan,
        'subtotal' => $request->harga * $request->qty,
    ]);

    // Update total pembelian
    $pembelian = PembelianPerKampus::where('uuid', $detail->uuid_pembelian)->first();
    if ($pembelian) {
        $newSubtotal = $request->harga * $request->qty;
        $pembelian->total += ($newSubtotal - $oldSubtotal);
        $pembelian->save();
    }

    return redirect()->back()->with('success', 'Detail berhasil diubah');
}


    public function destroy($uuid)
    {
        $pembelian = PembelianPerKampus::where('uuid', $uuid)->first();
        if ($pembelian) {
            $pembelian->delete();
            return redirect()->route('pembelian-new.index')->with('success', 'Data Pembelian berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Pembelian tidak ditemukan');
        }
    }

    public function deleteDetail($uuid) 
{
    $detail = DetailPembelianPerKampus::with('pembelianPerKampus') // eager load relasi
                ->where('uuid', $uuid)
                ->first();

    if (!$detail) {
        return redirect()->back()->with('error', 'Detail pembelian tidak ditemukan');
    }

    // Hitung semua detail terkait pembelian ini
    $jumlahDetail = DetailPembelianPerKampus::where('uuid_pembelian', $detail->uuid_pembelian)->count();

    if ($jumlahDetail == 1) {
        $detail->delete();
        $detail->pembelianPerKampus?->delete();
        return redirect()->route('pembelian-new.index')->with('success', 'Data pembelian & detail terakhir berhasil dihapus');
    }

    if ($detail->pembelianPerKampus) {
        $detail->pembelianPerKampus->total -= $detail->subtotal;
        $detail->pembelianPerKampus->save();
    }

    $detail->delete();

    return redirect()->back()->with('success', 'Detail berhasil dihapus');
}




}
