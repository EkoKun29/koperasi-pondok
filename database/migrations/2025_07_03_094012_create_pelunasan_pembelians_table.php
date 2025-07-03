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
        Schema::create('pelunasan_pembelians', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user')->nullable();
            $table->string('no_nota')->nullable();
            $table->string('nama_supplier')->nullable();
            $table->string('pelunas')->nullable();
            $table->string('nota_pembelian')->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->integer('sisa_piutang_sebelumnya')->nullable();
            $table->integer('cicilan')->nullable();
            $table->integer('transfer')->nullable();
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
        Schema::dropIfExists('pelunasan_pembelians');
    }
};
