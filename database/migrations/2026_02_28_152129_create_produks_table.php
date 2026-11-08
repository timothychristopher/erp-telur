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
    Schema::create('produks', function (Blueprint $table) {
        $table->id();
        $table->string('kode_produk')->unique();
        $table->string('nama_produk');
        $table->foreignId('jenis_produk_id')->constrained('jenis_produks')->onDelete('cascade');
        $table->integer('stok_minimal')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
