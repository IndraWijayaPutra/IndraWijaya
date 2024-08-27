<?php
include '../../config/config.php';

// Mengambil data pengunjung dengan filter
$filterName = isset($_GET['filter_name']) ? $_GET['filter_name'] : '';

// Query untuk mengambil data pengunjung dengan filter nama
$sql = "SELECT * FROM pengunjung WHERE username LIKE ? ORDER BY FIELD(role, 'Admin', 'Pengunjung')";

// Menyiapkan statement
$stmt = $koneksi->prepare($sql);

if ($stmt === false) {
    die("Gagal menyiapkan statement: " . $koneksi->error);
}

// Mengikat parameter dengan wildcard untuk LIKE
$filterNameParam = "%$filterName%";
$stmt->bind_param("s", $filterNameParam);

// Menjalankan statement
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengunjung</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <style>
        .table th, .table td {
            text-align: center;
        }
        .btn-print {
            background-color: #28a745; /* Warna hijau */
            color: white;
            border: none;
        }
        .btn-print:hover {
            background-color: #218838; /* Warna hijau gelap */
            color: white;
        }
        .alert {
            display: none; /* Hide alerts by default */
        }
        .btn-back {
            background-color: #007bff; /* Warna biru */
            color: white;
            border: none;
        }
        .btn-back:hover {
            background-color: #0056b3; /* Warna biru gelap */
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Data User</h2>
    <!-- Filter Form -->
    <form method="GET" class="mb-4">
        <div class="form-row">
            <div class="col-md-8 mb-3">
                <input type="text" class="form-control" name="filter_name" placeholder="Cari nama" value="<?php echo htmlspecialchars($filterName); ?>">
            </div>
            <div class="col-md-4 mb-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="user.php" class="btn btn-secondary">Reset Filter</a>
            </div>
        </div>
    </form>

    <div class="mb-3">
        <a href="add_user.php" class="btn btn-primary">Tambah User</a>
        <a href="print.php" class="btn btn-print">Cetak</a>
        <a href="http://localhost/coba/app/index.php" class="btn btn-back">Kembali</a> <!-- Tombol Kembali -->
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>No Telepon</th>
                    <th>Alamat</th>
                    <th>Umur</th>
                    <th>Role</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $no = 1; // Inisialisasi nomor urut
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['no_telepon']}</td>
                                <td>{$row['alamat']}</td>
                                <td>{$row['umur']}</td>
                                <td>{$row['role']}</td>
                                <td>{$row['tanggal_daftar']}</td>
                                <td>
                                    <a href='edit_user.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='#' data-id='{$row['id']}' class='btn btn-danger btn-sm delete-btn'>Hapus</a>
                                </td>
                            </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='10'>Tidak ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tampilkan pesan sukses jika ada parameter success di query string
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil diperbaharui.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'http://localhost/coba/app/view/user.php';
        });
    }
    
    // Konfirmasi hapus
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            const userId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to delete.php with user ID
                    window.location.href = `delete.php?id=${userId}`;
                }
            });
        });
    });
});
</script>
</body>
</html>
