@extends('layouts.app')

@section('konten')
    <h2 class="mb-4 text-dark">Master Produk</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $produkKritis = $produks->filter(function($item) {
            return $item->stok <= $item->stok_minimal;
        });
    @endphp

    @if($produkKritis->count() > 0)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 border-start border-warning border-4" role="alert">
            <h5 class="alert-heading text-warning mb-2">
                <i class="fas fa-exclamation-circle me-2"></i> Perhatian: Stok Produk Menipis!
            </h5>
            <p class="mb-2">Produk berikut sudah mencapai batas minimal stok. Segera jadwalkan produksi ulang:</p>
            <ul class="mb-0">
                @foreach($produkKritis as $pk)
                    <li>
                        <strong>{{ $pk->nama_produk }}</strong> 
                        (Sisa: <span class="text-danger fw-bold">{{ $pk->stok }} Unit</span> | Batas Min: {{ $pk->stok_minimal }})
                    </li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        @if(auth()->user()->role == 'Admin')
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
                <div class="card-body">
                    <h5 class="card-title mb-3">Tambah Produk</h5>
                    <form action="{{ route('produk.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control" placeholder="Cth: PRD-001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" placeholder="Cth: Liquid Egg White" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Produk</label>
                            <select name="jenis_produk_id" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach($jenisProduks as $jp)
                                    <option value="{{ $jp->id }}">{{ $jp->nama_jenis }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok Minimal (Alert)</label>
                            <input type="number" name="stok_minimal" class="form-control" min="0" value="0" required>
                        </div>
                        <button type="submit" class="btn text-white w-100" style="background-color: #fd7e14;">
                            <i class="fas fa-save me-1"></i> Simpan Produk
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <div class="{{ auth()->user()->role == 'Admin' ? 'col-md-8' : 'col-md-12' }}">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-secondary"><i class="fas fa-list"></i> Daftar Produk</h5>
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari produk...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Batas Min.</th>
                                    
                                    @if(auth()->user()->role == 'Admin')
                                    <th class="text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($produks as $p)
                                    <tr>
                                        <td class="fw-bold">{{ $p->kode_produk }}</td>
                                        <td>
                                            {{ $p->nama_produk }}<br>
                                            <span class="badge bg-info text-dark small" style="font-size: 0.7rem;">{{ $p->jenisProduk->nama_jenis ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $p->stok <= $p->stok_minimal ? 'bg-danger' : 'bg-success' }}" style="font-size: 0.9rem;">
                                                {{ $p->stok }} Unit
                                            </span>
                                        </td>
                                        <td>{{ $p->stok_minimal }} Unit</td>
                                        
                                        @if(auth()->user()->role == 'Admin')
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('produk.edit', $p->id) }}" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $p->nama_produk }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data produk.</td></tr>
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