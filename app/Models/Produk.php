<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    // Ganti 'kategori' jadi 'jenis_produk_id'
    protected $fillable = ['kode_produk', 'nama_produk', 'jenis_produk_id', 'stok', 'stok_minimal'];

    // Relasi ke tabel jenis_produks
    public function jenisProduk()
    {
        return $this->belongsTo(JenisProduk::class);
    }
}
