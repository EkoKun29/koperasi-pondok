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
        Schema::create('retur_penjualans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('nota_retur')->nullable();
            $table->string('nota_penjualan')->nullable();
            $table->date('tgl_penjualan')->nullable();
            $table->string('nama_personil')->nullable();
            $table->string('nama_kampus')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('jenis_penjualan')->nullable();
            $table->string('jenis_transaksi')->nullable();
            $table->integer('total')->nullable();
            $table->uuid('uuid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_penjualans');
    }
};
