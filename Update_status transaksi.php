<?php
// Ambil pesan dari URL
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Berhasil</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="alert alert-success mt-5">
        <?php echo $message; ?>
    </div>
    <!-- Tombol untuk kembali ke halaman utama atau login -->
    <a href="index.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
</div>

<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
