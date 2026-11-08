<?php

namespace App\Http\Controllers;

use App\Models\QualityControl;
use App\Models\Produksi;
use App\Models\Produk;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QualityControlController extends Controller
{
    public function index() {
        $qcs = QualityControl::with('produksi.produk')->latest()->get();
        
        $produksiSudahQC = QualityControl::pluck('produksi_id')->toArray();
        $produksis = Produksi::with('produk')->whereNotIn('id', $produksiSudahQC)->latest()->get();

        return view('quality_control.index', compact('qcs', 'produksis'));
    }

    public function store(Request $request) {
        $request->validate([
            'produksi_id' => 'required|unique:quality_controls,produksi_id',
            'tanggal_uji' => 'required|date',
            'status_uji' => 'required',
        ], [
            'produksi_id.unique' => 'GAGAL! Batch produksi ini sudah pernah diuji QC.'
        ]);

        DB::beginTransaction();
        try {
            $noQc = 'QC-' . date('Ymd') . '-' . rand(100, 999);

            QualityControl::create([
                'no_qc' => $noQc,
                'produksi_id' => $request->produksi_id,
                'tanggal_uji' => $request->tanggal_uji,
                'nama_pemeriksa' => Auth::user()->nama,
                'parameter_uji' => $request->parameter_uji,
                'status_uji' => $request->status_uji,
                'catatan' => $request->catatan,
            ]);

            $produksi = Produksi::findOrFail($request->produksi_id);

            if ($request->status_uji == 'Gagal') {
                $produk = Produk::findOrFail($produksi->produk_id);
                $produk->stok -= $produksi->hasil_produksi;
                $produk->save();

                if ($produksi->gudang_id) {
                    $gudang = \App\Models\Gudang::findOrFail($produksi->gudang_id);
                    $gudang->terpakai = max(0, $gudang->terpakai - $produksi->hasil_produksi);
                    $gudang->save();
                }
            }

            AuditLog::create([
                'user_id'    => Auth::id(),
                'aksi'       => 'Tambah',
                'tabel'      => 'quality_controls',
                'keterangan' => 'Mencatat Hasil QC: ' . $noQc . ' untuk Batch Produksi ' . $produksi->no_produksi . ' (Status: ' . $request->status_uji . ')',
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            $pesan = $request->status_uji == 'Gagal' ? 'QC Gagal! Stok Produk dan Kapasitas Gudang dikurangi.' : 'QC Lulus! Produk siap dijual.';
            return back()->with('success', $pesan);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}