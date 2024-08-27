<?php
session_start();
include('../config/config.php');

// Jika ada pesan flash dari proses tambah data
$flash_message = '';
if (isset($_SESSION['flash_message'])) {
    $flash_message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
}

// Cek status dari query string
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemesanan Tiket</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .table thead th, .table tbody td {
                border: 1px solid #000 !important;
            }
        }
        .alert {
            display: none;
        }
    </style>
</head>
<body>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="card-title">Data Pemesanan Tiket</h3>
                    </div>
                    <div class="card-body">
                        <!-- Tombol Kembali -->
                        <a href="http://localhost/coba/app/" class="btn btn-secondary mb-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>

                        <!-- Area Pesan Sukses atau Error -->
                        <div id="messageArea">
                            <?php
                            if (!empty($flash_message)) {
                                $type = htmlspecialchars($flash_message['type']);
                                $message = htmlspecialchars($flash_message['message']);
                                echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                                    ' . $message . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                            }
                            ?>
                        </div>

                        <!-- Filter Pencarian -->
                        <div class="form-group mb-3">
                            <input type="text" id="searchInput" class="form-control" placeholder="Filter berdasarkan username">
                        </div>
                        <!-- Filter Tanggal -->
                        <div class="form-group mb-3">
                            <label for="dateFilter">Filter berdasarkan tanggal:</label>
                            <input type="date" id="dateFilter" class="form-control">
                        </div>
                        <!-- Filter Bulan -->
                        <div class="form-group mb-3">
                            <label for="monthFilter">Filter berdasarkan bulan:</label>
                            <input type="month" id="monthFilter" class="form-control">
                        </div>

                        <!-- Tabel Data Pemesanan Tiket -->
                        <table id="example1" class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pesanan</th>
                                    <th>Username</th>
                                    <th>Tanggal Pemesanan</th>
                                    <th>Status Pemesanan</th>
                                    <th class="no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query untuk mendapatkan data pemesanan tiket bersama dengan username dari tabel pengunjung
                                $query = mysqli_query($koneksi, "
                                    SELECT pemesanan_tiket.*, pengunjung.username 
                                    FROM pemesanan_tiket 
                                    JOIN pengunjung ON pemesanan_tiket.pengunjung_id = pengunjung.id
                                ");
                                if (!$query) {
                                    die("Query gagal: " . mysqli_error($koneksi));
                                }
                                $no = 1;
                                while($data = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td class="kode_pesanan"><?php echo htmlspecialchars($data['kode_pesanan']); ?></td>
                                    <td class="username"><?php echo htmlspecialchars($data['username']); ?></td>
                                    <td class="tanggal_pemesanan"><?php echo htmlspecialchars($data['tanggal_pemesanan']); ?></td>
                                    <td><?php echo htmlspecialchars($data['status_pemesanan']); ?></td>
                                    <td class="no-print">
                                        <button class="btn btn-sm btn-danger" onclick="hapus_data(<?php echo $data['id']; ?>)">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                        <a href="edit/edit.php?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="http://localhost/coba/app/viewdata/lihat_data.php" class="btn btn-sm btn-success">
                                            <i class="fas fa-eye"></i> Lihat Data
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Menampilkan pesan konfirmasi jika ada
    var urlParams = new URLSearchParams(window.location.search);
    var status = urlParams.get('status');

    if (status === 'updated') {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Data berhasil diperbarui!',
            timer: 2000,
            showConfirmButton: false
        });
    } else if (status === 'deleted') {
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Data berhasil dihapus!',
            timer: 2000,
            showConfirmButton: false
        });
    } else if (status === 'error') {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan!',
            timer: 2000,
            showConfirmButton: false
        });
    }

    // Pesan akan hilang setelah 2 detik
    $(".alert").delay(2000).fadeOut(500);

    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#example1 tbody tr").filter(function() {
            $(this).toggle($(this).find('.username').text().toLowerCase().indexOf(value) > -1);
        });
    });

    $("#dateFilter").on("change", function() {
        var selectedDate = $(this).val();
        $("#example1 tbody tr").filter(function() {
            var dateText = $(this).find('.tanggal_pemesanan').text();
            $(this).toggle(dateText === selectedDate);
        });
    });

    $("#monthFilter").on("change", function() {
        var selectedMonth = $(this).val();
        $("#example1 tbody tr").filter(function() {
            var dateText = $(this).find('.tanggal_pemesanan').text();
            var month = dateText.substring(0, 7); // Ambil format YYYY-MM dari tanggal
            $(this).toggle(month === selectedMonth);
        });
    });
});

function hapus_data(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "Delete/hapus_data.php?id=" + id;
        }
    });
}
</script>

</body>
</html>
