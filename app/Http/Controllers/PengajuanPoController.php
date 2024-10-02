<?php

namespace App\Http\Controllers;

use App\Models\DetailPengajuanPo;
use App\Models\PengajuanPo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\NamaBarang;
use Illuminate\Support\Facades\Log;

class PengajuanPoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $po = PengajuanPo::orderBy('uuid', 'desc')->get();
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $po = PengajuanPo::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->get();

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $po = PengajuanPo::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $po = PengajuanPo::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->get();

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $po = PengajuanPo::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->get();

        }else{
            abort(403, 'Unauthorized action.');
        }

        $data=NamaBarang::all();
        return view('po.index',compact('po', 'data'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    private function generateNota()
    {
        $inisial = Auth::user()->role;
    
        // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
        $lastNote = PengajuanPo::where('no_nota', 'like', 'PPOK' . $inisial . '%')
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
    
        return 'PPOK' . $inisial . '-' . $numericPart;
    }
    

    public function create()
    {
        $data = NamaBarang::all();
        return view('po.create', compact('data'));
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_pengaju' => 'required|string',
        'tanggal' => 'required|string',
        'total' => 'required|numeric',
        'data' => 'required|array',
    ]);

    // Buat Pengajuan Piutang baru
    $pengajuanPO = PengajuanPo::create([
        'no_nota' => $this->generateNota(),
        'id_user' => Auth::user()->id,
        'tanggal' => $request->tanggal,
        'nama_pengaju' => $request->nama_pengaju,
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'total' => $request->total,
    ]);

    // Simpan detail barang
    foreach ($request->data as $item) {
        DetailPengajuanPo::create([
            'uuid_po' => $pengajuanPO->uuid,
            'nama_barang' => $item['nama_barang'],
            'harga' => $item['harga'],
            'qty' => $item['qty'],
            'keterangan' => $item['keterangan'],
            'total' => $item['total'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $pengajuanPO->uuid]);
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
        'total' => 'required|numeric',
    ]);

    try {
        // Buat detail baru
        $detail = DetailPengajuanPo::create([
            'uuid_po' => $uuid,
            'nama_barang' => $request->barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'keterangan' => $request->keterangan,
            'total' => $request->total,
        ]);

        Log::info('Detail Created:', $detail->toArray());

        // Update total di PengajuanNonProduksi
        $po = PengajuanPo::where('uuid', $uuid)->first();
        $po->total += $request->total; // Tambah subtotal ke total
        $po->save();

        Log::info('Total Updated:', $po->toArray());

        return response()->json(['success' => true, 'detail' => $detail, 'total' => $po->total]);
    } catch (\Exception $e) {
        Log::error('Error storing detail:', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Gagal menyimpan data'], 500);
    }
}

public function edit($uuid)
{
    // Retrieve the entry using the UUID
    $po = PengajuanPo::where('uuid', $uuid)->firstOrFail(); // Replace with your actual model name and logic if needed

    return response()->json([
        'nama_pengaju' => $po->nama_pengaju,
        'tanggal' => $po->tanggal,
        'total' => $po->total, // If you want to send the personil list back for dropdown (if used elsewhere)
    ]);
}
public function update(Request $request, $uuid)
{
    // Validate the incoming request data
    $request->validate([
        'nama_pengaju' => 'required|string|max:255',
        'total' => 'required|numeric',
    ]);

    // Find the entry to update
    $po = PengajuanPo::where('uuid', $uuid)->firstOrFail();

    // Update the entry with validated data
    $po->update([
        'nama_pengaju' => $request->nama_pengaju,
        'tanggal' => $request->tanggal,
        'total' => $request->total,
    ]);

    // Redirect back with a success message
    return redirect()->route('pengajuan-po.index')->with('success', 'Data updated successfully!');
}

    
    public function show($uuid)
    {
        $data = NamaBarang::all();
        $po = PengajuanPo::where('uuid', $uuid)->first();
        if (!$po) {
            return redirect()->back()->with('error', 'Pengajuan Piutang tidak ditemukan');
        }
        $detail = DetailPengajuanPo::where('uuid_po', $po->uuid)->get();

        if ($detail->isEmpty()) {
            dd('Detail Pengajuan Piutang tidak ditemukan');
        }

        return view('po.detail', compact('po', 'detail', 'data'));
    }

    public function editDetail($id)
    {
        $data = NamaBarang::all();
        $po = PengajuanPo::where('id', $id)->first();
        $detail = DetailPengajuanPo::where('uuid_po', $po->uuid)->get();
        $dtl = DetailPengajuanPo::findOrFail($id); // Change this as per your actual logic
    
        return view('po.edit-detail', compact('po', 'detail', 'data', 'dtl'));
    }

    public function updateDetail(Request $request, $uuid)
    {
        // Validate the incoming request data
        $request->validate([
            'id' => 'required|exists:detail_pengajuan_pos,id', // Adjust table name if necessary
            'barang' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        try {

            $po = PengajuanPo::where('uuid', $uuid)->firstOrFail();
            // Find the item by ID
            $detail = DetailPengajuanPo::findOrFail($request->id);

            $po->total -= $detail->total; // Subtract the old subtotal
            $po->total += $request->total; // Add the new subtotal


            // Update the detail with new data
            $detail->nama_barang = $request->barang;
            $detail->harga = $request->harga;
            $detail->qty = $request->qty;
            $detail->keterangan = $request->keterangan;
            $detail->total = $request->total;

            // Save the updated data
            $detail->save();
            $po->save();

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



    public function DeletePengajuan($uuid)
    {
        $po = PengajuanPo::where('uuid', $uuid)->first();
        if ($po) {
            $po->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function DeleteDetailPengajuan($id)
    {
        $detail = DetailPengajuanPo::where('id', $id)->first();

        // Mengakses data induk berdasarkan ID detail
        $po = $detail->pengajuanPO;

        $po->total -= $detail->total;
        $po->save();

        $detail->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }


    public function print($uuid)
    {
        $po = PengajuanPo::where('uuid', $uuid)->firstOrFail();
        $detail = DetailPengajuanPo::where('uuid_po', $po->uuid)->get();
        return view('po.print', compact('po', 'detail'));
    }
}
