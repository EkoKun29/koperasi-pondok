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
        Schema::create('detail_penjualan_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang')->nullable();
            $table->integer('harga');
            $table->integer('qty');
            $table->string('keterangan')->nullable();
            $table->integer('subtotal');
            $table->uuid('uuid_penjualan_acara')->nullable()->index();
            $table->foreign('uuid_penjualan_acara')
                  ->references('uuid')
                  ->on('penjualan_acaras')
                  ->onDelete('cascade');
            $table->uuid('uuid')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan_acaras');
    }
};
