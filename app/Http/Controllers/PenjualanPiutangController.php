<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualanPiutang;
use App\Models\NamaBarang;
use App\Models\PenjualanPiutang;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\Vue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class PenjualanPiutangController extends Controller
{
    
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $piutang = PenjualanPiutang::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $piutang = PenjualanPiutang::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $piutang = PenjualanPiutang::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $piutang = PenjualanPiutang::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $piutang = PenjualanPiutang::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }

        $data=NamaBarang::all();
        return view('penjualan.piutang.index',compact('piutang', 'data'))->with('i', (request()->input('page', 1) - 1) * 10);
    }


    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = PenjualanPiutang::where('no_nota', 'like', 'PPK' . $inisial . '%')
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
    
        return 'PPK' . $inisial . '-' . $numericPart;
    }
    

    public function create()
    {
        $data = NamaBarang::all();
        return view('penjualan.piutang.create', compact('data'));
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_pembeli' => 'required|string',
        'nama_personil' => 'required|string',
        'shift' => 'required|string',
        'total' => 'required|numeric',
        'data' => 'required|array',
    ]);

    // Buat Penjualan Piutang baru
    $penjualanPiutang = PenjualanPiutang::create([
        'no_nota' => $this->generateNota(),
        'id_user' => Auth::user()->id,
        'nama_pembeli' => $request->nama_pembeli,
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'nama_personil' => $request->nama_personil,
        'shift' => $request->shift,
        'total' => $request->total,
    ]);

    // Simpan detail barang
    foreach ($request->data as $item) {
        DetailPenjualanPiutang::create([
            'uuid_penjualan' => $penjualanPiutang->uuid,
            'nama_barang' => $item['nama_barang'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            'keterangan' => $item['keterangan'],
            'subtotal' => $item['subtotal'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $penjualanPiutang->uuid]);
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
        $detail = DetailPenjualanPiutang::create([
            'uuid_penjualan' => $uuid,
            'nama_barang' => $request->barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'keterangan' => $request->keterangan,
            'subtotal' => $request->subtotal,
        ]);

        Log::info('Detail Created:', $detail->toArray());

        // Update total di PenjualanNonProduksi
        $penjualan = PenjualanPiutang::where('uuid', $uuid)->first();
        $penjualan->total += $request->subtotal; // Tambah subtotal ke total
        $penjualan->save();

        Log::info('Total Updated:', $penjualan->toArray());

        return response()->json(['success' => true, 'detail' => $detail, 'total' => $penjualan->total]);
    } catch (\Exception $e) {
        Log::error('Error storing detail:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Gagal menyimpan data'], 500);
    }
}

public function edit($uuid)
{
    // Retrieve the entry using the UUID
    $piutang = PenjualanPiutang::where('uuid', $uuid)->firstOrFail(); // Replace with your actual model name and logic if needed

    return response()->json([
        'created_at' => $piutang->created_at->format('Y-m-d'),
        'nama_pembeli' => $piutang->nama_pembeli,
        'nama_personil' => $piutang->nama_personil,
        'shift' => $piutang->shift,
        'total' => $piutang->total, // If you want to send the personil list back for dropdown (if used elsewhere)
    ]);
}
public function update(Request $request, $uuid)
{
    // Validate the incoming request data
    $request->validate([
        'created_at' => 'required',
        'nama_pembeli' => 'required|string|max:255',
        'nama_personil' => 'required|string|max:255',
        'shift' => 'required|string|max:10',
        'total' => 'required|numeric',
    ]);

    // Find the entry to update
    $piutang = PenjualanPiutang::where('uuid', $uuid)->firstOrFail();

    // Update the entry with validated data
    $piutang->update([
        'created_at' => $request->created_at,
        'nama_pembeli' => $request->nama_pembeli,
        'nama_personil' => $request->nama_personil,
        'shift' => $request->shift,
        'total' => $request->total,
    ]);

    // Redirect back with a success message
    return redirect()->route('penjualan-piutang.index')->with('success', 'Data updated successfully!');
}

    
    public function show($uuid)
    {
        $data = NamaBarang::all();
        $piutang = PenjualanPiutang::where('uuid', $uuid)->first();
        if (!$piutang) {
            return redirect()->back()->with('error', 'Penjualan Piutang tidak ditemukan');
        }
        $detail = DetailPenjualanPiutang::where('uuid_penjualan', $piutang->uuid)->get();

        if ($detail->isEmpty()) {
            dd('Detail Penjualan Piutang tidak ditemukan');
        }

        return view('penjualan.piutang.detail', compact('piutang', 'detail', 'data'));
    }

    public function editDetail($id)
    {
        $data = NamaBarang::all();
        $piutang = PenjualanPiutang::where('id', $id)->first();
        $detail = DetailPenjualanPiutang::where('uuid_penjualan', $piutang->uuid)->get();
        $dtl = DetailPenjualanPiutang::findOrFail($id); // Change this as per your actual logic
    
        return view('penjualan.piutang.edit-detail', compact('piutang', 'detail', 'data', 'dtl'));
    }

    public function updateDetail(Request $request, $uuid)
    {
        // Validate the incoming request data
        $request->validate([
            'id' => 'required|exists:detail_penjualan_piutangs,id', // Adjust table name if necessary
            'barang' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        try {

            $penjualan = PenjualanPiutang::where('uuid', $uuid)->firstOrFail();
            // Find the item by ID
            $detail = DetailPenjualanPiutang::findOrFail($request->id);

            $penjualan->total -= $detail->subtotal; // Subtract the old subtotal
            $penjualan->total += $request->subtotal; // Add the new subtotal


            // Update the detail with new data
            $detail->nama_barang = $request->barang;
            $detail->harga = $request->harga;
            $detail->qty = $request->qty;
            $detail->keterangan = $request->keterangan;
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



    public function DeletePenjualan($uuid)
    {
        $piutang = PenjualanPiutang::where('uuid', $uuid)->first();
        if ($piutang) {
            $piutang->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function DeleteDetailPenjualan($id)
    {
        $detail = DetailPenjualanPiutang::where('id', $id)->first();

        // Mengakses data induk berdasarkan ID detail
        $penjualanPiutang = $detail->penjualanPiutang;

        $penjualanPiutang->total -= $detail->subtotal;
        $penjualanPiutang->save();

        $detail->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }


    public function print($uuid)
    {
        $piutang = PenjualanPiutang::where('uuid', $uuid)->firstOrFail();
        $detail = DetailPenjualanPiutang::where('uuid_penjualan', $piutang->uuid)->get();
        return view('penjualan.piutang.print', compact('piutang', 'detail'));
    }
}
