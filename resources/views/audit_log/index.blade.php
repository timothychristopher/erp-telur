@extends('layouts.app')

@section('konten')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark m-0">Jejak Aktivitas (Audit Log)</h3>
        
        <a href="{{ route('audit_log.pdf', ['search' => request('search')]) }}" class="btn btn-danger shadow-sm">
            <i class="fas fa-file-pdf"></i> Ekspor PDF
        </a>
    </div>

    <div class="card shadow-sm border-0" style="border-top: 4px solid #fd7e14 !important;">
        <div class="card-body">
            
            <form action="" method="GET" class="mb-3 d-print-none">
                <div class="input-group" style="max-width: 500px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari keterangan, tabel, aksi, atau nama pengguna..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ url()->current() }}" class="btn btn-outline-danger">Reset</a>
                    @endif
                </div>
            </form>

            <div class="table-responsive print-area">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Modul/Tabel</th>
                            <th>Keterangan</th>
                            <th>Alamat IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="small">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            
                            <td class="fw-bold text-dark">{{ $log->user->nama ?? 'System' }}</td>
                            
                            <td>
                                @if($log->aksi == 'Tambah') <span class="badge bg-success print-badge">TAMBAH</span>
                                @elseif($log->aksi == 'Hapus') <span class="badge bg-danger print-badge">HAPUS</span>
                                @elseif($log->aksi == 'Edit' || $log->aksi == 'Ubah') <span class="badge bg-warning text-dark print-badge">EDIT</span>
                                @else <span class="badge bg-info text-dark print-badge">{{ strtoupper($log->aksi) }}</span>
                                @endif
                            </td>
                            
                            <td><span class="badge bg-secondary print-badge">{{ $log->tabel }}</span></td>
                            
                            <td>{{ $log->keterangan }}</td>
                            
                            <td><small class="text-muted">{{ $log->ip_address }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada aktivitas yang tercatat atau ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-print-none">
                {{ $logs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<style>

    .pagination { margin-bottom: 0; }

</style>
@endsection