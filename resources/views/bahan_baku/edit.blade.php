@extends('layouts.app')
@section('konten')
    <div class="mb-4">
        <h2 class="text-dark">Edit Bahan Baku</h2>
        <a href="{{ route('bahan_baku.index') }}" class="text-decoration-none text-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            <form action="{{ route('bahan_baku.update', $bahanBaku->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Bahan</label>
                        <input type="text" name="kode_bahan" class="form-control" value="{{ $bahanBaku->kode_bahan }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Bahan</label>
                        <input type="text" name="nama_bahan" class="form-control" value="{{ $bahanBaku->nama_bahan }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Satuan</label>
                        <select name="satuan_id" class="form-select" required>
                            @foreach($satuans as $s)
                                <option value="{{ $s->id }}" {{ $bahanBaku->satuan_id == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_satuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Minimum</label>
                        <input type="number" name="stok_minimum" class="form-control" value="{{ $bahanBaku->stok_minimum }}" min="0" required>
                    </div>
                </div>
                <button type="submit" class="btn text-white mt-3" style="background-color: #fd7e14;">Update Data</button>
            </form>
        </div>
    </div>
@endsection