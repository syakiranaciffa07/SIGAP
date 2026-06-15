@extends('layouts.app')

@section('title', 'Tugas Penanganan Lapangan')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card card-custom p-4 bg-dark text-white shadow" style="background-image: linear-gradient(135deg, var(--bpbd-navy) 0%, var(--bpbd-navy-light) 100%); border-bottom: 4px solid var(--bpbd-orange);">
            <h2 class="fw-bold mb-1"><i class="fa-solid fa-list-check text-warning me-2"></i>Daftar Tugas Penanganan Lapangan</h2>
            <p class="text-white-50 mb-0">Berikut adalah daftar bencana atau kerusakan yang ditugaskan kepada Anda untuk ditangani di lapangan.</p>
        </div>
    </div>

    <div class="col-12">
        <div class="card card-custom p-4 shadow-sm border">
            @if($tasks->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-circle-check text-success fs-1 mb-3"></i>
                    <h5 class="fw-bold">Tidak Ada Tugas Aktif</h5>
                    <p class="mb-0">Kerja bagus! Anda tidak memiliki tugas penanganan yang tertunda saat ini.</p>
                </div>
            @else
                <div class="row">
                    @foreach($tasks as $report)
                        <div class="col-md-6 mb-4">
                            <div class="card card-custom border h-100 p-3 shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge {{ $report->type === 'banjir' ? 'bg-danger' : 'bg-primary' }} text-uppercase px-2.5 py-1.5 fw-bold">
                                        <i class="fa-solid {{ $report->type === 'banjir' ? 'fa-house-flood-water' : 'fa-road-barrier' }} me-1"></i>
                                        {{ $report->type === 'banjir' ? 'Banjir' : 'Infrastruktur' }}
                                    </span>
                                    <div>
                                        @if($report->status === 'ditugaskan')
                                            <span class="badge bg-primary text-uppercase px-2.5 py-1.5"><i class="fa-solid fa-circle-info me-1"></i>Ditugaskan</span>
                                        @elseif($report->status === 'ditangani')
                                            <span class="badge bg-warning text-dark text-uppercase px-2.5 py-1.5"><i class="fa-solid fa-person-digging me-1"></i>Ditangani</span>
                                        @elseif($report->status === 'selesai')
                                            <span class="badge bg-success text-uppercase px-2.5 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Selesai</span>
                                        @endif
                                    </div>
                                </div>

                                <h5 class="fw-bold mb-1" style="color: var(--bpbd-navy)">{{ $report->title }}</h5>
                                <small class="text-muted mb-3 d-block"><i class="fa-regular fa-clock me-1"></i>Ditugaskan sejak: {{ $report->assignments->first()?->assigned_at->diffForHumans() }}</small>

                                @if($report->photo)
                                    <div class="mb-3 text-center">
                                        <img src="{{ asset('storage/' . $report->photo) }}" class="img-fluid rounded border" style="max-height: 180px; width: 100%; object-fit: cover;" alt="Foto Laporan">
                                    </div>
                                @endif

                                <p class="text-muted mb-3 flex-grow-1" style="font-size: 0.95rem;">{{ $report->description }}</p>

                                <div class="p-3 bg-light rounded mb-3">
                                    <div class="mb-2">
                                        <strong>Lokasi Koordinat:</strong>
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="d-block text-decoration-none small mt-1">
                                            <i class="fa-solid fa-map-pin text-danger me-1"></i>{{ $report->latitude }}, {{ $report->longitude }} (Buka Google Maps)
                                        </a>
                                    </div>
                                    @if($report->type === 'banjir')
                                        <div>
                                            <strong>Tinggi Genangan Air:</strong>
                                            <span class="text-danger fw-bold">{{ $report->water_level }} cm</span>
                                        </div>
                                    @endif
                                </div>

                                @if($report->status !== 'selesai')
                                    <div class="pt-3 border-top d-flex gap-2">
                                        @if($report->status === 'ditugaskan')
                                            <form action="{{ route('petugas.reports.status', $report->id) }}" method="POST" class="w-100">
                                                @csrf
                                                <input type="hidden" name="status" value="ditangani">
                                                <button type="submit" class="btn btn-warning text-dark w-100 fw-bold py-2">
                                                    <i class="fa-solid fa-person-digging me-1"></i>Mulai Tangani
                                                </button>
                                            </form>
                                        @elseif($report->status === 'ditangani')
                                            <form action="{{ route('petugas.reports.status', $report->id) }}" method="POST" class="w-100">
                                                @csrf
                                                <input type="hidden" name="status" value="selesai">
                                                <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                                                    <i class="fa-solid fa-circle-check me-1"></i>Tandai Selesai
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <div class="pt-2 text-center text-success fw-semibold">
                                        <i class="fa-solid fa-circle-check me-1"></i>Laporan Selesai Ditangani
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
