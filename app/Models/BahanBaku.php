<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $fillable = ['kode_bahan', 'nama_bahan', 'satuan_id', 'stok_minimum', 'umur_simpan_hari','stok'];

        // Relasi ke Satuan (Satu bahan baku punya satu satuan)
    public function satuan() {
        return $this->belongsTo(Satuan::class);
}
}
