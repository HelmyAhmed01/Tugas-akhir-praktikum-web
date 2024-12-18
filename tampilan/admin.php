<?php
session_start();
include './koneksi/db.php';

// Cek apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php?page=login"); // Redirect ke login jika bukan admin
    exit();
}

// Ambil data statistik dari database (contoh)
$queryUsers = "SELECT COUNT(*) AS total_users FROM users";
$resultUsers = $conn->query($queryUsers);
$totalUsers = $resultUsers->fetch_assoc()['total_users'];

// Pastikan session username ada sebelum ditampilkan
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Mengatur lebar sidebar */
        nav.bg-dark {
            width: 250px;
        }

        /* Menyesuaikan konten utama */
        main.p-4 {
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Membuat teks dalam card lebih besar */
        .card-body .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-body .card-text {
            font-size: 1.25rem;
        }

        /* Membuat tombol logout lebih besar */
        .btn-danger {
            font-size: 1.1rem;
            padding: 10px;
        }

        /* Menambah jarak antar item menu */
        .nav-item.mb-2 {
            margin-bottom: 15px;
        }

        /* Mengatur header pada card */
        .card-header {
            font-size: 1.25rem;
            font-weight: bold;
        }

        /* Menambah margin bawah pada judul halaman */
        h2.mb-4 {
            margin-bottom: 30px;
        }

        /* Menyesuaikan ukuran teks pada statistik */
        .card-text.fs-3 {
            font-size: 2rem;
            font-weight: bold;
        }

        /* Menambah padding pada header */
        header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 1.75rem;
            margin: 0;
        }

        header .user-info {
            font-size: 1.1rem;
            font-weight: bold;
        }

        /* Responsif Header */
        @media (max-width: 768px) {
            header .container {
                flex-direction: column;
                text-align: center;
            }

            header h1 {
                margin-bottom: 10px;
            }
        }

        /* Menambahkan jarak setelah header dan sidebar */
        .container-fluid {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include './koneksi/header.php'; ?>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="bg-dark text-white p-3 vh-100">
            <h4 class="text-center mb-4">Menu Admin</h4>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="index.php?page=admin" class="nav-link text-white">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="index.php?page=manage_users" class="nav-link text-white">Kelola Pengguna</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="index.php?page=manage_recipes" class="nav-link text-white">Kelola Konten</a>
                </li>
                <li class="nav-item mt-3">
                    <a href="index.php?page=logout" class="btn btn-danger w-100">Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="p-4 flex-grow-1">
            <h2 class="mb-4">Dashboard Admin</h2>
            <p>Selamat datang, <strong><?php echo $username; ?></strong>!</p>

            <!-- Statistik -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Statistik</div>
                        <div class="card-body">
                            <h5 class="card-title">Total Pengguna</h5>
                            <p class="card-text fs-3"><?php echo $totalUsers; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Konten</div>
                        <div class="card-body">
                            <h5 class="card-title">Konten Tersedia</h5>
                            <p class="card-text fs-3">12</p> <!-- Contoh data -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-header">Aktivitas</div>
                        <div class="card-body">
                            <h5 class="card-title">Aktivitas Terkini</h5>
                            <p class="card-text fs-5">Sistem berjalan lancar</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <?php include './koneksi/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
