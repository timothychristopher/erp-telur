<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kemasan extends Model
{
    protected $fillable = ['kode_kemasan', 'nama_kemasan', 'kapasitas', 'stok', 'stok_minimal'];
}
