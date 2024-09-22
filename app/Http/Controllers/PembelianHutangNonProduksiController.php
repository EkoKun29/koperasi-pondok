<?php

namespace App\Http\Controllers;

use App\Models\DetailHutangNonProduksi;
use App\Models\User;
use App\Models\NamaBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PembelianHutangNonProduksi;
use Illuminate\Support\Facades\Log;

class PembelianHutangNonProduksiController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $hutangnonproduksi = PembelianHutangNonProduksi::orderBy('uuid', 'desc')->paginate(10);
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $hutangnonproduksi = PembelianHutangNonProduksi::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->paginate(10);

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $hutangnonproduksi = PembelianHutangNonProduksi::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $hutangnonproduksi = PembelianHutangNonProduksi::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $hutangnonproduksi = PembelianHutangNonProduksi::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->paginate(10);

        }else{
            abort(403, 'Unauthorized action.');
        }
        return view('pembelian.hutangnonproduksi.index',compact('hutangnonproduksi'))->with('i', (request()->input('page', 1) - 1) * 10);
    }


    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = PembelianHutangNonProduksi::where('no_nota', 'like', 'PMNPK' . $inisial . '%')
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
    
        return 'PMNPK' . $inisial . '-' . $numericPart;
    }
    

    public function create()
    {
        $data = NamaBarang::all();
        return view('pembelian.hutangnonproduksi.create', compact('data'));
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_supplier' => 'required|string',
        'tanggal' => 'required|date',
        'tanggal_jatuh_tempo' => 'required|date',
        'total' => 'required|numeric',
        'data' => 'required|array',
    ]);

    // Buat Penjualan Piutang baru
    $pembelianHutangNonProduksi = PembelianHutangNonProduksi::create([
        'no_nota' => $this->generateNota(),
        'id_user' => Auth::user()->id,
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'nama_supplier' => $request->nama_supplier,
        'tanggal' => $request->tanggal,
        'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
        'total' => $request->total,
    ]);

    // Simpan detail barang
    foreach ($request->data as $item) {
        DetailHutangNonProduksi::create([
            'uuid_hutangnonproduksi' => $pembelianHutangNonProduksi->uuid,
            'nama_barang' => $item['nama_barang'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            'check_barang' => $item['check_barang'],
            'subtotal' => $item['subtotal'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $pembelianHutangNonProduksi->uuid]);
}

public function storeDetail(Request $request, $uuid)
{
    // Log untuk melihat data yang masuk
    Log::info('Request Data:', $request->all());

    $request->validate([
        'nama_barang' => 'required|string',
        'harga' => 'required|numeric',
        'qty' => 'required|numeric',
        'subtotal' => 'required|numeric',
    ]);

    try {
        // Buat detail baru
        $detail = DetailHutangNonProduksi::create([
            'uuid_hutangnonproduksi' => $uuid,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'check_barang' => $request->check_barang,
            'subtotal' => $request->subtotal,
        ]);

        Log::info('Detail Created:', $detail->toArray());

        // Update total di PenjualanNonProduksi
        $pembelian = PembelianHutangNonProduksi::where('uuid', $uuid)->first();
        $pembelian->total += $request->subtotal; // Tambah subtotal ke total
        $pembelian->save();

        Log::info('Total Updated:', $pembelian->toArray());

        return response()->json(['success' => true, 'detail' => $detail, 'total' => $pembelian->total]);
    } catch (\Exception $e) {
        Log::error('Error storing detail:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Gagal menyimpan data'], 500);
    }
}
    
    public function show($uuid)
    {
        $data = NamaBarang::all();
        $hutangnonproduksi = PembelianHutangNonProduksi::where('uuid', $uuid)->first();
        if (!$hutangnonproduksi) {
            return redirect()->back()->with('error', 'Pembelian Hutang Non Produksi tidak ditemukan');
        }
        $detail = DetailHutangNonProduksi::where('uuid_hutangnonproduksi', $hutangnonproduksi->uuid)->get();

        if ($detail->isEmpty()) {
            dd('Detail Pembelian Hutang Non Produksi tidak ditemukan');
        }

        return view('pembelian.hutangnonproduksi.detail', compact('hutangnonproduksi', 'detail', 'data'));
    }

    public function edit(PembelianHutangNonProduksi $pembelianHutangNonProduksi)
    {
        $detail = DetailHutangNonProduksi::where('uuid_hutangnonproduksi', $pembelianHutangNonProduksi->uuid)->get();
        return view('pembelian.hutangnonproduksi.edit', compact('pembelianHutangNonProduksi', 'detail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PembelianHutangNonProduksi $pembelianHutangNonProduksi)
    {
        $DetailHutangNonProduksi = DetailHutangNonProduksi::where('uuid_hutangnonproduksi', $pembelianHutangNonProduksi->uuid)->get();
        foreach($DetailHutangNonProduksi as $dpp){
            $dpp->nama_barang = $request->barang;
            $dpp->qty = $request->qty;
            $dpp->harga = $request->harga;
            $dpp->check_barang = $request->check_barang;
            $dpp->subtotal = $request->qty * $request->harga;
            $dpp->save();
        }

        //update total penjualan piutang
        $DetailHutangNonProduksi = DetailHutangNonProduksi::where('uuid_hutangnonproduksi',$pembelianHutangNonProduksi->uuid)->get();
        $pembelianHutangNonProduksi->total = $DetailHutangNonProduksi->sum('subtotal');
        $pembelianHutangNonProduksi->save();

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function DeletePembelian($uuid)
    {
        $hutangnonproduksi = PembelianHutangNonProduksi::where('uuid', $uuid)->first();
        if ($hutangnonproduksi) {
            $hutangnonproduksi->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function DeleteDetailPembelian($id)
    {
        $detail = DetailHutangNonProduksi::where('id', $id)->first();

        // Mengakses data induk berdasarkan ID detail
        $pembelianHutangNonProduksi = $detail->pembelianHutangNonProduksi;

        $pembelianHutangNonProduksi->total -= $detail->subtotal;
        $pembelianHutangNonProduksi->save();

        $detail->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }


    public function print($uuid)
    {
        $hutangnonproduksi = PembelianHutangNonProduksi::where('uuid', $uuid)->firstOrFail();
        $detail = DetailHutangNonProduksi::where('uuid_hutangnonproduksi', $hutangnonproduksi->uuid)->get();
        return view('pembelian.hutangnonproduksi.print', compact('hutangnonproduksi', 'detail'));
    }
}
