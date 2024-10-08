<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\BarangTerjual;
use App\Models\DetailBarangTerjual;
use App\Models\NamaBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;


class BarangTerjualController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $terjual = BarangTerjual::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $terjual = BarangTerjual::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $terjual = BarangTerjual::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $terjual = BarangTerjual::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $terjual = BarangTerjual::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }
        return view('barang_terjual.index',compact('terjual'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = BarangTerjual::where('no_nota', 'like', 'BTK' . $inisial . '%')
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
    
        return 'BTK' . $inisial . '-' . $numericPart;
    }
    

    public function create()
    {
        $data = NamaBarang::all();
        return view('barang_terjual.create', compact('data'));
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_personil' => 'required|string',
        'shift' => 'required|string',
        'total' => 'required|numeric',
        'data' => 'required|array',
    ]);

    // Buat Penjualan terjual baru
    $barangTerjual = BarangTerjual::create([
        'no_nota' => $this->generateNota(),
        'id_user' => Auth::user()->id,
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'nama_personil' => $request->nama_personil,
        'shift' => $request->shift,
        'total' => $request->total,
    ]);

    // Simpan detail barang
    foreach ($request->data as $item) {
        DetailBarangTerjual::create([
            'uuid_terjual' => $barangTerjual->uuid,
            'nama_barang' => $item['nama_barang'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            'keterangan' => $item['keterangan'],
            'subtotal' => $item['subtotal'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $barangTerjual->uuid]);
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
        $detail = DetailBarangTerjual::create([
            'uuid_terjual' => $uuid,
            'nama_barang' => $request->barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'keterangan' => $request->keterangan,
            'subtotal' => $request->subtotal,
        ]);

        Log::info('Detail Created:', $detail->toArray());

        // Update total di PenjualanNonProduksi
        $penjualan = BarangTerjual::where('uuid', $uuid)->first();
        $penjualan->total += $request->subtotal; // Tambah subtotal ke total
        $penjualan->save();

        Log::info('Total Updated:', $penjualan->toArray());

        return response()->json(['success' => true, 'detail' => $detail, 'total' => $penjualan->total]);
    } catch (\Exception $e) {
        Log::error('Error storing detail:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Gagal menyimpan data'], 500);
    }
}

public function show($uuid)
{
    $data = NamaBarang::all();
    $terjual = BarangTerjual::where('uuid', $uuid)->first();
    if (!$terjual) {
        return redirect()->back()->with('error', 'Barang terjual tidak ditemukan');
    }
    $detail = DetailBarangTerjual::where('uuid_terjual', $terjual->uuid)->get();

    if ($detail->isEmpty()) {
        dd('Detail Barang terjual tidak ditemukan');
    }

    return view('barang_terjual.detail', compact('terjual', 'detail', 'data'));
}

public function edit(BarangTerjual $barangTerjual)
{
    $detail = DetailBarangTerjual::where('uuid_terjual', $barangTerjual->uuid)->get();
    return view('barang_terjual.edit', compact( 'detail'));
}

/**
 * Update the specified resource in storage.
 */
public function update(Request $request, BarangTerjual $barangTerjual)
{
    $detailBarangTerjual = DetailBarangTerjual::where('uuid_terjual', $barangTerjual->uuid)->get();
    foreach($barangTerjual as $dpp){
        $dpp->nama_barang = $request->barang;
        $dpp->qty = $request->qty;
        $dpp->harga = $request->harga;
        $dpp->keterangan = $request->keterangan;
        $dpp->subtotal = $request->qty * $request->harga;
        $dpp->save();
    }

    //update total penjualan terjual
    $detailBarangTerjual = DetailBarangTerjual::where('uuid_terjual',$barangTerjual->uuid)->get();
    $barangTerjual->total = $detailBarangTerjual->sum('subtotal');
    $barangTerjual->save();

    return redirect()->back()->with('success', 'Data berhasil diubah');
}

/**
 * Remove the specified resource from storage.
 */
public function DeleteBarangTerjual($uuid)
{
    $terjual = BarangTerjual::where('uuid', $uuid)->first();
    if ($terjual) {
        $terjual->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    } else {
        return redirect()->back()->with('error', 'Data tidak ditemukan');
    }
    return redirect()->back()->with('success', 'Data berhasil dihapus');
}

public function DeleteDetailTerjual($id)
{
    $detail = DetailBarangTerjual::where('id', $id)->first();

    // Mengakses data induk berdasarkan ID detail
    $barangTerjual = $detail->barangTerjual;

    $barangTerjual->total -= $detail->subtotal;
    $barangTerjual->save();

    $detail->delete();

    return redirect()->back()->with('success', 'Data berhasil dihapus');
}


public function print($uuid)
{
    $terjual = BarangTerjual::where('uuid', $uuid)->firstOrFail();
    $detail = DetailBarangTerjual::where('uuid_terjual', $terjual->uuid)->get();
    return view('barang_terjual.print', compact('terjual', 'detail'));
}
}
