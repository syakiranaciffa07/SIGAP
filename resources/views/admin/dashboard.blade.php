@extends('layouts.app')

@section('title', 'Dashboard Admin BPBD')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--bpbd-navy)"><i class="fa-solid fa-chart-line text-warning me-2"></i>Dashboard Admin BPBD</h2>
        <p class="text-muted mb-0">Verifikasi laporan masyarakat dan kelola penugasan petugas lapangan.</p>
    </div>
</div>

<!-- Stats Widgets -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card card-custom p-3 border-start border-4 border-secondary shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">TOTAL LAPORAN</span>
                    <h3 class="fw-bold mb-0 mt-1">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-secondary bg-opacity-10 p-3 rounded-circle text-secondary">
                    <i class="fa-solid fa-folder-open fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-custom p-3 border-start border-4 border-warning shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">MENUNGGU VERIFIKASI</span>
                    <h3 class="fw-bold mb-0 mt-1 text-warning">{{ $stats['masuk'] }}</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                    <i class="fa-solid fa-clock fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-custom p-3 border-start border-4 border-primary shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold">SEDANG DITANGANI</span>
                    <h3 class="fw-bold mb-0 mt-1 text-primary">{{ $stats['ditangani'] }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                    <i class="fa-solid fa-person-digging fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-custom p-3 border-start border-4 border-success shadow-sm">
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

<!-- Pending Verification Table -->
<div class="card card-custom shadow-sm mb-4 border">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-warning"><i class="fa-solid fa-clock me-2"></i>Laporan Menunggu Verifikasi ({{ $pendingReports->count() }})</h5>
    </div>
    <div class="card-body p-0">
        @if($pendingReports->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="fa-solid fa-circle-check text-success fs-3 mb-2"></i>
                <p class="mb-0">Tidak ada laporan yang menunggu verifikasi saat ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4">Tanggal / Pelapor</th>
                            <th>Kategori</th>
                            <th>Detail Laporan</th>
                            <th>Lokasi</th>
                            <th>Tinggi Air</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingReports as $report)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $report->created_at->format('d-m-Y H:i') }}</div>
                                    <small class="text-muted">{{ $report->user ? $report->user->name : 'Anonim' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $report->type === 'banjir' ? 'bg-danger' : 'bg-primary' }}">
                                        {{ $report->type === 'banjir' ? 'Banjir' : 'Infrastruktur' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $report->title }}</div>
                                    <small class="text-muted d-block" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $report->description }}</small>
                                    @if($report->photo)
                                        <a href="{{ asset('storage/' . $report->photo) }}" target="_blank" class="badge bg-light text-dark border text-decoration-none mt-1"><i class="fa-solid fa-image me-1"></i>Lihat Foto</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="text-decoration-none small">
                                        <i class="fa-solid fa-location-dot me-1"></i>{{ round($report->latitude, 4) }}, {{ round($report->longitude, 4) }}
                                    </a>
                                </td>
                                <td>
                                    @if($report->type === 'banjir')
                                        <strong>{{ $report->water_level }} cm</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <form action="{{ route('admin.reports.verify', $report->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success px-3 me-1"><i class="fa-solid fa-check me-1"></i>Verifikasi</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Active/Verified Reports Table -->
<div class="card card-custom shadow-sm border">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-primary"><i class="fa-solid fa-list-check me-2"></i>Daftar Penanganan Laporan Lapangan</h5>
    </div>
    <div class="card-body p-0">
        @if($assignedReports->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="fa-solid fa-info-circle fs-3 mb-2"></i>
                <p class="mb-0">Belum ada laporan aktif atau yang telah diverifikasi.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4">Kategori / Judul</th>
                            <th>Skor / Upvote</th>
                            <th>Status Penanganan</th>
                            <th>Petugas Terkini</th>
                            <th>Tugaskan Petugas</th>
                            <th class="pe-4">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedReports as $report)
                            @php
                                $currentAssignment = $report->assignments->first();
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge {{ $report->type === 'banjir' ? 'bg-danger' : 'bg-primary' }} me-2">
                                            {{ $report->type === 'banjir' ? 'Banjir' : 'Infrastruktur' }}
                                        </span>
                                        <span class="text-muted small">{{ $report->created_at->format('d-m-Y H:i') }}</span>
                                    </div>
                                    <div class="fw-bold text-dark">{{ $report->title }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><i class="fa-solid fa-fire text-warning me-1"></i>{{ $report->priority_score }}</span>
                                    <span class="small text-muted ms-2"><i class="fa-solid fa-arrow-up me-1"></i>{{ $report->upvote_count }}</span>
                                </td>
                                <td>
                                    @if($report->status === 'diverifikasi')
                                        <span class="badge bg-info text-dark">Diverifikasi</span>
                                    @elseif($report->status === 'ditugaskan')
                                        <span class="badge bg-primary">Ditugaskan</span>
                                    @elseif($report->status === 'ditangani')
                                        <span class="badge bg-warning text-dark">Ditangani</span>
                                    @elseif($report->status === 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($currentAssignment)
                                        <div class="fw-semibold text-primary"><i class="fa-solid fa-user-shield me-1"></i>{{ $currentAssignment->officer->name }}</div>
                                        <small class="text-muted text-xs">Sejak: {{ $currentAssignment->assigned_at->format('d-m-Y') }}</small>
                                    @else
                                        <span class="text-danger small fw-semibold"><i class="fa-solid fa-triangle-exclamation me-1"></i>Belum Ditugaskan</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.reports.assign', $report->id) }}" method="POST" class="row g-2 align-items-center">
                                        @csrf
                                        <div class="col-8">
                                            <select name="officer_id" class="form-select form-select-sm" required>
                                                <option value="" disabled selected>Pilih Petugas...</option>
                                                @foreach($officers as $officer)
                                                    <option value="{{ $officer->id }}" {{ ($currentAssignment && $currentAssignment->officer_id == $officer->id) ? 'selected' : '' }}>
                                                        {{ $officer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-sm btn-bpbd w-100 py-1.5"><i class="fa-solid fa-circle-arrow-right"></i></button>
                                        </div>
                                    </form>
                                </td>
                                <td class="pe-4">
                                    <form action="{{ route('admin.reports.status', $report->id) }}" method="POST" class="row g-2 align-items-center">
                                        @csrf
                                        <div class="col-8">
                                            <select name="status" class="form-select form-select-sm" required>
                                                <option value="masuk" {{ $report->status === 'masuk' ? 'selected' : '' }}>Masuk</option>
                                                <option value="diverifikasi" {{ $report->status === 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                                <option value="ditugaskan" {{ $report->status === 'ditugaskan' ? 'selected' : '' }}>Ditugaskan</option>
                                                <option value="ditangani" {{ $report->status === 'ditangani' ? 'selected' : '' }}>Ditangani</option>
                                                <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-sm btn-secondary w-100 py-1.5"><i class="fa-solid fa-floppy-disk"></i></button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
