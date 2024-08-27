<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <!-- Sertakan Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Sertakan CSS lainnya yang diperlukan -->
</head>
<body>
    <!-- Navigasi Anda -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Menu lain -->
            <li class="nav-item">
                <a href="http://localhost/coba/app/Yummy/index1.php" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Tentang Kami</p>
                </a>
            </li>
            <li class="nav-item menu-open">
                <a href="#" class="nav-link active">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Pesan Di sini<i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="http://localhost/coba/app/data_pengunjung.php" class="nav-link active">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Data Pengunjung</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/coba/app/pemesanan_kuliner_tiket.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pemesanan Tiket dan Kuliner</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://localhost/coba/app/ikan_pengunjung.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Jumlah ikan yang di tangkap</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://localhost/coba/app/pemetaan_lokasi_pengunjung.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pemetaan </p>
                        </a>
                    </li>
                    <li class="nav-item">
                    <a href="http://localhost/coba/app/status_pesanan.php" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Lihat Struk Anda</p>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://localhost/coba/app/data_ikan.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Bagikan Momen Anda di sini</p>
                        </a>
                    </li>
                </ul> 
            </li>
            <!-- Elemen logout -->
            <li class="nav-item">
                <a href="#" class="nav-link text-green" id="logoutLink">
                    <i class="nav-icon fas fa-power-off"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
    </nav>

    <!-- JavaScript untuk konfirmasi logout -->
    <script>
    document.getElementById('logoutLink').addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah tindakan default tautan
        var confirmLogout = confirm("Apakah Anda yakin ingin keluar?");
        if (confirmLogout) {
            window.location.href = 'logout.php'; // Ganti dengan URL logout yang sesuai
        }
    });
    </script>
</body>
</html>
