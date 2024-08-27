<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Lokasi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 80vh;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Daftar Lokasi</h1>
        <a href="create_location.php" class="btn btn-primary mb-3">Tambah Lokasi</a>
        <a href="http://localhost/coba/app/index.php?" class="btn btn-primary mb-3">Kembali</a>
        <div id="map"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-3.4678, 114.8217], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        fetch('get_locations.php')
            .then(response => response.json())
            .then(locations => {
                locations.forEach(location => {
                    L.marker([location.latitude, location.longitude])
                        .addTo(map)
                        .bindPopup(location.nama)
                        .openPopup();
                });
            })
            .catch(error => {
                console.error('Error fetching location data:', error);
            });
    </script>
</body>
</html>
