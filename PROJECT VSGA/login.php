<?php
// Memuat file konfigurasi, yang seharusnya sudah memulai sesi (session_start())
// dan membuat koneksi ke database.
require_once 'config.php';

// --- LOGIKA PENGALIHAN OTOMATIS ---
// Cek apakah pengguna sudah dalam keadaan login.
// Jika ya, tidak ada gunanya menampilkan halaman login lagi.
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // Langsung arahkan pengguna yang sudah login ke halaman utama.
    header("location: index.php");
    exit; // Hentikan eksekusi script setelah pengalihan.
}

// Memuat file header yang didesain khusus untuk halaman otentikasi (login, register).
// File ini mungkin memiliki tampilan yang lebih sederhana dibandingkan header utama situs.
include 'header_auth.php'; 
?>

<div class="form-container">
    <h1 style="text-align: center;">Sign In</h1>
    <p style="text-align: center; margin-bottom: 25px; color: #555;">Silakan isi kredensial Anda untuk login.</p>
    
    <?php 
    // --- BLOK UNTUK MENAMPILKAN PESAN ERROR ---
    // Blok ini akan memeriksa apakah ada pesan error yang disimpan di session dengan kunci 'login_error'.
    // Pesan ini diatur di 'proses_login.php' jika login gagal (misal, password salah).
    if(isset($_SESSION['login_error'])){
        // Jika ada, tampilkan pesan error tersebut di dalam sebuah div.
        echo '<div class="alert alert-danger">'.$_SESSION['login_error'].'</div>';
        // Setelah pesan ditampilkan, hapus dari session agar tidak muncul lagi saat halaman di-refresh.
        unset($_SESSION['login_error']);
    }
    ?>

    <form action="proses_login.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required> </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required> </div>
        <button type="submit" class="btn">Login</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        Belum punya akun? <a href="register.php" style="color: #1B8B8F; font-weight: bold; text-decoration: none;">Sign up sekarang.</a>
    </p>
</div>

<?php 
// Memuat file footer khusus untuk halaman otentikasi.
include 'footer_auth.php'; 
?>