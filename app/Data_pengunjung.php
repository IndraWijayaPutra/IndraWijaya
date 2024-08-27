<?php
session_start();
include('../config/config.php');

// Ambil bulan dan tahun dari parameter GET, jika tidak ada default ke string kosong
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

// Buat kueri SQL dengan filter bulan dan tahun
$sql = "SELECT p.username, p.alamat, p.umur, pt.tanggal_pemesanan
        FROM pengunjung p
        LEFT JOIN pemesanan_tiket pt ON p.id = pt.pengunjung_id
        WHERE (MONTH(pt.tanggal_pemesanan) = ? OR ? = '')
          AND (YEAR(pt.tanggal_pemesanan) = ? OR ? = '')";

// Siapkan pernyataan
$stmt = $koneksi->prepare($sql);

// Bind parameter
$search_bulan = $bulan ? $bulan : '';
$search_tahun = $tahun ? $tahun : '';
$stmt->bind_param("iiii", $search_bulan, $search_bulan, $search_tahun, $search_tahun);
$stmt->execute();

// Ambil hasil
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengunjung</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap-theme.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .card-header {
            background-color: #007bff;
            color: #fff;
        }
        .table thead th {
            background-color: #007bff;
            color: #fff;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header text-center">
                <h3>Data Pengunjung Pemancingan</h3>
            </div>
            <div class="card-body">
                <a href="http://localhost/coba/app/index.php?" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Kembali</a>

                <form method="GET" action="" class="mb-4">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="bulan">Pilih Bulan:</label>
                            <select name="bulan" id="bulan" class="form-control">
                                <option value="">-- Semua Bulan --</option>
                                <option value="01" <?php echo $bulan === '01' ? 'selected' : ''; ?>>Januari</option>
                                <option value="02" <?php echo $bulan === '02' ? 'selected' : ''; ?>>Februari</option>
                                <option value="03" <?php echo $bulan === '03' ? 'selected' : ''; ?>>Maret</option>
                                <option value="04" <?php echo $bulan === '04' ? 'selected' : ''; ?>>April</option>
                                <option value="05" <?php echo $bulan === '05' ? 'selected' : ''; ?>>Mei</option>
                                <option value="06" <?php echo $bulan === '06' ? 'selected' : ''; ?>>Juni</option>
                                <option value="07" <?php echo $bulan === '07' ? 'selected' : ''; ?>>Juli</option>
                                <option value="08" <?php echo $bulan === '08' ? 'selected' : ''; ?>>Agustus</option>
                                <option value="09" <?php echo $bulan === '09' ? 'selected' : ''; ?>>September</option>
                                <option value="10" <?php echo $bulan === '10' ? 'selected' : ''; ?>>Oktober</option>
                                <option value="11" <?php echo $bulan === '11' ? 'selected' : ''; ?>>November</option>
                                <option value="12" <?php echo $bulan === '12' ? 'selected' : ''; ?>>Desember</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tahun">Pilih Tahun:</label>
                            <select name="tahun" id="tahun" class="form-control">
                                <option value="">-- Semua Tahun --</option>
                                <?php
                                // Menampilkan daftar tahun dinamis berdasarkan data yang ada di database
                                $year_query = mysqli_query($koneksi, "SELECT DISTINCT YEAR(tanggal_pemesanan) AS tahun FROM pemesanan_tiket ORDER BY tahun DESC");
                                while ($row = mysqli_fetch_assoc($year_query)) {
                                    $year = $row['tahun'];
                                    echo '<option value="' . $year . '"' . ($tahun === $year ? ' selected' : '') . '>' . $year . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </form>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Alamat</th>
                            <th>Umur</th>
                            <th>Tanggal Pemesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                    <td><?php echo htmlspecialchars($row['umur']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tanggal_pemesanan']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
