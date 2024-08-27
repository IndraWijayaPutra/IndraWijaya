<?php
session_start();
include('../config/config.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Periksa apakah ID pemesanan tersedia di URL
if (!isset($_GET['pemesanan_id'])) {
    die("ID pemesanan tidak ditemukan.");
}

$pemesanan_id = intval($_GET['pemesanan_id']);

// Ambil data pemesanan dari database
$sql = "SELECT pt.*, 
               GROUP_CONCAT(wk.nama SEPARATOR ', ') AS nama_kuliner, 
               GROUP_CONCAT(pk.jumlah SEPARATOR ', ') AS jumlah_kuliner
        FROM pemesanan_tiket pt
        LEFT JOIN pemesanan_kuliner pk ON pt.id = pk.pemesanan_id
        LEFT JOIN wisata_kuliner wk ON pk.kuliner_id = wk.id
        WHERE pt.id = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $pemesanan_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Pemesanan tidak ditemukan.");
}

$pemesanan = $result->fetch_assoc();
$stmt->close();

// Tetapkan harga tiket tetap
$harga_tiket = 10000; // Harga tiket tetap 10.000 IDR
$total_harga_tiket = $harga_tiket; // Total harga tiket

// Hitung total harga kuliner
$total_harga_kuliner = 0;
$kuliner_names = explode(', ', $pemesanan['nama_kuliner'] ?? '');
$kuliner_amounts = explode(', ', $pemesanan['jumlah_kuliner'] ?? '');

foreach ($kuliner_amounts as $jumlah) {
    $total_harga_kuliner += 15000 * intval($jumlah); // Harga kuliner 15.000 IDR per item
}

// Total harga keseluruhan (tiket + kuliner)
$total_harga = $total_harga_tiket + $total_harga_kuliner;

// Menentukan kolam dengan kapasitas paling sedikit
$sql = "SELECT id, nama, kapasitas - jumlah_pengunjung AS sisa_kapasitas
        FROM kolam
        ORDER BY sisa_kapasitas ASC
        LIMIT 1";
$stmt = $koneksi->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Tidak ada kolam yang tersedia.");
}

$kolam = $result->fetch_assoc();
$kolam_id = $kolam['id'];
$kolam_nama = $kolam['nama'];
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pemesanan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        .kode-pemesanan {
            color: #28a745; /* Warna hijau */
            font-weight: bold;
            font-size: 1.2em;
        }
        .print-section {
            display: none;
        }
        @media print {
            .print-section {
                display: block;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Konfirmasi Pemesanan</h3>
            </div>
            <div class="card-body">
                <p><strong>ID Pemesanan:</strong> <?php echo htmlspecialchars($pemesanan_id); ?></p>
                <p><strong>Username Pengunjung:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>Tanggal Pemesanan:</strong> <?php echo htmlspecialchars($pemesanan['tanggal_pemesanan'] ?? ''); ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($pemesanan['status_pemesanan'] ?? '')); ?></p>
                <p><strong>Total Harga Tiket:</strong> Rp<?php echo number_format($total_harga_tiket, 0, ',', '.'); ?></p>
                <p><strong>Total Harga Kuliner:</strong> Rp<?php echo number_format($total_harga_kuliner, 0, ',', '.'); ?></p>
                <p><strong>Total Harga Keseluruhan:</strong> Rp<?php echo number_format($total_harga, 0, ',', '.'); ?></p>

                <h4>Detail Kuliner:</h4>
                <ul>
                    <?php
                    foreach ($kuliner_names as $index => $nama_kuliner) {
                        if (!empty($nama_kuliner)) {
                            $jumlah = $kuliner_amounts[$index] ?? '0';
                            echo "<li>" . htmlspecialchars($nama_kuliner) . " x " . htmlspecialchars($jumlah) . "</li>";
                        }
                    }
                    ?>
                </ul>

                <p><strong>Kolam Tujuan:</strong> <?php echo htmlspecialchars($kolam_nama); ?></p>

                <p class="kode-pemesanan">Gunakan kode ini untuk melihat pesanan Anda sudah disetujui: <strong><?php echo htmlspecialchars($pemesanan['kode_pesanan'] ?? 'Kode tidak tersedia'); ?></strong></p>

                <div class="no-print">
                    <a href="bayar.php?pemesanan_id=<?php echo htmlspecialchars($pemesanan_id); ?>" class="btn btn-primary">Bayar</a>
                    <a href="http://localhost/coba/app" class="btn btn-secondary">Kembali ke Dashboard</a>
                    <button class="btn btn-success" onclick="window.print()">Cetak Konfirmasi</button>
                </div>

                <div class="print-section">
                <p class="kode-pemesanan">Gunakan kode ini untuk melihat pesanan Anda sudah disetujui: <strong><?php echo htmlspecialchars($pemesanan['kode_pesanan']); ?></strong></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
