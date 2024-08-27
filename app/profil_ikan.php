<?php
include('../config/config.php');

// Query untuk mengambil data ikan dari database
$sql = "SELECT nama, jenis, habitat, makanan, berat_rata_rata, foto FROM ikan";
$result = $koneksi->query($sql);

$ikan_list = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ikan_list[] = $row;
    }
}
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ikan</title>
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
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: none;
            border-radius: 8px;
        }
        .card-img-top {
            border-radius: 8px 8px 0 0;
            height: 200px;
            object-fit: cover;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card-img-top img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
        .btn-back {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-align: center;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Ikan</h1>
        <a href="http://localhost/coba/app/" class="btn btn-back mb-3">Kembali</a>
        <div class="row">
            <?php foreach ($ikan_list as $ikan): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-img-top">
                            <img src="dist/img/<?php echo htmlspecialchars($ikan['foto'] ? $ikan['foto'] : 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($ikan['nama']); ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($ikan['nama']); ?></h5>
                            <p class="card-text"><strong>Jenis:</strong> <?php echo htmlspecialchars($ikan['jenis']); ?></p>
                            <p class="card-text"><strong>Habitat:</strong> <?php echo htmlspecialchars($ikan['habitat']); ?></p>
                            <p class="card-text"><strong>Makanan:</strong> <?php echo htmlspecialchars($ikan['makanan']); ?></p>
                            <p class="card-text"><strong>Berat Rata-Rata:</strong> <?php echo htmlspecialchars($ikan['berat_rata_rata']); ?> kg</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
