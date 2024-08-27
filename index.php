<?php
session_start();
include('config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa kredensial pengguna
    $sql = "SELECT id, role FROM pengunjung WHERE username = ? AND password = ?";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // Set session variabel jika login berhasil
            $stmt->bind_result($username, $role);
            $stmt->fetch();
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Arahkan berdasarkan peran
            if ($role == 'admin') {
                header("Location: ../app"); // Halaman admin
            } else {
                header("Location: ../app"); // Halaman pengunjung 
            }
            exit;
        } else {
            $pesan = "Username atau password salah.";
        }
        $stmt->close();
    } else {
        $pesan = "Gagal mempersiapkan query.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hai guyss | Log in (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="app/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="app/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="app/dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="app/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

  <style>
    body {
      background-image: url('app/dist/img/logo1.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .login-box {
      width: 360px;
      padding: 15px;
      background-color: rgba(64, 224, 208, 0.9); /* Latar belakang transparan */
      border-radius: 10px;
    }

    .login-box .btn {
      border-radius: 25px;
      padding: 10px;
      font-size: 18px;
    }

    .login-box .form-group {
      margin-bottom: 1rem;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="app/index2.html" class="h1"><b>Login Sini Angler</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Masukkan Username dan Password secara benar</p>

      <form action="config/autentikasi.php" method="post">
        <div class="form-group">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username" name="username" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
      </form>
      <p class="mb-0">
        <a href="http://localhost/coba/app/register.php" class="text-center">Tidak Punya akun? Daftar Sini Om</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="app/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="app/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="app/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="app/plugins/sweetalert2/sweetalert2.min.js"></script>

<?php
    $x = isset($_GET['error']) ? $_GET['error'] : null;

    if ($x == 1) {
        echo "
        <script>
            Swal.fire({
              icon: 'error',
              title: 'Login Gagal',
              text: 'Username atau password salah'
            });
        </script>";
    } elseif ($x == 2) {
        echo "
        <script>
            Swal.fire({
              icon: 'warning',
              title: 'Perhatian',
              text: 'Masukkan Username dan Password'
            });
        </script>";
    }
?>
</body>
</html>
