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
        Schema::create('detail_penjualan_piutangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang')->nullable();
            $table->integer('harga');
            $table->integer('qty');
            $table->string('keterangan')->nullable();
            $table->integer('subtotal');
            $table->uuid('uuid_penjualan')->nullable()->index();
            $table->foreign('uuid_penjualan')
                  ->references('uuid')
                  ->on('penjualan_piutangs')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan_piutangs');
    }
};
