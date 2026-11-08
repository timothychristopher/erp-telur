@extends('layouts.app')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Manajemen User</h2>
        <a href="{{ route('users.create') }}" class="btn text-white" style="background-color: #fd7e14;">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('users.index') }}" method="GET" class="d-flex" style="max-width: 350px; width: 100%;">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari data..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-search"></i>
            </button>
            
            @if(request('search'))
                <a href="{{ route('users.index') }}" class="btn btn-outline-danger ms-2" title="Reset Pencarian">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->nik ?? '-' }}</td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                                <td>
                                    @if($user->status_aktif)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($user->email !== 'admin@ovoegg.com')
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary d-inline" style="cursor: not-allowed;" title="Super Admin tidak bisa dihapus" disabled>
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data user. Silakan tambah user baru!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection