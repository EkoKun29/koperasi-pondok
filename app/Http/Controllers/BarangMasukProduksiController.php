<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasukProduksi;
use App\Models\DetailBarangMasukProduksi;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DetailPembelianPerKampus;
use App\Models\NamaBarang;
use GuzzleHttp\Client;



class BarangMasukProduksiController extends Controller
{
    public function index(){
        
        if (Auth::user()->role == 'admin') {
            $barang_masuk_produksi = BarangMasukProduksi::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $barang_masuk_produksi = BarangMasukProduksi::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $barang_masuk_produksi = BarangMasukProduksi::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $barang_masuk_produksi = BarangMasukProduksi::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $barang_masuk_produksi = BarangMasukProduksi::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }
        $data = NamaBarang::all();
        return view('barang_masuk_produksi.index', compact('barang_masuk_produksi', 'data'))->with('i', (request()->input('page', 1) - 1) * 10);

    }

    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = BarangMasukProduksi::where('nota', 'like', 'BMPK' . $inisial . '%')
                                    ->orderBy('id', 'desc')
                                    ->first();
    
        if ($lastNote) {
            // Ekstrak bagian numerik dari no_nota
            $parts = explode('-', $lastNote->nota);
            $numericPart = (int)end($parts);
            $numericPart++; // Increment bagian numerik
        } else {
            $numericPart = 1; // Mulai dari 1 jika tidak ada record sebelumnya
        }
    
        return 'BMPK' . $inisial . '-' . $numericPart;
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
        $detailPembelianPerKampus = DetailPembelianPerKampus::all();
        $data = NamaBarang::all();
        return view('barang_masuk_produksi.create', compact('detailPembelianPerKampus', 'data', 'db'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_personil' => 'required|string',
            'data' => 'required|array',
        ]);

        $barangMasukProduksi = BarangMasukProduksi::create([
            'id_user' => Auth::user()->id,
            'tanggal' => $request->tanggal,
            'nota' => $this->generateNota(),
            'nama_personil' => $request->nama_personil,
            'masuk_ke' => 'KAMPUS ' . Auth::user()->role,
            'keterangan' => "PRODUKSI",
        ]);

        foreach($request->data as $item){
            DetailBarangMasukProduksi::create([
                'uuid_masukproduksi' => $barangMasukProduksi->uuid,
                'nama_barang' => $item['nama_barang'],
                'qty' => $item['qty'],
                'satuan' => $item['satuan'],
            ]);
        }

        return response()->json(['success' => true, 'uuid' => $barangMasukProduksi->uuid]);
    }

    public function storeDetail(Request $request, $uuid)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'qty' => 'required|numeric',
            'satuan' => 'required|string',
        ]);

        try{
            $detail = DetailBarangMasukProduksi::create([
                'uuid_masukproduksi' => $uuid,
                'nama_barang' => $request->nama_barang,
                'qty' => $request->qty,
                'satuan' => $request->satuan,
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to create detail barang masuk.']);
        }

        return response()->json(['success' => true, 'detail' => $detail]);
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
        $detailPembelianPerKampus = DetailPembelianPerKampus::all();
        $data = NamaBarang::all();
        $barangMasukProduksi = BarangMasukProduksi::where('uuid', $uuid)->firstOrFail();
        $detailBarangMasukProduksi = DetailBarangMasukProduksi::where('uuid_masukproduksi', $uuid)->get();

        return view('barang_masuk_produksi.detail', compact('barangMasukProduksi', 'detailBarangMasukProduksi', 'data', 'detailPembelianPerKampus', 'db'));
    }

    public function edit($uuid)
{
    $barangMasukProduksi = BarangMasukProduksi::where('uuid', $uuid)->firstOrFail();

    return response()->json([
        'nota' => $barangMasukProduksi->nota,
        'tanggal' => $barangMasukProduksi->created_at->format('Y-m-d'),
        'nama_personil' => $barangMasukProduksi->nama_personil,
        'masuk_ke' => $barangMasukProduksi->masuk_ke,
    ]);
}
    public function update( Request $request, $uuid)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_personil' => 'required|string|max:255',
        ]);

        $barangMasukProduksi = BarangMasukProduksi::where('uuid', $uuid)->firstOrFail();
        $barangMasukProduksi->update($request->all());

        return redirect()->route('barang-masuk-produksi.index')->with('success', 'Barang masuk updated successfully.');
    }

    

    public function updateDetail(Request $request, $uuid)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'qty' => 'required|numeric',
            'satuan' => 'required|string|max:50',
        ]);

        $detailBarangMasukProduksi = DetailBarangMasukProduksi::where('uuid', $uuid)->firstOrFail();
        $detailBarangMasukProduksi->update($request->all());

        return redirect()->back()->with('success', 'Detail barang masuk updated successfully.');
    }

    public function DeleteBarangMasukProduksi($uuid)
    {
        $barangMasukProduksi = BarangMasukProduksi::where('uuid', $uuid)->firstOrFail();
        $barangMasukProduksi->delete();
        if ($barangMasukProduksi) {
        // Hapus detail terlebih dahulu
        $barangMasukProduksi->detailBarangMasukProduksi()->delete();

        // Baru hapus pembelian induk
        $barangMasukProduksi->delete();

        return redirect()->route('barang-masuk-produksi.index')->with('success', 'Data Barang Masuk berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Barang Masuk tidak ditemukan');
        }
    }

    public function DeleteDetailBarangMasukProduksi($uuid)
    {
        $detailBarangMasukProduksi = DetailBarangMasukProduksi::where('uuid', $uuid)->firstOrFail();
        $detailBarangMasukProduksi->delete();

        return redirect()->back()->with('success', 'Detail barang masuk deleted successfully.');
    }

    public function print ($uuid)
    {
        $barangMasukProduksi = BarangMasukProduksi::where('uuid', $uuid)->first();
        if (!$barangMasukProduksi) {
            return redirect()->back()->with('error', 'Barang Masuk Produksi tidak ditemukan');
        }
        $detail = DetailBarangMasukProduksi::where('uuid_masukproduksi', $uuid)->get();
        return view('barang_masuk_produksi.print', compact('barangMasukProduksi', 'detail'));
    }
    
}
