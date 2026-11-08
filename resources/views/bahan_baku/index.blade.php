@extends('layouts.app')

@section('konten')
    <h2 class="mb-4 text-dark">Master Bahan Baku</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $bahanKritis = $bahanBakus->filter(function($item) {
            return $item->stok <= $item->stok_minimum;
        });
    @endphp

    @if($bahanKritis->count() > 0)
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4" role="alert">
            <h5 class="alert-heading text-danger mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i> Peringatan Stok Kritis!
            </h5>
            <p class="mb-2">Terdapat <strong>{{ $bahanKritis->count() }}</strong> bahan baku yang stoknya sudah menyentuh batas minimal atau habis. Segera hubungi Supplier untuk restock:</p>
            <ul class="mb-0">
                @foreach($bahanKritis as $bk)
                    <li>
                        <strong>{{ $bk->nama_bahan }}</strong> 
                        (Sisa Stok: <span class="text-danger fw-bold">{{ $bk->stok }} {{ $bk->satuan->nama_satuan ?? '' }}</span> | Batas Min: {{ $bk->stok_minimum }})
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
                    <h5 class="card-title mb-3">Tambah Bahan Baku</h5>
                    <form action="{{ route('bahan_baku.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Kode Bahan</label>
                            <input type="text" name="kode_bahan" class="form-control" placeholder="Cth: BB-001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Bahan</label>
                            <input type="text" name="nama_bahan" class="form-control" placeholder="Cth: Telur Ayam Segar" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Satuan</label>
                            <select name="satuan_id" class="form-select" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuans as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok Minimum (Alert)</label>
                            <input type="number" name="stok_minimum" class="form-control" value="0" min="0" required>
                        </div>
                        <button type="submit" class="btn text-white w-100" style="background-color: #fd7e14;">
                            <i class="fas fa-save me-1"></i> Simpan
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
                                    <th>Nama Bahan</th>
                                    <th>Satuan</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Stok Min.</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($bahanBakus as $bb)
                                    <tr>
                                        <td class="fw-bold">{{ $bb->kode_bahan }}</td>
                                        <td>{{ $bb->nama_bahan }}</td>
                                        <td><span class="badge bg-light text-dark border">{{ $bb->satuan->nama_satuan }}</span></td>
                                        <td>
                                            <span class="badge {{ $bb->stok <= $bb->stok_minimum ? 'bg-danger' : 'bg-success' }}">
                                                {{ $bb->stok }}
                                            </span>
                                        </td>
                                        <td>{{ $bb->stok_minimum }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('bahan_baku.edit', $bb->id) }}" class="btn btn-sm btn-outline-primary me-2" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('bahan_baku.destroy', $bb->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus bahan baku {{ $bb->nama_bahan }}?');">
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
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada data bahan baku.</td>
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