<?php
session_start();
include('../config/config.php');

// Periksa apakah admin sudah login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
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

// Update status pemesanan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $sql_update = "UPDATE pemesanan_tiket SET status = ? WHERE id = ?";
    $stmt_update = $koneksi->prepare($sql_update);
    $stmt_update->bind_param("si", $status, $pemesanan_id);
    $stmt_update->execute();
    $stmt_update->close();

    // Log ke laporan keuangan
    $sql_laporan = "INSERT INTO laporan_keuangan (pemesanan_id, total_harga, status) VALUES (?, ?, ?)";
    $stmt_laporan = $koneksi->prepare($sql_laporan);
    $stmt_laporan->bind_param("iis", $pemesanan_id, $pemesanan['total_harga'], $status);
    $stmt_laporan->execute();
    $stmt_laporan->close();

    // Kirim nota pembayaran jika disetujui
    if ($status == 'disetujui') {
        // Kirim nota dengan barcode kepada pengunjung
        // Ini memerlukan pengaturan email atau sistem pengiriman nota
    }

    header("Location: manage_pemesanan.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pemesanan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Manage Pemesanan</h3>
            </div>
            <div class="card-body">
                <p><strong>ID Pemesanan:</strong> <?php echo htmlspecialchars($pemesanan['id']); ?></p>
                <p><strong>Username Pengunjung:</strong> <?php echo htmlspecialchars($pemesanan['username']); ?></p>
                <p><strong>Total Harga:</strong> Rp<?php echo number_format($pemesanan['total_harga'], 0, ',', '.'); ?></p>
                
                <form action="manage_pemesanan.php?pemesanan_id=<?php echo htmlspecialchars($pemesanan_id); ?>" method="post">
                    <div class="mb-3">
                        <label for="status" class="form-label">Ubah Status:</label>
                        <select id="status" name="status" class="form-select">
                            <option value="pending" <?php echo $pemesanan['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="disetujui" <?php echo $pemesanan['status'] == 'disetujui' ? 'selected' : ''; ?>>Disetujui</option>
                            <option value="berhasil" <?php echo $pemesanan['status'] == 'berhasil' ? 'selected' : ''; ?>>Berhasil</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
