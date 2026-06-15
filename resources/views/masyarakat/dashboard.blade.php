@extends('layouts.app')

@section('title', 'Peta Informasi Banjir Palembang')

@section('content')
<div class="row">
    <!-- Header Title -->
    <div class="col-12 mb-4">
        <div class="card card-custom p-4 text-white shadow-lg" style="background-image: linear-gradient(135deg, var(--bpbd-navy) 0%, var(--bpbd-navy-light) 100%);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-1"><i class="fa-solid fa-map-location-dot text-warning me-2"></i>Peta SiGAPBanjir Palembang</h2>
                    <p class="text-white-50 mb-0">Laporkan dan pantau langsung titik banjir serta kerusakan infrastruktur di seluruh wilayah Kota Palembang.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @auth
                        @if(auth()->user()->isMasyarakat())
                            <a href="{{ route('masyarakat.create') }}" class="btn btn-bpbd btn-lg px-4"><i class="fa-solid fa-circle-plus me-2"></i>Laporkan Kejadian</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-bpbd btn-lg px-4"><i class="fa-solid fa-right-to-bracket me-2"></i>Laporkan Kejadian</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Map Column -->
    <div class="col-12 mb-4">
        <div class="card card-custom p-3 shadow-sm border">
            <h5 class="fw-bold mb-3" style="color: var(--bpbd-navy)"><i class="fa-solid fa-earth-asia text-warning me-2"></i>Peta Lokasi Laporan</h5>
            <div id="map"></div>
            <!-- Legend -->
            <div class="d-flex flex-wrap gap-3 mt-3 justify-content-center p-2 bg-light rounded text-xs">
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #dc3545; width: 12px; height: 12px; display: inline-block;"></span> Banjir</span>
                <span class="d-flex align-items-center"><span class="badge rounded-circle p-1 me-1" style="background-color: #0d6efd; width: 12px; height: 12px; display: inline-block;"></span> Infrastruktur</span>
                <span class="d-flex align-items-center"><i class="fa-solid fa-circle text-secondary me-1" style="font-size: 8px;"></i> Masuk</span>
                <span class="d-flex align-items-center"><i class="fa-solid fa-circle text-info me-1" style="font-size: 8px;"></i> Diverifikasi</span>
                <span class="d-flex align-items-center"><i class="fa-solid fa-circle text-primary me-1" style="font-size: 8px;"></i> Ditugaskan</span>
                <span class="d-flex align-items-center"><i class="fa-solid fa-circle text-warning me-1" style="font-size: 8px;"></i> Ditangani</span>
                <span class="d-flex align-items-center"><i class="fa-solid fa-circle text-success me-1" style="font-size: 8px;"></i> Selesai</span>
            </div>
        </div>
    </div>

    <!-- Reports Listing Column -->
    <div class="col-12">
        <div class="card card-custom shadow-sm p-4 border">
            <ul class="nav nav-pills mb-4" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="all-reports-tab" data-bs-toggle="pill" data-bs-target="#all-reports" type="button"><i class="fa-solid fa-list-ul me-2"></i>Semua Laporan ({{ $reports->count() }})</button>
                </li>
                @auth
                    @if(auth()->user()->isMasyarakat())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="my-reports-tab" data-bs-toggle="pill" data-bs-target="#my-reports" type="button"><i class="fa-solid fa-folder-open me-2"></i>Laporan Saya ({{ $myReports->count() }})</button>
                        </li>
                    @endif
                @endauth
            </ul>

            <div class="tab-content" id="reportTabsContent">
                <!-- All Reports -->
                <div class="tab-pane fade show active" id="all-reports" role="tabpanel">
                    @if($reports->isEmpty())
                        <div class="text-center py-5">
                            <i class="fa-solid fa-folder-open text-muted fs-1 mb-3"></i>
                            <p class="text-muted mb-0">Belum ada laporan bencana yang dibuat.</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($reports as $report)
                                <div class="col-md-6 mb-4">
                                    @include('masyarakat._report_card', ['report' => $report])
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- My Reports -->
                @auth
                    @if(auth()->user()->isMasyarakat())
                        <div class="tab-pane fade" id="my-reports" role="tabpanel">
                            @if($myReports->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-folder-open text-muted fs-1 mb-3"></i>
                                    <p class="text-muted mb-0">Anda belum membuat laporan apapun.</p>
                                </div>
                            @else
                                <div class="row">
                                    @foreach($myReports as $report)
                                        <div class="col-md-6 mb-4">
                                            @include('masyarakat._report_card', ['report' => $report])
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize Palembang Map Focus
    const map = L.map('map').setView([-2.990934, 104.756554], 12);
    
    // Add CartoDB Positron TileLayer (beautiful light map style)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Get reports from Blade to JSON
    const reports = @json($reports);

    reports.forEach(function(report) {
        // Color-coding depending on type (Flood = Red/Orange, Infra = Blue/Purple)
        let color = '#0d6efd'; // Default Blue (Infra)
        if (report.type === 'banjir') {
            color = '#dc3545'; // Red (Banjir)
        }

        // Create marker
        const marker = L.circleMarker([report.latitude, report.longitude], {
            radius: 10,
            fillColor: color,
            color: '#ffffff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(map);

        // Badge Status formatting
        let statusBadge = '';
        if (report.status === 'masuk') {
            statusBadge = '<span class="badge bg-secondary">Masuk</span>';
        } else if (report.status === 'diverifikasi') {
            statusBadge = '<span class="badge bg-info text-dark">Diverifikasi</span>';
        } else if (report.status === 'ditugaskan') {
            statusBadge = '<span class="badge bg-primary">Ditugaskan</span>';
        } else if (report.status === 'ditangani') {
            statusBadge = '<span class="badge bg-warning text-dark">Ditangani</span>';
        } else if (report.status === 'selesai') {
            statusBadge = '<span class="badge bg-success">Selesai</span>';
        }

        // Build HTML popup
        let popupHtml = `
            <div style="width: 250px; font-family: 'Inter', sans-serif;">
                <h6 class="fw-bold mb-1" style="color: var(--bpbd-navy);">${report.title}</h6>
                <div class="mb-2">
                    <span class="badge ${report.type === 'banjir' ? 'bg-danger' : 'bg-primary'} me-1">${report.type === 'banjir' ? 'Banjir' : 'Infrastruktur'}</span>
                    ${statusBadge}
                </div>
                ${report.photo ? `<img src="/storage/${report.photo}" class="img-fluid rounded mb-2" style="max-height: 120px; width: 100%; object-fit: cover;">` : ''}
                <p class="text-muted small mb-2">${report.description.substring(0, 100)}...</p>
                ${report.type === 'banjir' ? `<p class="mb-1 small"><strong>Tinggi Air:</strong> ${report.water_level} cm</p>` : ''}
                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                    <span class="small text-muted"><i class="fa-solid fa-arrow-up text-warning me-1"></i>${report.upvote_count} Upvotes</span>
                    <span class="small text-muted"><i class="fa-solid fa-triangle-exclamation text-danger me-1"></i>Skor: ${report.priority_score}</span>
                </div>
            </div>
        `;

        marker.bindPopup(popupHtml);
    });
</script>
@endsection
