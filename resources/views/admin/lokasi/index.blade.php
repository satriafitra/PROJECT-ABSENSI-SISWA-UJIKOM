@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    :root {
        --primary-orange: #FF8C00;
        --dark-orange: #E67E22;
        --light-orange: #FFF3E0;
        --dark-text: #2D3436;
        --glass-bg: rgba(255, 255, 255, 0.95);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    .main-card {
        background: var(--glass-bg);
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 30px;
        border-top: 5px solid var(--primary-orange);
    }

    .header-section {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

    .header-icon {
        width: 50px;
        height: 50px;
        background: var(--light-orange);
        color: var(--primary-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 22px;
        margin-right: 15px;
    }

    .header-title h4 {
        margin: 0;
        font-weight: 700;
        color: var(--dark-text);
        letter-spacing: -0.5px;
    }

    .header-title p {
        margin: 0;
        font-size: 0.9rem;
        color: #636e72;
    }

    /* Custom Input Styling */
    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--dark-text);
        margin-bottom: 8px;
        display: block;
    }

    .custom-input {
        border: 2px solid #edf2f7;
        border-radius: 12px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .custom-input:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 4px rgba(255, 140, 0, 0.1);
        outline: none;
    }

    .custom-input[readonly] {
        background-color: #f1f2f6;
        cursor: not-allowed;
    }

    /* Map Styling */
    #map {
        height: 550px;
        width: 100%;
        border-radius: 20px;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
    }

    /* Button Styling */
    .btn-save {
        background: linear-gradient(135deg, var(--primary-orange), var(--dark-orange));
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        height: 50px;
        width: 100%;
        box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 140, 0, 0.4);
        color: white;
    }

    .input-group-text {
        background: transparent;
        border: 2px solid #edf2f7;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: var(--primary-orange);
    }

    .input-group .custom-input {
        border-radius: 0 12px 12px 0;
    }

    /* Badge Helper */
    .helper-text {
        background: #fdf2e9;
        color: #e67e22;
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 0.8rem;
        margin-top: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
</style>

<div class="container-fluid py-4">
    <div class="main-card">
        <div class="header-section">
            <div class="header-icon">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div class="header-title">
                <h4>Konfigurasi Titik Absensi</h4>
                <p>Tentukan lokasi pusat dan radius jangkauan absensi siswa</p>
            </div>
        </div>

        <form action="{{ route('admin.lokasi.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-lg-4 col-md-12">
                    <label class="form-label">Nama Lokasi / Instansi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-school"></i></span>
                        <input type="text" name="nama_lokasi" class="form-control custom-input" placeholder="Misal: Kampus Utama" required>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label class="form-label">Latitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control custom-input" placeholder="Otomatis" readonly required>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label class="form-label">Longitude</label>
                    <input type="text" id="longitude" name="longitude" class="form-control custom-input" placeholder="Otomatis" readonly required>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label class="form-label">Radius (Meter)</label>
                    <div class="input-group">
                        <input type="number" id="radius_input" name="radius" class="form-control custom-input" value="100" min="5" required>
                    </div>
                </div>

                <div class="col-lg-2 col-md-12">
                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                    <button type="submit" class="btn-save">
                        <i class="fa fa-cloud-upload-alt"></i> Simpan Lokasi
                    </button>
                </div>
            </div>
        </form>

        <div class="helper-text">
            <i class="fa-solid fa-circle-info"></i>
            Tip: Klik pada area peta untuk mengunci titik koordinat secara presisi.
        </div>

        <div id="map"></div>

        <div class="mt-4">
            <div class="main-card">
                <div class="header-section">
                    <div class="header-icon">
                        <i class="fa-solid fa-list"></i>
                    </div>
                    <div class="header-title">
                        <h4>Daftar Lokasi Tersimpan</h4>
                        <p>Lokasi yang sudah didaftarkan ke sistem</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background:#FFF3E0">
                            <tr>
                                <th>#</th>
                                <th>Nama Lokasi</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Radius</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($lokasi as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $item->nama_lokasi }}</strong>
                                </td>

                                <td>{{ $item->latitude }}</td>
                                <td>{{ $item->longitude }}</td>

                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $item->radius }} m
                                    </span>
                                </td>

                                <td>

                                    <button
                                        class="btn btn-sm btn-primary"
                                        onclick="focusLocation({{ $item->latitude }}, {{ $item->longitude }}, {{ $item->radius }})">
                                        <i class="fa fa-map-marker-alt"></i>
                                    </button>

                                    <form action="{{ route('admin.lokasi.destroy',$item->id) }}"
                                        method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>

                                    </form>

                                </td>

                            </tr>
                            @empty

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada lokasi tersimpan
                                </td>
                            </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inisialisasi Peta
    var map = L.map('map', {
        zoomControl: false // Kita pindahkan posisi zoom agar lebih rapi
    }).setView([-6.914744, 107.609810], 13);

    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

    // Style Peta Modern (CartoDB Positron)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var marker;
    var circle;

    function updateMap(lat, lng, radius) {
        if (marker) map.removeLayer(marker);
        if (circle) map.removeLayer(circle);

        // Icon Custom yang lebih menarik
        var customIcon = L.divIcon({
            html: '<i class="fa-solid fa-location-dot" style="color: #FF8C00; font-size: 30px;"></i>',
            className: 'custom-div-icon',
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });

        marker = L.marker([lat, lng], {
                icon: customIcon
            }).addTo(map)
            .bindPopup("<div style='font-family:Poppins; font-weight:600;'>Titik Absensi Terpilih</div>").openPopup();

        circle = L.circle([lat, lng], {
            color: '#FF8C00',
            fillColor: '#FF8C00',
            fillOpacity: 0.15,
            weight: 2,
            radius: parseInt(radius)
        }).addTo(map);

        // Zoom otomatis ke titik terpilih dengan halus
        map.flyTo([lat, lng], 16);
    }

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        var rad = document.getElementById('radius_input').value;

        document.getElementById('latitude').value = lat.toFixed(8);
        document.getElementById('longitude').value = lng.toFixed(8);

        updateMap(lat, lng, rad);
    });

    document.getElementById('radius_input').addEventListener('input', function() {
        var lat = document.getElementById('latitude').value;
        var lng = document.getElementById('longitude').value;
        var rad = this.value;

        if (lat && lng) {
            updateMap(lat, lng, rad);
        }
    });
</script>
@endsection