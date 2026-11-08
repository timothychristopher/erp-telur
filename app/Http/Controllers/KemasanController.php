<?php
namespace App\Http\Controllers;

use App\Models\Kemasan;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KemasanController extends Controller
{
    public function index() {
        $kemasans = Kemasan::all();
        return view('kemasan.index', compact('kemasans'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_kemasan' => 'required|unique:kemasans',
            'nama_kemasan' => 'required',
            'kapasitas' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
        ]);
        
        $kemasan = Kemasan::create($request->all());

        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Tambah',
            'tabel'      => 'kemasans',
            'keterangan' => 'Menambah Kemasan: ' . $kemasan->nama_kemasan,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Kemasan berhasil ditambahkan!');
    }

    public function edit(Kemasan $kemasan) {
        return view('kemasan.edit', compact('kemasan'));
    }

    public function update(Request $request, Kemasan $kemasan) {
        $request->validate([
            'kode_kemasan' => 'required|unique:kemasans,kode_kemasan,' . $kemasan->id,
            'nama_kemasan' => 'required',
            'kapasitas' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
        ]);
        
        $kemasan->update($request->all());

        // 4. REKAM KE AUDIT LOG (UBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Ubah',
            'tabel'      => 'kemasans',
            'keterangan' => 'Mengubah data Kemasan: ' . $kemasan->nama_kemasan,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('kemasan.index')->with('success', 'Kemasan berhasil diperbarui!');
    }
    public function destroy(Request $request, Kemasan $kemasan) {
        $namaKemasan = $kemasan->nama_kemasan; 

        $kemasan->delete();

        // 5. REKAM KE AUDIT LOG (HAPUS)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Hapus',
            'tabel'      => 'kemasans',
            'keterangan' => 'Menghapus Kemasan: ' . $namaKemasan,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Kemasan berhasil dihapus!');
    }
}