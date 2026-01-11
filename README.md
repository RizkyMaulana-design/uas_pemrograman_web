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

## . Core Architecture

Fondasi aplikasi yang menangani koneksi data dan pengaturan alur halaman (*Routing*).

### A.  Analisis Teknis: `index.php` (The Routing System)

<img width="1572" height="3788" alt="code index" src="https://github.com/user-attachments/assets/f6ee75d5-d5b7-49c6-95d4-290e0eb9788b" />


File ini berfungsi sebagai pintu gerbang tunggal (*Single Entry Point*). Artinya, semua permintaan dari pengguna—baik itu membuka dashboard, melihat produk, atau logout—harus melewati file ini terlebih dahulu.

#### 1. Inisialisasi & Konfigurasi Dasar

```php
session_start();
define('BASE_URL', 'http://localhost/uas_project');
require_once 'config/Database.php';

```

* **`session_start()`**: Perintah ini wajib diletakkan di baris paling atas. Fungsinya untuk memulai sesi server, memungkinkan aplikasi "mengingat" siapa yang sedang login (menyimpan data seperti `user_id` atau `role` saat berpindah halaman).
* **`define('BASE_URL', ...)`**: Mendefinisikan konstanta global yang berisi alamat dasar website. Ini sangat penting agar semua link (CSS, gambar, href) bersifat absolut dan tidak *error* (broken link) saat aplikasi diakses dari sub-folder yang berbeda.
* **`require_once`**: Memanggil file konfigurasi database agar koneksi tersedia untuk seluruh aplikasi.

#### 2. URL Parser (Penerjemah Alamat)

```php
$url = isset($_GET['url']) ? $_GET['url'] : 'login';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$params = explode('/', $url);
$page = $params[0];

```

* Bagian ini bertugas mengambil parameter dari *Address Bar* browser.
* **`rtrim($url, '/')`**: Menghapus tanda garis miring di akhir URL agar konsisten.
* **`FILTER_SANITIZE_URL`**: Fitur keamanan untuk membersihkan URL dari karakter-karakter aneh yang berpotensi membahayakan sistem (*Security Sanitization*).
* **`explode('/', $url)`**: Memecah URL menjadi potongan-potongan array. Contoh: jika URL adalah `admin_products/edit/5`, sistem akan tahu bahwa halaman yang dituju adalah `admin_products`.

#### 3. Middleware Security (Satpam Digital)

Kode ini memiliki dua lapisan keamanan (Logic) sebelum mengizinkan user masuk:

* **Logic 1: Guest Guard (Cegah Login Ulang)**
```php
if (isset($_SESSION['user_id']) && ($page == 'login' || $page == 'register')) { ... }

```


Jika pengguna **sudah login** tetapi mencoba membuka halaman Login atau Register, sistem akan langsung menendang mereka kembali ke Dashboard sesuai peran mereka (Admin ke Dashboard Admin, User ke Katalog). Ini mencegah kebingungan logika (*Looping*).
* **Logic 2: Auth Guard (Cegah Penyusup)**
```php
if (!isset($_SESSION['user_id']) && $page != 'login' && $page != 'register') { ... }

```


Jika pengguna **belum login** (tamu) dan mencoba memaksa masuk ke halaman dalam (misal mengetik `/admin_dashboard` di URL), sistem akan memaksa mereka kembali ke halaman Login. Ini adalah proteksi utama sistem Anda.

#### 4. The Dispatcher (Pengatur Lalu Lintas)

```php
switch ($page) {
    case 'login': require_once 'views/login.php'; break;
    case 'admin_dashboard': require_once 'views/admin/dashboard.php'; break;
    // ... dan seterusnya
}

```

* Menggunakan struktur **`switch-case`** untuk mencocokkan kata kunci di URL dengan file yang harus dibuka.
* Contoh: Jika URL meminta `catalog`, maka sistem akan memanggil file `views/user/catalog.php`.
* **Modularitas**: Dengan cara ini, kode menjadi rapi karena logika tampilan (*View*) dipisahkan ke dalam folder `views/`, dan `index.php` hanya bertugas memanggilnya.

#### 5. Handling Logout & Error 404

* **Logout**: Case `logout` akan menghancurkan sesi (`session_destroy()`) dan mengembalikan user ke halaman login.
* **Default (404)**: Jika pengguna mengetik alamat yang tidak terdaftar, blok `default` akan bekerja. Sistem cukup pintar untuk mengecek: jika user sudah login, kembalikan ke dashboard; jika belum, suruh login.


---

## 2. Authentication & Security

Sistem keamanan untuk memvalidasi identitas pengguna (`controllers/AuthController.php`).


<img width="2048" height="3836" alt="carbon (3)" src="https://github.com/user-attachments/assets/45808fb8-aec7-4bdc-aea9-2ef533a949e3" />
*(Gambar: Code`controllers/AuthController.php`)

*<img width="957" height="468" alt="login" src="https://github.com/user-attachments/assets/d93f0857-e372-4458-b4ce-dc3e4431370a" />
*(Gambar: Halaman Login)

<img width="960" height="475" alt="register" src="https://github.com/user-attachments/assets/9151c89a-7125-42bc-866f-26995ab29986" />
*(Gambar: Halaman Register)

Berikut adalah penjelasan teknis dan mendalam untuk file **`controllers/AuthController.php`** sesuai dengan gambar `carbon (3).jpg` yang Anda unggah.


### 📄 Analisis Teknis: `controllers/AuthController.php` (Authentication Logic)

File ini bertindak sebagai **Controller** yang menangani seluruh logika bisnis terkait otentikasi pengguna. File ini menjembatani input dari *View* (Form Login/Register) ke *Database*.

#### 1. Koneksi Database & Constructor

```php
public function __construct()
{
    try {
        $db = new Database();
        $this->conn = $db->getConnection();
    } catch (Exception $e) { ... }
}

```

* **Dependency Initialization:** Saat class `AuthController` dipanggil, fungsi `__construct` otomatis berjalan pertama kali untuk membuka koneksi ke database.
* **Error Containment:** Menggunakan blok `try-catch` agar jika database mati, program tidak *crash* total, melainkan menampilkan pesan error yang terkendali.

#### 2. Logika Registrasi (Secure Registration)

Fungsi `register()` menangani pendaftaran pengguna baru dengan standar keamanan tinggi.

* **Password Hashing (Enkripsi Satu Arah):**
```php
$hash = password_hash($password, PASSWORD_DEFAULT);

```


Sistem **TIDAK** menyimpan password asli (*plain text*) pengguna. Password diubah menjadi string acak menggunakan algoritma **Bcrypt** (`PASSWORD_DEFAULT`). Ini menjamin bahwa admin database sekalipun tidak bisa melihat password pengguna.
* **Prepared Statements (Anti SQL Injection):**
```php
$stmt = $this->conn->prepare("INSERT INTO users ... VALUES (:f, :u, :p, ...)");
$stmt->execute([':f' => $fullname, ...]);

```


Query SQL menggunakan *placeholders* (`:f`, `:u`) untuk memisahkan perintah SQL dari data input. Ini menutup celah keamanan dari serangan *SQL Injection*.
* **Duplicate Handling (Error Code 23000):**
```php
if ($e->getCode() == 23000) { ...header('Location: ...error=exists'); }

```


Sistem menangkap kode error SQL spesifik `23000` (Integrity Constraint Violation). Ini terjadi jika user mencoba mendaftar dengan *username* yang sudah ada. Sistem akan mengembalikan user ke halaman register dengan pesan "Username sudah terpakai".

#### 3. Logika Login (Verification & Session)

Fungsi `login()` bertugas memvalidasi kredensial pengguna.

* **Password Verification:**
```php
if ($user && password_verify($password, $user['password'])) { ... }

```


Menggunakan fungsi `password_verify()` untuk mencocokkan password yang diinput saat login dengan *hash* terenkripsi yang ada di database.
* **Session Management (State Persistence):**
```php
$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];
// ...

```


Jika login sukses, data penting (`id`, `role`, `fullname`, `photo`) disimpan ke dalam **SESSION**. Data inilah yang nantinya digunakan oleh `index.php` untuk menentukan apakah user boleh masuk ke Dashboard Admin atau Katalog User.
* **Splash Screen Redirection:**
Setelah sesi dibuat, pengguna tidak langsung masuk dashboard, melainkan diarahkan ke `views/splash.php` untuk menampilkan efek animasi *loading* "Hyperspace".

#### 4. Request Handler & Direct Access Protection

Bagian terbawah file berfungsi sebagai **Dispatcher** untuk menangani permintaan HTTP.

* **POST Method Check:**
```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { ... }

```


Memastikan logika hanya berjalan jika data dikirim melalui metode POST (aman).
* **Direct Access Block (Security):**
```php
} else {
    echo "<h1>AKSES DITOLAK</h1>";
    // ...
}

```


Jika ada pengguna iseng yang mencoba membuka file ini langsung dari browser (lewat URL `controllers/AuthController.php` tanpa form), sistem akan memblokir akses dan menampilkan pesan peringatan merah "AKSES DITOLAK".

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
