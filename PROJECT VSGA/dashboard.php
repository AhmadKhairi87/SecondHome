<?php
// Memuat file konfigurasi, yang seharusnya sudah memulai sesi (session_start())
// dan membuat koneksi ke database ($conn).
require_once 'config.php';

// --- KEAMANAN: OTENTIKASI PENGGUNA ---
// Memeriksa apakah pengguna sudah login. Jika belum, mereka tidak dapat mengakses dashboard.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    // Alihkan pengguna yang belum login ke halaman login.
    header("location: login.php");
    exit; // Hentikan eksekusi script lebih lanjut.
}

// Menyertakan file-file tata letak standar untuk bagian atas dan samping halaman.
include 'header.php';
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Dashboard Manajemen Artikel</h1>
    <p>Selamat datang, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Di sini Anda bisa mengelola artikel.</p>
    
    <a href="tambah_artikel.php" class="btn" style="display:inline-block; width:auto; margin-bottom: 20px;">+ Tambah Artikel Baru</a>

    <?php
    // --- BLOK UNTUK MENAMPILKAN PESAN SUKSES ---
    // Cek apakah ada pesan sukses yang disimpan di session (misalnya, setelah berhasil mengedit atau menghapus artikel).
    if (isset($_SESSION['success_message'])) {
        // Jika ada, tampilkan pesan di dalam sebuah div dengan kelas 'alert-success'.
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        // Hapus pesan dari session setelah ditampilkan agar tidak muncul lagi saat halaman di-refresh.
        unset($_SESSION['success_message']);
    }
    ?>

    <table class="dashboard-table">
        <thead>
            <tr>
                <th>Judul Artikel</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // --- LOGIKA PENGAMBILAN DATA ARTIKEL PENGGUNA ---
            
            // Ambil ID pengguna yang sedang login dari session.
            $user_id = $_SESSION['id'];
            // Siapkan query SQL untuk mengambil artikel HANYA yang 'user_id'-nya cocok dengan pengguna yang sedang login.
            // Ini adalah langkah otorisasi penting untuk memastikan pengguna tidak melihat artikel orang lain.
            // 'ORDER BY created_at DESC' mengurutkan artikel dari yang paling baru.
            $sql = "SELECT id, title, created_at FROM articles WHERE user_id = ? ORDER BY created_at DESC";
            
            // Persiapkan statement SQL untuk eksekusi yang aman.
            if($stmt = $conn->prepare($sql)){
                // Ikat (bind) variabel $user_id ke placeholder (?) sebagai integer ('i').
                $stmt->bind_param("i", $user_id);
                // Eksekusi query.
                $stmt->execute();
                // Ambil hasilnya.
                $result = $stmt->get_result();

                // Cek apakah query mengembalikan setidaknya satu baris (artinya pengguna punya artikel).
                if ($result->num_rows > 0) {
                    // Lakukan perulangan (loop) untuk setiap artikel yang ditemukan.
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        // Tampilkan judul artikel (diamankan dengan htmlspecialchars).
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        // Tampilkan tanggal, diformat agar lebih mudah dibaca (misal: 20 Jul 2025, 21:37).
                        echo "<td>" . date('d M Y, H:i', strtotime($row['created_at'])) . "</td>";
                        // Kolom untuk tombol-tombol aksi (Edit dan Hapus).
                        echo "<td class='action-links'>";
                        // Tautan Edit, mengirimkan ID artikel ke halaman edit_article.php.
                        echo "<a href='edit_article.php?id=" . $row['id'] . "' class='btn-sm btn-edit'>Edit</a>";
                        // Tautan Hapus, mengirimkan ID artikel ke halaman delete_article.php.
                        // `onclick='return confirm(...)'` adalah JavaScript untuk menampilkan dialog konfirmasi sebelum menghapus.
                        echo "<a href='delete_article.php?id=" . $row['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus artikel ini?\");' class='btn-sm btn-delete'>Hapus</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    // Jika pengguna belum memiliki artikel, tampilkan pesan ini di dalam tabel.
                    echo "<tr><td colspan='3' style='text-align:center;'>Anda belum menulis artikel apa pun.</td></tr>";
                }
                // Tutup statement setelah selesai.
                $stmt->close();
            }
            ?>
        </tbody>
    </table>
</main>

<?php 
// Menyertakan file footer.
include 'footer.php'; 
?>