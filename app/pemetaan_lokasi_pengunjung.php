<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemetaan Lokasi Pengunjung</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 80vh; /* Menggunakan 80% dari tinggi viewport */
            width: 100%;  /* Lebar peta 100% dari layar */
            border: 2px solid #ccc; /* Menambahkan border untuk tampilan yang lebih baik */
            border-radius: 5px; /* Menambahkan sudut melengkung pada border */
        }

        body {
            margin: 0; /* Menghapus margin di seluruh halaman */
            padding: 0; /* Menghapus padding di seluruh halaman */
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 0; /* Menghapus padding di container untuk peta yang melebar penuh */
        }

        .btn-custom {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1 class="text-center">Pemetaan Lokasi Pengunjung</h1>
        <div class="text-center mb-3">
            <a href="http://localhost/coba/app/" class="btn btn-secondary btn-custom">Kembali</a>
        </div>
        <div id="map"></div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-3.427709541138215, 114.83692839908947], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        fetch('get_locations.php')
            .then(response => response.json())
            .then(locations => {
                locations.forEach(location => {
                    let lat = parseFloat(location.latitude);
                    let lon = parseFloat(location.longitude);

                    if (!isNaN(lat) && !isNaN(lon)) {
                        let marker = L.marker([lat, lon], {
                            icon: L.icon({
                                iconUrl: 'https://unpkg.com/leaflet/dist/images/marker-icon.png',
                                iconSize: [32, 32], // Ukuran marker
                                iconAnchor: [16, 32], // Titik anchor marker
                                popupAnchor: [0, -32] // Titik popup marker
                            })
                        }).addTo(map);

                        marker.bindPopup(`
                            <strong>${location.nama}</strong><br>
                            Latitude: ${lat}<br>
                            Longitude: ${lon}<br>
                            <a href="https://www.google.com/maps/search/?api=1&query=${lat},${lon}" target="_blank">Lihat di Google Maps</a>
                        `).openPopup();
                    } else {
                        console.error('Koordinat tidak valid:', location.latitude, location.longitude);
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching location data:', error);
            });
    </script>
</body>
</html>
