<?php
// Memuat file konfigurasi, yang seharusnya sudah memulai sesi (session_start())
// dan membuat koneksi ke database ($conn).
require_once 'config.php';

// --- KEAMANAN TINGKAT 1: OTENTIKASI ---
// Cek apakah pengguna sudah login. Jika belum, mereka tidak boleh melakukan aksi hapus.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php"); // Alihkan ke halaman login.
    exit; // Hentikan eksekusi script.
}

// --- VALIDASI INPUT: MEMASTIKAN ID ARTIKEL ADA ---
// Cek apakah parameter 'id' ada di URL dan nilainya tidak kosong.
if(isset($_GET['id']) && !empty($_GET['id'])){
    // Variabel untuk menampung ID artikel dari URL dan ID pengguna dari sesi.
    $article_id = $_GET['id'];
    $user_id = $_SESSION['id'];

    // --- PERSIAPAN DAN EKSEKUSI PENGHAPUSAN (CARA AMAN) ---
    // Menyiapkan template SQL untuk menghapus data.
    // PENTING: Klausa `AND user_id = ?` adalah langkah keamanan dan otorisasi yang krusial.
    // Ini memastikan bahwa perintah DELETE hanya akan berhasil jika ID artikel cocok DAN
    // ID pengguna yang tercatat sebagai pemilik artikel sama dengan ID pengguna yang sedang login.
    // Ini mencegah Pengguna A menghapus artikel milik Pengguna B.
    $sql = "DELETE FROM articles WHERE id = ? AND user_id = ?";

    // Mempersiapkan statement SQL untuk mencegah SQL Injection.
    if($stmt = $conn->prepare($sql)){
        // Mengikat (bind) variabel PHP ke placeholder dalam statement.
        // "ii" mendefinisikan tipe data dari setiap variabel: i = integer ($article_id), i = integer ($user_id).
        $stmt->bind_param("ii", $article_id, $user_id);

        // Mencoba untuk mengeksekusi statement penghapusan.
        if($stmt->execute()){
            // Setelah eksekusi, cek berapa banyak baris yang terpengaruh/terhapus.
            if($stmt->affected_rows > 0){
                // Jika lebih dari 0 (biasanya 1), berarti penghapusan berhasil.
                // Simpan pesan sukses di session untuk ditampilkan di halaman dashboard.
                $_SESSION['success_message'] = "Artikel berhasil dihapus.";
            } else {
                // Jika 0, berarti tidak ada baris yang cocok dengan klausa WHERE.
                // Ini bisa terjadi jika artikel tidak ada, atau pengguna mencoba menghapus artikel orang lain.
                $_SESSION['error_message'] = "Gagal menghapus artikel atau Anda tidak punya izin.";
            }
        } else {
            // Jika terjadi error saat eksekusi query itu sendiri.
            $_SESSION['error_message'] = "Terjadi kesalahan saat mengeksekusi perintah.";
        }
        // Menutup statement untuk melepaskan sumber daya.
        $stmt->close();
    } else {
        // Jika terjadi error saat mempersiapkan statement (misal: error syntax SQL).
        $_SESSION['error_message'] = "Terjadi kesalahan pada server.";
    }

    // Setelah mencoba menghapus (baik berhasil maupun gagal), arahkan kembali ke dashboard.
    header("location: dashboard.php");
    exit();

} else {
    // Jika script diakses tanpa menyertakan ID artikel di URL,
    // langsung arahkan ke dashboard tanpa melakukan apa-apa.
    header("location: dashboard.php");
    exit();
}
?>