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
        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user')->nullable();
            $table->string('produk')->nullable();
            $table->integer('dus')->nullable();
            $table->integer('pcs')->nullable();
            $table->integer('total')->nullable();
            $table->string('shift')->nullable();
            $table->integer('pondok')->nullable();
            $table->integer('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opnames');
    }
};
