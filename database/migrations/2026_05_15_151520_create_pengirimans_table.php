<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('pengirimans', function (Blueprint $table) {
        $table->id();
        $table->string('no_pengiriman')->unique();
        $table->date('tanggal');
        $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
        $table->integer('jumlah');
        $table->foreignId('gudang_id')->constrained('gudangs')->onDelete('cascade');
        $table->string('penerima');
        $table->text('alamat_tujuan');
        $table->string('status')->default('Dikirim'); // Dikirim, Selesai
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimans');
    }
};
