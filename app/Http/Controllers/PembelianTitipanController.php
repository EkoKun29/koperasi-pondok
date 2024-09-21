<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelianTitipan;
use App\Models\User;
use App\Models\NamaBarang;
use Illuminate\Http\Request;
use App\Models\PembelianTitipan;
use Illuminate\Support\Facades\Auth;

class PembelianTitipanController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $titipan = PembelianTitipan::orderBy('uuid', 'desc')->paginate(10);
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $titipan = PembelianTitipan::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->paginate(10);

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $titipan = PembelianTitipan::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $titipan = PembelianTitipan::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $titipan = PembelianTitipan::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->paginate(10);

        }else{
            abort(403, 'Unauthorized action.');
        }
        return view('pembelian.titipan.index',compact('titipan'))->with('i', (request()->input('page', 1) - 1) * 10);
    }


    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = PembelianTitipan::where('no_nota', 'like', 'PMTK' . $inisial . '%')
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
    
        return 'PMTK' . $inisial . '-' . $numericPart;
    }
    

    public function create()
    {
        $data = NamaBarang::all();
        return view('pembelian.titipan.create', compact('data'));
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_personil' => 'required|string',
        'nama_penitip' => 'required|string',
        'total' => 'required|numeric',
        'data' => 'required|array',
    ]);

    // Buat Penjualan Piutang baru
    $pembelianTitipan = PembelianTitipan::create([
        'no_nota' => $this->generateNota(),
        'id_user' => Auth::user()->id,
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'nama_personil' => $request->nama_personil,
        'nama_penitip' => $request->nama_penitip,
        'tanggal' => $request->tanggal,
        'total' => $request->total,
    ]);

    // Simpan detail barang
    foreach ($request->data as $item) {
        DetailPembelianTitipan::create([
            'uuid_pembeliantitipan' => $pembelianTitipan->uuid,
            'nama_barang' => $item['nama_barang'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            'sisa_siang' => $item['sisa_siang'] ?? 0,
            'sisa_sore' => $item['sisa_sore'] ?? 0,
            'sisa_malam' => $item['sisa_malam'] ?? 0,
            'subtotal' => $item['subtotal'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $pembelianTitipan->uuid]);
}


    
    public function show($uuid)
    {
        $data = NamaBarang::all();
        $titipan = PembelianTitipan::where('uuid', $uuid)->first();
        if (!$titipan) {
            return redirect()->back()->with('error', 'Pembelian Titipan tidak ditemukan');
        }
        $detail = DetailPembelianTitipan::where('uuid_pembeliantitipan', $titipan->uuid)->get();

        if ($detail->isEmpty()) {
            dd('Detail Pembelian Titipan tidak ditemukan');
        }

        return view('pembelian.titipan.detail', compact('titipan', 'detail', 'data'));
    }

    public function edit(PembelianTitipan $pembelianTitipan)
    {
        $detail = DetailPembelianTitipan::where('uuid_pembeliantitipan', $pembelianTitipan->uuid)->get();
        return view('pembelian.titipan.edit', compact('pembelianTitipan', 'detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PembelianTitipan $pembelianTitipan)
    {
        $detailPembelianTitipan = DetailPembelianTitipan::where('uuid_pembeliantitipan', $pembelianTitipan->uuid)->get();
        foreach($detailPembelianTitipan as $dpp){
            $dpp->nama_barang = $request->barang;
            $dpp->qty = $request->qty;
            $dpp->harga = $request->harga;
            $dpp->sisa_siang = $request->sisa_siang;
            $dpp->sisa_sore = $request->sisa_sore;
            $dpp->sisa_malam = $request->sisa_malam;
            $dpp->subtotal = $request->qty * $request->harga;
            $dpp->save();
        }

        //update total penjualan piutang
        $detailPembelianTitipan = DetailPembelianTitipan::where('uuid_pembeliantitipan',$pembelianTitipan->uuid)->get();
        $pembelianTitipan->total = $detailPembelianTitipan->sum('subtotal');
        $pembelianTitipan->save();

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function DeletePembelian($uuid)
    {
        $titipan = PembelianTitipan::where('uuid', $uuid)->first();
        if ($titipan) {
            $titipan->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function DeleteDetailPembelian($id)
    {
        $detail = DetailPembelianTitipan::where('id', $id)->first();

        // Mengakses data induk berdasarkan ID detail
        $pembelianTitipan = $detail->pembelianTitipan;

        $pembelianTitipan->total -= $detail->subtotal;
        $pembelianTitipan->save();

        $detail->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }


    public function print($uuid)
    {
        $titipan = PembelianTitipan::where('uuid', $uuid)->firstOrFail();
        $detail = DetailPembelianTitipan::where('uuid_pembeliantitipan', $titipan->uuid)->get();
        return view('pembelian.titipan.print', compact('titipan', 'detail'));
    }
}
