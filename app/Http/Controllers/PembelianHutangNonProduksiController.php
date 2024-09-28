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

    public function edit($uuid)
{
    // Retrieve the entry using the UUID
    $hutangnonproduksi = PembelianHutangNonProduksi::where('uuid', $uuid)->firstOrFail();

    // Ensure tanggal is a Carbon instance
    $hutangnonproduksi->tanggal = \Carbon\Carbon::parse($hutangnonproduksi->tanggal); 
    $hutangnonproduksi->tanggal_jatuh_tempo = \Carbon\Carbon::parse($hutangnonproduksi->tanggal_jatuh_tempo); 

    return response()->json([
        'tanggal' => $hutangnonproduksi->tanggal->format('Y-m-d'), // Format the date correctly
        'nama_supplier' => $hutangnonproduksi->nama_supplier,
        'tanggal_jatuh_tempo' => $hutangnonproduksi->tanggal_jatuh_tempo->format('Y-m-d'),
        'total' => $hutangnonproduksi->total,
    ]);
}

public function update(Request $request, $uuid)
{
    // Validate the incoming request data
    $request->validate([
        'tanggal' => 'required|date',
        'nama_supplier' => 'required|string|max:255',
        'tanggal_jatuh_tempo' => 'required|date',
        'total' => 'required|numeric',
    ]);

    // Find the entry to update
    $hutangnonproduksi = PembelianHutangNonProduksi::where('uuid', $uuid)->firstOrFail();

    // Update the entry with validated data
    $hutangnonproduksi->update([
        'tanggal' => $request->tanggal,
        'nama_supplier' => $request->nama_supplier,
        'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
        'total' => $request->total,
    ]);

    return redirect()->route('pembelian-hutangnonproduksi.index')->with('success', 'Data updated successfully!');
}
    public function editDetail($id)
    {
        $data = NamaBarang::all();
        $hutangnonproduksi = PembelianHutangNonProduksi::where('id', $id)->first();
        $detail = DetailHutangNonProduksi::where('uuid_hutangnonproduksi', $hutangnonproduksi->uuid)->get();
        $dtl = DetailHutangNonProduksi::findOrFail($id); // Change this as per your actual logic
    
        return view('pembelian.hutangnonproduksi.edit-detail', compact('hutangnonproduksi', 'detail', 'data', 'dtl'));
    }

    public function updateDetail(Request $request, $uuid)
    {
        // Validate the incoming request data
        $request->validate([
            'id' => 'required|exists:detail_hutang_non_produksis,id', // Adjust table name if necessary
            'barang' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'check_barang' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        try {

            $pembelian = PembelianHutangNonProduksi::where('uuid', $uuid)->firstOrFail();
            // Find the item by ID
            $detail = DetailHutangNonProduksi::findOrFail($request->id);

            $pembelian->total -= $detail->subtotal; // Subtract the old subtotal
            $pembelian->total += $request->subtotal; // Add the new subtotal


            // Update the detail with new data
            $detail->nama_barang = $request->barang;
            $detail->harga = $request->harga;
            $detail->qty = $request->qty;
            $detail->check_barang = $request->check_barang;
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
