<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    switch ($page) {
        case 'login':
            include './tampilan/login.php';
            break;
        case 'register':  // Menambahkan case untuk register
            include './tampilan/register.php';  // Gantilah dengan path yang sesuai
            break;
        case 'admin':
            include './tampilan/admin.php';
            break;
        case 'user':
            include './tampilan/user.php';
            break;
        case 'logout': // Tambahkan case logout
            include './tampilan/logout.php';
            break;
        case 'manage_users':
        include './tampilan/manage_users.php';
            break;
        case 'manage_recipes':
            include './tampilan/manage_recipes.php';
            break;
        default:
            include './tampilan/404.php';
    }
} else {
    include './tampilan/home.php';
}
?>
