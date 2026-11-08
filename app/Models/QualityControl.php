<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_qc', 'produksi_id', 'tanggal_uji', 
        'nama_pemeriksa',
        'parameter_uji', 'status_uji', 'catatan'
    ];
    
    public function produksi() {
        return $this->belongsTo(Produksi::class);
    }
}