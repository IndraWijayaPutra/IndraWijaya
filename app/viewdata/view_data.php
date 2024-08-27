<?php
include('../../config/config.php'); // Pastikan file koneksi disertakan

$id = $_GET['id']; // Assuming id is passed in the query string
$query = mysqli_query($koneksi, "SELECT * FROM pengunjung WHERE id = $id");
$data = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html>
<head>
  <title>View Data Pengunjung</title>
  <!-- Add necessary stylesheets and scripts -->
</head>
<body>
  <div class="container">
    <h2>Detail Data Pengunjung</h2>
    <table class="table table-bordered">
      <tr>
        <th>ID</th>
        <td><?php echo $data['id']; ?></td>
      </tr>
      <tr>
        <th>Username</th>
        <td><?php echo $data['username']; ?></td>
      </tr>
      <tr>
        <th>Email</th>
        <td><?php echo $data['email']; ?></td>
      </tr>
      <tr>
        <th>No Telepon</th>
        <td><?php echo $data['no_telepon']; ?></td>
      </tr>
      <tr>
        <th>Alamat</th>
        <td><?php echo $data['alamat']; ?></td>
      </tr>
      <tr>
        <th>Role</th>
        <td><?php echo $data['role']; ?></td>
      </tr>
    </table>
    <button onclick="window.history.back()" class="btn btn-default">Kembali</button>
  </div>
</body>
</html>
