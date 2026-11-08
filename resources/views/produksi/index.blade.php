@extends('layouts.app')

@section('konten')
    <h2 class="mb-4 text-dark">Transaksi Produksi</h2>

    @if(session('success')) <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle"></i> {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div> @endif
    @if(session('error')) <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-triangle"></i> {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div> @endif

    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm border-0" style="border-top: 4px solid #0dcaf0 !important;">
                <div class="card-body">
                    <h5 class="mb-3 text-info"><i class="fas fa-cogs"></i> Form Produksi</h5>
                    <form action="{{ route('produksi.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">1. WAKTU PRODUKSI</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <hr>
                        
                        <div class="mb-2">
                            <label class="form-label text-muted small fw-bold">2. BAHAN BAKU (-)</label>
                            <div id="wrapperBahan">
                                <div class="row-bahan p-2 border rounded mb-2 bg-light">
                                    <select name="bahan_baku_id[]" class="form-select mb-2" required>
                                        <option value="">-- Pilih Bahan Baku --</option>
                                        @foreach($bahanBakus as $bb) 
                                            <option value="{{ $bb->id }}">{{ $bb->nama_bahan }} (Stok: {{ $bb->stok }})</option> 
                                        @endforeach
                                    </select>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text bg-white small">Dari</span>
                                        <select name="gudang_bahan_id[]" class="form-select small" required>
                                            <option value="">-- Gudang --</option>
                                            @foreach($gudangs as $gd) <option value="{{ $gd->id }}">{{ $gd->nama_gudang }}</option> @endforeach
                                        </select>
                                        <input type="number" name="jumlah_bahan[]" class="form-control" min="1" placeholder="Jumlah" required>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-info btn-sm w-100 mt-1" id="btnTambahBahan">
                                <i class="fas fa-plus-circle"></i> Tambah Bahan Baku Lainnya
                            </button>
                        </div>
                        <hr>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">3. KEMASAN (-)</label>
                            <select name="kemasan_id" class="form-select mb-2" required>
                                <option value="">-- Pilih Kemasan --</option>
                                @foreach($kemasans as $km) <option value="{{ $km->id }}">{{ $km->nama_kemasan }} (Stok: {{ $km->stok }})</option> @endforeach
                            </select>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light">Diambil dari</span>
                                <select name="gudang_kemasan_id" class="form-select" required>
                                    <option value="">-- Gudang --</option>
                                    @foreach($gudangs as $gd) <option value="{{ $gd->id }}">{{ $gd->nama_gudang }}</option> @endforeach
                                </select>
                            </div>
                            <input type="number" name="jumlah_kemasan" class="form-control" min="1" placeholder="Jumlah kemasan terpakai" required>
                        </div>
                        <hr>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">4. HASIL PRODUK JADI (+)</label>
                            <select name="produk_id" class="form-select mb-2" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produks as $pr) <option value="{{ $pr->id }}">{{ $pr->nama_produk }}</option> @endforeach
                            </select>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light">Simpan ke</span>
                                <select name="gudang_id" class="form-select" required>
                                    <option value="">-- Gudang Tujuan --</option>
                                    @foreach($gudangs as $gd) <option value="{{ $gd->id }}">{{ $gd->nama_gudang }}</option> @endforeach
                                </select>
                            </div>
                            <input type="number" name="hasil_produksi" class="form-control" min="1" placeholder="Jumlah produk jadi" required>
                        </div>

                        <button type="submit" class="btn btn-info text-white w-100 mt-2">
                            <i class="fas fa-industry me-1"></i> Proses Produksi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-3 text-secondary"><i class="fas fa-history"></i> Riwayat Produksi</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" style="font-size: 0.85rem;">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Dokumen</th>
                                    <th>Detail Bahan & Kemasan</th>
                                    <th>Hasil Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($produksis as $p)
                                    <tr>
                                        <td>
                                            <span class="badge bg-dark">{{ $p->no_produksi }}</span><br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="mb-2">
                                                <small class="fw-bold text-info">Bahan Baku:</small><br>
                                                @foreach($p->detailBahan as $item)
                                                    <span class="text-danger small">
                                                        <i class="fas fa-minus"></i> {{ $item->jumlah_pakai }} {{ $item->bahanBaku->nama_bahan ?? 'N/A' }}
                                                    </span><br>
                                                @endforeach
                                            </div>
                                            
                                            <div class="mt-1 border-top pt-1">
                                                <small class="fw-bold text-info">Kemasan:</small><br>
                                                <span class="text-danger small"><i class="fas fa-minus"></i> {{ $p->jumlah_kemasan }} {{ $p->kemasan->nama_kemasan ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-success fw-bold"><i class="fas fa-plus"></i> {{ $p->hasil_produksi }} {{ $p->produk->nama_produk ?? 'N/A' }}</span><br>
                                            <small class="text-muted">Gudang: {{ $p->gudang->nama_gudang ?? '-' }}</small><br>
                                            <span class="badge bg-success mt-1 small"><i class="fas fa-check"></i> {{ $p->status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada aktivitas produksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btnTambahBahan').addEventListener('click', function() {
            let container = document.getElementById('wrapperBahan');
            let firstRow = container.querySelector('.row-bahan');
            let newRow = firstRow.cloneNode(true);
            
            // Reset nilai input di baris baru
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            
            // Tambahkan tombol hapus untuk baris baru
            let deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'btn btn-danger btn-sm w-100 mt-1 mb-2';
            deleteBtn.innerHTML = '<i class="fas fa-trash"></i> Hapus Baris Ini';
            deleteBtn.onclick = function() { newRow.remove(); };
            
            newRow.appendChild(deleteBtn);
            container.appendChild(newRow);
        });
    </script>
@endsection