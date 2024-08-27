<?php
session_start();
include('../config/config.php');

// Periksa apakah admin sudah login
if (!isset($_SESSION['username'])) {
    die("Admin ID belum diatur. Pastikan admin sudah login.");
}

$username = $_SESSION['username'];

// Ambil bulan, tahun, dan periode dari formulir jika ada
$bulan_filter = isset($_POST['bulan_filter']) ? $_POST['bulan_filter'] : '';
$tahun_filter = isset($_POST['tahun_filter']) ? $_POST['tahun_filter'] : '';
$periode_filter = isset($_POST['periode_filter']) ? $_POST['periode_filter'] : 'bulanan';

$show_warning = false;
$warning_message = '';

// Validasi input
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($tahun_filter) || ($periode_filter == 'bulanan' && empty($bulan_filter))) {
        $show_warning = true;
        $warning_message = 'Semua field harus diisi. Pastikan Anda memilih bulan, tahun, dan periode yang sesuai.';
    } else {
        // Query untuk mengambil data penangkapan ikan dari semua pengunjung
        $sql = "SELECT i.nama, p.tanggal_penangkapan, SUM(p.jumlah) AS total_jumlah
                FROM penangkapan_ikan p
                JOIN ikan i ON p.ikan_id = i.id";

        $conditions = [];
        $params = [];
        $types = '';

        switch ($periode_filter) {
            case 'bulanan':
                $conditions[] = "MONTH(p.tanggal_penangkapan) = ?";
                $conditions[] = "YEAR(p.tanggal_penangkapan) = ?";
                $params[] = $bulan_filter;
                $params[] = $tahun_filter;
                $types = 'ii';
                break;
            case 'triwulan':
                $triwulan = ceil((int)$bulan_filter / 3);
                $conditions[] = "QUARTER(p.tanggal_penangkapan) = ?";
                $conditions[] = "YEAR(p.tanggal_penangkapan) = ?";
                $params[] = $triwulan;
                $params[] = $tahun_filter;
                $types = 'ii';
                break;
            case 'tahunan':
                $conditions[] = "YEAR(p.tanggal_penangkapan) = ?";
                $params[] = $tahun_filter;
                $types = 'i';
                break;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " GROUP BY i.id, i.nama, p.tanggal_penangkapan";

        if ($stmt = $koneksi->prepare($sql)) {
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Hitung total keseluruhan ikan yang ditangkap
            $total_ikan = 0;
            while ($row = $result->fetch_assoc()) {
                $total_ikan += (int) $row['total_jumlah']; // Konversi ke integer jika perlu
            }
            
            // Reset pointer hasil untuk menampilkan data lagi
            $result->data_seek(0);
        } else {
            die("Gagal mempersiapkan query: " . $koneksi->error);
        }
    }
}

$koneksi->close();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penangkapan Ikan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script>
        function printPage() {
            window.print();
        }

        // Fungsi untuk menghapus peringatan setelah 2 detik
        function hideAlertAfterDelay() {
            const alert = document.getElementById('warningAlert');
            if (alert) {
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 2000);
            }
        }

        // Panggil fungsi setelah halaman dimuat jika ada peringatan
        window.onload = hideAlertAfterDelay;
    </script>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header text-center">
                <h3>Daftar Penangkapan Ikan</h3>
            </div>
            <div class="card-body">
                <!-- Tombol Cetak -->
                <?php if (isset($result) && $result->num_rows > 0 && !$show_warning): ?>
                    <a href="jumlah_ikan_print.php"  class="btn btn-success mb-3">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                <?php else: ?>
                    <a href="jumlah_ikan_print.php" class="btn btn-secondary mb-3 disabled">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                <?php endif; ?>
                <!-- Tombol Kembali -->
                <a href="http://localhost/coba/app/" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <!-- Form Filter -->
                <form action="" method="post">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="periode_filter" class="form-label">Periode</label>
                            <select id="periode_filter" name="periode_filter" class="form-select">
                                <option value="bulanan" <?php echo $periode_filter == 'bulanan' ? 'selected' : ''; ?>>Bulanan</option>
                                <option value="triwulan" <?php echo $periode_filter == 'triwulan' ? 'selected' : ''; ?>>Triwulan</option>
                                <option value="tahunan" <?php echo $periode_filter == 'tahunan' ? 'selected' : ''; ?>>Tahunan</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="bulan_filter" class="form-label">Bulan</label>
                            <select id="bulan_filter" name="bulan_filter" class="form-select" <?php echo $periode_filter == 'tahunan' ? 'disabled' : ''; ?>>
                                <option value="">Pilih Bulan</option>
                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    $bulan = str_pad($i, 2, '0', STR_PAD_LEFT);
                                    $selected = ($bulan == $bulan_filter) ? 'selected' : '';
                                    echo "<option value='$bulan' $selected>" . date('F', mktime(0, 0, 0, $i, 10)) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="tahun_filter" class="form-label">Tahun</label>
                            <input type="number" id="tahun_filter" name="tahun_filter" class="form-control" value="<?php echo htmlspecialchars($tahun_filter); ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>

                <?php if ($show_warning): ?>
                    <div id="warningAlert" class="alert alert-warning mt-3">
                        <?php echo htmlspecialchars($warning_message); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($result) && $result->num_rows > 0): ?>
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Nama Ikan</th>
                                <th>Tanggal Penangkapan</th>
                                <th>Total Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tanggal_penangkapan']); ?></td>
                                    <td><?php echo htmlspecialchars($row['total_jumlah']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- Baris Total Keseluruhan -->
                            <tr class="table-dark">
                                <td><strong>Total Keseluruhan:</strong></td>
                                <td></td>
                                <td><strong><?php echo number_format($total_ikan); ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                <?php elseif (isset($result)): ?>
                    <p>Belum ada data penangkapan ikan untuk periode yang dipilih.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
