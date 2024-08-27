<?php
include('../config/config.php');

// Fungsi untuk mengatur ulang jumlah pengunjung
function resetPengunjungIfNeeded($koneksi) {
    // Ambil waktu terakhir reset dari database atau file
    $lastResetFile = 'last_reset_time.txt';

    // Jika file tidak ada, inisialisasi waktu reset dengan waktu sekarang
    if (!file_exists($lastResetFile)) {
        file_put_contents($lastResetFile, time());
    }

    // Baca waktu reset terakhir
    $lastResetTime = (int)file_get_contents($lastResetFile);
    $currentTime = time();
    $oneDayInSeconds = 24 * 60 * 60; // 24 jam dalam detik

    // Periksa apakah sudah 24 jam sejak reset terakhir
    if (($currentTime - $lastResetTime) >= $oneDayInSeconds) {
        // Reset jumlah pengunjung
        $resetQuery = "UPDATE kolam SET jumlah_pengunjung = 0";
        $koneksi->query($resetQuery);

        // Perbarui waktu reset terakhir
        file_put_contents($lastResetFile, $currentTime);
    }
}

// Panggil fungsi reset saat file diakses
resetPengunjungIfNeeded($koneksi);

// Ambil data kolam
$sql_kolam = "SELECT * FROM kolam";
$result_kolam = $koneksi->query($sql_kolam);

// Menyiapkan array untuk data kolam
$kolams = [];
while ($row = $result_kolam->fetch_assoc()) {
    $kolams[] = $row;
}

// Query SQL untuk menghitung total pengunjung
$sql = "SELECT COUNT(*) AS total_pengunjung FROM pengunjung";

// Eksekusi query
$result = $koneksi->query($sql);

// Cek apakah hasil query tidak null dan memiliki data
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_pengunjung = $row['total_pengunjung'];
} else {
    $total_pengunjung = 0; // Nilai default jika query tidak mengembalikan hasil
}

// Query untuk mendapatkan total pemasukan, pengeluaran, dan laba
$sql = "SELECT 
            SUM(total_pemasukan) AS total_pemasukan, 
            SUM(total_pengeluaran) AS total_pengeluaran, 
            SUM(laba_kotor) AS total_laba_kotor, 
            SUM(laba_bersih) AS total_laba_bersih, 
            SUM(biaya_operasional) AS total_biaya_operasional, 
            SUM(biaya_non_operasional) AS total_biaya_non_operasional, 
            SUM(pendapatan_non_operasional) AS total_pendapatan_non_operasional,
            SUM(total_harga) AS total_harga
        FROM laporan_keuangan";

$result = $koneksi->query($sql);

// Cek apakah hasil query tidak null dan memiliki data
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_pemasukan = $row['total_pemasukan'] ?? 0;
    $total_pengeluaran = $row['total_pengeluaran'] ?? 0;
    $total_laba_kotor = $row['total_laba_kotor'] ?? 0;
    $total_laba_bersih = $row['total_laba_bersih'] ?? 0;
    $total_biaya_operasional = $row['total_biaya_operasional'] ?? 0;
    $total_biaya_non_operasional = $row['total_biaya_non_operasional'] ?? 0;
    $total_pendapatan_non_operasional = $row['total_pendapatan_non_operasional'] ?? 0;
    $total_harga = $row['total_harga'] ?? 0;
} else {
    // Jika tidak ada data, set nilai default
    $total_pemasukan = 0;
    $total_pengeluaran = 0;
    $total_laba_kotor = 0;
    $total_laba_bersih = 0;
    $total_biaya_operasional = 0;
    $total_biaya_non_operasional = 0;
    $total_pendapatan_non_operasional = 0;
    $total_harga = 0;
}

// Hitung laba kotor dan laba bersih jika diperlukan
$laba_kotor = $total_pemasukan - $total_biaya_operasional - $total_biaya_non_operasional;
$laba_bersih = $laba_kotor - $total_pengeluaran;

// Hitung jumlah momen yang dibagikan
$sql_momen = "SELECT COUNT(*) as jumlah_momen FROM momen";
$result_momen = $koneksi->query($sql_momen);
$row_momen = $result_momen->fetch_assoc();
$jumlah_momen = $row_momen['jumlah_momen'];

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        .card-img-top {
            max-width: 80%; /* Membatasi lebar gambar sesuai dengan lebar kontainer */
            height: auto;
        }
        .col-md-6 {
            display: flex;
            flex-direction: column;
        }
        .card {
            margin-bottom: 20px;
        }
        #clock {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo htmlspecialchars($jumlah_momen); ?></h3>
                            <p>Lihat Momen Pengunjung Lain di sini</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="more_info.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">  
                        <div class="inner">
                            <h3>Rp. <?php echo number_format($total_pemasukan, 2, ',', '.'); ?></h3>
                            <p>Total pemasukan</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="laporan_keuangan.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo htmlspecialchars($total_pengunjung); ?></h3>
                            <p>Data User</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="view/user.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Rp. <?php echo number_format($total_pengeluaran, 2, ',', '.'); ?></h3>
                            <p>Total pengeluaran</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="laporan_keuangan.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <!-- Jam Lokal -->
            <div class="row">
                <div class="col-12">
                    <p id="clock">Waktu Sekarang: </p>
                </div>
            </div>

            <!-- Main row -->
            <div class="row">
                <!-- Kolam A dan B -->
                <?php foreach ($kolams as $kolam): ?>
                    <div class="col-md-6">
                        <div class="card">
                            <?php
                                // Menentukan gambar berdasarkan nama kolam
                                $gambar = 'default.jpg'; // Gambar default
                                if ($kolam['nama'] == 'Kolam A') {
                                    $gambar = 'kolam.jpg';
                                } elseif ($kolam['nama'] == 'Kolam B') {
                                    $gambar = 'kolam1.jpg';
                                }
                            ?>
                            <img src="dist/img/<?php echo htmlspecialchars($gambar); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($kolam['nama']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($kolam['nama']); ?></h5>
                                <p class="card-text">Jumlah Pengunjung: <?php echo htmlspecialchars($kolam['jumlah_pengunjung']); ?></p>
                                <p class="card-text">Kapasitas Maksimal: <?php echo htmlspecialchars($kolam['kapasitas']); ?></p>
                                <?php if ($kolam['jumlah_pengunjung'] >= $kolam['kapasitas']): ?>
                                    <p class="text-danger">Kolam penuh</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- End Kolam A dan B -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript untuk jam lokal -->
    <script>
        function updateClock() {
            var now = new Date();
            var options = {
                timeZone: 'Asia/Makassar', // Zona waktu untuk Kalimantan Selatan (Banjarbaru)
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false // Format 24 jam
            };
            var timeString = now.toLocaleTimeString('id-ID', options);
            document.getElementById('clock').textContent = 'Waktu Sekarang: ' + timeString;
        }

        // Update jam setiap detik
        setInterval(updateClock, 1000);

        // Inisialisasi jam saat pertama kali dimuat
        updateClock();
    </script>
</body>
</html>
