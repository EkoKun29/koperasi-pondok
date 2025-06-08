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
        Schema::create('detail_barang_masuks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid_barangmasuk')->nullable();
            $table->string('nama_barang')->nullable();
            $table->integer('qty')->nullable();
            $table->string('satuan')->nullable();
            $table->uuid('uuid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_barang_masuks');
    }
};
