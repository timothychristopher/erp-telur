<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nik' => '12345',
            'nama' => 'Admin OVOEGG',
            'email' => env('ADMIN_EMAIL', 'admin@ovoegg.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            'role' => 'Admin',
            'status_aktif' => true,
        ]);
    }
}