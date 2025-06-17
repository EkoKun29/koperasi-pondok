<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PindahStok;
use App\Models\DetailPindahStok;
use App\Models\NamaBarang;
use App\Models\Lokasi;


class PindahController extends Controller
{
    public function index(){
    	$pindah = PindahStok::get();
    	return view('pindah-stok.index', compact('pindah'));
    }

    public function form(){
    	$data = NamaBarang::all();
    	$lokasi = Lokasi::all();
    	return view('pindah-stok.form', compact('data', 'lokasi'));
    }

    public function store(Request $request){
    	// Validasi input
	    $request->validate([
	        'nama_pengaju' => 'required|string',
	        'tanggal' => 'required|string',
	        'dari' => 'required|string',
	        'ke' => 'required|string',
	        
	        'data' => 'required|array',
	    ]);

	    $surat_terakhir = PindahStok::orderBy('created_at', 'DESC')->first();
        if (!$surat_terakhir) {
            $st = 'PS'. '-' . '1';
        } else {
            $explode = explode('-', $surat_terakhir->nomor_surat);
            // dd($explode);
            $st = $explode[0] . '-' . $explode[1] + 1;
        }

	    // Buat Pengajuan Piutang baru
	    $PindahStok = PindahStok::create([
	        'nomor_surat' => $st,
	        'tanggal' => $request->tanggal,
	        'yang_memindah' => $request->nama_pengaju,
	        'dari' => $request->dari,
	        'ke' => $request->ke,
	    ]);

	    // Simpan detail barang
	    foreach ($request->data as $item) {
	        DetailPindahStok::create([
	            'id_pindah_stok' => $PindahStok->id,
	            'produk' => $item['nama_barang'],
	            'qty' => $item['qty'],
	            'keterangan_produksi' => $item['keterangan'],
	        ]);
	    }

    	return response()->json(['success' => true, 'id' => $PindahStok->id]);

    }

    public function print($id){
    	$pindah = PindahStok::find($id);
        $detail = DetailPindahStok::where('id_pindah_stok', $pindah->id)->get();
        return view('pindah-stok.print', compact('pindah', 'detail'));
    }
}
