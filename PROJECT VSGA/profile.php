<?php
// Memuat file konfigurasi (`config.php`). 'require_once' memastikan file ini
// hanya dimuat sekali dan akan menyebabkan error fatal jika file tidak ditemukan,
// yang mana penting untuk file konfigurasi.
require_once 'config.php';

// Menyertakan file `header.php`. File ini biasanya berisi bagian atas dari
// halaman HTML, seperti tag <head>, judul halaman, dan bagian awal navigasi.
include 'header.php';

// Menyertakan file `sidebar.php`. File ini berisi panel navigasi samping.
// Menggunakan 'include' memudahkan pengelolaan kode karena komponen yang sama
// dapat digunakan di banyak halaman.
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Profil Second Home</h1>
    
    <p>Ini adalah halaman profil dari second home.</p>
    <p>second home berdiri pada bla bla bla</p>
</main>

<?php
// Menyertakan file `footer.php`. File ini biasanya berisi bagian bawah halaman,
// seperti informasi hak cipta, tautan kontak, atau skrip tambahan.
include 'footer.php'; 
?>