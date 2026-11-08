<?php
namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index() {
        $produks = Produk::with('jenisProduk')->get();
        $jenisProduks = \App\Models\JenisProduk::all(); 
        return view('produk.index', compact('produks', 'jenisProduks'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode_produk' => 'required|unique:produks',
            'nama_produk' => 'required',
            'jenis_produk_id' => 'required', 
            'stok_minimal' => 'required|numeric|min:0',
        ]);
        
        $produk = Produk::create($request->all());

        // 3. REKAM KE AUDIT LOG (TAMBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Tambah',
            'tabel'      => 'produks',
            'keterangan' => 'Menambah Produk: ' . $produk->nama_produk,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Produk $produk) {
        $jenisProduks = \App\Models\JenisProduk::all();
        return view('produk.edit', compact('produk', 'jenisProduks'));
    }

    public function update(Request $request, Produk $produk) {
        $request->validate([
            'kode_produk' => 'required|unique:produks,kode_produk,' . $produk->id,
            'nama_produk' => 'required',
            'jenis_produk_id' => 'required', 
            'stok_minimal' => 'required|numeric|min:0',
        ]);
        
        $produk->update($request->all());

        // 4. REKAM KE AUDIT LOG (UBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Ubah',
            'tabel'      => 'produks',
            'keterangan' => 'Mengubah data Produk: ' . $produk->nama_produk,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk diperbarui!');
    }

    public function destroy(Request $request, Produk $produk) {
        $namaProduk = $produk->nama_produk;

        $produk->delete();

        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Hapus',
            'tabel'      => 'produks',
            'keterangan' => 'Menghapus Produk: ' . $namaProduk,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Produk berhasil dihapus!');
    }
}