<?php

namespace App\Http\Controllers;

use App\Models\JenisProduk;
use App\Models\AuditLog; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class JenisProdukController extends Controller
{
    public function index()
    {
        $jenisProduks = JenisProduk::all();
        return view('jenis_produk.index', compact('jenisProduks'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_jenis' => 'required|unique:jenis_produks']);
        
        $jenisProduk = JenisProduk::create($request->all());

        // 3. REKAM KE AUDIT LOG (TAMBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Tambah',
            'tabel'      => 'jenis_produks',
            'keterangan' => 'Menambah Jenis Produk: ' . $jenisProduk->nama_jenis,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Jenis produk berhasil ditambahkan!');
    }

    public function edit(JenisProduk $jenisProduk)
    {
        return view('jenis_produk.edit', compact('jenisProduk'));
    }

    public function update(Request $request, JenisProduk $jenisProduk)
    {
        $request->validate(['nama_jenis' => 'required|unique:jenis_produks,nama_jenis,' . $jenisProduk->id]);
        
        $jenisProduk->update($request->all());

        // 4. REKAM KE AUDIT LOG (UBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Ubah',
            'tabel'      => 'jenis_produks',
            'keterangan' => 'Mengubah data Jenis Produk: ' . $jenisProduk->nama_jenis,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('jenis_produk.index')->with('success', 'Jenis produk berhasil diperbarui!');
    }

    public function destroy(Request $request, JenisProduk $jenisProduk)
    {
        $namaJenis = $jenisProduk->nama_jenis;

        $jenisProduk->delete();

        // 5. REKAM KE AUDIT LOG (HAPUS)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Hapus',
            'tabel'      => 'jenis_produks',
            'keterangan' => 'Menghapus Jenis Produk: ' . $namaJenis,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Jenis produk berhasil dihapus!');
    }
}