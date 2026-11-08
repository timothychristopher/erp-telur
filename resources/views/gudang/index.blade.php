@extends('layouts.app')
@section('konten')
    <h2 class="mb-4 text-dark">Master Gudang</h2>
    
    @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @if(auth()->user()->role == 'Admin')
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
                <div class="card-body">
                    <h5 class="mb-3">Tambah Gudang</h5>
                    <form action="{{ route('gudang.store') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Kode Gudang</label>
                            <input type="text" name="kode_gudang" class="form-control" placeholder="Cth: GD-01" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Nama Gudang</label>
                            <input type="text" name="nama_gudang" class="form-control" placeholder="Cth: Gudang Baku" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Jenis Gudang</label>
                            <select name="jenis_gudang" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Cold Storage">Cold Storage (Pendingin)</option>
                                <option value="Dry Storage">Dry Storage (Kering)</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Kapasitas (Max)</label>
                            <input type="number" name="kapasitas" class="form-control" min="0" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Lokasi</label>
                            <textarea name="lokasi" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn text-white w-100 mt-2" style="background-color: #fd7e14;">
                            <i class="fas fa-save me-1"></i> Simpan
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
                        <h5 class="mb-0 text-secondary"><i class="fas fa-list"></i> Daftar Data & Kapasitas</h5>
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Cari data...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Gudang</th>
                                    <th>Jenis</th>
                                    <th style="width: 25%;">Kapasitas & Penggunaan</th>
                                    @if(auth()->user()->role == 'Admin')
                                    <th class="text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($gudangs as $g)
                                    <tr>
                                        <td class="fw-bold">{{ $g->kode_gudang }}</td>
                                        <td>{{ $g->nama_gudang }}<br><small class="text-muted">{{ $g->lokasi }}</small></td>
                                        <td>
                                            @if($g->jenis_gudang == 'Cold Storage')
                                                <span class="badge bg-info text-dark"><i class="fas fa-snowflake"></i> {{ $g->jenis_gudang }}</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="fas fa-box"></i> {{ $g->jenis_gudang }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php 
                                                $persen = ($g->kapasitas > 0) ? round(($g->terpakai / $g->kapasitas) * 100) : 0; 
                                                $warna = $persen > 90 ? 'bg-danger' : ($persen > 75 ? 'bg-warning' : 'bg-success');
                                            @endphp
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="text-muted">{{ $g->terpakai }} / {{ $g->kapasitas }} Unit</span>
                                                <span class="fw-bold {{ $persen > 90 ? 'text-danger' : '' }}">{{ $persen }}%</span>
                                            </div>
                                            <div class="progress shadow-sm" style="height: 10px; border-radius: 10px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated {{ $warna }}" role="progressbar" style="width: {{ $persen }}%;"></div>
                                            </div>
                                        </td>
                                        @if(auth()->user()->role == 'Admin')
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('gudang.edit', $g->id) }}" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('gudang.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus gudang {{ $g->nama_gudang }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ auth()->user()->role == 'Admin' ? '5' : '4' }}" class="text-center py-4">Belum ada data gudang.</td></tr>
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