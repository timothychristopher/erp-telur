<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Satuan;
use App\Models\AuditLog; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
class BahanBakuController extends Controller
{
    public function index() {
        $bahanBakus = BahanBaku::with('satuan')->get();
        $satuans = Satuan::all();
        return view('bahan_baku.index', compact('bahanBakus', 'satuans'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_bahan' => 'required|unique:bahan_bakus',
            'nama_bahan' => 'required',
            'satuan_id' => 'required',
            'stok_minimum' => 'required|numeric|min:0', 
        ]);
        
        $bahanBaku = BahanBaku::create($request->all());

        // 3. REKAM KE AUDIT LOG (TAMBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Tambah',
            'tabel'      => 'bahan_bakus',
            'keterangan' => 'Menambah Bahan Baku: ' . $bahanBaku->nama_bahan,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Bahan Baku berhasil disimpan!');
    }

    public function edit(BahanBaku $bahanBaku)
    {
        $satuans = Satuan::all();
        return view('bahan_baku.edit', compact('bahanBaku', 'satuans'));
    }

    public function update(Request $request, BahanBaku $bahanBaku)
    {
        $request->validate([
            'kode_bahan' => 'required|unique:bahan_bakus,kode_bahan,' . $bahanBaku->id,
            'nama_bahan' => 'required',
            'satuan_id' => 'required',
            'stok_minimum' => 'required|numeric|min:0', 
        ]);

        $bahanBaku->update($request->all());

        // 4. REKAM KE AUDIT LOG (UBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Ubah',
            'tabel'      => 'bahan_bakus',
            'keterangan' => 'Mengubah data Bahan Baku: ' . $bahanBaku->nama_bahan,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('bahan_baku.index')->with('success', 'Bahan baku berhasil diperbarui!');
    }

    public function destroy(Request $request, BahanBaku $bahanBaku) {
        $namaBahan = $bahanBaku->nama_bahan; 
        
        $bahanBaku->delete();

        // 5. REKAM KE AUDIT LOG (HAPUS)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Hapus',
            'tabel'      => 'bahan_bakus',
            'keterangan' => 'Menghapus Bahan Baku: ' . $namaBahan,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Bahan Baku berhasil dihapus!');
    }
}