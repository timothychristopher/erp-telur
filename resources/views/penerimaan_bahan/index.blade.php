@extends('layouts.app')

@section('konten')
    <h2 class="mb-4 text-dark">Transaksi Penerimaan Bahan Baku</h2>

    @if(session('success')) <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div> @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #198754 !important;">
                <div class="card-body">
                    <h5 class="mb-3 text-success"><i class="fas fa-truck-loading"></i> Form Penerimaan</h5>
                    <form action="{{ route('penerimaan_bahan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih Bahan Baku</label>
                            <select name="bahan_baku_id" class="form-select" required>
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahanBakus as $bb)
                                    <option value="{{ $bb->id }}">{{ $bb->kode_bahan }} - {{ $bb->nama_bahan }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Simpan di Gudang</label>
                            <select name="gudang_id" class="form-select" required>
                                <option value="">-- Pilih Lokasi Gudang --</option>
                                @foreach($gudangs as $gd)
                                    <option value="{{ $gd->id }}">{{ $gd->nama_gudang }} ({{ $gd->jenis_gudang }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah (Qty)</label>
                            <input type="number" name="jumlah" class="form-control" min="1" placeholder="Masukkan jumlah..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan / Catatan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Cth: Dikirim via truk box..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save me-1"></i> Simpan & Update Stok
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-3 text-secondary"><i class="fas fa-history"></i> Riwayat Penerimaan</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th>Bahan Baku</th>
                                    <th>Lokasi Gudang</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penerimaans as $p)
                                    <tr>
                                        <td><span class="badge bg-dark">{{ $p->no_penerimaan }}</span></td>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $p->supplier->nama_supplier }}</td>
                                        <td class="fw-bold text-primary">{{ $p->bahanBaku->nama_bahan }}</td>
                                        
                                        <td><span class="badge bg-info text-dark"><i class="fas fa-warehouse"></i> {{ $p->gudang->nama_gudang ?? '-' }}</span></td>
                                        
                                        <td><span class="badge bg-success">+ {{ $p->jumlah }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi penerimaan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection