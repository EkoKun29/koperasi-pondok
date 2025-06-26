<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('retur_penjualans', function (Blueprint $table) {
            $table->renameColumn('nota_penjualan', 'nota_barang_masuk');
            $table->renameColumn('tgl_penjualan', 'tgl_barang_masuk');
            $table->renameColumn('nama_konsumen', 'nama_supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retur_penjualans', function (Blueprint $table) {
            $table->renameColumn('nota_barang_masuk', 'nota_penjualan');
            $table->renameColumn('tgl_barang_masuk', 'tgl_penjualan');
            $table->renameColumn('nama_supplier', 'nama_konsumen');
        });
    }
};
