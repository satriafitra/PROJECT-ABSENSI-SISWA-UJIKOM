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
        background-color: #f4f7f6;
    }

    .main-wrapper {
        padding: 25px;
    }

    /* Section Card */
    .section-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
    }

    /* Map Styling */
    .map-container {
        position: relative;
        height: 500px;
        border-radius: 24px;
        overflow: hidden;
        border: 8px solid white;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    #map {
        height: 100%;
        width: 100%;
        z-index: 1;
    }

    .map-overlay-title {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        background: var(--glass-bg);
        padding: 15px 25px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-left: 5px solid var(--primary-orange);
    }

    /* Form Styling */
    .form-group-custom {
        margin-bottom: 5px;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--dark-text);
        margin-bottom: 10px;
        display: block;
    }

    .custom-input {
        border: 2px solid #edf2f7;
        border-radius: 12px;
        padding: 12px 15px;
        transition: all 0.3s ease;
        height: auto;
    }

    .custom-input:focus {
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 4px rgba(255, 140, 0, 0.1);
        outline: none;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 2px solid #edf2f7;
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #a0aec0;
    }

    .input-group .custom-input {
        border-radius: 0 12px 12px 0;
    }

    .btn-save {
        background: linear-gradient(135deg, var(--primary-orange), var(--dark-orange));
        color: white;
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 140, 0, 0.4);
        color: white;
    }

    /* Table Styling */
    .table-container {
        border-radius: 15px;
        overflow: hidden;
    }

    .table thead th {
        background: #f8f9fa;
        color: #718096;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        padding: 18px;
        border: none;
    }

    .table tbody td {
        padding: 20px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f2f6;
    }

    .badge-radius {
        background: var(--light-orange);
        color: var(--dark-orange);
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .btn-action {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: 0.3s;
        border: none;
    }

    .btn-view { background-color: #ebf4ff; color: #3182ce; }
    .btn-view:hover { background-color: #3182ce; color: white; }
    .btn-delete { background-color: #fff5f5; color: #e53e3e; }
    .btn-delete:hover { background-color: #e53e3e; color: white; }
</style>

<div class="main-wrapper">
    <div class="map-container" id="map-section">
        <div class="map-overlay-title">
            <h5 class="m-0 fw-bold"><i class="fa-solid fa-map-location-dot me-2 text-warning"></i>Lokasi Absensi</h5>
            <small class="text-muted">Klik peta atau daftar di bawah</small>
        </div>
        <div id="map"></div>
    </div>

    <div class="section-card">
        <h5 class="fw-bold mb-4 text-dark">Tambah Lokasi Baru</h5>
        <form action="{{ route('admin.lokasi.store') }}" method="POST">
            @csrf
            <div class="row g-4 align-items-end">
                <div class="col-lg-4 col-md-12">
                    <div class="form-group-custom">
                        <label class="form-label">Nama Instansi / Lokasi</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-building"></i></span>
                            <input type="text" name="nama_lokasi" id="input_nama" class="form-control custom-input" placeholder="Contoh: Gedung Pusat SMA 1" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="form-control custom-input bg-light" placeholder="Otomatis" readonly required>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label">Longitude</label>
                        <input type="text" id="longitude" name="longitude" class="form-control custom-input bg-light" placeholder="Otomatis" readonly required>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label">Radius (Meter)</label>
                        <input type="number" id="radius_input" name="radius" class="form-control custom-input" value="100" min="5" required>
                    </div>
                </div>
                <div class="col-lg-2 col-md-12">
                    <button type="submit" class="btn btn-save w-100">
                        <i class="fa fa-save me-2"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="section-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold m-0">Daftar Lokasi Terdaftar</h5>
                <p class="text-muted small m-0">Klik ikon mata untuk memfokuskan peta</p>
            </div>
            <div class="header-icon text-warning fs-3">
                <i class="fa-solid fa-list-check"></i>
            </div>
        </div>

        <div class="table-responsive table-container">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Instansi</th>
                        <th>Koordinat GPS</th>
                        <th>Jangkauan</th>
                        <th class="text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lokasi as $item)
                    <tr>
                        <td><span class="text-muted fw-bold">{{ $loop->iteration }}</span></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->nama_lokasi }}</div>
                            <small class="text-muted">ID: #LOC-{{ $item->id }}</small>
                        </td>
                        <td>
                            <code class="text-primary small fw-bold">{{ $item->latitude }}, {{ $item->longitude }}</code>
                        </td>
                        <td><span class="badge-radius"><i class="fa fa-bullseye me-1"></i> {{ $item->radius }}m</span></td>
                        <td class="text-center">
                            <button class="btn-action btn-view me-1" 
                                    onclick="focusLocation({{ $item->latitude }}, {{ $item->longitude }}, {{ $item->radius }}, '{{ addslashes($item->nama_lokasi) }}')"
                                    title="Tampilkan di Peta">
                                <i class="fa fa-eye"></i>
                            </button>
                            
                            <form action="{{ route('admin.lokasi.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" 
                                        onclick="return confirm('Hapus lokasi {{ $item->nama_lokasi }}?')"
                                        title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                            <p class="text-muted">Belum ada lokasi yang disimpan dalam database.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Inisialisasi Peta
    var map = L.map('map', {
        zoomControl: false
    }).setView([-6.914744, 107.609810], 13);

    L.control.zoom({ position: 'bottomright' }).addTo(map);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var marker, circle;

    // Fungsi Update Marker & Radius dengan Label Nama yang Dinamis
    function updateMap(lat, lng, radius, label = "Titik Baru Terpilih", fly = true) {
        if (marker) map.removeLayer(marker);
        if (circle) map.removeLayer(circle);

        var customIcon = L.divIcon({
            html: '<i class="fa-solid fa-location-dot" style="color: #FF8C00; font-size: 32px; filter: drop-shadow(0 3px 5px rgba(0,0,0,0.3));"></i>',
            className: 'custom-div-icon',
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });

        // Popup sekarang menampilkan label (Nama Lokasi)
        marker = L.marker([lat, lng], { icon: customIcon }).addTo(map)
                .bindPopup("<div style='font-family:Poppins; text-align:center;'><strong style='color:#FF8C00;'>" + label + "</strong><br><small>Lokasi Absensi</small></div>").openPopup();

        circle = L.circle([lat, lng], {
            color: '#FF8C00',
            fillColor: '#FF8C00',
            fillOpacity: 0.15,
            weight: 2,
            radius: parseInt(radius)
        }).addTo(map);

        if(fly) {
            map.flyTo([lat, lng], 17, { duration: 1.5 });
        }
    }

    // Fungsi Klik dari Tabel (Menerima parameter Nama)
    function focusLocation(lat, lng, radius, nama) {
        // Scroll ke peta
        document.getElementById('map-section').scrollIntoView({ behavior: 'smooth' });
        
        // Isi form jika user ingin mengedit/melihat
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('radius_input').value = radius;
        document.getElementById('input_nama').value = nama;
        
        // Tampilkan di peta dengan Nama Lokasi Aslinya
        updateMap(lat, lng, radius, nama, true);
    }

    // Event Klik Peta Manual
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(8);
        var lng = e.latlng.lng.toFixed(8);
        var rad = document.getElementById('radius_input').value;
        var namaInput = document.getElementById('input_nama').value || "Titik Baru";

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        updateMap(lat, lng, rad, namaInput, false);
    });

    // Sinkronisasi Radius saat input angka berubah
    document.getElementById('radius_input').addEventListener('input', function() {
        var lat = document.getElementById('latitude').value;
        var lng = document.getElementById('longitude').value;
        var rad = this.value;
        var nama = document.getElementById('input_nama').value || "Titik Baru";

        if (lat && lng) {
            updateMap(lat, lng, rad, nama, false);
        }
    });
</script>
@endsection