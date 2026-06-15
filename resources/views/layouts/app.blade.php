<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiGAPBanjir') - BPBD Kota Palembang</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        :root {
            --bpbd-orange: #ff6600;
            --bpbd-orange-hover: #e05300;
            --bpbd-navy: #0a1931;
            --bpbd-navy-light: #15305b;
            --bpbd-bg: #f4f6f9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bpbd-bg);
            color: #333333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Styling */
        .navbar-bpbd {
            background-color: var(--bpbd-navy);
            border-bottom: 4px solid var(--bpbd-orange);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            color: #ffffff !important;
            letter-spacing: 0.5px;
        }
        .navbar-brand span {
            color: var(--bpbd-orange);
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--bpbd-orange) !important;
        }

        /* Buttons Styling */
        .btn-bpbd {
            background-color: var(--bpbd-orange);
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-bpbd:hover {
            background-color: var(--bpbd-orange-hover);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 102, 0, 0.3);
        }
        .btn-outline-bpbd {
            border: 2px solid var(--bpbd-orange);
            color: var(--bpbd-orange);
            font-weight: 600;
            transition: all 0.3s;
            background-color: transparent;
        }
        .btn-outline-bpbd:hover {
            background-color: var(--bpbd-orange);
            color: white;
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            background: #ffffff;
        }
        
        /* Badges */
        .badge-role {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
            border-radius: 20px;
        }
        .badge-masyarakat { background-color: #e2e8f0; color: #4a5568; }
        .badge-admin { background-color: #fed7d7; color: #c53030; }
        .badge-petugas { background-color: #feebc8; color: #c05621; }
        .badge-pimpinan { background-color: #ebf8ff; color: #2b6cb0; }

        /* Leaflet Map */
        #map {
            height: 450px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }

        /* Footer */
        footer {
            margin-top: auto;
            background-color: var(--bpbd-navy);
            color: rgba(255,255,255,0.7);
            border-top: 1px solid rgba(255,255,255,0.1);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-bpbd py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('masyarakat.dashboard') }}">
                <i class="fa-solid fa-shield-halved me-2 text-warning"></i>SiGAP<span>Banjir</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('masyarakat.dashboard') ? 'active' : '' }}" href="{{ route('masyarakat.dashboard') }}">
                            <i class="fa-solid fa-map-location-dot me-1"></i>Peta Banjir
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->isMasyarakat())
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('masyarakat.create') ? 'active' : '' }}" href="{{ route('masyarakat.create') }}">
                                    <i class="fa-solid fa-circle-plus me-1"></i>Buat Laporan
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="fa-solid fa-chart-line me-1"></i>Dashboard Admin
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->isPetugas())
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('petugas.dashboard') ? 'active' : '' }}" href="{{ route('petugas.dashboard') }}">
                                    <i class="fa-solid fa-list-check me-1"></i>Tugas Lapangan
                                </a>
                            </li>
                        @endif
                        @if(auth()->user()->isPimpinan())
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('pimpinan.dashboard') ? 'active' : '' }}" href="{{ route('pimpinan.dashboard') }}">
                                    <i class="fa-solid fa-square-poll-vertical me-1"></i>Statistik & Grafik
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-light px-4 py-2" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket me-1"></i>Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-bpbd px-4 py-2" href="{{ route('register') }}"><i class="fa-solid fa-user-plus me-1"></i>Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <span class="text-white nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-circle-user me-1 text-warning"></i>{{ auth()->user()->name }}
                                <span class="badge badge-role badge-{{ auth()->user()->role }} ms-1">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </span>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fa-solid fa-right-from-bracket me-1"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-4 flex-grow-1">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show card-custom border-start border-4 border-success p-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-check fs-4 text-success me-3"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show card-custom border-start border-4 border-danger p-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-triangle-exclamation fs-4 text-danger me-3"></i>
                    <div>
                        <strong>Galat!</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center">
        <div class="container">
            <p class="mb-1 text-light-50">&copy; 2026 BPBD Kota Palembang. Hak Cipta Dilindungi.</p>
            <small class="text-white-50">Sistem Informasi Tanggap & Antisipasi Bencana Banjir</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet.js JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    @yield('scripts')
</body>
</html>
