<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\AuditLog; // <-- 1. IMPORT MODEL AUDIT LOG
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- 2. IMPORT AUTH

class GudangController extends Controller
{
    public function index() {
        $gudangs = Gudang::all();
        return view('gudang.index', compact('gudangs'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_gudang' => 'required|unique:gudangs',
            'nama_gudang' => 'required',
            'jenis_gudang' => 'required',
            'kapasitas' => 'required|numeric|min:0',
        ]);
        
        
        $gudang = Gudang::create($request->all());

    
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Tambah',
            'tabel'      => 'gudangs',
            'keterangan' => 'Menambah Gudang: ' . $gudang->nama_gudang,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Gudang berhasil ditambahkan!');
    }

    public function edit(Gudang $gudang) {
        return view('gudang.edit', compact('gudang'));
    }

    public function update(Request $request, Gudang $gudang) {
        $request->validate([
            'kode_gudang' => 'required|unique:gudangs,kode_gudang,' . $gudang->id,
            'nama_gudang' => 'required',
            'jenis_gudang' => 'required',
            'kapasitas' => 'required|numeric|min:0',
        ]);
        
        $gudang->update($request->all());

       
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Ubah',
            'tabel'      => 'gudangs',
            'keterangan' => 'Mengubah data Gudang: ' . $gudang->nama_gudang,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('gudang.index')->with('success', 'Data gudang berhasil diperbarui!');
    }

    public function destroy(Request $request, Gudang $gudang) {

        $namaGudang = $gudang->nama_gudang; 
        
        $gudang->delete();

        // 5. REKAM KE AUDIT LOG (HAPUS)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Hapus',
            'tabel'      => 'gudangs',
            'keterangan' => 'Menghapus Gudang: ' . $namaGudang,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Gudang berhasil dihapus!');
    }
}