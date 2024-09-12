<?php

namespace App\Http\Controllers;

use App\Models\PenjualanPiutang;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\Vue;

class PenjualanPiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('penjualan.piutang.index');
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
        //
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
