<!DOCTYPE html>
<html>
<head>
    <title>Laporan Produksi OVOEGG</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; padding: 0; color: #fd7e14; }
        .header p { margin: 5px 0 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
    </style>
</head>
<body>

    <div class="header">
        <h2>ERP OVOEGG - STANDARD FOOD GLOBALINDO</h2>
        <p>Laporan Rekapitulasi Produksi Liquid Egg</p>
        @if(request('tgl_mulai') && request('tgl_sampai'))
            <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse(request('tgl_mulai'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('tgl_sampai'))->format('d M Y') }}</p>
        @else
            <p><strong>Periode:</strong> Semua Waktu</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>No. Batch</th>
                <th>Bahan Baku Digunakan</th>
                <th>Hasil Produk Jadi</th>
                <th>Gudang Tujuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produksis as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                    <td><strong>{{ $p->no_produksi }}</strong></td>
                    <td class="text-start">
                        <div class="mb-2">
                            <small class="fw-bold text-primary">Bahan Baku:</small><br>
                            @foreach($p->detailBahan as $item)
                                <div class="ps-2">
                                    <i class="fas fa-caret-right text-muted"></i> 
                                    {{ $item->jumlah_pakai }} {{ $item->bahanBaku->nama_bahan ?? 'N/A' }}
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="border-top pt-1 mt-1">
                            <small class="fw-bold text-primary">Kemasan:</small><br>
                            <div class="ps-2">
                                <i class="fas fa-box-open text-muted"></i> 
                                {{ $p->jumlah_kemasan }} {{ $p->kemasan->nama_kemasan ?? 'N/A' }}
                            </div>
                        </div>
                    </td>
                    <td><strong>{{ $p->hasil_produksi }}</strong> {{ $p->produk->nama_produk ?? '-' }}</td>
                    <td>{{ $p->gudang->nama_gudang ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Tidak ada data produksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Cianjur, {{ date('d F Y') }}</p>
        <br><br><br>
        <p><strong>( _________________ )</strong></p>
        <p>Mengetahui, Pimpinan</p>
    </div>

</body>
</html>