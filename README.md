<div align="center">

  <img src="https://img.shields.io/badge/PROJECT-ROYAL%20COMMERCE-gold?style=for-the-badge&logo=unrealengine&logoColor=black" />
  <img src="https://img.shields.io/badge/BUILD-STABLE%20v1.0-success?style=for-the-badge" />
  <img src="https://img.shields.io/badge/STACK-PHP%20NATIVE%20OOP-777BB4?style=for-the-badge&logo=php&logoColor=white" />

  <br /> <br />

  <h1>👑 ROYAL COMMERCE: FULL STACK DOCUMENTATION</h1>
  <p><strong>Sistem E-Commerce End-to-End dengan Visualisasi 4 Dimensi & Manajemen Transaksi Real-time</strong></p>

  <br />

  <table align="center" style="border: 2px solid #FFD700; border-collapse: collapse;">
    <tr style="background-color: #0f0f0f; color: #FFD700;">
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
  <p><em>"Dokumentasi lengkap mencakup arsitektur backend, alur belanja (User Flow), manajemen produk, hingga pelaporan keuangan (Admin)."</em></p>
</div>

---

## 📚 Daftar Isi
1. [Core Architecture (Routing & Database)](#1-core-architecture)
2. [Authentication & Security](#2-authentication--security)
3. [User Experience (Splash Screen)](#3-user-experience-splash-screen)
4. [Admin Module: Dashboard & Products](#4-admin-module-dashboard--products)
5. [Shopping Module: Catalog & Cart](#5-shopping-module-catalog--cart-logic)
6. [Transaction Module: Checkout & Reports](#6-transaction-module-checkout--reports)
7. [User Profile Management](#7-user-profile-management)

---

## 1. Core Architecture

Fondasi aplikasi yang menangani koneksi data dan pengaturan alur halaman (*Routing*).

### A. Central Routing (`index.php`)
File ini berfungsi sebagai **Front Controller**. Semua request masuk melalui satu pintu untuk keamanan dan keteraturan URL.

* **URL Sanitization:** Menggunakan `filter_var(..., FILTER_SANITIZE_URL)` untuk membersihkan karakter ilegal dari URL.
* **Middleware Logic:**
    * Mencegah user yang belum login masuk ke halaman admin.
    * Mencegah user yang sudah login kembali ke halaman login (Redirect Loop Protection).
* **Dispatcher:** Menggunakan `switch-case` untuk memanggil file View yang tepat (`catalog`, `cart`, `admin_dashboard`) berdasarkan parameter URL.

### B. Database Connection (`config/Database.php`)
Menggunakan **PDO (PHP Data Objects)** dengan konfigurasi khusus.

* **Custom Port & Host:** Dikonfigurasi ke **Port 3307** dan Host **127.0.0.1** untuk stabilitas maksimal di server lokal (XAMPP).
* **OOP Encapsulation:** Kredensial database dibungkus dalam properti `private` agar tidak bisa diakses dari luar class.
* **Exception Handling:** Koneksi dibungkus dalam blok `try-catch` untuk menangani error database tanpa mematikan aplikasi secara kasar.

---

## 2. Authentication & Security

Sistem keamanan untuk memvalidasi identitas pengguna (`controllers/AuthController.php`).

![Screenshot Login](docs/foto_login.png)
*(Gambar: Halaman Login)*

### 🧠 Bedah Logika:
* **Password Hashing:** Password user tidak disimpan mentah. Saat Register, password dienkripsi menggunakan `password_hash()` (Algoritma Bcrypt).
* **Login Verification:** Saat Login, sistem mencocokkan input user dengan hash di database menggunakan `password_verify()`.
* **Role Management:** Sistem menyimpan status `role` ('admin' atau 'user') ke dalam Session PHP. Ini digunakan untuk membedakan fitur yang bisa diakses.

---

## 3. User Experience: Splash Screen

Transisi visual mewah sebelum masuk ke aplikasi utama (`views/splash.php`).

![Screenshot Splash](docs/foto_splash.png)
*(Gambar: Animasi Loading Hyperspace)*

### 🧠 Bedah Logika:
* **Asynchronous Delay:** Menggunakan JavaScript `setTimeout()` selama 3 detik untuk memberikan waktu bagi animasi loading bar.
* **Visual Engineering:** Menggunakan CSS Keyframes untuk menggerakkan elemen bintang (*Starfield*) secara acak, menciptakan efek perjalanan luar angkasa 4 Dimensi.

---

## 4. Admin Module: Dashboard & Products

Pusat kendali admin untuk memantau statistik dan mengelola stok barang.

![Screenshot Dashboard](docs/foto_dashboard_admin.png)
*(Gambar: Dashboard Admin)*

### A. Dashboard (`views/admin/dashboard.php`)
* **Real-time Counter:** Widget jumlah produk dan member dihitung langsung dari database menggunakan query `SELECT COUNT(*)`.

### B. Product CRUD (`views/admin/products.php`)
![Screenshot Produk](docs/foto_produk.png)
*(Gambar: Tabel Manajemen Produk)*

* **Modal Interaction:** Form tambah/edit barang menggunakan Popup Modal, sehingga admin tidak perlu berpindah halaman (Single Page Feel).
* **Image Handling:** Sistem upload gambar menggunakan fungsi `move_uploaded_file()` dengan penamaan file otomatis berbasis waktu (`time()`) untuk mencegah duplikasi nama file.

---

## 5. Shopping Module: Catalog & Cart Logic

Fitur utama bagi User untuk memilih barang dan mengelola belanjaan.

![Screenshot Katalog](docs/foto_katalog.png)
*(Gambar: Katalog Produk User)*

### A. Catalog (`views/user/catalog.php`)
* **Session Cart Storage:**
    * Keranjang belanja tidak langsung disimpan di database, melainkan di **PHP Session Array** (`$_SESSION['cart']`).
    * *Logika:* `$_SESSION['cart'][id_barang] = jumlah_qty`.
    * Ini membuat proses belanja sangat cepat karena tidak membebani database setiap kali user klik "Beli".

### B. Shopping Cart (`views/user/cart.php`)
![Screenshot Keranjang](docs/foto_keranjang.png)
*(Gambar: Halaman Keranjang Belanja)*

* **Dynamic Calculation:**
    * Halaman ini melakukan *looping* (perulangan) pada array session keranjang.
    * Mengambil harga terbaru dari database berdasarkan ID produk, lalu mengalikannya dengan kuantitas (`Price * Qty = Subtotal`).
* **Remove Item:** Fitur `unset($_SESSION['cart'][$id])` digunakan untuk menghapus item tertentu dari array keranjang.

---

## 6. Transaction Module: Checkout & Reports

Tahap akhir transaksi: Pembayaran oleh user dan pelaporan untuk admin.

### A. Checkout Logic (User)
* **Data Persistence:**
    * Saat user menekan tombol "Bayar", data dipindahkan dari *Session* (Temporary) ke tabel **`transactions`** di Database (Permanent).
    * Query: `INSERT INTO transactions (user_id, total_price, ...)`.
* **State Reset:** Setelah data berhasil disimpan, keranjang dikosongkan (`unset($_SESSION['cart'])`) agar siap untuk transaksi berikutnya.

### B. Admin Financial Report (`views/admin/report.php`)
![Screenshot Laporan](docs/foto_laporan.png)
*(Gambar: Laporan Keuangan dengan Efek Hujan Uang)*

* **Relational Query (SQL JOIN):**
    * Tabel transaksi hanya menyimpan `user_id` (Angka).
    * Agar laporan informatif, sistem menggunakan `INNER JOIN users` untuk mengambil **Nama Lengkap** pembeli berdasarkan ID tersebut.
* **Visual Data:** Background "Digital Rain" (Hujan Emas) dibuat menggunakan HTML5 Canvas untuk memvisualisasikan aliran data keuangan.

---

## 7. User Profile Management

Personalisasi akun pengguna (`views/user/profile.php`).

![Screenshot Profil](docs/foto_profil.png)
*(Gambar: Halaman Profil)*

### 🧠 Bedah Logika:
* **JavaScript FileReader:** Fitur preview foto profil. Saat user memilih file, gambar di layar langsung berubah secara *real-time* sebelum tombol simpan ditekan.
* **Smart Update:** Logika PHP mengecek apakah user mengisi kolom password. Jika kosong, sistem hanya mengupdate biodata. Jika terisi, sistem akan melakukan *Re-Hashing* password baru demi keamanan.

---

<div align="center">
  <h3>🔒 Penutup</h3>
  <p>Royal Commerce adalah bukti implementasi teknik pemrograman web modern yang menggabungkan logika backend yang kuat, keamanan data, dan estetika frontend kelas atas.</p>
  <p><strong>Copyright © 2026 Rizky Maulana. All Rights Reserved.</strong></p>
</div>
