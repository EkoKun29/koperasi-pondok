<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NamaBarang;

class SetoranController extends Controller
{
    
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $setoran = Setoran::orderBy('uuid', 'desc')->paginate(10);
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $setoran = Setoran::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->paginate(10);

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $setoran = Setoran::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $setoran = Setoran::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $setoran = Setoran::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->paginate(10);

        }else{
            abort(403, 'Unauthorized action.');
        }
        $data = NamaBarang::all();
        return view('setoran.index',compact('setoran','data'))->with('i', (request()->input('page', 1) - 1) * 10);
    }


    // private function generateNota()
    // {
    //     $inisial = Auth::user()->role;
    
    //     // Temukan nota terbaru dengan inisial yang sama, urutkan berdasarkan id secara menurun
    //     $lastNote = PenjualanPiutang::where('no_nota', 'like', 'PPK' . $inisial . '%')
    //                                 ->orderBy('id', 'desc')
    //                                 ->first();
    
    //     if ($lastNote) {
    //         // Ekstrak bagian numerik dari no_nota
    //         $parts = explode('-', $lastNote->no_nota);
    //         $numericPart = (int)end($parts);
    //         $numericPart++; // Increment bagian numerik
    //     } else {
    //         $numericPart = 1; // Mulai dari 1 jika tidak ada record sebelumnya
    //     }
    
    //     return 'PPK' . $inisial . '-' . $numericPart;
    // }
    

    // public function create()
    // {
    //     $data = NamaBarang::all();
    //     return view('penjualan.piutang.create', compact('data'));
    // }

    public function store(Request $request)
{
    $request->validate([
        'penyetor' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'nominal' => 'required|numeric|min:0',
    ]);

    $setoran = Setoran::create([
        'id_user' => Auth::user()->id,
        'tanggal' => $request->tanggal,
        'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
        'penyetor' => $request->penyetor,
        'penerima' => $request->penerima,
        'nominal' => $request->nominal,
    ]);

    // Kembalikan UUID untuk redirect
    return redirect()->route('setoran.index')->with('success', 'Setoran berhasil ditambahkan');;
}

// public function storeDetail(Request $request, $uuid)
// {
//     // Log untuk melihat data yang masuk
//     Log::info('Request Data:', $request->all());

//     $request->validate([
//         'barang' => 'required|string',
//         'harga' => 'required|numeric',
//         'qty' => 'required|numeric',
//         'keterangan' => 'required|string',
//         'subtotal' => 'required|numeric',
//     ]);

//     try {
//         // Buat detail baru
//         $detail = DetailPenjualanPiutang::create([
//             'uuid_penjualan' => $uuid,
//             'nama_barang' => $request->barang,
//             'harga' => $request->harga,
//             'qty' => $request->qty,
//             'keterangan' => $request->keterangan,
//             'subtotal' => $request->subtotal,
//         ]);

//         Log::info('Detail Created:', $detail->toArray());

//         // Update total di PenjualanNonProduksi
//         $penjualan = PenjualanPiutang::where('uuid', $uuid)->first();
//         $penjualan->total += $request->subtotal; // Tambah subtotal ke total
//         $penjualan->save();

//         Log::info('Total Updated:', $penjualan->toArray());

//         return response()->json(['success' => true, 'detail' => $detail, 'total' => $penjualan->total]);
//     } catch (\Exception $e) {
//         Log::error('Error storing detail:', ['error' => $e->getMessage()]);
//         return response()->json(['success' => false, 'message' => 'Gagal menyimpan data'], 500);
//     }
// }
    
//     public function show($uuid)
//     {
//         $data = NamaBarang::all();
//         $piutang = PenjualanPiutang::where('uuid', $uuid)->first();
//         if (!$piutang) {
//             return redirect()->back()->with('error', 'Penjualan Piutang tidak ditemukan');
//         }
//         $detail = DetailPenjualanPiutang::where('uuid_penjualan', $piutang->uuid)->get();

//         if ($detail->isEmpty()) {
//             dd('Detail Penjualan Piutang tidak ditemukan');
//         }

//         return view('penjualan.piutang.detail', compact('piutang', 'detail', 'data'));
//     }

//     public function editDetail($id)
//     {
//         $piutang = PenjualanPiutang::where('id', $id)->first();
//         $detail = DetailPenjualanPiutang::where('uuid_penjualan', $piutang->uuid)->get();
//         return view('penjualan.piutang.edit-detail', compact('piutang', 'detail'));
//     }

//     public function updateDetail(Request $request, $id)
// {
//     // Temukan Penjualan Piutang berdasarkan ID
//     $piutang = PenjualanPiutang::findOrFail($id);

//     // Ambil detail penjualan piutang berdasarkan UUID penjualan
//     $details = DetailPenjualanPiutang::where('uuid_penjualan', $piutang->uuid)->get();

//     // Cek apakah detail ada
//     if ($details->isEmpty()) {
//         return redirect()->back()->with('error', 'Detail Penjualan Piutang tidak ditemukan');
//     }

//     $detailId = $request->detail_id; // Ambil ID detail dari request
//     $detail = DetailPenjualanPiutang::findOrFail($detailId); // Temukan detail yang spesifik

//     // Update detail penjualan piutang
//     $detail->nama_barang = $request->barang; 
//     $detail->qty = $request->qty; 
//     $detail->harga = $request->harga; 
//     $detail->keterangan = $request->keterangan; 
//     $detail->subtotal = $request->qty * $request->harga;
//     $detail->save();

//     // Update total penjualan piutang
//     $piutang->total = $details->sum('subtotal'); // Menggunakan koleksi untuk menghitung total
//     $piutang->save();

//     return redirect()->back()->with('success', 'Data berhasil diubah');
// }



    public function DeleteSetoran($uuid)
    {
        $setoran = Setoran::where('uuid', $uuid)->first();
        if ($setoran) {
            $setoran->delete(); // Ini akan memicu cascade delete jika sudah diatur di model dan database
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    // public function DeleteDetailPenjualan($id)
    // {
    //     $detail = DetailPenjualanPiutang::where('id', $id)->first();

    //     // Mengakses data induk berdasarkan ID detail
    //     $penjualanPiutang = $detail->penjualanPiutang;

    //     $penjualanPiutang->total -= $detail->subtotal;
    //     $penjualanPiutang->save();

    //     $detail->delete();

    //     return redirect()->back()->with('success', 'Data berhasil dihapus');
    // }


    public function print($uuid)
    {
        $setoran = Setoran::where('uuid', $uuid)->firstOrFail();
        return view('setoran.print', compact('setoran'));
    }
}
