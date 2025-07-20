<?php
// Blok ini berfungsi untuk mengimpor file-file PHP yang penting.
// 'require_once' digunakan untuk file 'config.php' yang kemungkinan berisi konfigurasi database atau pengaturan penting lainnya.
// Penggunaan 'require_once' memastikan file tersebut hanya dimuat sekali dan akan menyebabkan error fatal jika file tidak ditemukan, sehingga menghentikan eksekusi script.
// 'include' digunakan untuk memuat file 'header.php' dan 'sidebar.php'.
// File-file ini biasanya berisi bagian atas (seperti navigasi) dan panel samping dari tata letak halaman web.
// Jika file yang di-'include' tidak ditemukan, script akan memberikan peringatan namun tetap melanjutkan eksekusi.
require_once 'config.php';
include 'header.php';
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Visi dan Misi</h1>
    <h2>Visi Kami</h2>
    <p>Menjadi rumah kedua yang paling nyaman, kreatif, dan inspiratif bagi setiap individu untuk bertumbuh dan menjalin pertemanan tanpa batas.</p>
    
    <h2>Misi Kami</h2>
    <ul>
        <li>Menyediakan ruang yang kondusif untuk berkreasi dan berbagi cerita.</li>
        <li>Membangun komunitas yang solid dan saling mendukung.</li>
        <li>Menyelenggarakan acara-acara yang memperkaya wawasan dan mempererat hubungan.</li>
    </ul>
</main>

<?php
// Blok PHP terakhir ini berfungsi untuk menyertakan bagian bawah (footer) dari halaman web.
// File 'footer.php' biasanya berisi informasi hak cipta, tautan kontak, atau skrip JavaScript yang perlu dimuat di akhir halaman.
// Menggunakan 'include' memungkinkan penggunaan ulang kode footer di banyak halaman berbeda.
include 'footer.php';
?>