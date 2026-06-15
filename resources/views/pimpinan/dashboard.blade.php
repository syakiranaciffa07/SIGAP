@extends('layouts.app')

@section('title', 'Laporan & Statistik Pimpinan')

@section('content')
<!-- Page Header -->
<div class="row align-items-center mb-4">
    <div class="col-md-8">
        <h2 class="fw-bold mb-1" style="color: var(--bpbd-navy)"><i class="fa-solid fa-square-poll-vertical text-warning me-2"></i>Laporan Statistik SiGAPBanjir</h2>
        <p class="text-muted mb-0">Tinjau grafik statistik kebencanaan dan unduh laporan resmi untuk kebutuhan dokumentasi.</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <div class="d-inline-flex gap-2">
            <a href="{{ route('pimpinan.export.excel') }}" class="btn btn-success"><i class="fa-solid fa-file-excel me-2"></i>Ekspor Excel (CSV)</a>
            <a href="{{ route('pimpinan.export.pdf') }}" target="_blank" class="btn btn-danger"><i class="fa-solid fa-file-pdf me-2"></i>Cetak PDF</a>
        </div>
    </div>
</div>

<!-- Stats Summary Grid -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card card-custom p-3 border shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">TOTAL ADUAN</span>
                    <h3 class="fw-bold mb-0 mt-1">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-dark bg-opacity-10 p-3 rounded-circle text-dark">
                    <i class="fa-solid fa-folder-open fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-custom p-3 border shadow-sm border-start border-4 border-danger">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">TIPE BANJIR</span>
                    <h3 class="fw-bold mb-0 mt-1 text-danger">{{ $stats['flood_count'] }}</h3>
                </div>
                <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                    <i class="fa-solid fa-house-flood-water fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-custom p-3 border shadow-sm border-start border-4 border-primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">TIPE INFRASTRUKTUR</span>
                    <h3 class="fw-bold mb-0 mt-1 text-primary">{{ $stats['infra_count'] }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                    <i class="fa-solid fa-road-barrier fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-custom p-3 border shadow-sm border-start border-4 border-success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">SELESAI DITANGANI</span>
                    <h3 class="fw-bold mb-0 mt-1 text-success">{{ $stats['selesai'] }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                    <i class="fa-solid fa-circle-check fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Line Chart: Monthly Trend -->
    <div class="col-lg-6 mb-4">
        <div class="card card-custom p-4 shadow-sm border h-100">
            <h5 class="fw-bold mb-3" style="color: var(--bpbd-navy)"><i class="fa-solid fa-chart-line text-warning me-2"></i>Tren Laporan Bulanan</h5>
            <div style="position: relative; height: 260px;">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart: Category & Bar Chart: Status -->
    <div class="col-lg-3 mb-4">
        <div class="card card-custom p-4 shadow-sm border h-100">
            <h5 class="fw-bold mb-3" style="color: var(--bpbd-navy)"><i class="fa-solid fa-chart-pie text-warning me-2"></i>Kategori Laporan</h5>
            <div style="position: relative; height: 260px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-3 mb-4">
        <div class="card card-custom p-4 shadow-sm border h-100">
            <h5 class="fw-bold mb-3" style="color: var(--bpbd-navy)"><i class="fa-solid fa-chart-bar text-warning me-2"></i>Status Laporan</h5>
            <div style="position: relative; height: 260px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Full Reports Table -->
<div class="card card-custom shadow-sm border">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-database me-2"></i>Arsip Data Laporan Masuk</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-muted">
                    <tr>
                        <th class="ps-4">ID / Tanggal</th>
                        <th>Kategori</th>
                        <th>Judul Laporan</th>
                        <th>Skor Prioritas</th>
                        <th>Tinggi Air</th>
                        <th>Status</th>
                        <th class="pe-4">Petugas Terkini</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-semibold">#{{ $report->id }}</span>
                                <div class="text-muted text-xs">{{ $report->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $report->type === 'banjir' ? 'bg-danger' : 'bg-primary' }}">
                                    {{ $report->type === 'banjir' ? 'Banjir' : 'Infrastruktur' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $report->title }}</div>
                                <small class="text-muted d-block" style="max-width: 350px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $report->description }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><i class="fa-solid fa-fire text-warning me-1"></i>{{ $report->priority_score }}</span>
                            </td>
                            <td>
                                {{ $report->type === 'banjir' ? $report->water_level . ' cm' : '-' }}
                            </td>
                            <td>
                                @if($report->status === 'masuk')
                                    <span class="badge bg-secondary">Masuk</span>
                                @elseif($report->status === 'diverifikasi')
                                    <span class="badge bg-info text-dark">Diverifikasi</span>
                                @elseif($report->status === 'ditugaskan')
                                    <span class="badge bg-primary">Ditugaskan</span>
                                @elseif($report->status === 'ditangani')
                                    <span class="badge bg-warning text-dark">Ditangani</span>
                                @elseif($report->status === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @endif
                            </td>
                            <td class="pe-4 text-primary fw-semibold">
                                @if($report->assignments->isNotEmpty())
                                    <i class="fa-solid fa-user-shield me-1"></i>{{ $report->assignments->first()->officer->name }}
                                @else
                                    <span class="text-muted small fw-normal">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Tren Laporan Bulanan
    const monthlyTrends = @json($monthlyTrends);
    const trendLabels = Object.keys(monthlyTrends);
    const trendValues = Object.values(monthlyTrends);

    new Chart(document.getElementById('monthlyTrendChart'), {
        type: 'line',
        data: {
            labels: trendLabels.length ? trendLabels : ['Belum Ada Data'],
            datasets: [{
                label: 'Jumlah Laporan',
                data: trendValues.length ? trendValues : [0],
                borderColor: '#ff6600',
                backgroundColor: 'rgba(255, 102, 0, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // 2. Kategori Laporan (Banjir vs Infrastruktur)
    const typeData = @json($typeChartData);
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: ['Banjir', 'Infrastruktur'],
            datasets: [{
                data: [typeData.banjir, typeData.infrastruktur],
                backgroundColor: ['#dc3545', '#0d6efd'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // 3. Status Laporan
    const statusData = @json($statusChartData);
    new Chart(document.getElementById('statusChart'), {
        type: 'bar',
        data: {
            labels: ['Masuk', 'Diverifikasi', 'Ditugaskan', 'Ditangani', 'Selesai'],
            datasets: [{
                label: 'Status Laporan',
                data: [
                    statusData.masuk,
                    statusData.diverifikasi,
                    statusData.ditugaskan,
                    statusData.ditangani,
                    statusData.selesai
                ],
                backgroundColor: ['#6c757d', '#0dcaf0', '#0d6efd', '#ffc107', '#198754'],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
