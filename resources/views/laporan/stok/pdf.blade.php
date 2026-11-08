<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Real-time OVOEGG</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .section-title { background: #eee; padding: 5px 10px; font-weight: bold; margin-top: 20px; border: 1px solid #ccc; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .text-danger { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">LAPORAN INVENTARIS STOK REAL-TIME</h2>
        <p style="margin:5px 0;">PT. Standard Food Globalindo (OVOEGG)</p>
        <p style="font-size: 10px;">Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="section-title">I. KATEGORI BAHAN BAKU</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Bahan Baku</th>
                <th width="20%">Stok Saat Ini</th>
                <th width="15%">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bahan as $index => $b)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $b->nama_bahan }}</td>
                <td class="text-center fw-bold">{{ $b->stok }}</td>
                <td class="text-center">{{ $b->satuan->nama_satuan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">II. KATEGORI PRODUK JADI (LIQUID EGG)</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Produk</th>
                <th width="20%">Stok Saat Ini</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produk as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $p->nama_produk }}</td>
                <td class="text-center">{{ $p->stok }} Unit</td>
                <td class="text-center">
                    {!! $p->stok <= $p->stok_minimal ? '<span class="text-danger">LOW STOCK</span>' : 'Tersedia' !!}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">III. KATEGORI KEMASAN</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Kemasan</th>
                <th width="20%">Stok Saat Ini</th>
                <th width="15%">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kemasan as $index => $k)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $k->nama_kemasan }}</td>
                <td class="text-center">{{ $k->stok }}</td>
                <td class="text-center">Pcs</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 40px; float: right; width: 250px; text-align: center;">
        <p>Mengetahui,</p>
        <p>Kepala Gudang / Logistik</p>
        <br><br><br>
        <p><strong>( _________________ )</strong></p>
    </div>
</body>
</html>