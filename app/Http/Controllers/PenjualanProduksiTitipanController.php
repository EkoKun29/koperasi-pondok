<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NamaBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PenjualanProduksiTitipan;
use App\Models\DetailPenjualanProduksiTitipan;
use Illuminate\Support\Facades\Log;

class PenjualanProduksiTitipanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $titipan = PenjualanProduksiTitipan::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $titipan = PenjualanProduksiTitipan::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $titipan = PenjualanProduksiTitipan::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $titipan = PenjualanProduksiTitipan::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $titipan = PenjualanProduksiTitipan::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }

        $data = NamaBarang::all();
        return view('penjualan.produksititipan.index',compact('titipan','data'))->with('i', (request()->input('page', 1) - 1) * 10);
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

public function storeDetail(Request $request, $uuid)
{
    // Log untuk melihat data yang masuk
    Log::info('Request Data:', $request->all());

    $request->validate([
        'barang' => 'required|string',
        'harga' => 'required|numeric',
        'qty' => 'required|numeric',
        'subtotal' => 'required|numeric',
    ]);

    try {
        // Buat detail baru
        $detail = DetailPenjualanProduksiTitipan::create([
            'uuid_titipan' => $uuid,
            'nama_barang' => $request->barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'subtotal' => $request->subtotal,
        ]);

        Log::info('Detail Created:', $detail->toArray());

        // Update total di PenjualanNonProduksi
        $penjualan = PenjualanProduksiTitipan::where('uuid', $uuid)->first();
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
    public function edit($uuid)
{
    // Retrieve the entry using the UUID
    $titipan = PenjualanProduksiTitipan::where('uuid', $uuid)->firstOrFail(); // Replace with your actual model name and logic if needed

    return response()->json([
        'nama_personil' => $titipan->nama_personil,
        'shift' => $titipan->shift,
        'total' => $titipan->total, // If you want to send the personil list back for dropdown (if used elsewhere)
    ]);
}
public function update(Request $request, $uuid)
{
    // Validate the incoming request data
    $request->validate([
        'nama_personil' => 'required|string|max:255',
        'shift' => 'required|string|max:10',
        'total' => 'required|numeric',
    ]);

    // Find the entry to update
    $titipan = PenjualanProduksiTitipan::where('uuid', $uuid)->firstOrFail();

    // Update the entry with validated data
    $titipan->update([
        'nama_personil' => $request->nama_personil,
        'shift' => $request->shift,
        'total' => $request->total,
    ]);

    // Redirect back with a success message
    return redirect()->route('penjualan-produksititipan.index')->with('success', 'Data updated successfully!');
}


 
    public function updateDetail(Request $request, $uuid)
    {
        // Validate the incoming request data
        $request->validate([
            'id' => 'required|exists:detail_penjualan_produksi_titipans,id', // Adjust table name if necessary
            'barang' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        try {

            $penjualan = PenjualanProduksiTitipan::where('uuid', $uuid)->firstOrFail();
            // Find the item by ID
            $detail = DetailPenjualanProduksiTitipan::findOrFail($request->id);

            $penjualan->total -= $detail->subtotal; // Subtract the old subtotal
            $penjualan->total += $request->subtotal; // Add the new subtotal


            // Update the detail with new data
            $detail->nama_barang = $request->barang;
            $detail->harga = $request->harga;
            $detail->qty = $request->qty;
            $detail->subtotal = $request->subtotal;

            // Save the updated data
            $detail->save();
            $penjualan->save();

            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil diperbarui',
                'detail' => $detail // Returning the updated detail
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data barang',
                'error' => $e->getMessage()
            ], 500);
        }
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
