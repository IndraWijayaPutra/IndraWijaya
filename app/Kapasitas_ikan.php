<?php
include('../config/config.php');

// Query untuk mengambil data kapasitas ikan dari tabel 'ikan'
$sql = "SELECT * FROM ikan";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kapasitas Ikan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .table thead th, .table tbody td {
                border: 1px solid #000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header text-center">
                <h3>Kapasitas Ikan</h3>
            </div>
            <div class="card-body">
                <!-- Tombol Kembali -->
                <a href="http://localhost/coba/app/" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <!-- Tombol Cetak -->
                <a href="kapasitas_ikan_print.php" class="btn btn-primary mb-3" target="_blank">
    <i class="fas fa-print"></i> Cetak
            </a>
                <?php
                if ($result->num_rows > 0) {
                    echo "<table class='table table-bordered'>
                            <thead>
                                <tr>
                                    <th>Nama Ikan</th>
                                    <th>Jenis</th>
                                    <th>Berat KG</th>
                                    <th>Kolam ID</th>
                                    <th>Jumlah</th>
                                    <th>Kapasitas</th>
                                </tr>
                            </thead>
                            <tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['nama']) . "</td>
                                <td>" . htmlspecialchars($row['jenis']) . "</td>
                                <td>" . htmlspecialchars($row['ukuran']) . "</td>
                                <td>" . htmlspecialchars($row['kolam_id']) . "</td>
                                <td>" . htmlspecialchars($row['jumlah']) . "</td>
                                <td>" . htmlspecialchars($row['kapasitas']) . "</td>
                            </tr>";
                    }
                    echo "  </tbody>
                         </table>";
                } else {
                    echo "Tidak ada data kapasitas ikan.";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
