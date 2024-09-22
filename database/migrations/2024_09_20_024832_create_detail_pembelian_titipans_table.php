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
        Schema::create('detail_pembelian_titipans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang')->nullable();
            $table->integer('harga');
            $table->integer('qty');
            $table->integer('sisa_siang')->nullable();
            $table->integer('sisa_sore')->nullable();
            $table->integer('sisa_malam')->nullable();
            $table->integer('sisa_akhir')->nullable();
            $table->integer('subtotal')->unsigned()->nullable()->default(12);
            $table->integer('subtotal_sisa')->nullable();
            $table->uuid('uuid_pembeliantitipan')->nullable()->index();
            $table->foreign('uuid_pembeliantitipan')
                  ->references('uuid')
                  ->on('pembelian_titipans')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian_titipans');
    }
};
