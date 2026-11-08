<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('role', 'like', "%{$search}%");
        })->get();

        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric|unique:users,nik',
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        $user = User::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
            'status_aktif' => $request->status_aktif ?? true,
        ]);

        AuditLog::record('Tambah', 'Manajemen User', 'Menambahkan user baru: ' . $user->nama);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nik' => 'required|numeric|unique:users,nik,' . $user->id,
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
        ]);

        $data = [
            'nik' => $request->nik,
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
            'status_aktif' => $request->status_aktif,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // CATAT KE AUDIT LOG
        AuditLog::record('Edit', 'Manajemen User', 'Mengubah data user: ' . $user->nama);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if ($user->email === 'admin@ovoegg.com') {
            return back()->with('error', 'AKSES DITOLAK! Akun Super Admin tidak boleh dihapus dari sistem.');
        }

        $namaUser = $user->nama; 
        $user->delete();

        // CATAT KE AUDIT LOG
        AuditLog::record('Hapus', 'Manajemen User', 'Menghapus akun pengguna: ' . $namaUser);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}