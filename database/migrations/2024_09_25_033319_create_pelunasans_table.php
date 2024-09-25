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
        Schema::create('pelunasans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user')->nullable();
            $table->string('no_nota')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('penyetor')->nullable();
            $table->string('nota_penjualan_piutang')->nullable();
            $table->date('tanggal_penjualan_piutang')->nullable();
            $table->integer('sisa_piutang_sebelumnya')->nullable();
            $table->integer('cicilan')->nullable();
            $table->integer('tunai')->nullable();
            $table->string('bank')->nullable();
            $table->integer('sisa_piutang_akhir')->nullable();
            $table->uuid('uuid')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelunasans');
    }
};
