<?php
// Memuat file konfigurasi, yang seharusnya sudah memulai sesi (session_start())
// dan membuat koneksi ke database ($conn).
require_once 'config.php';

// --- VALIDASI METODE REQUEST ---
// Script ini hanya akan berjalan jika diakses melalui metode POST (yaitu, dari pengiriman form).
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form dan membersihkan username dari spasi di awal/akhir.
    // Password tidak di-trim karena spasi bisa jadi bagian dari password yang disengaja.
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // --- VALIDASI INPUT DASAR ---
    // Memastikan tidak ada field yang kosong.
    if (empty($username) || empty($password)) {
        // Jika kosong, simpan pesan error di session dan kembalikan ke halaman login.
        $_SESSION['login_error'] = "Username dan password tidak boleh kosong.";
        header("location: login.php");
        exit;
    }

    // --- PERSIAPAN DAN EKSEKUSI QUERY (CARA AMAN) ---
    // Menyiapkan template SQL dengan placeholder (?) untuk mencegah SQL Injection.
    $sql = "SELECT id, username, password FROM users WHERE username = ?";

    // Mempersiapkan statement SQL.
    if ($stmt = $conn->prepare($sql)) {
        // Mengikat (bind) variabel $username ke placeholder sebagai string ("s").
        $stmt->bind_param("s", $username);

        // Menjalankan query yang sudah disiapkan.
        if ($stmt->execute()) {
            // Menyimpan hasil query ke dalam statement agar bisa dicek jumlah barisnya.
            $stmt->store_result();

            // --- VALIDASI HASIL QUERY ---
            // Cek apakah ada tepat satu baris hasil (artinya username ditemukan).
            if ($stmt->num_rows == 1) {
                // Mengikat kolom hasil query ke variabel-variabel PHP.
                $stmt->bind_result($id, $db_username, $hashed_password);
                
                // Mengambil data dari baris hasil dan memasukkannya ke variabel yang sudah diikat.
                if ($stmt->fetch()) {
                    // --- VERIFIKASI PASSWORD (LANGKAH PALING KRUSIAL) ---
                    // Menggunakan password_verify() untuk membandingkan password dari form
                    // dengan hash yang tersimpan di database. Ini adalah cara yang aman dan benar.
                    if (password_verify($password, $hashed_password)) {
                        // Jika password cocok, proses login berhasil.
                        
                        // Menetapkan variabel session untuk menandai pengguna sudah login.
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $db_username;

                        // Mengarahkan pengguna ke halaman utama (misal: index.php atau dashboard.php).
                        header("location: index.php");
                        exit(); // Hentikan script setelah redirect.
                    } else {
                        // Jika password tidak cocok.
                        $_SESSION['login_error'] = "Password yang Anda masukkan salah.";
                    }
                }
            } else {
                // Jika tidak ada baris hasil (num_rows bukan 1).
                $_SESSION['login_error'] = "Akun dengan username tersebut tidak ditemukan.";
            }
        } else {
            // Jika terjadi error saat $stmt->execute().
            $_SESSION['login_error'] = "Terjadi kesalahan. Silakan coba lagi.";
        }
        // Menutup statement untuk melepaskan sumber daya.
        $stmt->close();
    }
    // Menutup koneksi database.
    $conn->close();

    // Setelah semua proses di atas (termasuk jika ada error),
    // kembalikan pengguna ke halaman login untuk melihat pesan error.
    header("location: login.php");
    exit;

} else {
    // Jika script diakses langsung (bukan via POST), langsung arahkan ke halaman login.
    header("location: login.php");
    exit;
}
?>