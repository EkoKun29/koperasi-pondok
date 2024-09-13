<?php

namespace App\Http\Controllers;

use App\Models\PenjualanPiutang;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\Vue;
use Illuminate\Support\Facades\Auth;

class PenjualanPiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $piutang = PenjualanPiutang::orderBy('uuid', 'desc')->paginate(10);
        } else{
            $piutang = PenjualanPiutang::where('id_user', Auth::user()->id)->orderBy('uuid', 'desc')->paginate(10);
        }
        return view('penjualan.piutang.index',compact('piutang'))->with('i', (request()->input('page', 1) - 1) * 10);
    }


    private function generateNota()
    {
        $inisial = Auth::user()->inisial;

        // Temukan nota terbaru dengan inisial yang sama order by id desc
        $lastNote = PenjualanPiutang::where('nota', 'like', 'PP' . $inisial . '%')->orderBy('uuid', 'desc')->first();

        if ($lastNote) {
            $parts = explode('-', $lastNote->nota);
            $numericPart = (int)end($parts);
            $numericPart++; // Increment the numeric part
        } else {
            $numericPart = 1; // Start from 1 if no previous records with the same "inisial"
        }

        return 'PP' . $inisial . '-' . $numericPart;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penjualan.piutang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $length = PenjualanPiutang::latest('id')->first();

        if ($length) {
            $length = $length->id+1;
        } else {
            $length = 1;
        }

        $header = PenjualanPiutang::create([
            'no_nota'   => 'PP-' . $length,
            'tanggal'    => date('Y-m-d'),
            'id_user'    => Auth::user()->id,
            'nama_pembeli' => $request->nama_pembeli,
            'nama_koperasi' => 'KAMPUS ' . Auth::user()->role,
            'nama_personil' => $request->nama_personil,
            'shift' => $request->shift,
            'total' => $request->total,
        ]);

        
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
