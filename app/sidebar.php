<?php
// Pastikan variabel sesi tersedia dan berikan nilai default jika tidak ada
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
$role = isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : '';
// Sidebar content
?>

<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
    <div class="image">
    <?php
    if ($role == 'admin') {
        echo '<img src="dist/img/logo.jpg" class="img-circle elevation-2" alt="Admin Image">';
    } elseif ($role == 'pengunjung') {
        echo '<img src="dist/img/p.jpg" class="img-circle elevation-2" alt="Pengunjung Image">';
    } else {
        // Gambar default atau untuk peran lain
        echo '<img src="dist/img/default.jpg" class="img-circle elevation-2" alt="Default Image">';
    }
    ?>
</div>
        <div class="info">
            <a href="#" class="d-block"><?php echo $username . ' | ' . $role; ?></a>
        </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <?php
    if ($role === 'admin') {
        include('menu/menu_admin.php');
    } else {
        include('menu/menu_pengunjung.php');
    }
    ?>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
