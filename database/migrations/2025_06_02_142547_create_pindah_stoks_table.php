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
        Schema::create('pindah_stoks', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal')->nullable()->index();
            $table->string('nomor_surat')->nullable()->index();
            $table->string('dari')->nullable();
            $table->string('ke')->nullable();
            $table->string('yang_memindah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pindah_stoks');
    }
};
