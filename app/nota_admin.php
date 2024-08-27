<?php
session_start();
include('../config/config.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pesanan dari parameter URL
if (isset($_GET['pemesanan_id'])) {
    $pemesanan_id = intval($_GET['pemesanan_id']);

    // Ambil data pesanan dari database
    $sql = "SELECT pt.id, pt.tanggal_pemesanan, pt.status, pt.total_harga, pt.status_nota, p.username
            FROM pemesanan_tiket pt
            LEFT JOIN pengunjung p ON pt.pengunjung_id = p.id
            WHERE pt.id = ?";
    
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $pemesanan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    } else {
        die("Data tidak ditemukan.");
    }
} else {
    die("ID pesanan tidak ditemukan.");
}

// Menangani pengiriman nota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kirim_nota'])) {
    $update_sql = "UPDATE pemesanan_tiket SET status_nota = 'Dikirim' WHERE id = ?";
    $update_stmt = $koneksi->prepare($update_sql);
    $update_stmt->bind_param('i', $pemesanan_id);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Nota berhasil dikirim.'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Nota Pesanan</h2>
        <div class="card">
            <div class="card-header">
                Nota Pesanan ID: <?php echo htmlspecialchars($data['id']); ?>
            </div>
            <div class="card-body">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($data['username']); ?></p>
                <p><strong>Tanggal Pemesanan:</strong> <?php echo htmlspecialchars($data['tanggal_pemesanan']); ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst(htmlspecialchars($data['status'])); ?></p>
                <p><strong>Total Harga:</strong> Rp<?php echo number_format($data['total_harga'], 2, ',', '.'); ?></p>
                <p><strong>Status Nota:</strong> <?php echo ucfirst(htmlspecialchars($data['status_nota'])); ?></p>
            </div>
            <div class="card-footer">
                <?php if ($data['status_nota'] === 'Belum Dikirim'): ?>
                    <form method="post" action="">
                        <button type="submit" name="kirim_nota" class="btn btn-success">Kirim Nota ke Pengunjung</button>
                    </form>
                <?php else: ?>
                    <p>Nota sudah dikirim.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
