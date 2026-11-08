@extends('layouts.app')

@section('konten')
    <div class="mb-4">
        <h2 class="text-dark">Tambah User Baru</h2>
        <a href="{{ route('users.index') }}" class="text-decoration-none text-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke daftar
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIK (Nomor Induk Karyawan) <span class="text-danger">*</span></label>
                        <input type="text" name="nik" class="form-control" required placeholder="Contoh: 100123" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required placeholder="email@perusahaan.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role Akses <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Role --</option>
                            <option value="Admin">Admin</option>
                            <option value="Produksi">Staf Produksi</option>
                            <option value="QC">Staf Quality Control</option>
                            <option value="Gudang">Staf Gudang</option>
                            <option value="Pimpinan">Pimpinan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status_aktif" class="form-select" required>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-secondary me-2">Reset</button>
                    <button type="submit" class="btn text-white" style="background-color: #fd7e14;">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection