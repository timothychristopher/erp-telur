<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quality_controls', function (Blueprint $table) {
            // Tambahkan nilai default agar data lama tidak error
            $table->string('nama_pemeriksa')->default('Admin')->after('tanggal_uji');
        });
    }

    public function down(): void
    {
        Schema::table('quality_controls', function (Blueprint $table) {
            $table->dropColumn('nama_pemeriksa');
        });
    }
};
