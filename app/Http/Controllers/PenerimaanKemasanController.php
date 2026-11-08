<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanKemasan;
use App\Models\Kemasan;
use App\Models\Supplier;
use App\Models\Gudang;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenerimaanKemasanController extends Controller
{
    public function index()
    {
        $penerimaans = PenerimaanKemasan::with(['supplier', 'kemasan', 'gudang'])->latest()->get();
        $kemasans = Kemasan::all();
        $suppliers = Supplier::all();
        $gudangs = Gudang::all();

        return view('penerimaan_kemasan.index', compact('penerimaans', 'kemasans', 'suppliers', 'gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'required',
            'kemasan_id' => 'required',
            'gudang_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $gudang = Gudang::findOrFail($request->gudang_id);
        $sisa_ruang = $gudang->kapasitas - $gudang->terpakai;
        
        if ($request->jumlah > $sisa_ruang) {
            return back()->with('error', 'TRANSAKSI DITOLAK! Kapasitas ' . $gudang->nama_gudang . ' tidak cukup. Sisa ruang hanya untuk ' . $sisa_ruang . ' Unit/Kg.');
        }

        DB::beginTransaction();
        try {
            $tanggal = str_replace('-', '', $request->tanggal);
            $count = PenerimaanKemasan::whereDate('tanggal', $request->tanggal)->count() + 1;
            $noPenerimaan = 'TRK-' . $tanggal . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            PenerimaanKemasan::create([
                'no_penerimaan' => $noPenerimaan,
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'kemasan_id' => $request->kemasan_id,
                'gudang_id' => $request->gudang_id, 
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            $kemasan = Kemasan::findOrFail($request->kemasan_id);
            $kemasan->stok += $request->jumlah;
            $kemasan->save();

            $gudang->terpakai += $request->jumlah;
            $gudang->save();

            AuditLog::create([
                'user_id'    => Auth::id(),
                'aksi'       => 'Tambah',
                'tabel'      => 'penerimaan_kemasans',
                'keterangan' => 'Mencatat Penerimaan Kemasan: ' . $noPenerimaan . ' (' . $kemasan->nama_kemasan . ' - ' . $request->jumlah . ' unit)',
                'ip_address' => $request->ip(),
            ]);

            DB::commit(); 
            return back()->with('success', 'Transaksi berhasil! Kemasan masuk ke Gudang.');
            
        } catch (\Exception $e) {
            DB::rollback(); 
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}