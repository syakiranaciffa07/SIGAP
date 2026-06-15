@extends('layouts.app')

@section('title', 'Buat Laporan Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-custom shadow-lg p-4 border">
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                <a href="{{ route('masyarakat.dashboard') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
                <h3 class="fw-bold mb-0" style="color: var(--bpbd-navy)"><i class="fa-solid fa-circle-plus text-warning me-2"></i>Buat Laporan Bencana / Kerusakan</h3>
            </div>

            <form action="{{ route('masyarakat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left Inputs Form -->
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="type" class="form-label fw-semibold">Kategori Laporan</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="banjir" {{ old('type') == 'banjir' ? 'selected' : '' }}>Banjir (Genangan Air)</option>
                                <option value="infrastruktur" {{ old('type') == 'infrastruktur' ? 'selected' : '' }}>Kerusakan Infrastruktur (Drainase, Jembatan, Tanggul)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Judul Laporan</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Genangan setinggi lutut di Jl. Sudirman" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="water-level-group">
                            <label for="water_level" class="form-label fw-semibold">Ketinggian Genangan Air (cm)</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('water_level') is-invalid @enderror" id="water_level" name="water_level" value="{{ old('water_level', 0) }}" min="0">
                                <span class="input-group-text">cm</span>
                                @error('water_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Masukkan perkiraan tinggi genangan air dalam satuan centimeter.</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi / Kronologi Kejadian</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Jelaskan detail kejadian, kondisi terkini, bantuan yang mendesak dibutuhkan, dll." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label fw-semibold">Upload Foto Kejadian (Opsional)</label>
                            <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal resolusi file gambar adalah 2MB.</small>
                        </div>
                    </div>

                    <!-- Right Location Map Picker -->
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block">Tentukan Titik Koordinat Lokasi</label>
                            <small class="text-muted d-block mb-2">Klik pada peta di bawah ini untuk menentukan titik koordinat lokasi pelaporan dengan akurat.</small>
                            <div id="map-picker" style="height: 320px; border-radius: 8px; border: 1px solid #ced4da;" class="mb-3"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label fw-semibold">Latitude</label>
                                <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', -2.990934) }}" readonly required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label fw-semibold">Longitude</label>
                                <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', 104.756554) }}" readonly required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-bpbd btn-lg px-5"><i class="fa-solid fa-paper-plane me-2"></i>Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show/hide water level based on category selection
    const typeSelect = document.getElementById('type');
    const waterLevelGroup = document.getElementById('water-level-group');

    function toggleWaterLevel() {
        if (typeSelect.value === 'banjir') {
            waterLevelGroup.style.display = 'block';
        } else {
            waterLevelGroup.style.display = 'none';
        }
    }

    typeSelect.addEventListener('change', toggleWaterLevel);
    // Initial run
    toggleWaterLevel();

    // Map Coordinates Picker
    const defaultLat = parseFloat(document.getElementById('latitude').value);
    const defaultLng = parseFloat(document.getElementById('longitude').value);

    // Initialize Map Focus on Palembang
    const mapPicker = L.map('map-picker').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap &copy; CARTO',
        maxZoom: 20
    }).addTo(mapPicker);

    // Create marker
    let marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(mapPicker);

    // Update lat-lng input fields on drag end
    marker.on('dragend', function (e) {
        const position = marker.getLatLng();
        document.getElementById('latitude').value = position.lat.toFixed(6);
        document.getElementById('longitude').value = position.lng.toFixed(6);
    });

    // Update marker and inputs on map click
    mapPicker.on('click', function (e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;
        marker.setLatLng([lat, lng]);
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
    });
</script>
@endsection
