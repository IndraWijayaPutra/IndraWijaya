<?php
include '../config/config.php'; // Path ke config.php jika berada di direktori 'coba/config'

$message = '';

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Enkripsi password
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $umur = $_POST['umur']; // Ambil data umur dari form
    $role = 'pengunjung'; // Set role sebagai 'pengunjung'

    // SQL untuk insert data ke tabel pengunjung
    $sql = "INSERT INTO pengunjung (username, email, password, no_telepon, alamat, umur, role, tanggal_daftar) VALUES ('$username', '$email', '$password', '$no_telepon', '$alamat', '$umur', '$role', CURDATE())";

    if ($koneksi->query($sql) === TRUE) {
        $message = "Registrasi berhasil! Terima kasih telah melakukan registrasi, Silahkan Login.";
        echo "<script type='text/javascript'>
                alert('$message');
                window.location.href = 'http://localhost/coba/';
              </script>";
        exit();
    } else {
        $message = "Error: " . $sql . "<br>" . $koneksi->error;
    }

    $koneksi->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <!-- Include Bootstrap CSS & jQuery -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
</head>
<body>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Form Registrasi <small>Silahkan isi data di bawah ini</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="quickForm" action="" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Masukkan email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <label for="no_telepon">No Telepon</label>
                                <input type="text" name="no_telepon" class="form-control" id="no_telepon" placeholder="Masukkan no Telepon">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" class="form-control" id="alamat" placeholder="Masukkan Alamat"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="umur">Umur</label>
                                <input type="number" name="umur" class="form-control" id="umur" placeholder="Masukkan umur" required>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" name="register" class="btn btn-primary">Daftar</button>
                            <button onclick="window.location.href='http://localhost/coba/'">Kembali</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function () {
    $('#quickForm').validate({
        rules: {
            username: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 5
            },
            umur: {
                required: true,
                number: true,
                min: 0 // Umur harus berupa angka dan tidak kurang dari 0
            }
        },
        messages: {
            username: {
                required: "Tolong isi username",
                minlength: "Username harus terdiri dari minimal 3 karakter"
            },
            email: {
                required: "Tolong isi email",
                email: "Masukkan alamat email yang valid"
            },
            password: {
                required: "Tolong isi password",
                minlength: "Password harus terdiri dari minimal 5 karakter"
            },
            umur: {
                required: "Tolong isi umur",
                number: "Umur harus berupa angka",
                min: "Umur tidak boleh kurang dari 0"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>

<!-- Include Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
