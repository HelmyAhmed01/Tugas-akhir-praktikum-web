<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Periksa apakah ada parameter 'page' di URL
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'login':
            include './tampilan/login.php';
            break;
        case 'register':
            include './tampilan/register.php';
            break;
        case 'admin':
            // Pastikan hanya admin yang dapat mengakses halaman admin
            session_start();
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                include './tampilan/admin.php';
            } else {
                header('Location: login.php'); // Redirect ke login jika bukan admin
                exit;
            }
            break;
        case 'user':
            // Pastikan pengguna yang sudah login dapat mengakses halaman ini
            session_start();
            if (isset($_SESSION['user_id'])) {
                include './tampilan/user.php';
            } else {
                header('Location: login.php'); // Redirect ke login jika belum login
                exit;
            }
            break;
        case 'logout':
            // Logout: Hapus session dan cookie, kemudian redirect ke halaman login
            session_start();
            session_unset();
            session_destroy();
            setcookie('user_id', '', time() - 3600, "/");
            setcookie('role', '', time() - 3600, "/");
            setcookie('username', '', time() - 3600, "/");
            header('Location: login.php'); // Redirect ke halaman login setelah logout
            exit;
        case 'manage_users':
            // Hanya admin yang dapat mengakses halaman manage users
            session_start();
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                include './tampilan/manage_users.php';
            } else {
                header('Location: login.php');
                exit;
            }
            break;
        case 'manage_recipes':
            // Hanya admin yang dapat mengakses halaman manage recipes
            session_start();
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                include './tampilan/manage_recipes.php';
            } else {
                header('Location: login.php');
                exit;
            }
            break;
        case 'edit_recipe':
            // Memastikan ada parameter 'id' untuk mengedit resep
            if (isset($_GET['id'])) {
                include './tampilan/edit_recipe.php';
            } else {
                echo "Resep tidak ditemukan!";
                exit();
            }
            break;
        default:
            include './tampilan/404.php'; // Halaman 404 jika page tidak ditemukan
    }
} else {
    // Jika tidak ada parameter 'page', tampilkan halaman utama
    include './tampilan/home.php';
}
?>
