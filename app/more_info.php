<?php
include('../config/config.php');

// Ambil semua data momen dari database
$sql = "SELECT judul_laporan, nama_pengunjung, tanggal, kesan_pesan, gambar FROM momen";
$result = $koneksi->query($sql);
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Momen yang Dibagikan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        .img-auto {
            max-width: 10%; /* Membatasi lebar gambar sesuai dengan lebar kontainer */
            height: auto; /* Menjaga rasio aspek gambar */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Detail Momen yang Dibagikan</h1>
        <a href="http://localhost/coba/app/index.php?" class="btn btn-primary mb-3">Kembali</a>
        <?php if ($result->num_rows > 0): ?>
            <div class="list-group">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="list-group-item">
                        <h5>Judul: <?php echo htmlspecialchars($row['judul_laporan']); ?></h5>
                        <p><strong>Nama Pengunjung:</strong> <?php echo htmlspecialchars($row['nama_pengunjung']); ?></p>
                        <p><strong>Tanggal:</strong> <?php echo htmlspecialchars($row['tanggal']); ?></p>
                        <p><strong>Komentar Pengunjung:</strong> <?php echo htmlspecialchars($row['kesan_pesan']); ?></p>
                        <?php if (!empty($row['gambar'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar" class="img-auto mt-3">
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Tidak ada data momen yang dibagikan.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
