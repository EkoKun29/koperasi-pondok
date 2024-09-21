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
        Schema::create('pembelian_titipans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('no_nota')->unique();
            $table->string('nama_koperasi')->nullable();
            $table->string('nama_personil')->nullable();
            $table->string('nama_penitip')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('total');
            $table->uuid('uuid')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_titipans');
    }
};
