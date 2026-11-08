<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Standard Food Globalindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f4f6f9; }
        
        /* Tema Sidebar Gelap */
        .sidebar {
            height: 100vh;
            background-color: #2c3e50; 
            color: #c2c7d0;
            padding-top: 20px;
            position: fixed;
            width: 250px;
            overflow-y: auto;
        }
        
        .sidebar .brand {
            color: #fff;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #4f5962;
        }

        .sidebar .menu-header {
            font-size: 0.75rem;
            font-weight: bold;
            color: #8a93a2;
            text-transform: uppercase;
            margin: 20px 20px 10px 20px;
            letter-spacing: 1px;
        }

        .sidebar a {
            color: #c2c7d0;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .sidebar a:hover, .sidebar a.active {
            color: #fff;
            background-color: #fd7e14; 
            border-radius: 0 25px 25px 0;
            margin-right: 15px;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .text-orange { color: #fd7e14; }
    </style>
</head>
<body>

    @php $role = Auth::user()->role ?? ''; @endphp

    <div class="sidebar">
        <div class="brand">
            <i class="fas fa-egg text-orange"></i> ERP OVOEGG
        </div>

        <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a>

        @if(in_array($role, ['Admin', 'Produksi', 'QC', 'Gudang']))
            <div class="menu-header">Master Data</div>
            
            @if(in_array($role, ['Admin']))
                <a href="{{ route('satuan.index') }}" class="{{ request()->is('satuan*') ? 'active' : '' }}"><i class="fas fa-ruler"></i> Satuan</a>
                <a href="{{ route('supplier.index') }}" class="{{ request()->is('supplier*') ? 'active' : '' }}"><i class="fas fa-truck"></i> Supplier</a>
            @endif

            @if(in_array($role, ['Admin', 'QC']))
                <a href="{{ route('jenis_produk.index') }}" class="{{ request()->is('jenis-produk*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Jenis Produk</a>
            @endif

            @if(in_array($role, ['Admin', 'Produksi']))
                <a href="{{ route('bahan_baku.index') }}" class="{{ request()->is('bahan-baku*') ? 'active' : '' }}"><i class="fas fa-egg"></i> Bahan Baku</a>
            @endif

            @if(in_array($role, ['Admin']))
                <a href="{{ route('kemasan.index') }}" class="{{ request()->is('kemasan*') ? 'active' : '' }}"><i class="fas fa-box"></i> Kemasan</a>
            @endif

            @if(in_array($role, ['Admin', 'Gudang']))
                <a href="{{ route('gudang.index') }}" class="{{ request()->is('gudang*') ? 'active' : '' }}"><i class="fas fa-warehouse"></i> Gudang</a>
            @endif

            @if(in_array($role, ['Admin', 'Produksi']))
                <a href="{{ route('produk.index') }}" class="{{ request()->is('produk*') ? 'active' : '' }}"><i class="fas fa-cubes"></i> Produk</a>
            @endif
        @endif

        @if(in_array($role, ['Admin', 'Gudang', 'Produksi', 'QC']))
            <div class="menu-header">Transaksi</div>
            
            @if(in_array($role, ['Admin', 'Gudang']))
                <a href="{{ route('penerimaan_bahan.index') }}" class="{{ request()->is('penerimaan-bahan*') ? 'active' : '' }}"><i class="fas fa-dolly"></i> Penerimaan Bahan</a>
                <a href="{{ route('penerimaan_kemasan.index') }}" class="{{ request()->is('penerimaan-kemasan*') ? 'active' : '' }}"><i class="fas fa-box-open"></i> Penerimaan Kemasan</a>
            @endif

            @if(in_array($role, ['Admin', 'Produksi']))
                <a href="{{ route('produksi.index') }}" class="{{ request()->is('produksi*') ? 'active' : '' }}"><i class="fas fa-industry"></i> Produksi</a>
            @endif

            @if(in_array($role, ['Admin', 'QC']))
                <a href="{{ route('qc.index') }}" class="{{ request()->is('quality-control*') ? 'active' : '' }}"><i class="fas fa-flask"></i> Quality Control</a>
            @endif

            {{-- Pengiriman Barang dipindah ke bawah QC --}}
            @if(in_array($role, ['Admin', 'Gudang']))
                <a href="{{ route('pengiriman.index') }}" class="{{ request()->is('pengiriman*') ? 'active' : '' }}">
                    <i class="fas fa-truck-ramp-box"></i> Pengiriman Barang
                </a>
            @endif
        @endif

        @if(in_array($role, ['Admin', 'Pimpinan', 'Produksi', 'QC', 'Gudang']))
            <div class="menu-header">Laporan</div>
            
            @if(in_array($role, ['Admin', 'Pimpinan', 'Produksi']))
                <a href="{{ route('laporan.produksi.index') }}" class="{{ request()->is('laporan-produksi*') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Laporan Produksi</a>
            @endif

            @if(in_array($role, ['Admin', 'Pimpinan', 'QC']))
                <a href="{{ route('laporan.qc.index') }}" class="{{ request()->is('laporan-qc*') ? 'active' : '' }}"><i class="fas fa-check-double"></i> Laporan QC</a>
            @endif

            @if(in_array($role, ['Admin', 'Pimpinan', 'Gudang']))
                <a href="{{ route('laporan.stok.index') }}" class="{{ request()->is('laporan-stok*') ? 'active' : '' }}"><i class="fas fa-boxes-stacked"></i> Laporan Stok</a>
            @endif
        @endif

        @if($role == 'Admin')
            <div class="menu-header">Pengaturan</div>
            <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}"><i class="fas fa-users"></i> Manajemen User</a>
            <a href="{{ route('audit_log.index') }}" class="{{ request()->is('audit-log*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Audit Log
            </a>
        @endif
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-end align-items-center mb-4 pb-2 border-bottom">
            <span class="me-3 fw-bold text-dark">
                <i class="fas fa-user-circle text-orange" style="font-size: 1.2rem;"></i> 
                {{ Auth::user()->nama ?? 'Guest' }} ({{ Auth::user()->role ?? '' }})
            </span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>

        @yield('konten')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>