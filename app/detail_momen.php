<?php
include('../config/config.php');

// Ambil data momen
$sql_momen = "SELECT * FROM momen";
$result_momen = $koneksi->query($sql_momen);

// Menyiapkan array untuk data momen
$momen_list = [];
while ($row = $result_momen->fetch_assoc()) {
    $momen_list[] = $row;
}
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Momen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Detail Momen</h2>
        <?php if (count($momen_list) > 0): ?>
            <?php foreach ($momen_list as $momen): ?>
                <div class="card mb-3">
                    <img src="uploads/<?php echo htmlspecialchars($momen['gambar']); ?>" class="card-img-auto" alt="Gambar Momen">
                    <div class="card-body">
                        <h5 class="card-title">Kesan dan Pesan</h5>
                        <p class="card-text"><?php echo htmlspecialchars($momen['kesan_pesan']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada momen yang dibagikan.</p>
        <?php endif; ?>
    </div>
</body>
</html>
