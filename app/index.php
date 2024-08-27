<!DOCTYPE html>
<html lang="en">
<head>
  <!-- SweetAlert CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
  <!-- SweetAlert JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <!-- Print.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
</head>

<?php 
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: ../index.php?session=expired');
  exit;
}

// Ambil peran pengguna dari session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

include('header.php'); 
include('../config/config.php'); 
?>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <?php include('preloader.php');?>
  <?php include('navbar.php');?>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <?php include('logo.php');?>
    <?php include('sidebar.php');?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php include('contentheader.php');?>
    <!-- /.content-header -->

    <!-- Main content -->
    <?php 
    if ($role == 'admin') {
      // Untuk admin, tampilkan dashboard admin atau halaman lain yang sesuai
      if (isset($_GET['page'])) {
        if ($_GET['page'] == 'dashboard') {
            include('admin_dashboard.php'); // Halaman admin dashboard
        } else if ($_GET['page'] == 'data_pengunjung') {
            include('Data_pengunjung.php');
        } else if ($_GET['page'] == 'edit-data') {
            include('edit/edit.php');
        } else if ($_GET['page'] == 'view-data') {
            include('lihat_data.php');
        } else {
            include('not_found.php');
        }
      } else {
        include('admin_dashboard.php');
      }
    } else if ($role == 'pengunjung') {
      // Untuk pengunjung, tampilkan dashboard pengunjung atau halaman lain yang sesuai
      if (isset($_GET['page'])) {
        if ($_GET['page'] == 'dashboard') {
            include('pengunjung_dashboard.php'); // Halaman pengunjung dashboard
        } else if ($_GET['page'] == 'view-data') {
            include('lihat_data.php');
        } else {
            include('not_found.php');
        }
      } else {
        include('pengunjung_dashboard.php');
      }
    } else {
      include('not_found.php');
    }
    ?>

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include('footer.php');?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>

<!-- ./wrapper -->

<!-- jQuery -->

</body>
</html>
