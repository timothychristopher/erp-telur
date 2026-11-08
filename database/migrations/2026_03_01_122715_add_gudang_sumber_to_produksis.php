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
            // Menambah kolom untuk mencatat dari gudang mana bahan dan kemasan diambil
            $table->foreignId('gudang_bahan_id')->nullable()->constrained('gudangs');
            $table->foreignId('gudang_kemasan_id')->nullable()->constrained('gudangs');
        });
    }

    public function down(): void
    {
        Schema::table('produksis', function (Blueprint $table) {
            $table->dropForeign(['gudang_bahan_id']);
            $table->dropForeign(['gudang_kemasan_id']);
            $table->dropColumn(['gudang_bahan_id', 'gudang_kemasan_id']);
        });
    }
};
