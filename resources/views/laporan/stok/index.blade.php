@extends('layouts.app')
@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-boxes text-warning me-2"></i> Laporan Stok Real-Time</h2>
        <a href="{{ route('laporan.stok.pdf') }}" target="_blank" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Cetak Status Stok
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">Bahan Baku</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        @foreach($bahan as $b)
                        <tr>
                            <td class="ps-3">{{ $b->nama_bahan }}</td>
                            <td class="text-end pe-3 fw-bold">{{ $b->stok }} {{ $b->satuan->nama_satuan ?? '' }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">Produk Jadi</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        @foreach($produk as $p)
                        <tr>
                            <td class="ps-3">{{ $p->nama_produk }}</td>
                            <td class="text-end pe-3 fw-bold text-success">{{ $p->stok }} Unit</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">Kemasan</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        @foreach($kemasan as $k)
                        <tr>
                            <td class="ps-3">{{ $k->nama_kemasan }}</td>
                            <td class="text-end pe-3 fw-bold text-info">{{ $k->stok }} Pcs</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection