@extends('layouts.app')

@section('konten')
    <h2 class="mb-4 text-dark">Transaksi Penerimaan Kemasan</h2>

    @if(session('success')) <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}</div> @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #6f42c1 !important;">
                <div class="card-body">
                    <h5 class="mb-3" style="color: #6f42c1;"><i class="fas fa-dolly"></i> Form Penerimaan</h5>
                    <form action="{{ route('penerimaan_kemasan.store') }}" method="POST">
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
                            <label class="form-label">Pilih Kemasan</label>
                            <select name="kemasan_id" class="form-select" required>
                                <option value="">-- Pilih Kemasan --</option>
                                @foreach($kemasans as $k)
                                    <option value="{{ $k->id }}">{{ $k->kode_kemasan }} - {{ $k->nama_kemasan }} (Stok: {{ $k->stok }})</option>
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
                            <label class="form-label">Jumlah Masuk (Pcs)</label>
                            <input type="number" name="jumlah" class="form-control" min="1" placeholder="Masukkan jumlah..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn text-white w-100" style="background-color: #6f42c1;">
                            <i class="fas fa-save me-1"></i> Simpan & Tambah Stok
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-secondary"><i class="fas fa-history"></i> Riwayat Penerimaan</h5>
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari data...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Supplier</th>
                                    <th>Kemasan</th>
                                    <th>Lokasi Gudang</th>
                                    <th>Jumlah Masuk</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($penerimaans as $p)
                                    <tr>
                                        <td><span class="badge bg-dark">{{ $p->no_penerimaan }}</span></td>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                                        <td>{{ $p->supplier->nama_supplier }}</td>
                                        <td class="fw-bold" style="color: #6f42c1;">{{ $p->kemasan->nama_kemasan }}</td>
                                        
                                        <td><span class="badge bg-info text-dark"><i class="fas fa-warehouse"></i> {{ $p->gudang->nama_gudang ?? '-' }}</span></td>

                                        <td><span class="badge bg-success">+ {{ $p->jumlah }} Pcs</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi penerimaan kemasan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#tableBody tr');
            if(searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const filter = this.value.toLowerCase();
                    tableRows.forEach(row => {
                        if (row.querySelector('td[colspan]')) return; 
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(filter) ? '' : 'none';
                    });
                });
            }
        });
    </script>
@endsection