@extends('layouts.app')
@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark mb-0"><i class="fas fa-chart-line text-primary me-2"></i> Laporan Produksi</h2>
    </div>

    <div class="card shadow-sm border-0 mb-4" style="border-top: 4px solid #0d6efd;">
        <div class="card-body">
            <form action="{{ route('laporan.produksi.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">Sampai Tanggal</label>
                    <input type="date" name="tgl_sampai" class="form-control" value="{{ request('tgl_sampai') }}" required>
                </div>
                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Filter Data</button>
                    <a href="{{ route('laporan.produksi.index') }}" class="btn btn-light me-3 border"><i class="fas fa-sync"></i> Reset</a>
                    
                    @if(count($produksis) > 0)
                        <a href="{{ route('laporan.produksi.pdf', ['tgl_mulai' => request('tgl_mulai'), 'tgl_sampai' => request('tgl_sampai')]) }}" target="_blank" class="btn btn-danger me-1">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('laporan.produksi.excel', ['tgl_mulai' => request('tgl_mulai'), 'tgl_sampai' => request('tgl_sampai')]) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>No. Batch</th>
                            <th>Bahan Baku Digunakan</th>
                            <th>Hasil Produk Jadi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produksis as $index => $p)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                                <td class="fw-bold">{{ $p->no_produksi }}</td>
                                <td class="text-start">
                                    <div class="mb-2">
                                        <small class="fw-bold text-primary">Bahan Baku:</small><br>
                                        @foreach($p->detailBahan as $item)
                                            <div class="ps-2">
                                                <i class="fas fa-caret-right text-muted"></i> 
                                                {{ $item->jumlah_pakai }} {{ $item->bahanBaku->nama_bahan ?? 'N/A' }}
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="border-top pt-1 mt-1">
                                        <small class="fw-bold text-primary">Kemasan:</small><br>
                                        <div class="ps-2">
                                            <i class="fas fa-box-open text-muted"></i> 
                                            {{ $p->jumlah_kemasan }} {{ $p->kemasan->nama_kemasan ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success" style="font-size: 0.9rem;">+ {{ $p->hasil_produksi }} {{ $p->produk->nama_produk ?? '' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-muted">Tidak ada data produksi pada rentang tanggal tersebut.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection