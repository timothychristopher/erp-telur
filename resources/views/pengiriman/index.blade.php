@extends('layouts.app')

@section('konten')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Logistik Keluar (Outbound)</h1>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus fa-sm text-white-50"></i> Proses Pengiriman Baru
        </button>
    </div>

    {{-- 1. BAGIAN ALERT BERHASIL --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 2. BAGIAN ALERT GAGAL / WARNING (Kunci yang tadinya hilang) --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-1"></i> <strong>Gagal Memproses!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>No. Pengiriman</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Gudang Asal</th>
                            <th>Penerima & Alamat</th> 
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengirimans as $item)
                        <tr>
                            <td><strong>{{ $item->no_pengiriman }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->gudang->nama_gudang ?? 'Gudang Dihapus' }}</td>
                            <td>
                                <strong>{{ $item->penerima }}</strong><br>
                                <small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $item->alamat_tujuan }}</small>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('pengiriman.pdf', $item->id) }}" class="btn btn-danger btn-sm">
                                    <i class="fas fa-file-pdf"></i> Surat Jalan
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data pengiriman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Form Outbound Logistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pengiriman.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Pilih Produk (Stok Tersedia)</label>
                                <select name="produk_id" class="form-select" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($produks as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_produk }} (Stok: {{ $p->stok }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Jumlah Dikirim</label>
                                <input type="number" name="jumlah" class="form-control" placeholder="0" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold">Gudang Asal</label>
                        <select name="gudang_id" class="form-select" required>
                            @foreach($gudangs as $g)
                                @if(str_contains(strtolower($g->nama_gudang), 'freezer'))
                                    <option value="{{ $g->id }}">{{ $g->nama_gudang }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold">Nama Penerima / Customer / Ekspedisi</label>
                        <input type="text" name="penerima" class="form-control" placeholder="Input nama penerima..." required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold">Alamat Tujuan Pengiriman</label>
                        <textarea name="alamat_tujuan" class="form-control" rows="3" placeholder="Input alamat tujuan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Pengiriman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection