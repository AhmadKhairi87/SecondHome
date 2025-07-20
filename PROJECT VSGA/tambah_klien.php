<?php
// Memulai atau melanjutkan sesi. Fungsi ini harus dipanggil di paling atas
// sebelum ada output HTML apapun. Ini diperlukan untuk mengakses variabel $_SESSION.
session_start();

// --- BLOK KEAMANAN ---
// Memeriksa apakah variabel session 'loggedin' tidak ada atau nilainya bukan true.
// Ini adalah gerbang untuk memastikan hanya pengguna yang sudah login yang bisa mengakses halaman ini.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Jika pengguna belum login, alihkan (redirect) mereka ke halaman login.
    header("location: login.php");
    // Hentikan eksekusi script lebih lanjut agar konten di bawah tidak ikut dimuat.
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Klien Baru - Secord Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="main-container">
        <div class="content-wrapper">
            <?php 
            // Menyisipkan file sidebar.php. Ini adalah praktik yang baik untuk menggunakan kembali
            // komponen halaman seperti menu navigasi di beberapa halaman.
            include 'sidebar.php'; 
            ?>

            <main class="main-content">
                <h1>Tambah Klien Baru</h1>
                <p>Isi form di bawah untuk menambahkan data klien baru.</p>

                <form action="proses_tambah_klien.php" method="POST" enctype="multipart/form-data" class="form-container" style="margin: 0; max-width: none;">
                    
                    <div class="form-group">
                        <label for="nama_klien">Nama Klien</label>
                        <input type="text" name="nama_klien" id="nama_klien" required> </div>

                    <div class="form-group">
                        <label for="foto_klien">Foto/Logo Klien</label>
                        <input type="file" name="foto_klien" id="foto_klien" required accept="image/png, image/jpeg, image/gif, image/webp"> </div>

                    <button type="submit" class="btn">Simpan Klien</button>
                    <a href="klien.php" style="text-decoration: none; margin-left: 10px;">Batal</a>

                </form>
            </main>
        </div>
    </div>

</body>
</html>