<?php
// --- LANGKAH 1: MEMULAI ATAU MELANJUTKAN SESI ---
// Ini mungkin terdengar aneh, tetapi untuk dapat menghancurkan (destroy) sebuah sesi,
// kita harus memulainya terlebih dahulu. Fungsi session_start() akan memuat
// data sesi yang ada sehingga kita bisa memanipulasinya.
session_start();

// --- LANGKAH 2: MENGOSONGKAN SEMUA VARIABEL SESI ---
// Baris ini akan mengambil semua data yang tersimpan di dalam array superglobal $_SESSION
// (misalnya, $_SESSION['loggedin'], $_SESSION['username'], dll.) dan menggantinya
// dengan sebuah array kosong. Ini secara efektif "membersihkan" semua data sesi.
$_SESSION = array();

// --- LANGKAH 3: MENGHANCURKAN SESI SECARA PERMANEN ---
// Ini adalah langkah kunci. session_destroy() akan menghapus file sesi dari
// penyimpanan server. Setelah fungsi ini dijalankan, sesi yang terkait dengan
// ID sesi pengguna tersebut tidak akan ada lagi.
session_destroy();

// --- LANGKAH 4: MENGALIHKAN PENGGUNA ---
// Setelah sesi berhasil dihancurkan, arahkan pengguna kembali ke halaman login.
// Ini memberikan feedback visual bahwa mereka telah berhasil logout.
header("location: login.php");
// 'exit' sangat penting untuk memastikan tidak ada kode lain yang dieksekusi
// setelah perintah pengalihan (redirect) dikirimkan.
exit;
?>