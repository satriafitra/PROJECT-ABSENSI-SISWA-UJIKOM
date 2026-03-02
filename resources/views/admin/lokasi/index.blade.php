@extends('layouts.admin')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
#map{
    height:500px;
    width:100%;
    border-radius:10px;
    margin-top:15px;
}
.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
.form-group{
    margin-bottom:15px;
}
label{
    font-weight:600;
}
</style>

<div class="container-fluid">

    <div class="card">

        <h4>
            <i class="fa fa-map-marker-alt"></i>
            Setting Lokasi Absensi
        </h4>

        <form action="{{ route('admin.lokasi.store') }}" method="POST">
            @csrf

            <div class="row">

                <div class="col-md-6 form-group">
                    <label>Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" class="form-control" required>
                </div>

                <div class="col-md-3 form-group">
                    <label>Latitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" readonly required>
                </div>

                <div class="col-md-3 form-group">
                    <label>Longitude</label>
                    <input type="text" id="longitude" name="longitude" class="form-control" readonly required>
                </div>

                <div class="col-md-3 form-group">
                    <label>Radius (meter)</label>
                    <input type="number" name="radius" class="form-control" value="100" required>
                </div>

            </div>

            <button class="btn btn-primary">
                <i class="fa fa-save"></i> Simpan Lokasi
            </button>

        </form>

        <div id="map"></div>

    </div>

</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

    // Koordinat Jawa Barat (Bandung)
    var map = L.map('map').setView([-6.914744,107.609810],10);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution:'&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    // klik map
    map.on('click', function(e){

        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // hapus marker lama
        if(marker){
            map.removeLayer(marker);
        }

        // buat marker baru
        marker = L.marker([lat,lng]).addTo(map)
            .bindPopup("Lokasi dipilih")
            .openPopup();

        // isi input form
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

    });

</script>

@endsection