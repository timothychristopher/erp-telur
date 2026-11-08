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
        Schema::create('produksi_bahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained()->onDelete('cascade');
            $table->foreignId('bahan_baku_id')->constrained('bahan_bakus');
            $table->double('jumlah_pakai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_bahans');
    }
};
