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
        Schema::table('produksis', function (Blueprint $table) {
            // Kita buat kolom lama menjadi nullable (boleh kosong) 
            // agar tidak bentrok dengan sistem detail bahan yang baru
            $table->unsignedBigInteger('bahan_baku_id')->nullable()->change();
            $table->double('jumlah_bahan')->nullable()->change();
            $table->unsignedBigInteger('gudang_bahan_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('produksis', function (Blueprint $table) {
            $table->unsignedBigInteger('bahan_baku_id')->nullable(false)->change();
            $table->double('jumlah_bahan')->nullable(false)->change();
            $table->unsignedBigInteger('gudang_bahan_id')->nullable(false)->change();
        });
    }
};
