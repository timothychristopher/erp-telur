<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\JenisProdukController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\KemasanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\PenerimaanBahanController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\PenerimaanKemasanController;
use App\Http\Controllers\QualityControlController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\PengirimanController; // <-- 1. IMPORT CONTROLLER BARU

// Route untuk Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================================================================
// AREA WAJIB LOGIN
// =========================================================================
Route::middleware('auth')->group(function () {
    
    // DASHBOARD (Semua Role Boleh Akses)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // =========================================================================
    // 1. AREA KHUSUS ADMIN (FULL AKSES MASTER DATA & USER)
    // =========================================================================
    Route::middleware('role:Admin')->group(function () {
        // Manajemen User
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Master Data (Full Akses CRUD)
        Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
        Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
        Route::get('/satuan/{satuan}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
        Route::put('/satuan/{satuan}', [SatuanController::class, 'update'])->name('satuan.update');
        Route::delete('/satuan/{satuan}', [SatuanController::class, 'destroy'])->name('satuan.destroy');

        Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
        Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
        Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
        Route::get('/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');

        Route::get('/kemasan', [KemasanController::class, 'index'])->name('kemasan.index');
        Route::post('/kemasan', [KemasanController::class, 'store'])->name('kemasan.store');
        Route::delete('/kemasan/{kemasan}', [KemasanController::class, 'destroy'])->name('kemasan.destroy');
        Route::get('/kemasan/{kemasan}/edit', [KemasanController::class, 'edit'])->name('kemasan.edit');
        Route::put('/kemasan/{kemasan}', [KemasanController::class, 'update'])->name('kemasan.update');

        // Aksi Berbahaya (Tambah/Edit/Hapus) disembunyikan HANYA untuk Admin
        Route::post('/bahan-baku', [BahanBakuController::class, 'store'])->name('bahan_baku.store');
        Route::delete('/bahan-baku/{bahanBaku}', [BahanBakuController::class, 'destroy'])->name('bahan_baku.destroy');
        Route::get('/bahan-baku/{bahanBaku}/edit', [BahanBakuController::class, 'edit'])->name('bahan_baku.edit');
        Route::put('/bahan-baku/{bahanBaku}', [BahanBakuController::class, 'update'])->name('bahan_baku.update');

        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
        Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');

        Route::post('/jenis-produk', [JenisProdukController::class, 'store'])->name('jenis_produk.store');
        Route::get('/jenis-produk/{jenisProduk}/edit', [JenisProdukController::class, 'edit'])->name('jenis_produk.edit');
        Route::put('/jenis-produk/{jenisProduk}', [JenisProdukController::class, 'update'])->name('jenis_produk.update');
        Route::delete('/jenis-produk/{jenisProduk}', [JenisProdukController::class, 'destroy'])->name('jenis_produk.destroy');

        Route::post('/gudang', [GudangController::class, 'store'])->name('gudang.store');
        Route::get('/gudang/{gudang}/edit', [GudangController::class, 'edit'])->name('gudang.edit');
        Route::put('/gudang/{gudang}', [GudangController::class, 'update'])->name('gudang.update');
        Route::delete('/gudang/{gudang}', [GudangController::class, 'destroy'])->name('gudang.destroy');

        // RUTE AUDIT LOG
        Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit_log.index');
        Route::get('/audit-log/pdf', [AuditLogController::class, 'exportPdf'])->name('audit_log.pdf');
    });

    // =========================================================================
    // 2. AREA READ-ONLY MASTER DATA (Patungan Admin & Staf Terkait)
    // =========================================================================
    
    // Bahan Baku & Produk (Boleh dilihat Admin & Produksi)
    Route::middleware('role:Admin,Produksi')->group(function () {
        Route::get('/bahan-baku', [BahanBakuController::class, 'index'])->name('bahan_baku.index');
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    });

    // Jenis Produk (Boleh dilihat Admin & QC)
    Route::middleware('role:Admin,QC')->group(function () {
        Route::get('/jenis-produk', [JenisProdukController::class, 'index'])->name('jenis_produk.index');
    });

    // Gudang (Boleh dilihat Admin & Gudang)
    Route::middleware('role:Admin,Gudang')->group(function () {
        Route::get('/gudang', [GudangController::class, 'index'])->name('gudang.index');
    });

    // =========================================================================
    // 3. AREA TRANSAKSI (Sesuai Hak Akses)
    // =========================================================================

    // Penerimaan Bahan & Kemasan (Admin & Gudang)
    Route::middleware('role:Admin,Gudang')->group(function () {
        Route::get('/penerimaan-bahan', [PenerimaanBahanController::class, 'index'])->name('penerimaan_bahan.index');
        Route::post('/penerimaan-bahan', [PenerimaanBahanController::class, 'store'])->name('penerimaan_bahan.store');
        Route::get('/penerimaan-kemasan', [PenerimaanKemasanController::class, 'index'])->name('penerimaan_kemasan.index');
        Route::post('/penerimaan-kemasan', [PenerimaanKemasanController::class, 'store'])->name('penerimaan_kemasan.store');
        
        // --- LOGISTIK KELUAR / OUTBOUND (Admin & Gudang) ---
        Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('pengiriman.index');
        Route::post('/pengiriman', [PengirimanController::class, 'store'])->name('pengiriman.store');
        Route::get('/pengiriman/{id}/surat-jalan', [PengirimanController::class, 'printPdf'])->name('pengiriman.pdf');
    });

    // Produksi (Admin & Produksi)
    Route::middleware('role:Admin,Produksi')->group(function () {
        Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi.index');
        Route::post('/produksi', [ProduksiController::class, 'store'])->name('produksi.store');
    });

    // QC (Admin & QC)
    Route::middleware('role:Admin,QC')->group(function () {
        Route::get('/quality-control', [QualityControlController::class, 'index'])->name('qc.index');
        Route::post('/quality-control', [QualityControlController::class, 'store'])->name('qc.store');
    });

    // =========================================================================
    // 4. AREA LAPORAN (Pimpinan, Admin, & Staf Terkait)
    // =========================================================================
    
    Route::middleware('role:Admin,Pimpinan,Produksi')->group(function () {
        Route::get('/laporan-produksi', [LaporanController::class, 'produksiIndex'])->name('laporan.produksi.index');
        Route::get('/laporan-produksi/pdf', [LaporanController::class, 'produksiPdf'])->name('laporan.produksi.pdf');
        Route::get('/laporan-produksi/excel', [LaporanController::class, 'produksiExcel'])->name('laporan.produksi.excel');
    });

    // Laporan QC (Admin, Pimpinan, QC)
    Route::middleware('role:Admin,Pimpinan,QC')->group(function () {
        Route::get('/laporan-qc', [LaporanController::class, 'qcIndex'])->name('laporan.qc.index');
        Route::get('/laporan-qc/pdf', [LaporanController::class, 'qcPdf'])->name('laporan.qc.pdf');
    });

    // Laporan Stok (Admin, Pimpinan, Gudang)
    Route::middleware('role:Admin,Pimpinan,Gudang')->group(function () {
        Route::get('/laporan-stok', [LaporanController::class, 'stokIndex'])->name('laporan.stok.index');
        Route::get('/laporan-stok/pdf', [LaporanController::class, 'stokPdf'])->name('laporan.stok.pdf');
    });

});