<?php
// Memanggil file konfigurasi (biasanya berisi koneksi database) dan memulai atau melanjutkan sesi.
// 'require_once' memastikan file hanya dimuat sekali.
require_once 'config.php';

// --- BAGIAN KEAMANAN: Memeriksa Status Login Pengguna ---
// Script ini akan memeriksa apakah variabel session "loggedin" ada dan nilainya true.
// Ini adalah langkah penting untuk melindungi halaman agar hanya bisa diakses oleh pengguna yang sudah login.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Jika tidak ada session login, pengguna akan dialihkan ke halaman 'login.php'.
    header("location: login.php");
    // 'exit' digunakan untuk menghentikan eksekusi script lebih lanjut setelah pengalihan.
    exit;
}

// Menyertakan file-file tata letak standar untuk bagian atas (header) dan panel samping (sidebar).
// Ini membuat kode lebih rapi dan mudah dikelola (prinsip Don't Repeat Yourself).
include 'header.php';
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Tambah Artikel Baru</h1>
    <p>Silakan isi detail artikel di bawah ini.</p>

    <div class="form-container" style="max-width: none; margin: 20px 0;">
        
        <?php
        // --- BLOK UNTUK MENAMPILKAN PESAN ERROR ---
        // Memeriksa apakah ada pesan error yang disimpan dalam session dengan kunci 'article_error'.
        // Pesan ini biasanya diatur di file pemrosesan form jika terjadi kesalahan input atau validasi.
        if (isset($_SESSION['article_error'])) {
            // Jika ada, tampilkan pesan error tersebut di dalam sebuah div.
            echo '<div class="alert alert-danger">' . $_SESSION['article_error'] . '</div>';
            // Setelah ditampilkan, hapus pesan dari session agar tidak muncul lagi saat halaman dimuat ulang.
            unset($_SESSION['article_error']);
        }
        ?>

        <form action="proses_tambah_artikel.php" method="POST">
            <div class="form-group">
                <label for="title">Judul Artikel</label>
                <input type="text" id="title" name="title" required> </div>
            <div class="form-group">
                <label for="content">Konten Artikel</label>
                <textarea id="content" name="content" rows="10" required></textarea> </div>
            <button type="submit" class="btn">Publikasikan Artikel</button>
        </form>
    </div>
</main>

<?php
// Menyertakan file footer.php untuk menampilkan bagian bawah halaman.
include 'footer.php';
?>