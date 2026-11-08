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
        Schema::create('quality_controls', function (Blueprint $table) {
            $table->id();
            $table->string('no_qc')->unique();
            $table->foreignId('produksi_id')->constrained('produksis')->cascadeOnDelete();
            $table->date('tanggal_uji');
            $table->string('parameter_uji'); // Misal: pH, Kadar Bakteri, Warna
            $table->enum('status_uji', ['Lulus', 'Gagal']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_controls');
    }
};
