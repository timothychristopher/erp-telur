@extends('layouts.app')
@section('konten')
    <div class="mb-4">
        <h2>Edit Kemasan</h2>
        <a href="{{ route('kemasan.index') }}" class="text-secondary text-decoration-none"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            <form action="{{ route('kemasan.update', $kemasan->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Kode</label><input type="text" name="kode_kemasan" class="form-control" value="{{ $kemasan->kode_kemasan }}" required></div>
                    <div class="col-md-6 mb-3"><label>Nama Kemasan</label><input type="text" name="nama_kemasan" class="form-control" value="{{ $kemasan->nama_kemasan }}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Kapasitas</label><input type="number" step="0.01" name="kapasitas" class="form-control" value="{{ $kemasan->kapasitas }}" min="0" required></div>
                    <div class="col-md-6 mb-3"><label>Stok Minimal</label><input type="number" name="stok_minimal" class="form-control" value="{{ $kemasan->stok_minimal }}" min="0" required></div>
                </div>
                <button type="submit" class="btn text-white" style="background-color: #fd7e14;">Update Data</button>
            </form>
        </div>
    </div>
@endsection