<?php
session_start();
include './koneksi/auth.php';

// Durasi timeout dalam detik (10 detik untuk keperluan pengujian)
$timeout_duration = 10; 

// Cek apakah sesi sudah ada
if (isset($_SESSION['user_id'])) {  
    // Periksa apakah waktu terakhir interaksi disimpan
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
        // Jika sesi sudah timeout, hapus sesi dan cookie
        session_unset();
        session_destroy();
        setcookie('user_id', '', time() - 3600, "/");
        setcookie('role', '', time() - 3600, "/");
        setcookie('username', '', time() - 3600, "/");
        header('Location: login.php?timeout=1'); // Redirect ke login dengan pesan timeout
        exit;
    }
    // Perbarui waktu terakhir aktivitas
    $_SESSION['last_activity'] = time();

    // Redirect sesuai role pengguna
    if ($_SESSION['role'] === 'admin') {
        header('Location: index.php?page=admin');
        exit;
    } else {
        header('Location: index.php?page=user');
        exit;
    }
} elseif (isset($_COOKIE['user_id']) && isset($_COOKIE['role']) && isset($_COOKIE['username'])) {
    // Jika cookie ada, set sesi berdasarkan cookie
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['role'] = $_COOKIE['role'];
    $_SESSION['username'] = $_COOKIE['username'];

    // Periksa waktu aktivitas terakhir dari sesi
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
        // Jika sesi sudah timeout, hapus sesi dan cookie
        session_unset();
        session_destroy();
        setcookie('user_id', '', time() - 3600, "/");
        setcookie('role', '', time() - 3600, "/");
        setcookie('username', '', time() - 3600, "/");
        header('Location: login.php?timeout=1'); // Redirect ke login dengan pesan timeout
        exit;
    }

    // Perbarui waktu terakhir aktivitas
    $_SESSION['last_activity'] = time();

    // Redirect sesuai role pengguna
    if ($_SESSION['role'] === 'admin') {
        header('Location: index.php?page=admin');
        exit;
    } else {
        header('Location: index.php?page=user');
        exit;
    }
}

// Proses Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include './koneksi/db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username atau password tidak boleh kosong.";
    } else {
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['user_id'] = 1;  // ID admin
            $_SESSION['role'] = 'admin';
            $_SESSION['last_activity'] = time(); // Set waktu aktivitas terakhir
            setcookie('user_id', 1, time() + $timeout_duration, "/");
            setcookie('role', 'admin', time() + $timeout_duration, "/");
            setcookie('username', 'admin', time() + $timeout_duration, "/");

            header('Location: index.php?page=admin');
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_activity'] = time(); // Set waktu aktivitas terakhir
            setcookie('user_id', $user['id'], time() + $timeout_duration, "/");
            setcookie('role', $user['role'], time() + $timeout_duration, "/");
            setcookie('username', $user['username'], time() + $timeout_duration, "/");

            header('Location: index.php?page=user');
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ResepNusantara</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include './koneksi/header.php'; ?>

    <main>
        <section class="login-container">
            <div class="login-card">
                <h1>Login</h1>
                <?php 
                if (isset($_GET['timeout'])) {
                    echo "<p class='error'>Sesi Anda telah habis. Silakan login kembali.</p>";
                }
                if (isset($error)) echo "<p class='error'>$error</p>"; 
                ?>
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn">Masuk</button>
                </form>
                <p>Belum memiliki akun? <a href="index.php?page=register">Daftar di sini</a></p>
            </div>
        </section>
    </main>

    <?php include './koneksi/footer.php'; ?>
</body>
</html>
