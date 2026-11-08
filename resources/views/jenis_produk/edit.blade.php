@extends('layouts.app')

@section('konten')
    <div class="mb-4">
        <h2 class="text-dark">Edit Jenis Produk</h2>
        <a href="{{ route('jenis_produk.index') }}" class="text-decoration-none text-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            <form action="{{ route('jenis_produk.update', $jenisProduk->id) }}" method="POST">
                @csrf 
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Jenis Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jenis" class="form-control" value="{{ $jenisProduk->nama_jenis }}" required>
                </div>
                <button type="submit" class="btn text-white mt-2" style="background-color: #fd7e14;">
                    <i class="fas fa-save me-1"></i> Update Data
                </button>
            </form>
        </div>
    </div>
@endsection