<?php
include('../config/config.php');

$sql = "SELECT * FROM momen";
$result = $koneksi->query($sql);
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Momen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Data Momen</h1>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="../uploads/<?php echo htmlspecialchars($row['gambar']); ?>" class="card-img-top" alt="Gambar">
                        <div class="card-body">
                            <p class="card-text"><?php echo htmlspecialchars($row['kesan_pesan']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
