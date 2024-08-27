<?php
session_start();
include('../config/config.php');

// Periksa apakah pengguna sudah login dan merupakan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil data pesanan dengan status 'Dalam Proses'
$sql = "SELECT pt.id, pt.tanggal_pemesanan, pt.status_pemesanan, pt.total_harga, p.username, pt.kode_pesanan, pt.status_nota
        FROM pemesanan_tiket pt
        LEFT JOIN pengunjung p ON pt.pengunjung_id = p.id
        WHERE pt.status_pemesanan = 'Dalam Proses'";

$result = $koneksi->query($sql);

if (!$result) {
    die("Query Error: " . $koneksi->error);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Pengunjung</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #f8f9fa;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
        .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Pesanan Pengunjung</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Username</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Status</th>
                        <th>Total Harga</th>
                        <th>Status Nota</th>
                        <th>Kode Pesanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['tanggal_pemesanan']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($row['status_pemesanan'])); ?></td>
                            <td>Rp<?php echo number_format($row['total_harga'], 2, ',', '.'); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($row['status_nota'])); ?></td>
                            <td><?php echo htmlspecialchars($row['kode_pesanan']); ?></td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="setujuiPesanan('<?php echo $row['kode_pesanan']; ?>')">Setujui</button>
                                <button class="btn btn-danger btn-sm" onclick="tolakPesanan('<?php echo $row['kode_pesanan']; ?>')">Tolak</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <a href="http://localhost/coba/app/index.php?" class="btn btn-secondary">Kembali</a>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function setujuiPesanan(kode_pesanan) {
    if (confirm('Apakah Anda yakin ingin menyetujui pesanan ini?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'setuju.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                if (xhr.responseText === 'success') {
                    setTimeout(function() {
                        alert('Pesanan telah disetujui.');
                        location.reload(); // Refresh halaman untuk memperbarui status
                    }, 2000);
                } else {
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            } else {
                alert('Terjadi kesalahan.');
            }
        };
        xhr.send('kode_pesanan=' + encodeURIComponent(kode_pesanan));
    }
}

function tolakPesanan(kode_pesanan) {
    if (confirm('Apakah Anda yakin ingin menolak pesanan ini?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'tolak.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                if (xhr.responseText === 'success') {
                    setTimeout(function() {
                        alert('Pesanan ditolak.');
                        location.reload(); // Refresh halaman untuk memperbarui status
                    }, 2000);
                } else {
                    alert('Terjadi kesalahan: ' + xhr.responseText);
                }
            } else {
                alert('Terjadi kesalahan.');
            }
        };
        xhr.send('kode_pesanan=' + encodeURIComponent(kode_pesanan));
    }
}
    </script>
</body>
</html>
