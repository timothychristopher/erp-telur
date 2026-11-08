<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanBahan extends Model
{
    use HasFactory;

    // Tambahkan gudang_id di sini biar datanya bisa disimpan!
    protected $fillable = ['no_penerimaan', 'tanggal', 'supplier_id', 'bahan_baku_id', 'gudang_id', 'jumlah', 'keterangan'];

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function bahanBaku() { return $this->belongsTo(BahanBaku::class); }
    public function gudang() { return $this->belongsTo(Gudang::class); } // Relasi ke Gudang
}