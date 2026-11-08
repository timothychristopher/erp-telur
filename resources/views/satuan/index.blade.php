@extends('layouts.app')
@section('konten')
    <h2 class="mb-4 text-dark">Master Satuan</h2>
    
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
                    <h5 class="mb-3">Tambah Satuan</h5>
                    <form action="{{ route('satuan.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Satuan</label>
                            <input type="text" name="nama_satuan" class="form-control" placeholder="Cth: Kg, Liter, Pcs" required>
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

                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Satuan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @forelse($satuans as $index => $s)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $s->nama_satuan }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('satuan.edit', $s->id) }}" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-edit"></i></a>
                                            
                                            <form action="{{ route('satuan.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus satuan {{ $s->nama_satuan }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada data satuan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
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