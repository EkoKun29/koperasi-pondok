<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPo;
use Illuminate\Http\Request;

class PengajuanPoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('po.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(PengajuanPo $pengajuanPo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengajuanPo $pengajuanPo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengajuanPo $pengajuanPo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengajuanPo $pengajuanPo)
    {
        //
    }
}
