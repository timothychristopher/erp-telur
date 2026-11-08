<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom stok di tabel kemasans
        Schema::table('kemasans', function (Blueprint $table) {
            $table->integer('stok')->default(0)->after('kapasitas');
        });

        // Tambah kolom stok di tabel produks
        Schema::table('produks', function (Blueprint $table) {
            $table->integer('stok')->default(0)->after('jenis_produk_id');
        });
    }

    public function down(): void
    {
        Schema::table('kemasans', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
    }
};