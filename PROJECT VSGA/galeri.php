<?php
// Fungsi: Memulai atau melanjutkan sesi PHP.
// Tujuan: Diperlukan untuk menggunakan variabel superglobal $_SESSION, yang akan kita pakai untuk menyimpan pesan notifikasi (feedback) untuk pengguna.
session_start();

// Fungsi: Memuat file konfigurasi eksternal.
// Tujuan: File ini biasanya berisi pengaturan penting seperti koneksi ke database. 'require_once' memastikan file hanya dimuat sekali.
require_once 'config.php';

// Variabel: $upload_dir
// Tujuan: Mendefinisikan nama folder tempat semua gambar galeri akan disimpan dan diakses.
$upload_dir = 'uploads/';
// Variabel: $pesan
// Tujuan: Diinisialisasi sebagai string kosong. Variabel ini akan diisi dengan HTML notifikasi (sukses atau error) untuk ditampilkan kepada pengguna.
$pesan = "";

// --- BLOK LOGIKA: MENANGANI UPLOAD GAMBAR ---
// Blok ini hanya akan dieksekusi jika form upload foto telah disubmit (tombol dengan name="submit_image" ditekan).
if (isset($_POST['submit_image'])) {
    // Cek apakah ada file yang dikirim via input dengan name="new_image" DAN tidak ada error saat proses upload di sisi server (error code 0).
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] == 0) {
        // Fungsi: pathinfo()
        // Tujuan: Memecah informasi path file menjadi beberapa bagian (nama direktori, nama file, ekstensi).
        $file_info = pathinfo($_FILES['new_image']['name']);
        // Variabel: $nama_file, $ekstensi_file
        // Tujuan: Menyimpan nama asli file dan ekstensinya. strtolower() digunakan agar ekstensi seragam (misal: JPG, jpg, Jpg menjadi jpg).
        $nama_file = $file_info['filename'];
        $ekstensi_file = strtolower($file_info['extension']);
        
        // Variabel: $nama_file_unik
        // Tujuan: Membuat nama file yang baru dan unik dengan menambahkan timestamp (jumlah detik sejak 1 Jan 1970) ke nama file asli. Ini mencegah file dengan nama sama saling menimpa.
        $nama_file_unik = $nama_file . '_' . time() . '.' . $ekstensi_file;
        // Variabel: $target_file
        // Tujuan: Menggabungkan direktori upload dengan nama file unik untuk membuat path lengkap tujuan penyimpanan file.
        $target_file = $upload_dir . $nama_file_unik;

        // Fungsi: move_uploaded_file()
        // Tujuan: Memindahkan file yang di-upload dari direktori sementara PHP ke folder tujuan ('uploads/').
        if (move_uploaded_file($_FILES['new_image']['tmp_name'], $target_file)) {
            // Jika berhasil, simpan pesan sukses ke dalam session.
            $_SESSION['pesan'] = "Foto berhasil di-upload!";
        } else {
            // Jika gagal, simpan pesan error.
            $_SESSION['pesan_error'] = "Gagal memindahkan file.";
        }
    } else {
        // Jika tidak ada file yang dipilih atau terjadi error lain saat upload.
        $_SESSION['pesan_error'] = "Terjadi masalah saat upload. Pastikan Anda memilih file.";
    }
    // Fungsi: header() dan exit
    // Tujuan: Melakukan redirect kembali ke halaman galeri. Ini adalah implementasi pola Post/Redirect/Get (PRG)
    // untuk mencegah form disubmit ulang (menyebabkan duplikat upload) jika pengguna me-refresh halaman.
    header('Location: galeri.php');
    exit;
}

// --- BLOK LOGIKA: MENGHAPUS GAMBAR ---
// Blok ini hanya akan dieksekusi jika ada parameter 'delete' di URL (misal: galeri.php?delete=namafile.jpg).
if (isset($_GET['delete'])) {
    // Fungsi: basename()
    // Tujuan: Sebagai langkah keamanan, fungsi ini akan mengambil hanya nama file dari parameter URL,
    // mencegah serangan Path Traversal (misal: ?delete=../config.php).
    $image_to_delete = basename($_GET['delete']);
    // Variabel: $file_path
    // Tujuan: Membuat path lengkap ke file yang akan dihapus.
    $file_path = $upload_dir . $image_to_delete;

    // Fungsi: file_exists() dan unlink()
    // Tujuan: Cek dulu apakah file benar-benar ada di server, baru kemudian hapus file tersebut menggunakan unlink().
    if (file_exists($file_path)) {
        unlink($file_path); // Hapus file fisik dari server.
        $_SESSION['pesan'] = "Foto berhasil dihapus.";
    } else {
        $_SESSION['pesan_error'] = "Gagal menghapus, file tidak ditemukan.";
    }
    // Sama seperti blok upload, redirect untuk 'membersihkan' URL dan mencegah aksi berulang saat refresh.
    header('Location: galeri.php');
    exit;
}

// --- BLOK PENGAMBILAN PESAN NOTIFIKASI DARI SESSION ---
// Blok ini akan memeriksa apakah ada pesan notifikasi yang tersimpan di session dari aksi sebelumnya.
if (isset($_SESSION['pesan'])) {
    $pesan = "<div class='pesan-sukses'>" . $_SESSION['pesan'] . "</div>";
    unset($_SESSION['pesan']); // Fungsi: unset(). Hapus pesan dari session setelah diambil, agar hanya tampil sekali.
}
if (isset($_SESSION['pesan_error'])) {
    $pesan = "<div class='pesan-error'>" . $_SESSION['pesan_error'] . "</div>";
    unset($_SESSION['pesan_error']);
}

// Fungsi: include
// Tujuan: Menyertakan file-file tata letak standar untuk bagian atas dan samping halaman.
include 'header.php';
include 'sidebar.php';
?>
<style>
    /* Styling untuk notifikasi sukses (latar hijau) */
    .pesan-sukses { background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px; }
    /* Styling untuk notifikasi error (latar merah) */
    .pesan-error { background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px; }
    /* Styling untuk area form tambah foto */
    .add-photo-form { background-color: #f9f9f9; }
    /* Efek bayangan dan transisi pada gambar galeri */
    .gallery-item img { box-shadow: 0 4px 8px rgba(0,0,0,0.1); transition: transform 0.2s; }
    /* Efek zoom saat kursor mouse di atas gambar */
    .gallery-item:hover img { transform: scale(1.05); }
    /* Styling untuk tombol hapus ('X') */
    .delete-btn { position: absolute; top: 8px; right: 8px; background-color: rgba(255, 0, 0, 0.8); color: white; width: 24px; height: 24px; border-radius: 50%; text-decoration: none; font-weight: bold; line-height: 24px; text-align: center; border: 1px solid white; transition: background-color 0.2s; }
    /* Efek hover untuk tombol hapus */
    .delete-btn:hover { background-color: red; }
</style>

<main class="main-content">
    <h1>Halaman Galeri</h1>

    <?php 
    // Fungsi: echo
    // Tujuan: Menampilkan variabel $pesan yang berisi HTML notifikasi (jika ada).
    echo $pesan; 
    ?>

    <div class="add-photo-form" style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h3>Tambah Foto Baru</h3>
        <form action="galeri.php" method="post" enctype="multipart/form-data">
            <input type="file" name="new_image" accept="image/jpeg,image/png,image/gif" required>
            <button type="submit" name="submit_image">Upload Foto</button>
        </form>
    </div>

    <div class="gallery-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
        <?php
        // Fungsi: glob()
        // Tujuan: Mencari semua file di dalam folder $upload_dir yang cocok dengan pola. GLOB_BRACE memungkinkan penggunaan {jpg,jpeg,png,gif} untuk mencakup semua format gambar yang diizinkan.
        $images = glob($upload_dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        
        // Cek apakah ada gambar yang ditemukan.
        if (count($images) > 0) {
            // Fungsi: array_multisort(), array_map(), filemtime()
            // Tujuan: Mengurutkan array $images berdasarkan tanggal modifikasi file (terbaru ke terlama).
            // array_map('filemtime', $images) membuat array baru berisi timestamp setiap file.
            // array_multisort mengurutkan array timestamp tersebut (SORT_DESC = descending/menurun), dan menerapkan urutan yang sama ke array $images.
            array_multisort(array_map('filemtime', $images), SORT_DESC, $images);

            // Melakukan perulangan (loop) untuk setiap path gambar dalam array $images yang sudah terurut.
            foreach ($images as $image) {
                echo '<div class="gallery-item" style="position: relative;">';
                // Fungsi: htmlspecialchars()
                // Tujuan: Melakukan 'escaping' pada path gambar untuk keamanan, mencegah serangan XSS.
                echo '<img src="' . htmlspecialchars($image) . '" alt="Gambar Galeri" style="width: 100%; height: auto; border-radius: 8px;">';
                // Membuat tombol hapus untuk setiap gambar.
                // Fungsi urlencode(basename($image)) digunakan untuk memastikan nama file aman untuk digunakan di dalam URL.
                // Atribut `onclick` berisi JavaScript untuk menampilkan dialog konfirmasi sebelum menghapus.
                echo '<a href="?delete=' . urlencode(basename($image)) . '" onclick="return confirm(\'Apakah Anda yakin ingin menghapus foto ini?\');" class="delete-btn">X</a>';
                echo '</div>';
            }
        } else {
            // Jika tidak ada gambar, tampilkan pesan ini.
            echo '<p>Belum ada foto di galeri. Silakan upload foto pertama Anda.</p>';
        }
        ?>
    </div>
</main>

<?php 
// Menyertakan file footer untuk bagian bawah halaman.
include 'footer.php'; 
?>