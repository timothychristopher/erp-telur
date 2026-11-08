<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PenerimaanKemasan extends Model
    {
    protected $fillable = ['no_penerimaan', 'tanggal', 'supplier_id', 'kemasan_id', 'gudang_id', 'jumlah', 'keterangan'];
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function kemasan() { return $this->belongsTo(Kemasan::class); }
    public function gudang() { return $this->belongsTo(Gudang::class); }
    }