<?php
// Memuat file konfigurasi. File ini biasanya berisi koneksi ke database
// dan juga sering digunakan untuk memulai sesi dengan session_start().
require_once 'config.php';

// --- LOGIKA PENGALIHAN OTOMATIS ---
// Cek apakah pengguna sudah dalam keadaan login.
// Jika ya, tidak ada gunanya menampilkan halaman registrasi.
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // Alihkan pengguna yang sudah login ke halaman utama (index.php).
    header("location: index.php");
    // Hentikan eksekusi script untuk memastikan pengalihan terjadi.
    exit;
}

// Memuat file header yang didesain khusus untuk halaman otentikasi (login, register).
// File ini mungkin memiliki tampilan yang lebih sederhana dibandingkan header utama.
include 'header_auth.php'; 
?>

<div class="form-container">
    <h1 style="text-align: center;">Sign Up</h1>
    <p style="text-align: center; margin-bottom: 25px; color: #555;">Buat akun baru untuk memulai.</p>
    
    <?php 
    // --- BLOK UNTUK MENAMPILKAN PESAN FEEDBACK ---
    // Blok ini akan menampilkan pesan sukses atau error setelah proses registrasi di 'proses_daftar_baru.php'.
    if(isset($_SESSION['register_message'])){
        // Logika cerdas untuk menentukan jenis pesan (sukses atau error).
        // Jika pesan mengandung kata 'berhasil', maka tipe pesannya 'success' (hijau). Jika tidak, 'danger' (merah).
        $message_type = strpos($_SESSION['register_message'], 'berhasil') !== false ? 'success' : 'danger';
        // Tampilkan pesan di dalam div dengan kelas CSS yang sesuai ('alert-success' atau 'alert-danger').
        echo '<div class="alert alert-'.$message_type.'">'.$_SESSION['register_message'].'</div>';
        // Setelah pesan ditampilkan, hapus dari session agar tidak muncul lagi saat halaman di-refresh.
        unset($_SESSION['register_message']);
    }
    ?>

    <form action="proses_daftar_baru.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required> </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required> </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required> </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn">Register</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        Sudah punya akun? <a href="login.php" style="color: #1B8B8F; font-weight: bold; text-decoration: none;">Sign in di sini.</a>
    </p>
</div>

<?php 
// Memuat file footer khusus untuk halaman otentikasi.
include 'footer_auth.php'; 
?>