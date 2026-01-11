<div align="center">

  <img src="https://img.shields.io/badge/PROJECT-ROYAL%20COMMERCE-gold?style=for-the-badge&logo=unrealengine&logoColor=black" />
  <img src="https://img.shields.io/badge/TYPE-FINAL%20PROJECT-blue?style=for-the-badge" />
  <img src="https://img.shields.io/badge/CODE-PHP%20NATIVE%20OOP-777BB4?style=for-the-badge&logo=php&logoColor=white" />

  <br /> <br />

  <h1>👑 ROYAL COMMERCE: ARCHITECTURE DOCUMENTATION</h1>
  <p><strong>Sistem E-Commerce Berbasis Web dengan Implementasi 4D Visual & Secure Authentication</strong></p>

  <br />

  <table align="center" style="border: 2px solid #FFD700; border-collapse: collapse;">
    <tr style="background-color: #000; color: #FFD700;">
      <th style="padding: 10px; border: 1px solid #FFD700;">NAMA MAHASISWA</th>
      <th style="padding: 10px; border: 1px solid #FFD700;">NIM</th>
      <th style="padding: 10px; border: 1px solid #FFD700;">KELAS</th>
      <th style="padding: 10px; border: 1px solid #FFD700;">MATA KULIAH</th>
    </tr>
    <tr>
      <td align="center" style="padding: 10px; border: 1px solid #FFD700;"><strong>Rizky Maulana</strong></td>
      <td align="center" style="padding: 10px; border: 1px solid #FFD700;">312410430</td>
      <td align="center" style="padding: 10px; border: 1px solid #FFD700;">TI 24 A 3</td>
      <td align="center" style="padding: 10px; border: 1px solid #FFD700;">Pemrograman Web</td>
    </tr>
  </table>

  <br />
  <p><em>"Dokumentasi ini menjelaskan secara rinci alur logika, struktur database, dan implementasi antarmuka."</em></p>
</div>

---

## 📚 Daftar Isi
1. [Arsitektur Database](#1-arsitektur-database-connection-layer)
2. [Sistem Otentikasi & Keamanan](#2-sistem-otentikasi--keamanan-logic-layer)
3. [Manajemen Sesi & Splash Screen](#3-manajemen-sesi--splash-screen-ux-layer)
4. [Logika Dashboard & Routing](#4-logika-dashboard--routing)
5. [Manajemen Produk (Advanced CRUD)](#5-manajemen-produk-advanced-crud--upload)
6. [Fitur User & Profil](#6-fitur-user--manipulasi-profil)
7. [Reporting & Data Visualization](#7-reporting--data-visualization)

---

## 1. Arsitektur Database (Connection Layer)

Fondasi aplikasi dibangun di atas file konfigurasi database yang menggunakan pendekatan OOP (*Object Oriented Programming*).

![Screenshot Database Code](docs/foto_database.png)
*(Gambar: Struktur Tabel dan File Database.php)*

### 🧠 Bedah Coding (`config/Database.php`):
* **Class Encapsulation:** Kode dibungkus dalam `class Database`. Variabel sensitif seperti `$host`, `$db_name`, `$username`, dan `$password` bersifat `private` agar tidak bisa diakses langsung dari luar class.
* **PHP Data Objects (PDO):** Saya tidak menggunakan `mysqli` biasa, melainkan **PDO**.
    * *Alasan:* PDO mendukung berbagai driver database dan memiliki fitur keamanan *Prepared Statements* yang lebih baik untuk mencegah SQL Injection.
* **Error Handling:** Menggunakan blok `try { ... } catch (PDOException $e)`.
    * Jika koneksi gagal, sistem tidak akan menampilkan error fatal yang membocorkan path server, melainkan pesan error yang terkontrol.
* **Connection Attributes:** `setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)` diaktifkan agar setiap kesalahan query langsung terdeteksi sebagai *Exception*.

---

## 2. Sistem Otentikasi & Keamanan (Logic Layer)

Sistem login dan register menggunakan validasi ketat untuk membedakan hak akses Admin dan User.

![Screenshot Halaman Login](docs/foto_login.png)
*(Gambar: Form Login dengan desain 4D)*

### 🧠 Bedah Coding (`controllers/AuthController.php`):
* **Password Hashing:**
    * Saat **Register**, password tidak disimpan sebagai teks biasa (Plaintext). Saya menggunakan fungsi `password_hash($password, PASSWORD_DEFAULT)` yang mengubah password menjadi string acak (Bcrypt).
    * Saat **Login**, verifikasi dilakukan dengan `password_verify()`. Ini menjamin keamanan data user jika database bocor.
* **Prepared Statements:**
    * Query Login: `SELECT * FROM users WHERE username = :u`.
    * Penggunaan placeholder `:u` mencegah hacker menyisipkan kode berbahaya (SQL Injection) pada input username.
* **Role Identification:**
    * Sistem mengecek kolom `role` di database. Jika `admin`, diarahkan ke Dashboard Admin. Jika `user`, diarahkan ke Katalog.

---

## 3. Manajemen Sesi & Splash Screen (UX Layer)

Salah satu fitur unggulan yang memberikan kesan "Mewah" adalah transisi antar halaman.

![Screenshot Splash Screen](docs/foto_splash.png)
*(Gambar: Layar loading Hyperspace sebelum masuk sistem)*

### 🧠 Bedah Coding (`views/splash.php`):
* **Session State:** Pada awal file, terdapat `session_start()`. Ini wajib ada untuk membaca data user yang sedang login.
* **Intermediate Page Logic:**
    * File ini berfungsi sebagai "Ruang Tunggu". Tidak ada interaksi user di sini.
    * JavaScript `setTimeout(function() { window.location.href = ... }, 2800);` digunakan untuk menahan halaman selama 2.8 detik. Waktu ini disesuaikan agar animasi loading bar selesai tepat waktu.
* **Visual Logic:** Animasi bintang (*Starfield*) dibuat menggunakan CSS & JavaScript loop yang me-render elemen `div` secara acak (`Math.random()`) pada sumbu X dan Y layar.

---

## 4. Logika Dashboard & Routing

Pusat kendali aplikasi yang membatasi akses berdasarkan hak pengguna.

![Screenshot Dashboard](docs/foto_dashboard_admin.png)
*(Gambar: Dashboard Admin)*

### 🧠 Bedah Coding (`views/admin/dashboard.php`):
* **Access Control List (ACL):**
    * Baris pertama kode berisi:
        ```php
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
            header("Location: login.php"); exit();
        }
        ```
    * *Fungsi:* Ini adalah "Satpam". Jika ada user biasa mencoba mengetik URL admin secara manual, mereka akan langsung ditendang keluar.
* **Dynamic Data Counting:**
    * Widget "Total Produk" dan "Total Member" tidak statis.
    * Menggunakan query `SELECT COUNT(*) FROM products`. Angka yang muncul adalah hasil perhitungan *real-time* dari database.

---

## 5. Manajemen Produk (Advanced CRUD & Upload)

Fitur paling kompleks yang menangani manipulasi data barang.

![Screenshot CRUD Produk](docs/foto_produk.png)
*(Gambar: Tabel Produk)*

![Screenshot Modal Form](docs/foto_modal_tambah.png)
*(Gambar: Form Modal Pop-up)*

### 🧠 Bedah Coding (`views/admin/products.php`):
* **Single Page Application (SPA) Feel:**
    * Menggunakan Bootstrap **Modal** (Popup). User tidak perlu berpindah halaman untuk menambah atau mengedit barang. Ini meningkatkan *User Experience* (UX).
* **JavaScript DOM Manipulation:**
    * Terdapat fungsi `editProduct(id, name, price...)`.
    * Saat tombol Edit ditekan, JavaScript mengambil data dari baris tabel dan "menyuntikkannya" ke dalam formulir Modal secara instan.
* **File Upload Handling:**
    * Form menggunakan atribut `enctype="multipart/form-data"` (Wajib untuk upload file).
    * **Validasi:** Sistem mengecek ekstensi file (harus jpg/png).
    * **Renaming:** File gambar diubah namanya menggunakan `time()` (Timestamp) agar unik. Contoh: `prod_17658293.jpg`. Ini mencegah error jika ada dua user mengupload file bernama "foto.jpg".

---

## 6. Fitur User & Manipulasi Profil

Memungkinkan pengguna mengelola data pribadi mereka secara mandiri.

![Screenshot Profil](docs/foto_profil.png)
*(Gambar: Halaman Profil User)*

### 🧠 Bedah Coding (`views/user/profile.php`):
* **Real-time Image Preview:**
    * Menggunakan **JavaScript FileReader API**.
    * Saat user memilih foto dari galeri, script membaca file tersebut dan langsung mengganti atribut `src` pada tag `<img>` profil. Hasilnya, foto berubah di layar *sebelum* tombol simpan ditekan.
* **Conditional Update Query:**
    * Logika PHP mendeteksi: "Apakah user mengisi kolom password?".
    * Jika kosong, query SQL hanya mengupdate nama/alamat.
    * Jika terisi, query SQL ikut mengupdate (dan mengenkripsi ulang) password baru.

---

## 7. Reporting & Data Visualization

Laporan transaksi dengan visualisasi data keuangan "The Matrix".

![Screenshot Laporan](docs/foto_laporan.png)
*(Gambar: Laporan Transaksi)*

### 🧠 Bedah Coding (`views/admin/report.php`):
* **SQL JOIN Operation:**
    * Tabel `transactions` hanya menyimpan ID User (angka).
    * Agar laporan terbaca manusia, saya menggunakan `INNER JOIN users ON transactions.user_id = users.id`.
    * *Hasil:* Laporan menampilkan "Nama Lengkap Pembeli", bukan hanya "ID 5".
* **Canvas Rendering Context 2D:**
    * Efek "Hujan Emas" tidak menggunakan video (berat), melainkan digambar manual oleh kode JavaScript pada elemen HTML5 `<canvas>`.
    * Kode me-looping array karakter, memberikan posisi acak, dan menjatuhkannya ke bawah setiap milidetik.

---

<div align="center">
  <h3>🔒 Penutup</h3>
  <p>Seluruh kode program disusun dengan memperhatikan prinsip <strong>Clean Code</strong>, keamanan data, dan estetika visual.</p>
  <p><strong>Copyright © 2026 Rizky Maulana.</strong></p>
</div>
