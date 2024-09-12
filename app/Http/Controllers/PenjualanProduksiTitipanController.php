<?php

namespace App\Http\Controllers;

use App\Models\PenjualanProduksiTitipan;
use Illuminate\Http\Request;

class PenjualanProduksiTitipanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('penjualan.produksititipan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penjualan.produksititipan.create');
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
    public function show(PenjualanProduksiTitipan $penjualanProduksiTitipan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenjualanProduksiTitipan $penjualanProduksiTitipan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenjualanProduksiTitipan $penjualanProduksiTitipan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenjualanProduksiTitipan $penjualanProduksiTitipan)
    {
        //
    }
}
