<?php
// Memuat file konfigurasi, yang biasanya berisi koneksi database atau pengaturan global lainnya.
require_once 'config.php';

// --- BAGIAN KEAMANAN ---
// Memeriksa apakah session "loggedin" sudah di-set dan nilainya adalah true.
// Ini adalah cara standar untuk memastikan bahwa hanya pengguna yang sudah berhasil login
// yang dapat mengakses halaman ini. Jika tidak, pengguna akan dialihkan ke halaman login.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit; // Menghentikan eksekusi script setelah pengalihan.
}

// Memuat file-file tata letak standar: header (bagian atas halaman) dan sidebar (panel samping).
include 'header.php';
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Tambah Produk Baru</h1>
    <p>Isi formulir di bawah untuk menambahkan produk baru ke dalam katalog.</p>

    <div class="form-container" style="max-width: none; margin: 20px 0;">
        
        <?php
        // --- PENANGANAN PESAN ERROR ---
        // Blok ini memeriksa apakah ada pesan error yang disimpan di session.
        // Pesan ini kemungkinan diatur di file 'proses_tambah_produk.php' jika terjadi kegagalan validasi atau upload.
        if (isset($_SESSION['product_error'])) {
            // Jika ada, tampilkan pesan error di dalam sebuah div dengan kelas 'alert'.
            echo '<div class="alert alert-danger">' . $_SESSION['product_error'] . '</div>';
            // Hapus pesan error dari session setelah ditampilkan agar tidak muncul lagi saat halaman di-refresh.
            unset($_SESSION['product_error']);
        }
        ?>

        <form action="proses_tambah_produk.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nama Produk</label>
                <input type="text" id="name" name="name" required> </div>
            <div class="form-group">
                <label for="description">Keterangan / Deskripsi Produk</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="product_image">Foto Produk</label>
                <input type="file" id="product_image" name="product_image" accept="image/jpeg, image/png" required>
                <small>Format yang didukung: JPG, PNG. Ukuran maks: 2MB.</small> </div>
            <button type="submit" class="btn">Tambah Produk</button>
        </form>
    </div>
</main>

<?php
// Memuat file footer (bagian bawah halaman).
include 'footer.php';
?>