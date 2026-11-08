<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduksiBahan extends Model
{
    protected $fillable = ['produksi_id', 'bahan_baku_id', 'jumlah_pakai'];

    // Relasi balik ke Produksi
    public function produksi() {
        return $this->belongsTo(Produksi::class);
    }

    // Relasi ke tabel Bahan Baku agar bisa ambil Nama Bahannya
    public function bahanBaku() {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}