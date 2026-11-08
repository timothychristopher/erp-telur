<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Supplier::all();
        return view('supplier.index', compact('suppliers'));
    }

    public function store(Request $request) {
        $request->validate(['nama_supplier' => 'required']);
        
        $supplier = Supplier::create($request->all());

        // 3. REKAM KE AUDIT LOG (TAMBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Tambah',
            'tabel'      => 'suppliers',
            'keterangan' => 'Menambah Supplier: ' . $supplier->nama_supplier,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Supplier berhasil ditambahkan!');
    }
    
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'telepon' => 'nullable|numeric',
        ]);

        $supplier->update($request->all());

        // 4. REKAM KE AUDIT LOG (UBAH)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Ubah',
            'tabel'      => 'suppliers',
            'keterangan' => 'Mengubah data Supplier: ' . $supplier->nama_supplier,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil diperbarui!');
    }

    public function destroy(Request $request, Supplier $supplier) {
        $namaSupplier = $supplier->nama_supplier;

        $supplier->delete();

        // REKAM KE AUDIT LOG (HAPUS)
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Hapus',
            'tabel'      => 'suppliers',
            'keterangan' => 'Menghapus Supplier: ' . $namaSupplier,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Supplier berhasil dihapus!');
    }
}