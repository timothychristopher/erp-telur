@extends('layouts.app')
@section('konten')
    <div class="mb-4">
        <h2 class="text-dark">Edit Gudang</h2>
        <a href="{{ route('gudang.index') }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            <form action="{{ route('gudang.update', $gudang->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3"><label>Kode Gudang</label><input type="text" name="kode_gudang" class="form-control" value="{{ $gudang->kode_gudang }}" required></div>
                    <div class="col-md-6 mb-3"><label>Nama Gudang</label><input type="text" name="nama_gudang" class="form-control" value="{{ $gudang->nama_gudang }}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Jenis Gudang</label>
                        <select name="jenis_gudang" class="form-select" required>
                            <option value="Cold Storage" {{ $gudang->jenis_gudang == 'Cold Storage' ? 'selected' : '' }}>Cold Storage (Pendingin)</option>
                            <option value="Dry Storage" {{ $gudang->jenis_gudang == 'Dry Storage' ? 'selected' : '' }}>Dry Storage (Kering)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3"><label>Kapasitas</label><input type="number" name="kapasitas" class="form-control" value="{{ $gudang->kapasitas }}" min="0" required></div>
                </div>
                <div class="mb-3"><label>Lokasi</label><textarea name="lokasi" class="form-control" rows="2">{{ $gudang->lokasi }}</textarea></div>
                <button type="submit" class="btn text-white" style="background-color: #fd7e14;"><i class="fas fa-save me-1"></i> Update Data</button>
            </form>
        </div>
    </div>
@endsection