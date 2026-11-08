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
        Schema::create('produksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_produksi')->unique();
            $table->date('tanggal');
            
            // Bahan yang dipakai
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus')->cascadeOnDelete();
            $table->integer('jumlah_bahan'); // Berapa Kg telur yang dipecah
            
            // Kemasan yang dipakai
            $table->foreignId('kemasan_id')->constrained('kemasans')->cascadeOnDelete();
            $table->integer('jumlah_kemasan'); // Berapa botol yang dipakai
            
            // Hasil produksinya
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->integer('hasil_produksi'); // Berapa botol Liquid Egg yang jadi
            
            $table->string('status')->default('Selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksis');
    }
};
