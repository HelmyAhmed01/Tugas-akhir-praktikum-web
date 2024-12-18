<?php

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
    <style>
        /* Reset */
        body, html, ul, li, h2, p {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header */
        header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            font-size: 1.75rem;
            margin: 0;
        }

        /* Layout */
        .d-flex {
            display: flex;
        }

        /* Sidebar */
        nav {
            background-color: #343a40;
            color: white;
            width: 250px;
            min-height: 100vh;
            padding: 20px;
        }

        nav h4 {
            text-align: center;
            margin-bottom: 20px;
        }

        .nav-item {
            list-style: none;
            margin-bottom: 15px;
        }

        .nav-item a {
            color: white;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-item a:hover {
            background-color: #495057;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            text-align: center;
            padding: 10px;
            border: none;
            cursor: pointer;
            display: block;
            margin-top: 30px;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Main Content */
        main {
            flex-grow: 1;
            padding: 20px;
        }

        main h2 {
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        /* Card Styles */
        .card-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            padding: 20px;
            flex: 1;
            max-width: calc(33.333% - 20px);
            box-sizing: border-box;
            text-align: center;
        }

        .card.bg-success {
            background-color: #28a745;
        }

        .card.bg-info {
            background-color: #17a2b8;
        }

        .card h5 {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Responsif */
        @media (max-width: 768px) {
            nav {
                width: 200px;
            }

            .card-container {
                flex-direction: column;
                gap: 10px;
            }

            .card {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Dashboard Admin</h1>
    </header>

    <div class="d-flex">
        <!-- Sidebar -->
        <nav>
            <h4>Menu Admin</h4>
            <ul>
                <li class="nav-item">
                    <a href="index.php?page=admin">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=manage_users">Kelola Pengguna</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=manage_recipes">Kelola Konten</a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=logout" class="btn-danger">Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main>
            <h2>Dashboard Admin</h2>
            <p>Selamat datang, <strong><?php echo $username; ?></strong>!</p>

            <!-- Statistik -->
            <div class="card-container">
                <div class="card">
                    <h5>Total Pengguna</h5>
                    <p><?php echo $totalUsers; ?></p>
                </div>
                <div class="card bg-success">
                    <h5>Konten Tersedia</h5>
                    <p>12</p>
                </div>
                <div class="card bg-info">
                    <h5>Aktivitas Terkini</h5>
                    <p>Sistem berjalan lancar</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
