<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

echo "Logout berhasil. Redirect ke halaman sebelumnya...";
header("Refresh:2; url=index.php?page=login"); // Redirect setelah 2 detik
exit();
?>
