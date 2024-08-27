<?php
include '../../config/config.php';
include '../includes/header.php';

// Mengambil data pengunjung
$sql = "SELECT * FROM pengunjung";
$result = $conn->query($sql);
?>

<h2>Daftar Pengunjung</h2>
<a href="add_user.php" class="btn btn-primary mb-3">Tambah Pengunjung</a>
<table class="table table-striped">
    <thead>
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
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['no_telepon']}</td>
                        <td>{$row['alamat']}</td>
                        <td>{$row['umur']}</td>
                        <td>{$row['role']}</td>
                        <td>{$row['tanggal_daftar']}</td>
                        <td>
                            <a href='edit_user.php?id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_user.php?id={$row['id']}' class='btn btn-danger btn-sm'>Hapus</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>Tidak ada data.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
