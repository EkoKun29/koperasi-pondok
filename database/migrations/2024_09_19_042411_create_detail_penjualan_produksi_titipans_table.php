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
        Schema::create('detail_penjualan_produksi_titipans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang')->nullable();
            $table->integer('harga');
            $table->integer('qty');
            $table->integer('subtotal');
            $table->uuid('uuid_titipan')->nullable()->index();
            $table->foreign('uuid_titipan')
                  ->references('uuid')
                  ->on('penjualan_produksi_titipans')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan_produksi_titipans');
    }
};
