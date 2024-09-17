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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $piutang = PenjualanPiutang::orderBy('uuid', 'desc')->paginate(10);
        } elseif (Auth::user()->role == '1'){
            $usersWithRole1 = User::where('role', '1')->pluck('id');
            $piutang = PenjualanPiutang::whereIn('id_user', $usersWithRole1)
                                    ->orderBy('uuid', 'desc')
                                    ->paginate(10);

        }elseif(Auth::user()->role == '2'){
            $usersWithRole2 = User::where('role', '2')->pluck('id');
            $piutang = PenjualanPiutang::whereIn('id_user', $usersWithRole2)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '3'){
            $usersWithRole3 = User::where('role', '3')->pluck('id');
            $piutang = PenjualanPiutang::whereIn('id_user', $usersWithRole3)->orderBy('uuid', 'desc')->paginate(10);

        }elseif(Auth::user()->role == '4'){
            $usersWithRole4 = User::where('role', '4')->pluck('id');
            $piutang = PenjualanPiutang::whereIn('id_user', $usersWithRole4)->orderBy('uuid', 'desc')->paginate(10);

        }else{
            abort(403, 'Unauthorized action.');
        }
        return view('penjualan.piutang.index',compact('piutang'))->with('i', (request()->input('page', 1) - 1) * 10);
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
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = NamaBarang::all();
        return view('penjualan.piutang.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pembeli' => 'required|string',
            'nama_personil' => 'required|string',
            'shift' => 'required|string',
            'total' => 'required|numeric',
            'data' => 'required|array',
            'data.*.nama_barang' => 'required|string',
            'data.*.harga' => 'required|numeric',
            'data.*.qty' => 'required|integer',
            'data.*.subtotal' => 'required|numeric',
            'data.*.keterangan' => 'required|string',
        ]);
    
        try {
            // Logika penyimpanan data
            $header = PenjualanPiutang::create([
                'no_nota' => $this->generateNota(),
                'id_user' => Auth::user()->id,
                'nama_pembeli' => $request->nama_pembeli,
                'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
                'nama_personil' => $request->nama_personil,
                'shift' => $request->shift,
                'total' => 0, // Set total sementara ke 0
            ]);
    
            $total = 0;
            foreach ($request->data as $item) {
                DetailPenjualanPiutang::create([
                    'uuid_penjualan' => $header->uuid,
                    'nama_barang' => $item['nama_barang'],
                    'harga' => $item['harga'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                    'keterangan' => $item['keterangan'],
                ]);
    
                // Hitung total
                $total += $item['subtotal'];
            }
    
            // Update total pada header
            $header->total = $total;
            $header->save();
    
            // Log jika berhasil
            Log::info('Penjualan berhasil disimpan');
    
            return response()->json(['success' => true]);
    
        } catch (\Exception $e) {
            // Log error detail
            Log::error('Error saving data: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(PenjualanPiutang $penjualanPiutang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenjualanPiutang $penjualanPiutang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenjualanPiutang $penjualanPiutang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenjualanPiutang $penjualanPiutang)
    {
        //
    }
}
