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
        Schema::create('detail_pembelian_cashes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang')->nullable();
            $table->integer('harga');
            $table->integer('qty');
            $table->string('cek_barang')->nullable();
            $table->integer('subtotal');
            $table->uuid('uuid_cash')->nullable()->index();
            $table->foreign('uuid_cash')
                  ->references('uuid')
                  ->on('pembelian_cashes')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian_cashes');
    }
};
