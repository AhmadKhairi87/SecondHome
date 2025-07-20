<?php
// Memulai atau melanjutkan sesi. Ini adalah praktik yang baik untuk diletakkan di awal,
// bahkan jika halaman ini tidak secara langsung menggunakan data sesi, halaman lain yang di-include (seperti sidebar) mungkin menggunakannya.
session_start();
// Memuat file konfigurasi yang berisi koneksi database ($conn).
require_once 'config.php';

// Menyiapkan query SQL untuk mengambil data semua klien dari database.
// 'ORDER BY nama_klien ASC' akan mengurutkan hasilnya secara alfabetis berdasarkan nama klien.
$sql = "SELECT id, nama_klien, foto_klien FROM klien ORDER BY nama_klien ASC";
// Menjalankan query dan menyimpan hasilnya di variabel $result.
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klien Kami - Secord Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="main-container">

        <?php 
            // Komentar ini menunjukkan bahwa Anda bisa menyertakan file header di sini jika ada.
            // Saat ini baris ini tidak aktif karena diberi tanda komentar.
            // include 'header.php'; 
        ?>

        <div class="content-wrapper">

            <?php 
                // Menyertakan file sidebar.php untuk menampilkan menu navigasi samping.
                include 'sidebar.php'; 
            ?>

            <main class="main-content">
                <h1>Klien Kami</h1>
                <p>Berikut adalah beberapa klien yang telah mempercayai kami.</p>

                <div class="client-gallery">
                    <?php
                    // Memeriksa apakah query berhasil DAN ada setidaknya satu baris data klien.
                    if ($result && $result->num_rows > 0) {
                        // Melakukan perulangan (loop) untuk setiap data klien yang ditemukan.
                        while($row = $result->fetch_assoc()) {
                    ?>
                            <div class="client-card">
                                <div class="client-card-image-wrapper">
                                    <img src="uploads/<?php echo htmlspecialchars($row['foto_klien']); ?>" alt="Logo <?php echo htmlspecialchars($row['nama_klien']); ?>">
                                </div>
                                <h4><?php echo htmlspecialchars($row['nama_klien']); ?></h4>
                            </div>
                    <?php
                        } // Akhir dari loop while
                    } else {
                        // Jika tidak ada klien di database, tampilkan pesan ini.
                        echo "<p class='no-clients'>Belum ada data klien untuk ditampilkan.</p>";
                    }
                    // Menutup koneksi database setelah selesai digunakan untuk melepaskan sumber daya.
                    $conn->close();
                    ?>
                </div>
            </main>
            
            <main class="main-content">
                <h1>Klien Kami</h1>
                <p>Berikut adalah beberapa klien yang telah mempercayai kami.</p>

                <a href="tambah_klien.php" class="btn" style="margin-bottom: 25px; display: inline-block; width: auto;">+ Tambah Klien Baru</a>

                <div class="client-gallery">
                    <?php
                        // Sisa kode ini tidak akan berjalan karena koneksi DB sudah ditutup di atas.
                        // Ini adalah contoh mengapa penggabungan kedua blok <main> penting.
                        // ... (sisa kode biarkan saja) ...
                    ?>
                </div>
            </main>

        </div> </div> </body>
</html>