@extends('layouts.app')

@section('konten')
    <h2 class="mb-4 text-dark">Master Supplier</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
                <div class="card-body">
                    <h5 class="card-title mb-3">Tambah Supplier</h5>
                    <form action="{{ route('supplier.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Supplier</label>
                            <input type="text" name="nama_supplier" class="form-control" placeholder="Cth: Peternakan Jaya" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" placeholder="0812..." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap..."></textarea>
                        </div>
                        <button type="submit" class="btn text-white w-100" style="background-color: #fd7e14;">
                            <i class="fas fa-save me-1"></i> Simpan Supplier
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
                                    <th>Nama Supplier</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @forelse($suppliers as $s)
                                    <tr>
                                        <td class="fw-bold">{{ $s->nama_supplier }}</td>
                                        <td>{{ $s->telepon ?? '-' }}</td>
                                        <td><small class="text-muted">{{ $s->alamat ?? '-' }}</small></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('supplier.edit', $s->id) }}" class="btn btn-sm btn-outline-primary me-2" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('supplier.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus supplier {{ $s->nama_supplier }}?')">
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
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada data supplier.</td>
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