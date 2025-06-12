<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\PembelianPerKampus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\DetailBarangMasuk;
use App\Models\DetailPembelianPerKampus;
use Illuminate\Support\Str;
use App\Models\NamaBarang;
use GuzzleHttp\Client;

class BarangMasukController extends Controller
{
    public function index(){
        
        if (Auth::user()->role == 'admin') {
            $barang_masuk = BarangMasuk::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $barang_masuk = BarangMasuk::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $barang_masuk = BarangMasuk::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $barang_masuk = BarangMasuk::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $barang_masuk = BarangMasuk::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }
        $data = NamaBarang::all();
        return view('barang_masuk.index', compact('barang_masuk', 'data'))->with('i', (request()->input('page', 1) - 1) * 10);

    }

    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = BarangMasuk::where('nota', 'like', 'BMK' . $inisial . '%')
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
    
        return 'BMK' . $inisial . '-' . $numericPart;
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
        return view('barang_masuk.create', compact('detailPembelianPerKampus', 'data', 'db'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_personil' => 'required|string',
            'data' => 'required|array',
        ]);

        $barangMasuk = BarangMasuk::create([
            'id_user' => Auth::user()->id,
            'tanggal' => $request->tanggal,
            'nota' => $this->generateNota(),
            'nama_personil' => $request->nama_personil,
            'masuk_ke' => 'KAMPUS ' . Auth::user()->role,
        ]);

        foreach($request->data as $item){
            DetailBarangMasuk::create([
                'uuid_barangmasuk' => $barangMasuk->uuid,
                'nama_barang' => $item['nama_barang'],
                'qty' => $item['qty'],
                'satuan' => $item['satuan'],
            ]);
        }

        return response()->json(['success' => true, 'uuid' => $barangMasuk->uuid]);
    }

    public function storeDetail(Request $request, $uuid)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'qty' => 'required|numeric',
            'satuan' => 'required|string',
        ]);

        try{
            $detail = DetailBarangMasuk::create([
                'uuid_barangmasuk' => $uuid,
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
        $barangMasuk = BarangMasuk::where('uuid', $uuid)->firstOrFail();
        $detailBarangMasuk = DetailBarangMasuk::where('uuid_barangmasuk', $uuid)->get();

        return view('barang_masuk.detail', compact('barangMasuk', 'detailBarangMasuk', 'data', 'detailPembelianPerKampus', 'db'));
    }

    public function update( Request $request, $uuid)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_personil' => 'required|string|max:255',
        ]);

        $barangMasuk = BarangMasuk::where('uuid', $uuid)->firstOrFail();
        $barangMasuk->update($request->all());

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk updated successfully.');
    }

    public function updateDetail(Request $request, $uuid)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'qty' => 'required|numeric',
            'satuan' => 'required|string',
        ]);

        $detailBarangMasuk = DetailBarangMasuk::where('uuid', $uuid)->firstOrFail();
        $detailBarangMasuk->update($request->all());

        return redirect()->back()->with('success', 'Detail barang masuk updated successfully.');
    }

    public function DeleteBarangMasuk($uuid)
    {
        $barangMasuk = BarangMasuk::where('uuid', $uuid)->firstOrFail();
        $barangMasuk->delete();
        if ($barangMasuk) {
        // Hapus detail terlebih dahulu
        $barangMasuk->detailBarangMasuk()->delete();

        // Baru hapus pembelian induk
        $barangMasuk->delete();

        return redirect()->route('barang-masuk.index')->with('success', 'Data Barang Masuk berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Barang Masuk tidak ditemukan');
        }
    }

    public function DeleteDetailBarangMasuk($uuid)
    {
        $detailBarangMasuk = DetailBarangMasuk::where('uuid', $uuid)->firstOrFail();
        $detailBarangMasuk->delete();

        return redirect()->back()->with('success', 'Detail barang masuk deleted successfully.');
    }
    


}
