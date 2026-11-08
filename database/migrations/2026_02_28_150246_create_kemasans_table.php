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
        Schema::create('kemasans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kemasan')->unique();
            $table->string('nama_kemasan');
            $table->decimal('kapasitas', 8, 2); // Misal: 1.00 (Liter)
            $table->integer('stok_minimal')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kemasans');
    }
};
