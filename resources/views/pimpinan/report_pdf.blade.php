<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Resmi SiGAPBanjir - BPBD Kota Palembang</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #000000;
            padding: 20px;
        }

        .header-print {
            border-bottom: 3px double #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .title-official {
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
            color: #0a1931;
        }

        .subtitle-official {
            font-size: 13px;
            color: #555555;
            margin-bottom: 0;
        }

        .table-print th {
            background-color: #f2f2f2 !important;
            color: #000000 !important;
            font-weight: 600;
            text-align: center;
        }

        .table-print td, .table-print th {
            border: 1px solid #dddddd !important;
            padding: 8px !important;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Print Trigger Alert -->
        <div class="alert alert-info d-flex justify-content-between align-items-center no-print my-3 border shadow-sm">
            <div>
                <i class="fa-solid fa-circle-info me-2 text-primary"></i><strong>Mode Cetak Laporan:</strong> Halaman ini disiapkan untuk dicetak. Dialog print browser akan terbuka otomatis.
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-primary btn-sm me-2"><i class="fa-solid fa-print me-1"></i>Cetak</button>
                <button onclick="window.close()" class="btn btn-outline-secondary btn-sm">Tutup</button>
            </div>
        </div>

        <!-- Official Header -->
        <div class="header-print text-center">
            <h1 class="title-official">PEMERINTAH KOTA PALEMBANG</h1>
            <h2 class="title-official" style="font-size: 18px;">BADAN PENANGGULANGAN BENCANA DAERAH (BPBD)</h2>
            <p class="subtitle-official">Jl. Merdeka No. 1, Kota Palembang, Sumatera Selatan | Kode Pos: 30131</p>
            <p class="subtitle-official">Email: bpbd@palembang.go.id | Telepon: (0711) 123456</p>
        </div>

        <!-- Report Title -->
        <div class="text-center mb-4">
            <h4 class="fw-bold text-uppercase">Laporan Rekapitulasi Penanganan Bencana Banjir & Infrastruktur</h4>
            <p class="text-muted small">Tanggal Cetak: {{ date('d-m-Y H:i') }}</p>
        </div>

        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-4">
                <div class="p-3 border rounded text-center">
                    <h6 class="text-uppercase text-muted small fw-semibold">Total Pengaduan</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
            <div class="col-4">
                <div class="p-3 border rounded text-center">
                    <h6 class="text-uppercase text-muted small fw-semibold">Selesai Ditangani</h6>
                    <h3 class="fw-bold mb-0 text-success">{{ $stats['selesai'] }}</h3>
                </div>
            </div>
            <div class="col-4">
                <div class="p-3 border rounded text-center">
                    <h6 class="text-uppercase text-muted small fw-semibold">Proses Penanganan</h6>
                    <h3 class="fw-bold mb-0 text-warning">{{ $stats['ditangani'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="mb-4">
            <table class="table table-bordered table-print align-middle">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 15%">Tanggal Laporan</th>
                        <th style="width: 15%">Kategori</th>
                        <th style="width: 30%">Detail Kejadian & Deskripsi</th>
                        <th style="width: 10%">Tinggi Air</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 15%">Petugas Lapangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($reports as $report)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td>{{ $report->created_at->format('d-m-Y H:i') }}</td>
                            <td class="text-center">
                                <strong>{{ $report->type === 'banjir' ? 'Banjir' : 'Infrastruktur' }}</strong>
                            </td>
                            <td>
                                <div class="fw-bold mb-1">{{ $report->title }}</div>
                                <small class="text-muted d-block" style="font-size: 0.85rem;">{{ $report->description }}</small>
                            </td>
                            <td class="text-center">
                                {{ $report->type === 'banjir' ? $report->water_level . ' cm' : '-' }}
                            </td>
                            <td class="text-center">
                                @if($report->status === 'masuk')
                                    <span>Masuk</span>
                                @elseif($report->status === 'diverifikasi')
                                    <span>Diverifikasi</span>
                                @elseif($report->status === 'ditugaskan')
                                    <span>Ditugaskan</span>
                                @elseif($report->status === 'ditangani')
                                    <span>Ditangani</span>
                                @elseif($report->status === 'selesai')
                                    <span class="text-success fw-bold">Selesai</span>
                                @endif
                            </td>
                            <td>
                                @if($report->assignments->isNotEmpty())
                                    {{ $report->assignments->first()->officer->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Signature Area -->
        <div class="row mt-5 pt-4">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p>Palembang, {{ date('d F Y') }}</p>
                <p class="fw-bold" style="margin-bottom: 70px;">Kepala BPBD Kota Palembang</p>
                <p class="text-decoration-underline fw-bold mb-0">H. Akhmad Bastari, S.T., M.T.</p>
                <p class="text-muted small">NIP. 19710812 199703 1 004</p>
            </div>
        </div>
    </div>

    <!-- Print Auto Activation Script -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
