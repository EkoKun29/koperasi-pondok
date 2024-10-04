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
        Schema::create('buku_piutangs', function (Blueprint $table) {
            $table->id();
            $table->string('konsumen')->nullable();
            $table->date('tanggal');
            $table->string('no_nota');
            $table->integer('sisa_piutang')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_piutangs');
    }
};
