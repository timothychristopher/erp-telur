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
            'email' => 'admin@ovoegg.com',
            'password' => Hash::make('admin123'), // Passwordnya nanti ini
            'role' => 'Admin',
            'status_aktif' => true,
        ]);
    }
}