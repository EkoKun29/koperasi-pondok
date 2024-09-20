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
        Schema::create('detail_non_produksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang')->nullable();
            $table->integer('harga');
            $table->string('keterangan')->nullable();
            $table->integer('qty');
            $table->integer('subtotal')->unsigned()->nullable()->default(12);
            $table->uuid('uuid_nonproduksi')->nullable()->index();
            $table->foreign('uuid_nonproduksi')
                  ->references('uuid')
                  ->on('penjualan_non_produksis')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_non_produksis');
    }
};
