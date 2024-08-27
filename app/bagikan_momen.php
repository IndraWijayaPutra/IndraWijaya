<?php
include('../config/config.php');

// Proses pengiriman formulir
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kesan_pesan = $_POST['kesan_pesan'];
    $file_gambar = $_FILES['gambar']['name'];
    $target_file = "../uploads/" . basename($file_gambar);

    // Upload gambar
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO momen (kesan_pesan, gambar) VALUES ('$kesan_pesan', '$file_gambar')";
        if ($koneksi->query($sql)) {
            $message = "Momen berhasil dibagikan!";
        } else {
            $message = "Terjadi kesalahan saat membagikan momen.";
        }
    } else {
        $message = "Gagal mengunggah gambar.";
    }
    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bagikan Momen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Bagikan Momen Anda</h2>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="kesan_pesan" class="form-label">Kesan dan Pesan</label>
                <textarea class="form-control" id="kesan_pesan" name="kesan_pesan" required></textarea>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Unggah Gambar</label>
                <input class="form-control" type="file" id="gambar" name="gambar" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
</body>
</html>
