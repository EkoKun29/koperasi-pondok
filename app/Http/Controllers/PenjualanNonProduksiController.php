<?php

namespace App\Http\Controllers;

use App\Models\DetailNonProduksi;
use App\Models\User;
use App\Models\NamaBarang;
use Illuminate\Http\Request;
use App\Models\PenjualanNonProduksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PenjualanNonProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $nonproduksi = PenjualanNonProduksi::orderBy('uuid', 'desc')->paginate(10);
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $nonproduksi = PenjualanNonProduksi::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->paginate(10);

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $nonproduksi = PenjualanNonProduksi::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $nonproduksi = PenjualanNonProduksi::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $nonproduksi = PenjualanNonProduksi::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->paginate(10);

        }else{
            abort(403, 'Unauthorized action.');
        }
        return view('penjualan.nonproduksi.index',compact('nonproduksi'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = PenjualanNonProduksi::where('no_nota', 'like', 'PNPK' . $inisial . '%')
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
    
        return 'PNPK' . $inisial . '-' . $numericPart;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = NamaBarang::all();
        return view('penjualan.nonproduksi.create', compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        // 'nama_pembeli' => 'required|string',
        'nama_personil' => 'required|string',
        'shift' => 'required|string',
        'total' => 'required|numeric',
        'data' => 'required|array',
    ]);

    // Buat Penjualan Piutang baru
    $penjualanNonProduksi = PenjualanNonProduksi::create([
        'no_nota' => $this->generateNota(),
        'id_user' => Auth::user()->id,
        // 'nama_pembeli' => $request->nama_pembeli,
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'nama_personil' => $request->nama_personil,
        'shift' => $request->shift,
        'total' => $request->total,
    ]);

    // Simpan detail barang
    foreach ($request->data as $item) {
        DetailNonProduksi::create([
            'uuid_nonproduksi' => $penjualanNonProduksi->uuid,
            'nama_barang' => $item['nama_barang'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            'keterangan' => $item['keterangan'],
            'subtotal' => $item['subtotal'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $penjualanNonProduksi->uuid]);
}

public function storeDetail(Request $request, $uuid)
{
    // Log untuk melihat data yang masuk
    Log::info('Request Data:', $request->all());

    $request->validate([
        'barang' => 'required|string',
        'harga' => 'required|numeric',
        'qty' => 'required|numeric',
        'keterangan' => 'required|string',
        'subtotal' => 'required|numeric',
    ]);

    try {
        // Buat detail baru
        $detail = DetailNonProduksi::create([
            'uuid_nonproduksi' => $uuid,
            'nama_barang' => $request->barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'keterangan' => $request->keterangan,
            'subtotal' => $request->subtotal,
        ]);

        Log::info('Detail Created:', $detail->toArray());

        // Update total di PenjualanNonProduksi
        $penjualan = PenjualanNonProduksi::where('uuid', $uuid)->first();
        $penjualan->total += $request->subtotal; // Tambah subtotal ke total
        $penjualan->save();

        Log::info('Total Updated:', $penjualan->toArray());

        return response()->json(['success' => true, 'detail' => $detail, 'total' => $penjualan->total]);
    } catch (\Exception $e) {
        Log::error('Error storing detail:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Gagal menyimpan data'], 500);
    }
}



    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        $data = NamaBarang::all();
        $nonproduksi = PenjualanNonProduksi::where('uuid', $uuid)->first();
        if (!$nonproduksi) {
            return redirect()->back()->with('error', 'Penjualan Non Produksi tidak ditemukan');
        }
        $detail = DetailNonProduksi::where('uuid_nonproduksi', $nonproduksi->uuid)->get();

        if ($detail->isEmpty()) {
            dd('Detail Penjualan Non Produksi tidak ditemukan');
        }

        return view('penjualan.nonproduksi.detail', compact('nonproduksi', 'detail', 'data'));
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenjualanNonProduksi $penjualanNonProduksi)
    {
        $detail = DetailNonProduksi::where('uuid_nonproduksi', $penjualanNonProduksi->uuid)->get();
        return view('penjualan.nonproduksi.edit', compact('penjualanNonProduksi', 'detail'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenjualanNonProduksi $penjualanNonProduksi)
    {
        $detailPenjualanNonProduksi = DetailNonProduksi::where('uuid_nonproduksi', $penjualanNonProduksi->uuid)->get();
        foreach($detailPenjualanNonProduksi as $dpp){
            $dpp->nama_barang = $request->barang;
            $dpp->qty = $request->qty;
            $dpp->harga = $request->harga;
            $dpp->keterangan = $request->keterangan;
            $dpp->subtotal = $request->qty * $request->harga;
            $dpp->save();
        }

        //update total penjualan piutang
        $detailPenjualanNonProduksi = DetailNonProduksi::where('uuid_nonproduksi',$penjualanNonProduksi->uuid)->get();
        $penjualanNonProduksi->total = $detailPenjualanNonProduksi->sum('subtotal');
        $penjualanNonProduksi->save();

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function DeletePenjualan($uuid)
    {
        $nonproduksi = PenjualanNonProduksi::where('uuid', $uuid)->first();
        if ($nonproduksi) {
            $nonproduksi->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function DeleteDetailPenjualan($id)
    {
        $detail = DetailNonProduksi::where('id', $id)->first();

        // Mengakses data induk berdasarkan ID detail
        $penjualanNonProduksi = $detail->penjualanNonProduksi;

        $penjualanNonProduksi->total -= $detail->subtotal;
        $penjualanNonProduksi->save();

        $detail->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }


    public function print($uuid)
    {
        $nonproduksi = PenjualanNonProduksi::where('uuid', $uuid)->firstOrFail();
        $detail = DetailNonProduksi::where('uuid_nonproduksi', $nonproduksi->uuid)->get();
        return view('penjualan.nonproduksi.print', compact('nonproduksi', 'detail'));
    }
}
