<!DOCTYPE html>
<html>
<head>
    <title>Surat Jalan - {{ $pengiriman->no_pengiriman }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .footer { margin-top: 30px; width: 100%; }
        .signature { text-align: center; width: 33%; float: left; }
    </style>
</head>
<body>
    <div class="header">
        <h2>CV. STANDARD FOOD GLOBALINDO</h2>
        <p>Laporan Manajemen Logistik Keluar (Outbound)</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="150">No. Pengiriman</td>
            <td>: <strong>{{ $pengiriman->no_pengiriman }}</strong></td>
            <td width="100">Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($pengiriman->tanggal)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>Penerima</td>
            <td>: {{ $pengiriman->penerima }}</td>
            <td>Gudang Asal</td>
            <td>: {{ $pengiriman->gudang->nama_gudang }}</td>
        </tr>
        <tr>
            <td>Alamat Tujuan</td>
            <td colspan="3">: {{ $pengiriman->alamat_tujuan }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>No</th>
                <th>Deskripsi Produk</th>
                <th>Jumlah</th>
                <th>Satuan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $pengiriman->produk->nama_produk }}</td>
                <td>{{ $pengiriman->jumlah }}</td>
                <td>{{ $pengiriman->produk->satuan->nama_satuan ?? 'PCS' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Penerima,</p>
            <br><br><br>
            <p>( ........................ )</p>
        </div>
        <div class="signature">
            <p>Sopir/Ekspedisi,</p>
            <br><br><br>
            <p>( ........................ )</p>
        </div>
        <div class="signature">
            <p>Admin Gudang,</p>
            <br><br><br>
            <p>( {{ Auth::user()->nama }} )</p>
        </div>
    </div>
</body>
</html>