<aside class="sidebar">
    <nav class="side-nav">
        <ul>
            <li>
                <a href="artikel.php" class="dropdown-toggle">Artikel</a>
                <ul class="dropdown-menu">
                    <li><a href="kategori.php?k=teknologi">Teknologi</a></li>
                    <li><a href="kategori.php?k=berita">Berita</a></li>
                    <li><a href="kategori.php?k=tutorial">Tutorial</a></li>
                </ul>
            </li>
            <li><a href="event.php">Event</a></li>
            <li><a href="galeri.php">Galeri</a></li>
            <li><a href="klien.php">Foto Klien Kami</a></li>

            <li class="divider"></li>
            
            <?php 
            // --- LOGIKA KONDISIONAL BERBASIS STATUS LOGIN ---
            // Ini adalah bagian dinamis dari sidebar. Tampilannya akan berubah tergantung
            // apakah pengguna sudah login atau belum.

            // Memeriksa DUA kondisi:
            // 1. isset($_SESSION["loggedin"]): Apakah variabel session 'loggedin' sudah di-set?
            // 2. $_SESSION["loggedin"] === true: Apakah nilainya benar-benar 'true'?
            // Penggunaan '===' memastikan pengecekan tipe data dan nilai (lebih aman).
            // Tanda ':' adalah sintaks alternatif untuk '{' yang sering dipakai dalam template.
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): 
            ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li>
                    <a href="logout.php">
                        Sign Out (<?php 
                            // Menampilkan nama pengguna yang sedang login.
                            // htmlspecialchars() adalah FUNGSI KEAMANAN PENTING untuk mencegah serangan Cross-Site Scripting (XSS).
                            // Fungsi ini akan mengubah karakter khusus seperti < dan > menjadi entitas HTML (&lt; dan &gt;),
                            // sehingga tidak bisa dieksekusi sebagai kode oleh browser jika nama pengguna mengandung script berbahaya.
                            echo htmlspecialchars($_SESSION["username"]); 
                        ?>)
                    </a>
                </li>

            <?php else: // Jika kondisi 'if' di atas TIDAK terpenuhi (pengguna BELUM login). ':' adalah sintaks alternatif untuk 'else {' ?>
                
                <li><a href="login.php">Sign In</a></li>
                <li><a href="register.php">Sign Up</a></li>

            <?php endif; // Menutup blok kondisional if-else. Ini adalah sintaks alternatif untuk '}'. ?>
        </ul>
    </nav>
</aside>