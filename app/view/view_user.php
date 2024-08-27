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
            vertical-align: middle;
        }
        .btn-custom {
            margin-right: 5px;
        }
        .btn-print {
            background-color: #28a745;
            color: white;
        }
        .btn-print:hover {
            background-color: #218838;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
        .search-form {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4 text-center">Data User</h2>
    <!-- Filter Form -->
    <form method="GET" class="search-form mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="filter_name" placeholder="Cari nama" value="<?php echo htmlspecialchars($filterName); ?>">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary btn-custom">Filter</button>
                <a href="user.php" class="btn btn-secondary btn-custom">Reset</a>
            </div>
        </div>
    </form>

    <div class="mb-3 text-center">
        <a href="add_user.php" class="btn btn-primary btn-custom">Tambah User</a>
        <a href="print.php" class="btn btn-print btn-custom">Cetak</a>
        <a href="http://localhost/coba/app/index.php" class="btn btn-back btn-custom">Kembali</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
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
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['no_telepon']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['umur']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_daftar']) ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm btn-custom">Edit</a>
                                <button class="btn btn-danger btn-sm delete-btn btn-custom" data-id="<?= $row['id'] ?>">Hapus</button>
                                <a href="view_user.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm btn-custom">Detail</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center">Tidak ada data.</td></tr>
                <?php endif; ?>
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

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
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
                    window.location.href = `delete.php?id=${userId}`;
                }
            });
        });
    });
});
</script>
</body>
</html>
