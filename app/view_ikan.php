<?php
include('../config/config.php');

// Query untuk mengambil data ikan
$sql = "SELECT nama, jenis FROM ikan";
$result = $koneksi->query($sql);

$ikan_list = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ikan_list[] = $row;
    }
}
$koneksi->close();

// Pesan status
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Profil Ikan</title>
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
        .alert {
            margin-top: 20px;
            position: relative;
        }
        .alert.hide {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Daftar Profil Ikan</h1>
        <?php if ($status == 'added'): ?>
            <div id="message" class="alert alert-success">
                Data ikan berhasil ditambahkan.
            </div>
        <?php elseif ($status == 'updated'): ?>
            <div id="message" class="alert alert-success">
                Data ikan berhasil diperbarui.
            </div>
        <?php elseif ($status == 'deleted'): ?>
            <div id="message" class="alert alert-success">
                Data ikan berhasil dihapus.
            </div>
        <?php elseif ($status == 'error'): ?>
            <div id="message" class="alert alert-danger">
                Terjadi kesalahan saat memproses data.
            </div>
        <?php endif; ?>
        <a href="create_ikan.php" class="btn btn-primary mb-3">Tambah Profil Ikan</a>
        <a href="index.php" class="btn btn-secondary mb-3">Kembali</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; // Mulai dari nomor 1
                foreach ($ikan_list as $ikan): ?>
                <tr>
                    <td><?php echo htmlspecialchars($no++); ?></td>
                    <td><?php echo htmlspecialchars($ikan['nama']); ?></td>
                    <td><?php echo htmlspecialchars($ikan['jenis']); ?></td>
                    <td>
                        <a href="edit_ikan.php?nama=<?php echo urlencode($ikan['nama']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_ikan.php?nama=<?php echo urlencode($ikan['nama']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus ikan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var message = document.getElementById('message');
            if (message) {
                setTimeout(function() {
                    message.classList.add('hide');
                }, 3000); // Pesan akan hilang setelah 3000 ms (3 detik)
            }
        });
    </script>
</body>
</html>
        