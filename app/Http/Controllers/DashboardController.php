<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Produk;
use App\Models\Produksi;
use App\Models\QualityControl;
use App\Models\User;
use App\Models\Gudang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
    $data['total_bahan']   = BahanBaku::count();
    $data['total_produk']  = Produk::count();
    $data['total_user']    = User::count();
    $data['total_produksi'] = Produksi::count();
    
    // Data Grafik Garis (Produksi)
    $data['label_grafik'] = [];
    $data['data_grafik']  = [];
    for ($i = 6; $i >= 0; $i--) {
        $tgl = date('Y-m-d', strtotime("-$i days"));
        $data['label_grafik'][] = date('d M', strtotime($tgl));
        $data['data_grafik'][]  = Produksi::whereDate('tanggal', $tgl)->count();
    }

    $data['qc_lulus'] = QualityControl::where('status_uji', 'Lulus')->count();
    $data['qc_gagal'] = QualityControl::where('status_uji', 'Gagal')->count();

    $data['recent_produksi'] = Produksi::with('produk')->latest()->take(5)->get();
    $data['gudangs'] = Gudang::all();

    return view('dashboard', $data);
    }
}