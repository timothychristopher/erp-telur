@extends('layouts.app')
@section('konten')
    <div class="mb-4">
        <h2 class="text-dark">Edit Satuan</h2>
        <a href="{{ route('satuan.index') }}" class="text-decoration-none text-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important; max-width: 500px;">
        <div class="card-body">
            <form action="{{ route('satuan.update', $satuan->id) }}" method="POST">
                @csrf 
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Satuan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_satuan" class="form-control" value="{{ $satuan->nama_satuan }}" required>
                </div>
                <button type="submit" class="btn text-white mt-2 w-100" style="background-color: #fd7e14;">
                    <i class="fas fa-save me-1"></i> Update Satuan
                </button>
            </form>
        </div>
    </div>
@endsection