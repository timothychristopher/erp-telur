<?php

namespace App\Http\Controllers;

use App\Models\Pengirimans;
use App\Models\Produk;
use App\Models\Gudang;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;


class PengirimanController extends Controller
{
    public function index() {
        $pengirimans = Pengirimans::with(['produk', 'gudang'])->latest()->get();
        $produks = Produk::where('stok', '>', 0)->get();
        
        // Filter hanya mengambil gudang dengan nama spesifik
        $gudangs = Gudang::where('nama_gudang', 'LIKE', '%Freezer Produk Jadi%')->get();

        return view('pengiriman.index', compact('pengirimans', 'produks', 'gudangs'));
    }
public function store(Request $request) {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
            'gudang_id' => 'required|exists:gudangs,id',
            'penerima' => 'required|string|max:255', // Wajib diisi
            'alamat_tujuan' => 'required|string',    // Wajib diisi
        ], [
            'penerima.required' => 'Nama penerima wajib diisi, Bos!',
            'alamat_tujuan.required' => 'Alamat tujuan jangan dikosongin ya!',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $gudang = Gudang::findOrFail($request->gudang_id);

        // 1. KUNCI VALIDASI GLOBAL: Cek stok master produk
        if ($produk->stok < $request->jumlah) {
            return back()->with('error', "Gagal! Stok global untuk {$produk->nama_produk} tidak mencukupi. Sisa stok global: {$produk->stok}");
        }

        // 2. KUNCI VALIDASI GUDANG SPECIFIK: Cek ketersediaan produk di gudang yang dipilih
        // Catatan: Logika ini mendeteksi jumlah kapasitas produk jadi yang tersimpan di dalam gudang terkait
        if ($gudang->terpakai < $request->jumlah) {
            return back()->with('error', "Gagal! Stok produk di gudang [{$gudang->nama_gudang}] tidak mencukupi untuk pengiriman ini. Tersedia di gudang: {$gudang->terpakai}");
        }

        DB::beginTransaction();
        try {
            $noPengiriman = 'OUT-' . date('Ymd') . '-' . rand(100, 999);

            $pengiriman = Pengirimans::create([
                'no_pengiriman' => $noPengiriman,
                'tanggal' => now(),
                'produk_id' => $request->produk_id,
                'jumlah' => $request->jumlah,
                'gudang_id' => $request->gudang_id,
                'penerima' => $request->penerima,
                'alamat_tujuan' => $request->alamat_tujuan,
            ]);

            // Kurangi Stok Global Produk
            $produk->decrement('stok', $request->jumlah);

            // Kurangi Kapasitas Gudang yang Terpakai secara aman
            $gudang->terpakai = max(0, $gudang->terpakai - $request->jumlah);
            $gudang->save();

            // REKAM KE AUDIT LOG KEAMANAN
            AuditLog::create([
                'user_id' => Auth::id(),
                'aksi' => 'Tambah',
                'tabel' => 'pengirimans',
                'keterangan' => "Mengirim {$request->jumlah} {$produk->nama_produk} ke {$request->penerima} dari gudang {$gudang->nama_gudang} (No: {$noPengiriman})",
                'ip_address' => $request->ip(),
            ]);

            DB::commit();
            return back()->with('success', 'Pengiriman berhasil diproses dan stok telah dikurangi!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

public function printPdf($id)
{
    $pengiriman = Pengirimans::with(['produk', 'gudang'])->findOrFail($id);

    // Tambahkan ip_address agar database tidak error lagi
    AuditLog::create([
        'user_id'    => Auth::id(),
        'aksi'       => 'Unduh Surat Jalan',
        'tabel'      => 'pengirimans',
        'keterangan' => 'User ' . Auth::user()->nama . ' mengunduh Surat Jalan No: ' . $pengiriman->no_pengiriman,
        'ip_address' => request()->ip(), // Mengambil IP Address user otomatis
    ]);

    $data = [
        'title' => 'SURAT JALAN - ' . $pengiriman->no_pengiriman,
        'pengiriman' => $pengiriman,
        'tanggal' => date('d/m/Y')
    ];

    $pdf = Pdf::loadView('pengiriman.pdf', $data);
    
    return $pdf->stream('Surat_Jalan_' . $pengiriman->no_pengiriman . '.pdf');
}
}