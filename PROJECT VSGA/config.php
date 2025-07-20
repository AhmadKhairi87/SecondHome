<?php
/*
File: config.php
Deskripsi: File ini berisi konfigurasi untuk koneksi ke database
dan memulai session. Ini adalah praktik terbaik untuk memisahkan
konfigurasi dari logika utama aplikasi.
*/

// --- Memulai Session dengan Aman ---
// Cek dulu apakah sesi sudah aktif. Jika belum, baru mulai sesi.
// Ini akan mencegah error "session is already active".
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- Konfigurasi Database ---
// Ganti nilai berikut sesuai dengan pengaturan server database Anda
define('DB_SERVER', 'localhost'); // Server database, biasanya 'localhost'
define('DB_USERNAME', 'root');      // Username database Anda
define('DB_PASSWORD', '');          // Password database Anda
define('DB_NAME', 'secord_home_db'); // Nama database yang sudah dibuat

// --- Membuat Koneksi ke Database menggunakan MySQLi ---
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// --- Memeriksa Koneksi ---
// Jika koneksi gagal, hentikan skrip dan tampilkan pesan error.
if($conn->connect_error){
    // die() akan menghentikan eksekusi skrip sepenuhnya.
    die("ERROR: Koneksi Gagal. " . $conn->connect_error);
}
?>
