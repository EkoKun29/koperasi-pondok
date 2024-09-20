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
        Schema::create('detail_hutang_non_produksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang')->nullable();
            $table->integer('harga');
            $table->integer('qty');
            $table->integer('subtotal')->unsigned()->nullable()->default(12);
            $table->string('check_barang');
            $table->uuid('uuid_hutangnonproduksi')->nullable()->index();
            $table->foreign('uuid_hutangnonproduksi')
                  ->references('uuid')
                  ->on('pembelian_hutang_non_produksis')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_hutang_non_produksis');
    }
};
