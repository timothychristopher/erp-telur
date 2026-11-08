<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanBahan;
use App\Models\BahanBaku;
use App\Models\Supplier;
use App\Models\Gudang;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenerimaanBahanController extends Controller
{
    public function index()
    {
        $penerimaans = PenerimaanBahan::with(['supplier', 'bahanBaku', 'gudang'])->latest()->get();
        $bahanBakus = BahanBaku::all();
        $suppliers = Supplier::all();
        $gudangs = Gudang::all(); 

        return view('penerimaan_bahan.index', compact('penerimaans', 'bahanBakus', 'suppliers', 'gudangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier_id' => 'required',
            'bahan_baku_id' => 'required',
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
            $count = PenerimaanBahan::whereDate('tanggal', $request->tanggal)->count() + 1;
            $noPenerimaan = 'TRX-' . $tanggal . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            PenerimaanBahan::create([
                'no_penerimaan' => $noPenerimaan,
                'tanggal' => $request->tanggal,
                'supplier_id' => $request->supplier_id,
                'bahan_baku_id' => $request->bahan_baku_id,
                'gudang_id' => $request->gudang_id,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
            ]);

            $bahan = BahanBaku::findOrFail($request->bahan_baku_id);
            $bahan->stok += $request->jumlah;
            $bahan->save();

            $gudang->terpakai += $request->jumlah;
            $gudang->save();

            AuditLog::create([
                'user_id'    => Auth::id(),
                'aksi'       => 'Tambah',
                'tabel'      => 'penerimaan_bahans',
                'keterangan' => 'Mencatat Penerimaan Bahan: ' . $noPenerimaan . ' (' . $bahan->nama_bahan . ' - ' . $request->jumlah . ' unit)',
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return back()->with('success', 'Transaksi berhasil! Barang masuk ke Gudang.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}