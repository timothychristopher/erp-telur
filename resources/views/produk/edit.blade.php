@extends('layouts.app')
@section('konten')
    <div class="mb-4">
        <h2 class="text-dark">Edit Produk</h2>
        <a href="{{ route('produk.index') }}" class="text-decoration-none text-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            <form action="{{ route('produk.update', $produk->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Produk</label>
                        <input type="text" name="kode_produk" class="form-control" value="{{ $produk->kode_produk }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="Putih Telur" {{ $produk->kategori == 'Putih Telur' ? 'selected' : '' }}>Putih Telur</option>
                            <option value="Kuning Telur" {{ $produk->kategori == 'Kuning Telur' ? 'selected' : '' }}>Kuning Telur</option>
                            <option value="Telur Utuh" {{ $produk->kategori == 'Telur Utuh' ? 'selected' : '' }}>Telur Utuh (Whole Egg)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Minimum</label>
                        <input type="number" name="stok_minimal" class="form-control" value="{{ $produk->stok_minimal }}" min="0" required>
                    </div>
                </div>
                <button type="submit" class="btn text-white mt-3" style="background-color: #fd7e14;">
                    <i class="fas fa-save me-1"></i> Update Produk
                </button>
            </form>
        </div>
    </div>
@endsection