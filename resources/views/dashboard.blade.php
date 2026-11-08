@extends('layouts.app')

@section('konten')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Operational Command Center</h1>
        <div class="d-none d-sm-inline-block">
            <span class="badge bg-dark p-2" style="font-size: 0.9rem; letter-spacing: 1px;">
                <i class="fas fa-clock me-1 text-orange"></i> 
                <span id="realtime-clock">--:--:-- WIB</span> </span>
        </div>
    </div>

    <div class="row">
        @php
            $cards = [
                ['label' => 'Bahan Baku', 'val' => $total_bahan, 'icon' => 'fa-egg', 'color' => '#fd7e14'],
                ['label' => 'Produk Jadi', 'val' => $total_produk, 'icon' => 'fa-box', 'color' => '#198754'],
                ['label' => 'Total Produksi', 'val' => $total_produksi, 'icon' => 'fa-industry', 'color' => '#0dcaf0'],
                ['label' => 'Total Pengguna', 'val' => $total_user, 'icon' => 'fa-users', 'color' => '#6610f2']
            ];
        @endphp
        @foreach($cards as $c)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: {{ $c['color'] }};">{{ $c['label'] }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $c['val'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas {{ $c['icon'] }} fa-2x" style="color: #e3e6f0;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-chart-line text-orange me-2"></i> Grafik Produksi Mingguan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="produksiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-microscope text-orange me-2"></i> Quality Control Summary</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2" style="height: 250px;">
                        <canvas id="qcPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Lulus ({{ $qc_lulus }})</span>
                        <span class="mr-2 ms-3"><i class="fas fa-circle text-danger"></i> Gagal ({{ $qc_gagal }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-warehouse text-orange me-2"></i> Monitoring Okupansi Gudang</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($gudangs as $g)
                        @php $persen = ($g->kapasitas > 0) ? ($g->terpakai / $g->kapasitas) * 100 : 0; @endphp
                        <div class="col-md-4 mb-3">
                            <div class="p-3 border rounded shadow-sm">
                                <div class="small fw-bold mb-1">{{ $g->nama_gudang }}</div>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height: 12px; border-radius: 6px;">
                                        <div class="progress-bar {{ $persen > 85 ? 'bg-danger' : 'bg-warning' }}" style="width: {{ $persen }}%"></div>
                                    </div>
                                    <span class="ms-2 small fw-bold">{{ round($persen) }}%</span>
                                </div>
                                <div class="mt-2 text-muted small">{{ $g->terpakai }} / {{ $g->kapasitas }} Unit</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Line Chart Produksi
    const ctxLine = document.getElementById('produksiChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($label_grafik) !!},
            datasets: [{
                label: 'Produksi',
                data: {!! json_encode($data_grafik) !!},
                borderColor: '#fd7e14',
                backgroundColor: 'rgba(253, 126, 20, 0.05)',
                borderWidth: 3,
                tension: 0.3,
                fill: true
            }]
        },
        options: { maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    // 2. Pie Chart QC
    const ctxPie = document.getElementById('qcPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Lulus', 'Gagal'],
            datasets: [{
                data: [{{ $qc_lulus }}, {{ $qc_gagal }}],
                backgroundColor: ['#198754', '#dc3545'],
                hoverBackgroundColor: ['#157347', '#bb2d3b'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: { maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '70%' }
    });
</script>

<style>
    .text-orange { color: #fd7e14; }
    .stat-card { border-radius: 15px; transition: all 0.3s; }
    .stat-card:hover { transform: translateY(-7px); box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important; }
    .progress-bar { transition: width 1s ease-in-out; }
</style>

<script>
    function updateClock() {
        const now = new Date();
        
        // Gunakan toLocaleTimeString agar lebih simpel dan akurat untuk detik
        const timeString = now.toLocaleTimeString('id-ID', { 
            timeZone: 'Asia/Jakarta', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false 
        });
        
        // Update elemen HTML (pastikan ID-nya sama dengan yang di atas)
        const clockElement = document.getElementById('realtime-clock');
        if (clockElement) {
            clockElement.textContent = timeString.replace(/\./g, ':') + ' WIB';
        }
    }

    // Jalankan tiap 1 detik
    setInterval(updateClock, 1000);
    updateClock(); // Jalankan langsung tanpa nunggu 1 detik pertama
</script>

@endsection