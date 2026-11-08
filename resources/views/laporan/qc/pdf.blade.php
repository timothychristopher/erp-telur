<!DOCTYPE html>
<html>
<head>
    <title>Laporan Quality Control OVOEGG</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #fd7e14; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #fd7e14; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .status-lulus { color: green; font-weight: bold; }
        .status-gagal { color: red; font-weight: bold; }
        .footer { margin-top: 30px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Quality Control Produk</h2>
        <p>PT. Standard Food Globalindo - OVOEGG Division</p>
        <p>Periode: {{ request('tgl_mulai') ?? 'Semua' }} s/d {{ request('tgl_sampai') ?? 'Semua' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Uji</th>
                <th>No. Batch Produksi</th>
                <th>Nama Produk</th>
                <th>Pemeriksa</th>
                <th>Parameter Uji</th>
                <th>Hasil Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($qcs as $index => $q)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($q->tanggal_uji)->format('d/m/Y') }}</td>
                <td>{{ $q->produksi->no_produksi ?? '-' }}</td>
                <td>{{ $q->produksi->produk->nama_produk ?? '-' }}</td>
                <td>{{ $q->nama_pemeriksa }}</td>
                <td>{{ $q->parameter_uji }}</td>
                <td class="{{ $q->status_uji == 'Lulus' ? 'status-lulus' : 'status-gagal' }}">
                    {{ strtoupper($q->status_uji) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Cianjur, {{ date('d F Y') }}</p>
        <p>Manager QC,</p>
        <br><br><br>
        <p><strong>( _________________ )</strong></p>
    </div>
</body>
</html>