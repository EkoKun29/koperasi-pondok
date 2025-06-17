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
        Schema::create('detail_pindah_stoks', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pindah_stok')->nullable()->index();
            $table->string('produk')->nullable()->index();
            $table->integer('qty')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('subtotal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pindah_stoks');
    }
};
