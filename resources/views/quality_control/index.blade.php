@extends('layouts.app')

@section('konten')
<h2 class="mb-4 text-dark">Quality Control (Uji Mutu)</h2>

@if(session('success')) 
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
@endif

@if(session('error')) 
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 
@endif

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0" style="border-top: 4px solid #6610f2 !important;">
            <div class="card-body">
                <h5 class="mb-3" style="color: #6610f2;"><i class="fas fa-microscope"></i> Input Hasil Uji</h5>
                <form action="{{ route('qc.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Batch Produksi</label>
                        <select name="produksi_id" class="form-select" required>
                            <option value="">-- Pilih Batch (Hanya yg blm QC) --</option>
                            @foreach($produksis as $p)
                                <option value="{{ $p->id }}">{{ $p->no_produksi }} - {{ $p->produk->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Uji</label>
                            <input type="date" name="tanggal_uji" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Pemeriksa</label>
                            <input type="text" class="form-control bg-light" name="nama_pemeriksa" value="{{ Auth::user()->nama }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Parameter (pH/Bakteri/Warna)</label>
                        <input type="text" name="parameter_uji" class="form-control" placeholder="Cth: pH 6.5, Salmonella Negatif" required>
                        <small class="text-muted">*Wajib diisi untuk standar mutu.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Hasil Keputusan</label>
                        <select name="status_uji" class="form-select" required>
                            <option value="Lulus">✅ Lulus (Siap Jual)</option>
                            <option value="Gagal">❌ Gagal (Reject & Potong Stok)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan / Detail Temuan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Jelaskan kondisi produk secara detail..." required></textarea>
                    </div>

                    <button type="submit" class="btn text-white w-100 mt-2" style="background-color: #6610f2;">
                        <i class="fas fa-save me-1"></i> Simpan Hasil QC
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 text-secondary"><i class="fas fa-history"></i> Riwayat Pemeriksaan Mutu</h5>
                    <div class="input-group" style="width: 250px;">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari data...">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No. QC</th>
                                <th>Produk & Batch</th>
                                <th>Pemeriksa</th>
                                <th>Parameter & Catatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($qcs as $q)
                            <tr>
                                <td>
                                    <span class="badge bg-dark">{{ $q->no_qc }}</span><br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($q->tanggal_uji)->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $q->produksi->produk->nama_produk }}</strong><br>
                                    <small class="text-muted">Batch: {{ $q->produksi->no_produksi }}</small>
                                </td>
                                <td class="text-primary">
                                    <i class="fas fa-user-check"></i> {{ $q->nama_pemeriksa }}
                                </td>
                                <td>
                                    <small><strong>Param:</strong> {{ $q->parameter_uji }}</small><br>
                                    <small class="text-muted"><strong>Ket:</strong> {{ $q->catatan }}</small>
                                </td>
                                <td>
                                    @if($q->status_uji == 'Lulus')
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Lulus</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times"></i> Reject</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data pemeriksaan mutu.</td>
                                </tr>
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