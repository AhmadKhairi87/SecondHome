<?php
// Memuat file konfigurasi (`config.php`). Ini penting untuk pengaturan dasar
// dan koneksi database. `require_once` memastikan file ini hanya dimuat sekali.
require_once 'config.php';

// Menyertakan file `header.php`. File ini berisi bagian atas dari
// tata letak HTML, seperti tag <head> dan bagian awal navigasi.
include 'header.php';

// Menyertakan file `sidebar.php`, yang berisi menu navigasi samping.
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Halaman Event</h1>
    
    <p>Konten untuk event akan ditampilkan di sini.</p>
</main>

<?php
// Menyertakan file `footer.php`. File ini berisi bagian bawah halaman,
// seperti informasi hak cipta atau tautan tambahan.
include 'footer.php';
?>