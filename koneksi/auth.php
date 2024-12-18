<?php
include 'db.php'; // Pastikan koneksi database benar

// Fungsi untuk login
function login($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user'] = $user; // Simpan data lengkap pengguna dalam session
            return $user['role']; // Kembalikan role user
        }
    }
    return false; // Login gagal
}

// Fungsi untuk cek login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk logout
function logout() {
    session_destroy();
    header("Location: index.php?page=login");
    exit();
}

?>
