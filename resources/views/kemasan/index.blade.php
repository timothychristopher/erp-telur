@extends('layouts.app')

@section('konten')
    <h2 class="mb-4 text-dark">Master Kemasan</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $kemasanKritis = $kemasans->filter(function($item) {
            return $item->stok <= $item->stok_minimal;
        });
    @endphp

    @if($kemasanKritis->count() > 0)
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4" role="alert">
            <h5 class="alert-heading text-danger mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i> Peringatan Stok Kritis!
            </h5>
            <p class="mb-2">Terdapat <strong>{{ $kemasanKritis->count() }}</strong> kemasan yang stoknya sudah menyentuh batas minimal atau habis. Segera hubungi Supplier untuk restock:</p>
            <ul class="mb-0">
                @foreach($kemasanKritis as $k)
                    <li>
                        <strong>{{ $k->nama_kemasan }}</strong> 
                        (Sisa Stok: <span class="text-danger fw-bold">{{ $k->stok }} Pcs</span> | Batas Min: {{ $k->stok_minimal }})
                    </li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
                <div class="card-body">
                    <h5 class="card-title mb-3">Tambah Kemasan</h5>
                    <form action="{{ route('kemasan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Kode Kemasan</label>
                            <input type="text" name="kode_kemasan" class="form-control" placeholder="Cth: K-001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kemasan</label>
                            <input type="text" name="nama_kemasan" class="form-control" placeholder="Cth: Jerigen Putih" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kapasitas (Liter/Kg)</label>
                            <input type="number" step="0.01" name="kapasitas" class="form-control" placeholder="0.00" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok Minimal (Alert)</label>
                            <input type="number" name="stok_minimal" class="form-control" placeholder="0" min="0" required>
                        </div>
                        <button type="submit" class="btn text-white w-100" style="background-color: #fd7e14;">
                            <i class="fas fa-save me-1"></i> Simpan Kemasan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0 text-secondary"><i class="fas fa-list"></i> Daftar Data</h5>
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Kemasan</th>
                                    <th>Kapasitas</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Stok Min.</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($kemasans as $k)
                                    <tr>
                                        <td class="fw-bold">{{ $k->kode_kemasan }}</td>
                                        <td>{{ $k->nama_kemasan }}</td>
                                        <td>{{ $k->kapasitas }}</td>
                                        <td>
                                            <span class="badge {{ $k->stok <= $k->stok_minimal ? 'bg-danger' : 'bg-success' }}">
                                                {{ $k->stok }} Pcs
                                            </span>
                                        </td>
                                        <td>{{ $k->stok_minimal }} Pcs</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('kemasan.edit', $k->id) }}" class="btn btn-sm btn-outline-primary me-2" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('kemasan.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kemasan {{ $k->nama_kemasan }}?')">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada data kemasan.</td>
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
                        if(text.includes(filter)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
@endsection