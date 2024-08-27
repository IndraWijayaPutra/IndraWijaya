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
$sql = "SELECT * FROM pemesanan_tiket WHERE id = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $pemesanan_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Pemesanan tidak ditemukan.");
}

$pemesanan = $result->fetch_assoc();
$stmt->close();

// Format harga
$total_harga = number_format($pemesanan['total_harga'], 0, ',', '.');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        @media print {
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
                <h3>Nota Pembayaran</h3>
            </div>
            <div class="card-body">
                <p><strong>ID Pemesanan:</strong> <?php echo $pemesanan['id']; ?></p>
                <p><strong>Username Pengunjung:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>Tanggal Pemesanan:</strong> <?php echo $pemesanan['tanggal_pemesanan']; ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($pemesanan['status']); ?></p>
                <p><strong>Total Harga:</strong> Rp<?php echo $total_harga; ?></p>

                <p>Silakan bayar total harga ke kasir.</p>

                <button class="btn btn-primary no-print" onclick="window.print()">Cetak Nota</button>
                <a href="http://localhost/coba/app" class="btn btn-secondary no-print">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <script>
        // Cetak halaman secara otomatis saat halaman dimuat
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
