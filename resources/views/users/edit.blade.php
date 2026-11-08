@extends('layouts.app')

@section('konten')
    <div class="mb-4">
        <h2 class="text-dark">Edit Data User</h2>
        <a href="{{ route('users.index') }}" class="text-decoration-none text-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke daftar
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf 
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIK <span class="text-danger">*</span></label>
                        <input type="text" name="nik" class="form-control" required value="{{ $user->nik }}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required value="{{ $user->nama }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required value="{{ $user->email }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password <span class="text-muted" style="font-size: 0.8rem;">(Kosongkan jika tidak ingin ganti)</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Ketik password baru jika ingin diubah">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role Akses <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Produksi" {{ $user->role == 'Produksi' ? 'selected' : '' }}>Staf Produksi</option>
                            <option value="QC" {{ $user->role == 'QC' ? 'selected' : '' }}>Staf Quality Control</option>
                            <option value="Gudang" {{ $user->role == 'Gudang' ? 'selected' : '' }}>Staf Gudang</option>
                            <option value="Pimpinan" {{ $user->role == 'Pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status_aktif" class="form-select" required>
                            <option value="1" {{ $user->status_aktif == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $user->status_aktif == 0 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn text-white" style="background-color: #fd7e14;">
                        <i class="fas fa-save"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection