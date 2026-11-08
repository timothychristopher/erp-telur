@extends('layouts.app')
@section('konten')
    <h2 class="mb-4 text-dark">Master Jenis Produk</h2>

    @if(session('success')) <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div> @endif

    <div class="row">
        @if(auth()->user()->role == 'Admin')
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #6610f2 !important;">
                <div class="card-body">
                    <h5 class="mb-3" style="color: #6610f2;">Tambah Jenis Produk</h5>
                    <form action="{{ route('jenis_produk.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Jenis</label>
                            <input type="text" name="nama_jenis" class="form-control" placeholder="Cth: Liquid Egg Yolk" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan / Parameter Standar</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Cth: Viskositas standar 200cP..."></textarea>
                        </div>
                        <button type="submit" class="btn text-white w-100" style="background-color: #6610f2;">
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
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Jenis</th>
                                    <th>Keterangan / Standar QC</th>
                                    @if(auth()->user()->role == 'Admin')
                                    <th class="text-center" style="width: 150px;">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jenisProduks ?? [] as $jp)
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $jp->nama_jenis }}</td>
                                        <td>{{ $jp->keterangan ?: '-' }}</td>
                                        @if(auth()->user()->role == 'Admin')
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('jenis_produk.edit', $jp->id) }}" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('jenis_produk.destroy', $jp->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ auth()->user()->role == 'Admin' ? '3' : '2' }}" class="text-center py-4">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection