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
    Schema::create('bahan_bakus', function (Blueprint $table) {
        $table->id();
        $table->string('kode_bahan')->unique();
        $table->string('nama_bahan');
        $table->foreignId('satuan_id')->constrained('satuans')->onDelete('cascade');
        $table->float('stok_minimum')->unsigned()->default(0);
        $table->integer('umur_simpan_hari')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
