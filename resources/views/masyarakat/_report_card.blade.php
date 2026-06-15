<div class="card card-custom h-100 border p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="badge {{ $report->type === 'banjir' ? 'bg-danger' : 'bg-primary' }} text-uppercase px-2.5 py-1.5 fw-bold">
            <i class="fa-solid {{ $report->type === 'banjir' ? 'fa-house-flood-water' : 'fa-road-barrier' }} me-1"></i>
            {{ $report->type === 'banjir' ? 'Banjir' : 'Infrastruktur' }}
        </span>
        
        <div>
            @if($report->status === 'masuk')
                <span class="badge bg-secondary text-uppercase px-2.5 py-1.5"><i class="fa-solid fa-clock me-1"></i>Masuk</span>
            @elseif($report->status === 'diverifikasi')
                <span class="badge bg-info text-dark text-uppercase px-2.5 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Diverifikasi</span>
            @elseif($report->status === 'ditugaskan')
                <span class="badge bg-primary text-uppercase px-2.5 py-1.5"><i class="fa-solid fa-user-shield me-1"></i>Ditugaskan</span>
            @elseif($report->status === 'ditangani')
                <span class="badge bg-warning text-dark text-uppercase px-2.5 py-1.5"><i class="fa-solid fa-person-digging me-1"></i>Ditangani</span>
            @elseif($report->status === 'selesai')
                <span class="badge bg-success text-uppercase px-2.5 py-1.5"><i class="fa-solid fa-circle-check me-1"></i>Selesai</span>
            @endif
        </div>
    </div>

    <h5 class="fw-bold mb-1" style="color: var(--bpbd-navy)">{{ $report->title }}</h5>
    <small class="text-muted mb-2 d-block"><i class="fa-regular fa-clock me-1"></i>{{ $report->created_at->diffForHumans() }}</small>
    
    @if($report->photo)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $report->photo) }}" class="img-fluid rounded border" style="max-height: 200px; width: 100%; object-fit: cover;" alt="Foto Laporan">
        </div>
    @endif

    <p class="text-muted mb-3 flex-grow-1" style="font-size: 0.95rem;">{{ Str::limit($report->description, 140) }}</p>

    @if($report->type === 'banjir')
        <div class="p-2 mb-3 bg-light rounded border-start border-3 border-danger d-flex align-items-center">
            <i class="fa-solid fa-water text-danger fs-5 me-2"></i>
            <div>
                <span class="text-muted small d-block">Tinggi Genangan Air</span>
                <strong class="text-danger fs-6">{{ $report->water_level }} cm</strong>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-dark border p-2 text-sm">
                <i class="fa-solid fa-fire text-warning me-1"></i>Skor Prioritas: <strong>{{ $report->priority_score }}</strong>
            </span>
            <span class="text-muted small" title="Jumlah laporan serupa di area yang sama">
                <i class="fa-solid fa-copy me-1"></i>Duplikat: {{ $report->duplicate_count }}
            </span>
        </div>

        <div>
            @auth
                <form action="{{ route('reports.upvote', $report->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn {{ $report->isUpvotedByUser(auth()->id()) ? 'btn-warning text-dark' : 'btn-outline-warning' }} btn-sm px-3 fw-bold">
                        <i class="fa-solid fa-arrow-up me-1"></i>
                        {{ $report->upvote_count }} Dukung
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-warning btn-sm px-3 fw-bold">
                    <i class="fa-solid fa-arrow-up me-1"></i>
                    {{ $report->upvote_count }} Dukung
                </a>
            @endauth
        </div>
    </div>
</div>
