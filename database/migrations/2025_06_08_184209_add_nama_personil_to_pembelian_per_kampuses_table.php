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
        Schema::table('pembelian_per_kampuses', function (Blueprint $table) {
            $table->string('nama_personil')->nullable()->after('nama_supplier');
            $table->string('ket_pembayaran')->nullable()->after('nama_personil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_per_kampuses', function (Blueprint $table) {
            //
        });
    }
};
