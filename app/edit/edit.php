<?php
session_start();
include('../../config/config.php'); // Path relatif yang benar

// Cek jika koneksi database berhasil
if (!isset($koneksi)) {
    die("Koneksi database gagal.");
}

// Cek jika parameter 'id' ada di URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM pemesanan_tiket WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    if (!$result) {
        die("Query gagal: " . mysqli_error($koneksi));
    }
    $data = mysqli_fetch_assoc($result);
} else {
    die("ID tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pemesanan Tiket</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Data Pemesanan Tiket</h2>
    <!-- Formulir untuk mengedit data pemesanan tiket -->
<form action="../add/update/update_data.php" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">

    <div class="form-group">
        <label for="tanggal_pemesanan">Tanggal Pemesanan:</label>
        <input type="date" class="form-control" id="tanggal_pemesanan" name="tanggal_pemesanan" value="<?php echo htmlspecialchars($data['tanggal_pemesanan']); ?>" required>
    </div>

    <div class="form-group">
        <label for="status_pemesanan">Status Pemesanan:</label>
        <select class="form-control" id="status_pemesanan" name="status_pemesanan" required>
            <option value="Dalam Proses" <?php echo $data['status_pemesanan'] == 'Dalam Proses' ? 'selected' : ''; ?>>Dalam Proses</option>
            <option value="Disetujui" <?php echo $data['status_pemesanan'] == 'Disetujui' ? 'selected' : ''; ?>>Disetujui</option>
            <option value="Ditolak" <?php echo $data['status_pemesanan'] == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
        </select>
    </div>

    <div class="form-group">
        <label for="status_nota">Status Nota:</label>
        <select class="form-control" id="status_nota" name="status_nota" required>
            <option value="Belum Dikirim" <?php echo $data['status_nota'] == 'Belum Dikirim' ? 'selected' : ''; ?>>Belum Dikirim</option>
            <option value="Dikirim" <?php echo $data['status_nota'] == 'Dikirim' ? 'selected' : ''; ?>>Dikirim</option>
        </select>
    </div>

    <div class="form-group">
        <label for="kode_pesanan">Kode Pesanan:</label>
        <input type="text" class="form-control" id="kode_pesanan" name="kode_pesanan" value="<?php echo htmlspecialchars($data['kode_pesanan']); ?>" required>
    </div>

    <div class="form-group">
        <label for="jumlah_pengunjung">Jumlah Pengunjung:</label>
        <input type="number" class="form-control" id="jumlah_pengunjung" name="jumlah_pengunjung" value="<?php echo htmlspecialchars($data['jumlah_pengunjung']); ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
