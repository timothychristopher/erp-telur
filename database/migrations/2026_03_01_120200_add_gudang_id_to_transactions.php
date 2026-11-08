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
    Schema::table('penerimaan_bahans', function (Blueprint $table) {
        $table->foreignId('gudang_id')->nullable()->constrained('gudangs');
    });

    Schema::table('produksis', function (Blueprint $table) {
        // Produk jadi biasanya masuk ke gudang tertentu (misal Freezer)
        $table->foreignId('gudang_id')->nullable()->constrained('gudangs');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
