<?php
include('../config/config.php');

// Periksa apakah formulir dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_pesanan = isset($_POST['kode_pesanan']) ? $_POST['kode_pesanan'] : '';

    // Validasi input
    if (empty($kode_pesanan)) {
        $error = "Kode pemesanan tidak boleh kosong.";
    } else {
        // Ambil data pemesanan berdasarkan kode
        $sql = "SELECT tanggal_pemesanan, status_pemesanan, total_harga FROM pemesanan_tiket WHERE kode_pesanan = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $kode_pesanan);
        $stmt->execute();
        $stmt->bind_result($tanggal_pemesanan, $status_pemesanan, $total_harga);
        if ($stmt->fetch()) {
            $data_found = true;
        } else {
            $error = "Kode pemesanan tidak ditemukan.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Melihat Status Pemesanan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        .status-approved {
            background-color: #28a745;
            color: white;
        }
        .status-default {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Melihat Status Pemesanan</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="kode_pesanan" class="form-label">Kode Pemesanan</label>
                        <input type="text" class="form-control" id="kode_pesanan" name="kode_pesanan" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Lihat Status</button>
                    <a href="http://localhost/coba/app/index.php?" class="btn btn-primary ">Kembali</a>
                </form>

                <?php if (isset($data_found) && $data_found): ?>
                <div class="mt-4">
                    <h4>Detail Pemesanan</h4>
                    <p><strong>Tanggal Pemesanan:</strong> <?php echo htmlspecialchars($tanggal_pemesanan); ?></p>
                    <p>
                        <strong>Status:</strong> 
                        <span class="badge <?php echo ($status_pemesanan === 'Disetujui') ? 'status-approved' : 'status-default'; ?>">
                            <?php echo htmlspecialchars($status_pemesanan); ?>
                        </span>
                    </p>
                    <p><strong>Total Harga:</strong> Rp<?php echo number_format($total_harga, 0, ',', '.'); ?></p>
                    <div class="mt-4">
                        <a href="http://localhost/coba/app/" class="btn btn-secondary">Kembali</a>
                        <a href="cetak_pemesanan.php?pemesanan_id=<?php echo urlencode($kode_pesanan); ?>" class="btn btn-info" target="_blank">Cetak</a>
                    </div>
                </div>
                <?php elseif (isset($error)): ?>
                <div class="alert alert-danger mt-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
