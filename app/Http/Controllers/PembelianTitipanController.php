<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\NamaBarang;
use Illuminate\Http\Request;
use App\Models\PembelianTitipan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailPembelianTitipan;
use Illuminate\Support\Facades\Validator;

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
            'sisa_akhir' => $item['sisa_akhir'],
            'subtotal_sisa' => $item['subtotal_sisa'] ?? 0,
            'subtotal' => $item['subtotal'],
        ]);
    }

    // Kembalikan UUID untuk redirect
    return response()->json(['success' => true, 'uuid' => $pembelianTitipan->uuid]);
}

public function storeDetail(Request $request, $uuid)
{
    // Log untuk melihat data yang masuk
    Log::info('Request Data:', $request->all());

    $request->validate([
        'nama_barang' => 'required|string',
        'harga' => 'required|numeric',
        'qty' => 'required|numeric',
        'sisa_akhir' => 'required|numeric',
        'subtotal_sisa' => 'required|numeric',
        'subtotal' => 'required|numeric',
    ]);

    try {
        // Buat detail baru
        $detail = DetailPembelianTitipan::create([
            'uuid_pembeliantitipan' => $uuid,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'qty' => $request->qty,
            'sisa_siang' => $request->sisa_siang,
            'sisa_sore' => $request->sisa_sore,
            'sisa_malam' => $request->sisa_malam,
            'sisa_akhir' => $request->sisa_akhir,
            'subtotal_sisa' => $request->subtotal_sisa,
            'subtotal' => $request->subtotal,
        ]);

        Log::info('Detail Created:', $detail->toArray());

        // Update total di PenjualanNonProduksi
        $pembelian = PembelianTitipan::where('uuid', $uuid)->first();
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

    public function editDetail($id)
{
    // Retrieve all available items for selection
    $data = NamaBarang::all();

    // Retrieve the detail record by ID
    $detail = DetailPembelianTitipan::findOrFail($id);
    
    // Retrieve the parent PembelianTitipan using the detail's uuid
    $titipan = PembelianTitipan::where('uuid', $detail->uuid_pembeliantitipan)->first();

    // Ensure titipan is found
    if (!$titipan) {
        return redirect()->back()->with('error', 'Pembelian Titipan tidak ditemukan');
    }

    // Retrieve all details for the given PembelianTitipan
    $allDetails = DetailPembelianTitipan::where('uuid_pembeliantitipan', $titipan->uuid)->get();

    return view('pembelian.titipan.edit-detail', compact('titipan', 'allDetails', 'data', 'detail'));
}


        public function updateDetail(Request $request, $uuid)
        {
            // Validate the incoming request data
            $request->validate([
                'id' => 'required|exists:detail_pembelian_titipans,id',
                'nama_barang' => 'required|string|max:255',
                'harga' => 'required|numeric',
                'qty' => 'required|integer|min:1',
                'sisa_siang' => 'nullable|integer',
                'sisa_sore' => 'nullable|integer',
                'sisa_malam' => 'nullable|integer',
                'sisa_akhir' => 'required|integer',
                'subtotal_sisa' => 'required|numeric',
                'subtotal' => 'required|numeric',
            ]);

            try {

                $pembelian = PembelianTitipan::where('uuid', $uuid)->firstOrFail();
                // Find the item by ID
                $detail = DetailPembelianTitipan::findOrFail($request->id);

                $pembelian->total -= $detail->subtotal; // Subtract the old subtotal
                $pembelian->total += $request->subtotal; // Add the new subtotal


                // Update the detail with new data
                $detail->nama_barang = $request->nama_barang;
                $detail->harga = $request->harga;
                $detail->qty = $request->qty;
                $detail->sisa_siang = $request->sisa_siang;
                $detail->sisa_sore = $request->sisa_sore;
                $detail->sisa_malam = $request->sisa_malam;
                $detail->sisa_akhir = $request->sisa_akhir;
                $detail->subtotal_sisa = $request->subtotal_sisa;
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
