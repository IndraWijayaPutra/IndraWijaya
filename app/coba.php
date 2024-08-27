<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemetaan Lokasi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 90vh; /* Menggunakan 90% dari tinggi viewport */
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
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1 class="text-center">Pemetaan Lokasi</h1>
        <div class="mb-3">
            <a href="create_location.php" class="btn btn-primary btn-custom">Tambah Lokasi</a>
            <a href="view_locations.php" class="btn btn-secondary">Daftar Lokasi</a>
            <a href="http://localhost:8080/coba/app/index.php?" class="btn btn-secondary">Kembali</a>
        </div>
        <div id="map"></div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-3.427709541138215, 114.83692839908947], 10);
        var markers = {}; // Objek untuk menyimpan marker

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        function fetchLocations() {
            fetch('get_locations.php')
                .then(response => response.json())
                .then(locations => {
                    // Hapus semua marker yang ada
                    Object.values(markers).forEach(marker => map.removeLayer(marker));
                    markers = {}; // Reset marker storage

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
                                Jumlah Pengunjung: ${location.jumlah_pengunjung}<br>
                                Rekomendasi: ${location.rekomendasi}<br>
                                <a href="#" class="btn btn-danger btn-sm delete-location" data-id="${location.id}">Hapus</a>
                            `).openPopup();

                            // Simpan marker untuk penghapusan nanti
                            markers[location.id] = marker;
                        } else {
                            console.error('Koordinat tidak valid:', location.latitude, location.longitude);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching location data:', error);
                });
        }

        fetchLocations();

        // Event delegation untuk tombol hapus
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('delete-location')) {
                e.preventDefault();
                var locationId = e.target.getAttribute('data-id');
                if (confirm('Apakah Anda yakin ingin menghapus lokasi ini?')) {
                    fetch('delete_location.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            'id': locationId,
                            '_method': 'DELETE' // Simulasi metode DELETE
                        })
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result === 'success') {
                            alert('Lokasi berhasil dihapus!');
                            fetchLocations(); // Perbarui peta setelah penghapusan
                        } else {
                            alert('Terjadi kesalahan saat menghapus lokasi.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            }
        });

        // Menampilkan pesan sukses atau error jika ada
        var urlParams = new URLSearchParams(window.location.search);
        var status = urlParams.get('status');
        if (status === 'success') {
            alert('Lokasi berhasil Ditambahkan!');
        } else if (status === 'error') {
            alert('Terjadi kesalahan saat menghapus lokasi.');
        }
    </script>
</body>
</html>
