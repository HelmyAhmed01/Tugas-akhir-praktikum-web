<?php
session_start();
include './koneksi/db.php';

// Cek apakah pengguna sudah login dan memiliki role 'user'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: index.php?page=login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include './koneksi/header.php'; ?>
    <main>
        <h1>Dashboard User</h1>
        <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p> <!-- Menampilkan username pengguna -->
        <a href="logout.php">Logout</a>
    </main>
    <?php include './koneksi/footer.php'; ?>
</body>
</html>
