<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NamaBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PenjualanProduksiTitipan;
use App\Models\DetailPenjualanProduksiTitipan;

class PenjualanProduksiTitipanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $titipan = PenjualanProduksiTitipan::orderBy('uuid', 'desc')->paginate(10);
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $titipan = PenjualanProduksiTitipan::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->paginate(10);

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $titipan = PenjualanProduksiTitipan::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $titipan = PenjualanProduksiTitipan::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $titipan = PenjualanProduksiTitipan::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->paginate(10);

        }else{
            abort(403, 'Unauthorized action.');
        }
        return view('penjualan.produksititipan.index',compact('titipan'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = PenjualanProduksiTitipan::where('no_nota', 'like', 'PTK' . $inisial . '%')
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
    
        return 'PTK' . $inisial . '-' . $numericPart;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = NamaBarang::all();
        return view('penjualan.produksititipan.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_personil' => 'required|string',
        'shift' => 'required|string',
        'total' => 'required|numeric',
        'data' => 'required|array',
    ]);

    // Buat Penjualan Piutang baru
    $penjualanTitipan = PenjualanProduksiTitipan::create([
        'no_nota' => $this->generateNota(),
        'id_user' => Auth::user()->id, 
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'nama_personil' => $request->nama_personil,
        'shift' => $request->shift,
        'total' => $request->total,
    ]);

    // Simpan detail barang
    foreach ($request->data as $item) {
        DetailPenjualanProduksiTitipan::create([
            'uuid_titipan' => $penjualanTitipan->uuid,
            'nama_barang' => $item['nama_barang'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            // 'keterangan' => $item['keterangan'],
            'subtotal' => $item['subtotal'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $penjualanTitipan->uuid]);
}


    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        $data = NamaBarang::all();
        $titipan = PenjualanProduksiTitipan::where('uuid', $uuid)->first();
        if (!$titipan) {
            return redirect()->back()->with('error', 'Penjualan Titipan tidak ditemukan');
        }
        $detail = DetailPenjualanProduksiTitipan::where('uuid_titipan', $titipan->uuid)->get();

        if ($detail->isEmpty()) {
            dd('Detail Penjualan Titipan tidak ditemukan');
        }

        return view('penjualan.produksititipan.detail', compact('titipan', 'detail', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenjualanProduksiTitipan $penjualanTitipan)
    {
        $detail = DetailPenjualanProduksiTitipan::where('uuid_titipan', $penjualanTitipan->uuid)->get();
        return view('penjualan.produksititipan.edit', compact('penjualanTitipan', 'detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenjualanProduksiTitipan $penjualanTitipan)
    {
        $detailPenjualanTitipan = DetailPenjualanProduksiTitipan::where('uuid_titipan', $penjualanTitipan->uuid)->get();
        foreach($detailPenjualanTitipan as $dpp){
            $dpp->nama_barang = $request->barang;
            $dpp->qty = $request->qty;
            $dpp->harga = $request->harga;
            // $dpp->keterangan = $request->keterangan;
            $dpp->subtotal = $request->qty * $request->harga;
            $dpp->save();
        }

        //update total penjualan piutang
        $detailPenjualanTitipan = DetailPenjualanProduksiTitipan::where('uuid_titipan',$penjualanTitipan->uuid)->get();
        $penjualanTitipan->total = $detailPenjualanTitipan->sum('subtotal');
        $penjualanTitipan->save();

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function DeletePenjualan($uuid)
    {
        $titipan = PenjualanProduksiTitipan::where('uuid', $uuid)->first();
        if ($titipan) {
            $titipan->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function DeleteDetailPenjualan($id)
    {
        $detail = DetailPenjualanProduksiTitipan::where('id', $id)->first();

        // Mengakses data induk berdasarkan ID detail
        $penjualanTitipan = $detail->titipan;

        $penjualanTitipan->total -= $detail->subtotal;
        $penjualanTitipan->save();

        $detail->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function print($uuid)
    {
        $titipan = PenjualanProduksiTitipan::where('uuid', $uuid)->firstOrFail();
        $detail = DetailPenjualanProduksiTitipan::where('uuid_titipan', $titipan->uuid)->get();
        return view('penjualan.produksititipan.print', compact('titipan', 'detail'));
    }

}
