<?php
// Password yang ingin di-hash
$password = 'admin'; // Gantilah ini dengan password yang Anda inginkan

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Tampilkan hash password
echo "Hash Password: " . $hashed_password;
?>
