<?php

namespace App\Http\Controllers;

use App\Models\PenjualanNonProduksi;
use Illuminate\Http\Request;

class PenjualanNonProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('penjualan.nonproduksi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penjualan.nonproduksi.create');
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
    public function show(PenjualanNonProduksi $penjualanNonProduksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenjualanNonProduksi $penjualanNonProduksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenjualanNonProduksi $penjualanNonProduksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenjualanNonProduksi $penjualanNonProduksi)
    {
        //
    }
}
