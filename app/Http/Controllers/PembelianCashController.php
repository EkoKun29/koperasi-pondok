<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DetailPembelianCash;
use App\Models\NamaBarang;
use App\Models\PembelianCash;
use Laravel\Ui\Presets\Vue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class PembelianCashController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $beli_cash = PembelianCash::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $beli_cash = PembelianCash::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $beli_cash = PembelianCash::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $beli_cash = PembelianCash::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $beli_cash = PembelianCash::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }

        $data=NamaBarang::all();
        return view('pembelian.cash.index',compact('beli_cash'))->with('i', (request()->input('page', 1) - 1) * 10);
    }


    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = PembelianCash::where('no_nota', 'like', 'PMCK' . $inisial . '%')
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
    
        return 'PMCK' . $inisial . '-' . $numericPart;
    }
    

    public function create()
    {
        $data = NamaBarang::all();
        return view('pembelian.cash.create', compact('data'));
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'total' => 'required|numeric',
        'data' => 'required|array',
    ]);

    // Buat Penjualan Piutang baru
    $beli_cash = PembelianCash::create([
        'no_nota' => $this->generateNota(),
        'id_user' => Auth::user()->id,
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'tanggal' => $request->tanggal,
        'total' => $request->total,
    ]);

    // Simpan detail barang
    foreach ($request->data as $item) {
        DetailPembelianCash::create([
            'uuid_cash' => $beli_cash->uuid,
            'nama_barang' => $item['nama_barang'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            'cek_barang' => $item['cek_barang'],
            'keterangan' => $item['keterangan'],
            'subtotal' => $item['subtotal'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $beli_cash->uuid]);
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
        $detail = DetailPembelianCash::create([
            'uuid_cash' => $uuid,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'cek_barang' => $request->cek_barang,
            'keterangan' => $request->keterangan,
            'subtotal' => $request->subtotal,
        ]);

        Log::info('Detail Created:', $detail->toArray());

        // Update total di PenjualanNonProduksi
        $pembelian = PembelianCash::where('uuid', $uuid)->first();
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
        $beli_cash = PembelianCash::where('uuid', $uuid)->first();
        if (!$beli_cash) {
            return redirect()->back()->with('error', 'Pembelian Cash tidak ditemukan');
        }
        $detail = DetailPembelianCash::where('uuid_cash', $beli_cash->uuid)->get();

        if ($detail->isEmpty()) {
            dd('Detail Penjualan Piutang tidak ditemukan');
        }

        return view('pembelian.cash.detail', compact('beli_cash', 'detail', 'data'));
    }

    public function edit($uuid)
{
    // Retrieve the entry using the UUID
    $cash = PembelianCash::where('uuid', $uuid)->firstOrFail();

    // Ensure tanggal is a Carbon instance
    $cash->tanggal = \Carbon\Carbon::parse($cash->tanggal); 

    return response()->json([
        'tanggal' => $cash->tanggal->format('Y-m-d'), // Format the date correctly
        'total' => $cash->total,
    ]);
}

public function update(Request $request, $uuid)
{
    // Validate the incoming request data
    $request->validate([
        'tanggal' => 'required|date',
        'total' => 'required|numeric',
    ]);

    // Find the entry to update
    $cash = PembelianCash::where('uuid', $uuid)->firstOrFail();

    // Update the entry with validated data
    $cash->update([
        'tanggal' => $request->tanggal,
        'total' => $request->total,
    ]);

    return redirect()->route('pembelian-cash.index')->with('success', 'Data updated successfully!');
}

    public function editDetail($id)
    {
        $data = NamaBarang::all();
        $cash = PembelianCash::where('id', $id)->first();
        $detail = DetailPembelianCash::where('uuid_cash', $cash->uuid)->get();
        $dtl = DetailPembelianCash::findOrFail($id); // Change this as per your actual logic
    
        return view('pembelian.cash.edit-detail', compact('cash', 'detail', 'data', 'dtl'));
    }

    public function updateDetail(Request $request, $uuid)
    {
        // Validate the incoming request data
        $request->validate([
            'id' => 'required|exists:detail_pembelian_cashes,id', // Adjust table name if necessary
            'barang' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'cek_barang' => 'required|string',
            'keterangan' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        try {

            $pembelian = PembelianCash::where('uuid', $uuid)->firstOrFail();
            // Find the item by ID
            $detail = DetailPembelianCash::findOrFail($request->id);

            $pembelian->total -= $detail->subtotal; // Subtract the old subtotal
            $pembelian->total += $request->subtotal; // Add the new subtotal


            // Update the detail with new data
            $detail->nama_barang = $request->barang;
            $detail->harga = $request->harga;
            $detail->qty = $request->qty;
            $detail->cek_barang = $request->cek_barang;
            $detail->keterangan = $request->keterangan;
            $detail->subtotal = $request->subtotal;

            // Save the updated data
            $detail->save();
            $pembelian->save();

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
    public function DeletePembelian($uuid)
    {
        $beli_cash = PembelianCash::where('uuid', $uuid)->first();
        if ($beli_cash) {
            $beli_cash->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function DeleteDetailPembelian($id)
    {
        $detail = DetailPembelianCash::where('id', $id)->first();

        // Mengakses data induk berdasarkan ID detail
        $pembelianCash = $detail->pembelianCash;

        $pembelianCash->total -= $detail->subtotal;
        $pembelianCash->save();

        $detail->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }


    public function print($uuid)
    {
        $beli_cash = PembelianCash::where('uuid', $uuid)->firstOrFail();
        $detail = DetailPembelianCash::where('uuid_cash', $beli_cash->uuid)->get();
        return view('pembelian.cash.print', compact('beli_cash', 'detail'));
    }
}
