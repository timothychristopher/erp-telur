<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // WAJIB ADA UNTUK AMBIL ID USER

class AuditLog extends Model
{
    // Pastikan user_id ada di dalam fillable agar bisa disimpan
    protected $fillable = [
        'user_id', 
        'aksi', 
        'tabel', 
        'keterangan', 
        'ip_address'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Fungsi Perekam
    public static function record($aksi, $tabel, $keterangan)
    {
        // Debug: Pastikan Auth::id() tidak null
        $userId = Auth::id(); 

        return self::create([
            'user_id'    => $userId, // Jika ini null, maka di view akan muncul "System"
            'aksi'       => $aksi,
            'tabel'      => $tabel,
            'keterangan' => $keterangan,
            'ip_address' => request()->ip(),
        ]);
    }
}