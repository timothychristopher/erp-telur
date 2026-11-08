<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_produksi', 'tanggal', 
        // 'bahan_baku_id', 'jumlah_bahan', 'gudang_bahan_id', <-- INI KITA HAPUS NANTI
        'kemasan_id', 'jumlah_kemasan', 'gudang_kemasan_id',
        'produk_id', 'hasil_produksi', 'gudang_id', 
        'status'
    ];

    // --- RELASI BARU (Langkah 2) ---
    // Ini fungsi agar tabel Produksi bisa memanggil banyak bahan sekaligus
    public function detailBahan() 
    { 
        return $this->hasMany(ProduksiBahan::class, 'produksi_id'); 
    }

    // --- RELASI LAMA ---
    public function kemasan() { return $this->belongsTo(Kemasan::class); }
    public function produk() { return $this->belongsTo(Produk::class); }

    public function gudangKemasan() { return $this->belongsTo(Gudang::class, 'gudang_kemasan_id'); }
    public function gudang() { return $this->belongsTo(Gudang::class, 'gudang_id'); } 
}