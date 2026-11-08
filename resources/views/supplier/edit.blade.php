@extends('layouts.app')
@section('konten')
    <div class="mb-4">
        <h2 class="text-dark">Edit Supplier</h2>
        <a href="{{ route('supplier.index') }}" class="text-decoration-none text-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Supplier</label>
                    <input type="text" name="nama_supplier" class="form-control" value="{{ $supplier->nama_supplier }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ $supplier->telepon }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ $supplier->alamat }}</textarea>
                </div>
                <button type="submit" class="btn text-white" style="background-color: #fd7e14;">Update Data</button>
            </form>
        </div>
    </div>
@endsection