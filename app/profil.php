<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Objek Wisata Pemancingan</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Full Page Background Image */
        .profil-header {
            background: url('/coba/app/dist/img/logo.jpg') no-repeat center center fixed;
            background-size: cover; /* Menutupi seluruh area header */
            height: 100vh; /* Memastikan tinggi header sama dengan tinggi viewport */
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }
        .profil-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Overlay gradasi untuk meningkatkan kontras teks */
            z-index: 1;
        }
        .profil-header h1, .profil-header p {
            position: relative;
            z-index: 2;
        }
        .profil-header h1 {
            font-size: 3rem;
            margin: 0;
            font-weight: bold;
        }
        .profil-header p {
            font-size: 1.5rem;
            margin: 0;
        }
        .profil-content {
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            margin-top: -10%;
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        .profil-image {
            width: 100%;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-bottom: 0;
        }
        .card-body {
            background-color: white;
        }
        .btn-back {
            background-color: #28a745;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #218838;
            color: white;
        }
        .footer {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-top: 0;
        }
        .footer p {
            margin: 0;
            font-size: 1rem;
        }
        .icon-info {
            font-size: 1.5rem;
            margin-right: 10px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <!-- Header Profil -->
    <header class="profil-header">
        <div>
            <h1>Objek Wisata Pemancingan Ewin</h1>
            <p>Tempat ideal untuk menikmati pengalaman memancing yang tak terlupakan</p>
        </div>
    </header>

    <!-- Konten Profil -->
    <div class="container profil-content">
        <div class="row">
            <div class="col-md-4">
                <img src="/coba/app/dist/img/mancing5.jpg" alt="Gambar Profil" class="profil-image">
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-info-circle icon-info"></i> Deskripsi
                            </div>
                            <div class="card-body">
                                <p>Objek wisata pemancingan Ewin adalah destinasi yang ideal untuk para penggemar memancing. Terletak di lokasi strategis, kami menyediakan berbagai fasilitas yang nyaman dan layanan yang ramah.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-map-marker-alt icon-info"></i> Alamat
                            </div>
                            <div class="card-body">
                                <p>Jl. Raya Pemancingan No. 123, Kota Pancing, Provinsi Pancingan, Indonesia</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-clock icon-info"></i> Jam Buka
                            </div>
                            <div class="card-body">
                                <p>Senin - Minggu: 08:00 - 18:00 WIB</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-cogs icon-info"></i> Fasilitas
                            </div>
                            <div class="card-body">
                                <p>Kami menyediakan area memancing, tempat parkir, kamar mandi, dan area makan,Tempat Ngastol.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-phone-alt icon-info"></i> Kontak
                            </div>
                            <div class="card-body">
                                <p><i class="fas fa-phone"></i> Telepon: +62 123 4567 890</p>
                                <p><i class="fas fa-envelope"></i> Email: info@ewin.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="index.php" class="btn btn-back mt-3">Kembali</a>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Objek Wisata Pemancingan Ewin. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
