<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\ProduksiBahan;
use App\Models\BahanBaku;
use App\Models\Kemasan;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    public function index()
    {
        $produksis = Produksi::with(['detailBahan.bahanBaku', 'kemasan', 'produk', 'gudang', 'gudangKemasan'])->latest()->get();
        $bahanBakus = BahanBaku::all();
        $kemasans = Kemasan::all();
        $produks = Produk::all();
        $gudangs = Gudang::all();

        return view('produksi.index', compact('produksis', 'bahanBakus', 'kemasans', 'produks', 'gudangs'));
    }

    public function store(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'bahan_baku_id' => 'required|array',
        'bahan_baku_id.*' => 'required|exists:bahan_bakus,id',
        'jumlah_bahan' => 'required|array',
        'jumlah_bahan.*' => 'required|numeric|min:1',
        'gudang_bahan_id' => 'required|array',
        'kemasan_id' => 'required', 
        'jumlah_kemasan' => 'required|numeric|min:1', 
        'gudang_kemasan_id' => 'required',
        'produk_id' => 'required', 
        'hasil_produksi' => 'required|numeric|min:1', 
        'gudang_id' => 'required',
    ]);

    // 1. VALIDASI STOK BAHAN BAKU BERDASARKAN GUDANG MASING-MASING
    foreach ($request->bahan_baku_id as $key => $bb_id) {
        $jmlInput = $request->jumlah_bahan[$key];
        $gId = $request->gudang_bahan_id[$key];

        $bahanMasuk = \App\Models\PenerimaanBahan::where('bahan_baku_id', $bb_id)->where('gudang_id', $gId)->sum('jumlah');
        $bahanKeluar = \App\Models\ProduksiBahan::where('bahan_baku_id', $bb_id)
                                                ->where('gudang_bahan_id', $gId)
                                                ->sum('jumlah_pakai');
        
        $stokGudang = $bahanMasuk - $bahanKeluar;
        $bahanMaster = BahanBaku::find($bb_id);

        if ($jmlInput > $stokGudang) {
            return back()->with('error', "Gagal! Stok {$bahanMaster->nama_bahan} di gudang tidak cukup. Sisa stok riil: {$stokGudang}");
        }
    }

    // 2. KUNCI BARU: VALIDASI STOK KEMASAN (Mencegah Bypass Kemasan)
    $kemasanMaster = Kemasan::findOrFail($request->kemasan_id);
    if ($request->jumlah_kemasan > $kemasanMaster->stok) {
        return back()->with('error', "Gagal! Stok kemasan {$kemasanMaster->nama_kemasan} tidak mencukupi. Sisa stok saat ini: {$kemasanMaster->stok}");
    }

    // 3. VALIDASI KAPASITAS GUDANG TUJUAN PRODUK JADI
    $gudangTujuan = Gudang::findOrFail($request->gudang_id);
    if ($request->hasil_produksi > ($gudangTujuan->kapasitas - $gudangTujuan->terpakai)) {
        return back()->with('error', 'Kapasitas gudang tujuan penuh!');
    }

    // JIKA SEMUA VALIDASI LOLOS, BARU PROSES DATABASENYA
    DB::beginTransaction();
    try {
        $tanggalString = str_replace('-', '', $request->tanggal);
        $count = Produksi::whereDate('tanggal', $request->tanggal)->count() + 1;
        $noProduksi = 'PROD-' . $tanggalString . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        // Simpan induk produksi
        $produksi = Produksi::create([
            'no_produksi' => $noProduksi,
            'tanggal' => $request->tanggal,
            'kemasan_id' => $request->kemasan_id,
            'jumlah_kemasan' => $request->jumlah_kemasan,
            'gudang_kemasan_id' => $request->gudang_kemasan_id,
            'produk_id' => $request->produk_id,
            'hasil_produksi' => $request->hasil_produksi,
            'gudang_id' => $request->gudang_id,
            'status' => 'Selesai'
        ]);

        $logBahan = [];
        foreach ($request->bahan_baku_id as $key => $bb_id) {
            $jml = $request->jumlah_bahan[$key];
            $gId = $request->gudang_bahan_id[$key];
            
            ProduksiBahan::create([
                'produksi_id' => $produksi->id,
                'bahan_baku_id' => $bb_id,
                'gudang_bahan_id' => $gId, 
                'jumlah_pakai' => $jml
            ]);

            $bModel = BahanBaku::find($bb_id);
            $bModel->decrement('stok', $jml);
            
            $gBahan = Gudang::find($gId);
            $gBahan->terpakai = max(0, $gBahan->terpakai - $jml);
            $gBahan->save();
            
            $logBahan[] = "{$jml} {$bModel->nama_satuan} {$bModel->nama_bahan}"; 
        }

        // Potong stok kemasan global & update kapasitas gudang kemasan asal
        $kemasanMaster->decrement('stok', $request->jumlah_kemasan);
        
        $gKemasan = Gudang::findOrFail($request->gudang_kemasan_id);
        $gKemasan->terpakai = max(0, $gKemasan->terpakai - $request->jumlah_kemasan);
        $gKemasan->save();

        // Tambah stok produk jadi & update kapasitas gudang produk jadi tujuan
        $produk = Produk::findOrFail($request->produk_id);
        $produk->increment('stok', $request->hasil_produksi);
        $gudangTujuan->increment('terpakai', $request->hasil_produksi);

        // Catat ke Audit Log Keamanan
        AuditLog::create([
            'user_id'    => Auth::id(),
            'aksi'       => 'Tambah',
            'tabel'      => 'produksis',
            'keterangan' => "Mencatat Produksi: {$noProduksi} yang menggunakan bahan: " . implode(', ', $logBahan),
            'ip_address' => $request->ip(),
        ]);

        DB::commit();
        return back()->with('success', 'Produksi Berhasil dengan Banyak Bahan!');

    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}
}