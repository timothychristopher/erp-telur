@extends('layouts.app')
@section('konten')
    <h2 class="mb-4"><i class="fas fa-microscope text-success me-2"></i> Laporan QC</h2>
    <div class="card shadow-sm border-0 mb-4" style="border-top: 4px solid #198754;">
        <div class="card-body">
            <form action="{{ route('laporan.qc.index') }}" method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="tgl_sampai" class="form-control" value="{{ request('tgl_sampai') }}">
                </div>
                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-filter"></i> Filter</button>
                    @if(count($qcs) > 0)
                        <a href="{{ route('laporan.qc.pdf', ['tgl_mulai' => request('tgl_mulai'), 'tgl_sampai' => request('tgl_sampai')]) }}" target="_blank" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Batch</th>
                        <th>Nama Produk</th>
                        <th>Hasil Uji</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($qcs as $index => $q)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($q->tanggal_uji)->format('d/m/Y') }}</td>
                        <td>{{ $q->produksi->no_produksi ?? '-' }}</td>
                        <td>{{ $q->produksi->produk->nama_produk ?? '-' }}</td>
                        <td>
                            @if($q->status_uji == 'Lulus')
                                <span class="badge bg-success"><i class="fas fa-check"></i> LULUS</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> GAGAL</span>
                            @endif
                        </td>
                        <td>{{ $q->catatan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection