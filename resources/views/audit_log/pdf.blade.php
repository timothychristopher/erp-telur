<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Audit Log</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #777;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .badge {
            font-weight: bold;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Jejak Aktivitas (Audit Log)</h2>
        <p>CV. Standard Food Globalindo</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        @if(request('search'))
            <p><strong>Filter Pencarian:</strong> "{{ request('search') }}"</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Waktu</th>
                <th width="15%">Pengguna</th>
                <th width="8%">Aksi</th>
                <th width="15%">Tabel/Modul</th>
                <th width="37%">Keterangan</th>
                <th width="10%">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $log->user->nama ?? 'System' }}</td>
                <td class="text-center"><strong>{{ strtoupper($log->aksi) }}</strong></td>
                <td>{{ $log->tabel }}</td>
                <td>{{ $log->keterangan }}</td>
                <td class="text-center">{{ $log->ip_address }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px;">
                    Tidak ada data log yang ditemukan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>