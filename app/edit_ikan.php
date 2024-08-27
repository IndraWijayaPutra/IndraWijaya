<?php
include('../config/config.php');
session_start(); // Pastikan session dimulai

// Ambil nama ikan dari parameter URL
$nama = isset($_GET['nama']) ? $_GET['nama'] : '';

if (empty($nama)) {
    die("Nama ikan tidak valid.");
}

// Ambil data ikan berdasarkan nama
$sql = "SELECT * FROM ikan WHERE nama = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $nama);
$stmt->execute();
$result = $stmt->get_result();
$ikan = $result->fetch_assoc();

if (!$ikan) {
    die("Data ikan tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Ikan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Edit Profil Ikan</h1>
        <form action="proses_edit_ikan.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="nama_lama" value="<?php echo htmlspecialchars($ikan['nama']); ?>">
            <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($ikan['foto']); ?>">
            
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($ikan['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label for="jenis">Jenis</label>
                <input type="text" class="form-control" id="jenis" name="jenis" value="<?php echo htmlspecialchars($ikan['jenis']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ukuran">Ukuran (m)</label>
                <input type="number" step="0.01" class="form-control" id="ukuran" name="ukuran" value="<?php echo htmlspecialchars($ikan['ukuran']); ?>" required>
            </div>
            <div class="form-group">
                <label for="kolam_id">Kolam ID</label>
                <input type="number" class="form-control" id="kolam_id" name="kolam_id" value="<?php echo htmlspecialchars($ikan['kolam_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?php echo htmlspecialchars($ikan['jumlah']); ?>" required>
            </div>
            <div class="form-group">
                <label for="habitat">Habitat</label>
                <textarea class="form-control" id="habitat" name="habitat" required><?php echo htmlspecialchars($ikan['habitat']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="makanan">Makanan</label>
                <textarea class="form-control" id="makanan" name="makanan" required><?php echo htmlspecialchars($ikan['makanan']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="berat_rata_rata">Berat Rata-Rata (kg)</label>
                <input type="number" step="0.01" class="form-control" id="berat_rata_rata" name="berat_rata_rata" value="<?php echo htmlspecialchars($ikan['berat_rata_rata']); ?>" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
                <?php if ($ikan['foto']): ?>
                    <img src="dist/img/<?php echo htmlspecialchars($ikan['foto']); ?>" alt="<?php echo htmlspecialchars($ikan['nama']); ?>" width="150" class="mt-3">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="view_ikan.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
