<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengirimans extends Model
{
    protected $fillable = ['no_pengiriman', 'tanggal', 'produk_id', 'jumlah', 'gudang_id', 'penerima', 'alamat_tujuan', 'status'];
    public function produk() { return $this->belongsTo(Produk::class); }
    public function gudang() { return $this->belongsTo(Gudang::class); }
}
