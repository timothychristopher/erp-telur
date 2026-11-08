<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::all();
        return view('satuan.index', compact('satuans'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_satuan' => 'required|unique:satuans,nama_satuan']);
        
        $satuan = Satuan::create($request->all());

        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Tambah',
            'tabel'      => 'satuans',
            'keterangan' => 'Menambah Satuan: ' . $satuan->nama_satuan,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Satuan berhasil ditambah!');
    }

    public function edit(Satuan $satuan)
    {
        return view('satuan.edit', compact('satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => 'required|unique:satuans,nama_satuan,' . $satuan->id,
        ]);

        $satuan->update($request->all());

        // 4. REKAM KE AUDIT LOG (UBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Ubah',
            'tabel'      => 'satuans',
            'keterangan' => 'Mengubah data Satuan menjadi: ' . $satuan->nama_satuan,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('satuan.index')->with('success', 'Data satuan berhasil diperbarui!');
    }

    public function destroy(Request $request, Satuan $satuan)
    {
        $namaSatuan = $satuan->nama_satuan;

        $satuan->delete();

        // REKAM KE AUDIT LOG (HAPUS)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Hapus',
            'tabel'      => 'satuans',
            'keterangan' => 'Menghapus Satuan: ' . $namaSatuan,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Satuan berhasil dihapus!');
    }
}