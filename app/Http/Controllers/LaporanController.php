<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produksi;
use App\Models\QualityControl;
use App\Models\BahanBaku;
use App\Models\Produk;
use App\Models\Kemasan;
use App\Models\AuditLog; // Pastikan ini diimport
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{

    public function produksiIndex(Request $request)
    {
        $query = Produksi::with(['detailBahan.bahanBaku', 'kemasan', 'produk', 'gudang']);
        
        if ($request->tgl_mulai && $request->tgl_sampai) {
            $query->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_sampai]);
        }

        $produksis = $query->latest()->get();
        return view('laporan.produksi.index', compact('produksis'));
    }

    public function produksiPdf(Request $request)
    {
        $query = Produksi::with(['detailBahan.bahanBaku', 'kemasan', 'produk', 'gudang']);
        if ($request->tgl_mulai && $request->tgl_sampai) {
            $query->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_sampai]);
        }
        $produksis = $query->latest()->get();

        AuditLog::record('Download PDF', 'Laporan Produksi', 'Mengunduh laporan produksi format PDF');

        $pdf = Pdf::loadView('laporan.produksi.pdf', compact('produksis'))->setPaper('A4', 'landscape');
        return $pdf->download('Laporan_Produksi_OVOEGG.pdf');
    }

    public function produksiExcel(Request $request)
    {
        $query = Produksi::with(['detailBahan.bahanBaku', 'kemasan', 'produk', 'gudang']);
        if ($request->tgl_mulai && $request->tgl_sampai) {
            $query->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_sampai]);
        }
        $produksis = $query->latest()->get();

        AuditLog::record('Download CSV', 'Laporan Produksi', 'Mengunduh laporan produksi format CSV/Excel');

        $fileName = 'Laporan_Produksi.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function() use($produksis) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Tanggal', 'No Batch', 'Daftar Bahan', 'Produk', 'Gudang']);
            
            foreach ($produksis as $index => $p) {
                $daftarBahan = $p->detailBahan->map(function($item) {
                    return ($item->bahanBaku->nama_bahan ?? 'N/A') . " (" . $item->jumlah_pakai . ")";
                })->implode(', ');

                fputcsv($file, [
                    $index+1, 
                    $p->tanggal, 
                    $p->no_produksi, 
                    $daftarBahan,
                    $p->produk->nama_produk ?? 'N/A', 
                    $p->gudang->nama_gudang ?? 'N/A'
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function qcIndex(Request $request) 
    {
        $query = QualityControl::with(['produksi.produk']);
        if ($request->tgl_mulai && $request->tgl_sampai) {
            $query->whereBetween('tanggal_uji', [$request->tgl_mulai, $request->tgl_sampai]);
        }
        $qcs = $query->latest()->get();
        return view('laporan.qc.index', compact('qcs'));
    }

    public function qcPdf(Request $request) 
    {
        $query = QualityControl::with(['produksi.produk']);
        if ($request->tgl_mulai && $request->tgl_sampai) {
            $query->whereBetween('tanggal_uji', [$request->tgl_mulai, $request->tgl_sampai]);
        }
        $qcs = $query->latest()->get();

        AuditLog::record('Download PDF', 'Laporan QC', 'Mengunduh laporan Quality Control format PDF');

        $pdf = Pdf::loadView('laporan.qc.pdf', compact('qcs'))->setPaper('A4', 'landscape');
        return $pdf->download('Laporan_QC_OVOEGG.pdf');
    }

    public function stokIndex() 
    {
        $bahan = BahanBaku::all();
        $produk = Produk::all();
        $kemasan = Kemasan::all();
        return view('laporan.stok.index', compact('bahan', 'produk', 'kemasan'));
    }

    public function stokPdf() 
    {
        $bahan = BahanBaku::all();
        $produk = Produk::all();
        $kemasan = Kemasan::all();

        AuditLog::record('Download PDF', 'Laporan Stok', 'Mengunduh laporan stok real-time format PDF');

        $pdf = Pdf::loadView('laporan.stok.pdf', compact('bahan', 'produk', 'kemasan'))->setPaper('A4', 'portrait');
        return $pdf->download('Laporan_Stok_Realtime.pdf');
    }
}