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
        Schema::create('setorans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('nama_koperasi')->nullable();
            $table->string('penyetor')->nullable();
            $table->string('penerima')->nullable();
            $table->integer('nominal')->nullable();
            $table->uuid('uuid')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setorans');
    }
};
