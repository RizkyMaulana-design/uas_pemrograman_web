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

<img width="2048" height="8082" alt="carbon" src="https://github.com/user-attachments/assets/0481cf02-be93-4894-be81-8a99c11a3f26" />

*(CODE: views/splash.php)*


<img width="936" height="425" alt="sflash screen" src="https://github.com/user-attachments/assets/e4e96dc2-71cb-4304-836e-52857cc4c5d2" />

*(Gambar: Animasi Loading Hyperspace)*

Tentu, ini adalah bedah kode untuk file **`views/splash.php`** berdasarkan gambar *source code* (`carbon.jpg`) dan tampilan hasilnya (`sflash screen.jpg`) yang Anda kirimkan.

Fitur ini berfungsi sebagai **Jembatan Visual (Transition Layer)** yang memberikan kesan canggih (*High-Tech*) sebelum pengguna masuk ke aplikasi utama.


#### 1. Session Validation & Routing Logic

Di baris paling atas, PHP memastikan hanya pengguna sah yang bisa melihat layar ini.

```php
session_start();
// Cek jika tidak ada session login, tendang ke login
if (!isset($_SESSION['role'])) {
    header("Location: .../login");
    exit();
}
// Tentukan tujuan berdasarkan Role
$target_url = ($_SESSION['role'] == 'admin') ? '.../admin_dashboard' : '.../catalog';

```

* **Security Check:** Script langsung mengecek `$_SESSION['role']`. Jika seseorang mencoba membuka file ini tanpa login, mereka langsung dilempar kembali ke halaman Login.
* **Dynamic Routing:** Variabel `$target_url` disiapkan di awal. Jika Admin, tujuan berikutnya adalah Dashboard. Jika User biasa, tujuannya adalah Katalog.

#### 2. Visual Engineering (CSS Animation)

Tampilan "Hyperspace" atau lorong waktu dibuat murni menggunakan **CSS3** tanpa video (sehingga ringan).

* **Container 3D:**
```css
.hyperspace { perspective: 100px; transform-style: preserve-3d; ... }

```


CSS `perspective` memberikan efek kedalaman (depth), seolah-olah bintang-bintang bergerak dari jauh mendekat ke layar monitor.
* **Star Animation (`@keyframes warp`):**
```css
@keyframes warp {
    0% { transform: translateZ(-100px); opacity: 0; }
    100% { transform: translateZ(200px); opacity: 1; }
}

```


Animasi ini menggerakkan elemen bintang dari sumbu Z negatif (jauh di belakang layar) ke sumbu Z positif (depan muka pengguna), menciptakan ilusi kecepatan tinggi.
* **Progress Bar:**
Elemen `.progress-bar` menggunakan animasi `load` selama 2.5 detik yang berjalan dari lebar 0% ke 100%, sinkron dengan waktu tunggu redirect.

#### 3. JavaScript Logic (The Engine)

Bagian JavaScript di bawah body bertugas merender partikel bintang secara acak dan menangani pengalihan halaman.

* **Auto-Redirect Timer:**
```javascript
setTimeout(function() {
    window.location.href = '<?= $target_url ?>';
}, 2800);

```


Fungsi ini menahan pengguna selama **2.8 detik**. Waktu ini diseting sedikit lebih lama dari animasi loading bar (2.5 detik) agar transisi terasa natural.
* **Procedural Generation (Pembangkitan Partikel):**
```javascript
for (let i = 0; i < 100; i++) {
    let star = document.createElement('div');
    // Random Position X, Y, Z
    let x = (Math.random() - 0.5) * window.innerWidth * 2;
    let y = (Math.random() - 0.5) * window.innerHeight * 2;
    ...
}

```


Looping ini membuat **100 elemen bintang** secara otomatis. Posisi X dan Y diacak (`Math.random()`) agar bintang tersebar merata di seluruh layar, tidak menumpuk di satu titik. Ini teknik yang efisien karena tidak membebani server (Client-side rendering).

---

## 4. Admin Module: Dashboard & Products

<img width="2048" height="14936" alt="carbon (1)" src="https://github.com/user-attachments/assets/7260be9a-b110-4f60-9ec8-5ef94a1192dd" />

Pusat kendali admin untuk memantau statistik dan mengelola stok barang.

<img width="959" height="466" alt="dashboard admin" src="https://github.com/user-attachments/assets/bbc2f6d1-7e33-4a37-b752-5931817d7f69" />

*(Gambar: Dashboard Admin)*


### 📄 Analisis Teknis: `views/admin/dashboard.php` (Admin Control Center)

File ini menggabungkan logika backend untuk mengambil statistik data dengan frontend dinamis yang menampilkan waktu dan informasi secara *real-time*.

#### 1. Security & Access Control (Satpam Pintu Masuk)

Di baris paling atas, kode melakukan pengecekan hak akses yang ketat.

```php
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: " . BASE_URL . "/login");
    exit();
}

```

* **Role Verification:** Sistem memverifikasi apakah sesi pengguna memiliki status `admin`. Jika user biasa (atau hacker) mencoba masuk dengan mengetik URL manual, mereka akan langsung ditendang keluar ke halaman login. Ini adalah lapisan keamanan level tertinggi untuk halaman admin.

#### 2. Backend Logic: Data Aggregation (Statistik Real-time)

Sebelum halaman dimuat, PHP melakukan komunikasi dengan database untuk menghitung total data.

```php
// Query menghitung jumlah baris data
$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_transactions = $conn->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

```

* **Efficient Querying:** Menggunakan perintah SQL `COUNT(*)` dan `fetchColumn()`. Metode ini sangat efisien karena database hanya mengirimkan satu angka (jumlah total), bukan mengirimkan ribuan baris data, sehingga dashboard tetap ringan meski datanya banyak.
* **Data Binding:** Angka hasil hitungan (`$total_products`, dll) kemudian "ditempel" langsung ke dalam kartu HTML di bawahnya. Terlihat di screenshot ada angka "5 Unit Barang" dan "1 Total Transaksi".

#### 3. Frontend Engineering: Digital Clock & Typing Effect

Tampilan visual dashboard dirancang agar terasa "hidup".

* **Live Digital Clock:**
```javascript
function updateTime() { ... }
setInterval(updateTime, 1000);

```


Menggunakan JavaScript `setInterval` yang berjalan setiap 1000ms (1 detik) untuk memperbarui jam, menit, dan detik secara *live* tanpa perlu refresh halaman. Tampilannya dibuat besar dengan font digital untuk kesan futuristik.
* **Typing Animation (System Message):**
```html
<div class="typing-effect">Selamat Datang, <?= strtoupper($_SESSION['fullname']) ?> .|</div>

```


Mengambil nama admin dari session (`$_SESSION['fullname']`) dan mengubahnya menjadi huruf kapital (`strtoupper`). Efek kursor berkedip (`.|`) biasanya dibuat menggunakan CSS Animation untuk memberikan kesan seolah-olah sistem sedang menyapa pengguna secara interaktif.

#### 4. UI/UX Design: Dashboard Cards (Glassmorphism)

Elemen kotak-kotak statistik (Unit Barang, Transaksi, User) menggunakan desain modern.

* **Card Layout:**
Setiap kartu menampilkan Ikon (Box/Chip/User), Angka Besar (dari database), dan Tombol Aksi ("Kelola Data").
* **Visual Style:**
Background gelap dengan aksen *Neon Blue* dan font *Monospace* memberikan nuansa *Cyberpunk* atau teknologi tinggi, sesuai dengan tema "Royal 4D" aplikasi Anda.


### B. Product CRUD (`views/admin/products.php`)

<img width="2048" height="16426" alt="code product admin" src="https://github.com/user-attachments/assets/d5c3f09e-9816-4aae-b80e-13fb0891efeb" />


<img width="951" height="468" alt="admin product" src="https://github.com/user-attachments/assets/806b25b1-9a6b-4f3c-8961-3046e079b08a" />
*(Gambar: Tabel Manajemen Produk)*


📄 Analisis Teknis: `views/admin/products.php` (Product CRUD System)

Modul ini dirancang dengan pendekatan **Unified View-Controller**, di mana logika pemrosesan data (PHP) dan antarmuka pengguna (HTML) berada dalam satu file untuk efisiensi.

#### 1. Backend Logic: Request Handling (Create, Update, Delete)

Di bagian paling atas file (sebelum HTML), terdapat blok logika PHP yang menangani aksi formulir.

* **Action Dispatcher:**
Kode memeriksa `$_POST['action']` untuk menentukan apakah admin sedang melakukan **Tambah (Add)** atau **Edit**.
* *Create Logic:* Menjalankan query `INSERT INTO products ...`
* *Update Logic:* Menjalankan query `UPDATE products SET ... WHERE id = ...`


* **Advanced File Upload Algorithm:**
Sistem menangani upload gambar dengan algoritma keamanan:
* **Validation:** Memastikan file yang diupload adalah gambar (JPG/PNG).
* **Renaming Strategy:** Menggunakan fungsi `time()` untuk menamai ulang file (contoh: `17058392.jpg`). Ini mencegah konflik jika ada dua admin mengupload file dengan nama "foto.jpg" yang sama.
* **File Movement:** Menggunakan `move_uploaded_file()` untuk memindahkan gambar dari folder sementara server ke `assets/images/products/`.


* **Deletion Logic:**
Menangkap parameter URL `?delete_id=...`. Sebelum menghapus, sistem melakukan query `DELETE FROM products` dengan filter ID yang aman untuk mencegah penghapusan massal yang tidak disengaja.

#### 2. Data Presentation (Table Rendering)

Sistem mengambil seluruh data produk menggunakan query `SELECT * FROM products ORDER BY id DESC` agar barang terbaru muncul paling atas.

* **Dynamic Looping (`foreach`):**
HTML tabel dibangkitkan secara dinamis. Kode melakukan *looping* pada array hasil query untuk membuat baris tabel (`<tr>`) sebanyak jumlah produk yang ada di database.
* **Asset Mapping:**
Kolom gambar menggunakan tag `<img>` yang sumbernya (`src`) diarahkan dinamis ke folder aset: `src="<?= BASE_URL ?>/assets/images/products/<?= $row['image'] ?>"`.

#### 3. Frontend Interactivity: Modal & DOM Manipulation

Salah satu fitur UX unggulan adalah penggunaan **Modal (Pop-up)** untuk menambah dan mengedit barang, sehingga admin tidak perlu berpindah halaman.

* **Single Modal Implementation:**
Hanya ada **SATU** blok kode Modal HTML di bagian bawah file. Modal ini bersifat *reusable* (bisa dipakai untuk Tambah maupun Edit).
* **JavaScript Data Injection:**
Saat tombol "Edit" (ikon pensil) ditekan, fungsi JavaScript dipanggil:
```javascript
function editProduct(id, name, price, stock, description, image) {
    // Mengubah judul modal jadi "Edit Barang"
    document.getElementById('modalTitle').innerText = "Edit Barang";
    // Mengisi input field dengan data dari baris tabel
    document.getElementById('prodName').value = name;
    document.getElementById('prodPrice').value = price;
    // ...
}

```


Teknik ini disebut **DOM Manipulation**, yang membuat aplikasi terasa cepat dan responsif tanpa *reload* halaman yang tidak perlu.

---

### C. Membership Management (`views/admin/members.php`)

<img width="2048" height="15496" alt="code anggota" src="https://github.com/user-attachments/assets/c850df24-13ea-4d72-8357-c172163dfb98" />


<img width="958" height="442" alt="members" src="https://github.com/user-attachments/assets/10b2a18c-95ef-4908-9bd5-8f703cce69b7" />

*(Gambar:Membership Management)*

Modul ini tidak sekadar menampilkan daftar nama, tetapi melakukan *Data Filtering* dan *Conditional Rendering* (penyesuaian tampilan berdasarkan kelengkapan data user).

#### 1. Backend Logic: Filtering & Security

Di bagian paling atas, skrip melakukan tugas krusial:

* **Role-Based Security:**
Sama seperti dashboard, file ini dilindungi oleh pengecekan `$_SESSION['role'] == 'admin'`. Akses ilegal langsung ditolak.
* **Targeted Query (Data Filtering):**
```php
$members = $conn->query("SELECT * FROM users WHERE role = 'user'")->fetchAll(...);

```


Sistem tidak mengambil semua data. Query SQL menggunakan klausa `WHERE role = 'user'`.
* *Tujuannya:* Agar akun sesama Admin tidak muncul di daftar ini. Halaman ini khusus untuk memantau pelanggan (User biasa).



#### 2. Conditional Rendering (Logika Tampilan Cerdas)

Fitur unggulan di halaman ini adalah kemampuannya menangani data kosong. Tidak semua user mengisi profil lengkap saat mendaftar, dan sistem menanganinya dengan elegan:

* **Phone Number Check (Ternary Logic):**
```php
<?= !empty($m['phone']) ? $m['phone'] : '<span class="...">Tidak Ada</span>' ?>

```


Sistem mengecek kolom telepon. Jika user belum mengisi nomor WA, sistem tidak membiarkannya kosong (blank), melainkan menampilkan lencana "Tidak Ada" agar Admin tahu data belum lengkap.
* **Address Validation:**
Hal serupa diterapkan pada alamat. Jika kosong, sistem menampilkan peringatan visual "Belum diisi" dengan ikon merah (`text-danger`). Ini memudahkan Admin untuk melihat user mana yang profilnya belum valid.
* **Dynamic Avatar Fallback:**
```php
$img = !empty($m['photo']) ? $m['photo'] : 'default.jpg';

```


Jika user belum upload foto profil, sistem otomatis menggunakan `default.jpg` agar tampilan tabel tetap rapi dan tidak rusak (broken image).

#### 3. Visual Engineering: "Gyroscope" Theme

Desain halaman ini berbeda dari Dashboard atau Produk.

* **Background Animation:**
Menggunakan elemen CSS `gyroscope` (lingkaran garis tipis di latar belakang) yang memberikan kesan elemen data yang saling terhubung dan berputar perlahan.
* **Total Counter Badge:**
Di pojok kanan atas terdapat tombol "TOTAL: X MEMBER". Angka ini bukan teks statis, melainkan hasil hitungan array PHP `count($members)`, memberikan informasi jumlah pelanggan secara instan.
* **Card-based Layout:**
Alih-alih tabel kaku biasa, data ditampilkan dalam bentuk *Horizontal Card* dengan efek *Glassmorphism* (transparan), memberikan kesan eksklusif bagi setiap anggota yang terdaftar.

---

## 5. Shopping Module: Catalog & Cart Logic

Fitur utama bagi User untuk memilih barang dan mengelola belanjaan.

<img width="2048" height="3762" alt="code katalog user" src="https://github.com/user-attachments/assets/decae867-100a-4efe-978d-c7eb76fa0505" />


<img width="960" height="471" alt="katalog user" src="https://github.com/user-attachments/assets/f70cd3fc-e22f-4457-bcb2-6b73e1c9ba6f" />

*(Gambar: Katalog Produk User)*

### A. Catalog (`views/user/catalog.php`)
Berikut adalah analisis teknis mendalam untuk file **`views/user/catalog.php`**, berdasarkan kode program (`code katalog user.jpg`) dan tampilan antarmuka (`katalog user.jpg`).

Halaman ini menerapkan konsep **Dynamic Content Rendering**, di mana tampilan grid produk berubah sesuai dengan database dan filter pencarian pengguna.

#### 1. Backend Logic: Search Algorithm & Data Fetching

Fitur pencarian di halaman ini tidak menggunakan JavaScript klien semata, melainkan manipulasi query SQL yang aman.

* **Query String Parameter Handling:**
```php
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $where = "WHERE name LIKE '%$search%'";
}

```


Sistem menangkap input dari URL parameter `?search=...`. Logika kondisional ini menyuntikkan klausa `WHERE ... LIKE` ke dalam query utama.
* *Hasil:* Jika user mengetik "Hoodie", query SQL otomatis berubah hanya mengambil produk yang namanya mengandung kata "Hoodie".


* **Data Retrieval:**
Query dasar `SELECT * FROM products $where ORDER BY id DESC` memastikan produk terbaru selalu muncul di urutan pertama (LIFO - *Last In First Out*).

#### 2. Conditional State Rendering (Logika Stok)

Salah satu fitur proteksi transaksi adalah validasi stok langsung di tampilan (*View Layer*).

* **Stock Availability Check:**
Sistem mengecek kolom `stock` sebelum merender tombol beli.
```php
<?php if ($p['stock'] > 0): ?>
    <a href="...">Masukkan Keranjang</a>
<?php else: ?>
    <button disabled>Stok Habis</button>
<?php endif; ?>

```


* **Logic:** Jika stok tersedia, tombol "MASUKKAN KERANJANG" berwarna emas aktif.
* **Fallback:** Jika stok 0, tombol berubah menjadi abu-abu (`btn-secondary`) dan dinonaktifkan (`disabled`), mencegah user membeli barang kosong.



#### 3. Frontend Engineering: Grid System & Card Components

Tampilan visual menggunakan **Bootstrap Grid System** yang responsif.

* **Responsive Layout:**
Class `row-cols-1 row-cols-md-3 row-cols-lg-4` mengatur tata letak otomatis: 1 kolom di HP, 3 di Tablet, dan 4 di Laptop.
* **Visual Formatting:**
* **Price Formatting:** Menggunakan `number_format($p['price'], 0, ',', '.')` untuk mengubah angka mentah database (contoh: `300`) menjadi format mata uang yang mudah dibaca (`Rp 300` atau `Rp 300.000`).
* **Text Truncation:** Class `text-truncate` digunakan pada judul produk agar layout kartu tetap rapi meskipun nama produknya sangat panjang.



#### 4. User Feedback Mechanism

Sistem memberikan umpan balik visual setelah transaksi.

* **Success Alert:**
```php
if (isset($_GET['msg']) && $_GET['msg'] == 'success_transaction')

```


Jika URL mengandung parameter sukses (biasanya setelah *redirect* dari checkout), sistem menampilkan kotak pesan hijau (*Alert Success*) "Pesanan Berhasil!", memberikan kepastian kepada user bahwa transaksi mereka telah diproses.

---

### B. Shopping Cart (`views/user/cart.php`)

<img width="2048" height="6444" alt="code keranjang user" src="https://github.com/user-attachments/assets/44c9e3e0-71f0-495f-aec1-2ec9cafebfd7" />



<img width="960" height="455" alt="keranjang" src="https://github.com/user-attachments/assets/14ea7ecc-1528-4363-be9a-03be4157f49d" />

*(Gambar: Halaman Keranjang Belanja)*


Halaman ini berfungsi sebagai etalase digital. Fitur utamanya adalah kemampuan menampilkan produk secara dinamis dan mekanisme pencarian data.

#### 1. Backend Logic: Search Algorithm & Filtering

Sistem pencarian tidak menggunakan JavaScript sederhana, melainkan manipulasi **SQL Query** di sisi server.

* **Conditional Query Construction:**
```php
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $where = "WHERE name LIKE '%$search%'";
}

```


Script menangkap input pencarian dari URL. Jika ada kata kunci (misal: "Hoodie"), query SQL disuntikkan klausa `WHERE name LIKE '%...%'`. Ini memungkinkan pencarian parsial (mengetik "Hood" tetap menemukan "Hoodie").

#### 2. Stock Validation (Logic Guard)

Sistem memiliki proteksi agar user tidak membeli barang kosong.

* **Conditional Button Rendering:**
```php
<?php if ($p['stock'] > 0): ?>
    <a href="...">Masukkan Keranjang</a>
<?php else: ?>
    <button disabled>Stok Habis</button>
<?php endif; ?>

```


Logika `if-else` ini bekerja di level tampilan.
* **Stok Ada:** Tombol "BELI" muncul dan aktif.
* **Stok 0:** Tombol berubah menjadi abu-abu (`btn-secondary`) dan atribut `disabled` aktif, memblokir interaksi klik.



#### 3. Transaction Feedback Loop

Fitur UX yang memberikan kepastian kepada pengguna.

* **Success Alert:**
```php
if (isset($_GET['msg']) && $_GET['msg'] == 'success_transaction')

```


Setelah checkout berhasil, sistem melempar user kembali ke katalog dengan parameter `?msg=success_transaction`. Kode ini menangkap parameter tersebut dan menampilkan notifikasi hijau "Pesanan Berhasil!".

---

### 📄 Analisis Teknis: `views/user/cart.php` (Cart & Checkout System)

Halaman ini adalah modul paling kompleks dari sisi User karena menggabungkan **Session Handling**, **Database Fetching**, dan **JavaScript Interactivity**.

#### 1. Session-Based Storage (Keranjang Belanja)

Keranjang belanja tidak disimpan di database (agar tidak membebani server), melainkan di **PHP Session Array**.

* **Efficient Data Retrieval (SQL `IN` Clause):**
```php
$ids = implode(',', array_keys($_SESSION['cart']));
$stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");

```


Alih-alih melakukan query berulang-ulang untuk setiap barang, sistem mengambil ID semua barang di keranjang, lalu mengambil detailnya sekaligus menggunakan perintah SQL `WHERE id IN (...)`. Ini sangat efisien untuk performa website.

#### 2. Real-time Calculation Logic

Di dalam tabel keranjang, sistem melakukan perhitungan matematika otomatis.

* **Looping & Aggregation:**
```php
$subtotal = $row['price'] * $qty;
$grandtotal += $subtotal;

```


Sistem mengalikan Harga x Jumlah untuk setiap baris, lalu mengakumulasikannya ke variabel `$grandtotal` untuk mendapatkan total tagihan akhir yang akurat.

#### 3. Checkout Form & Payment Logic (JavaScript)

Formulir checkout di sebelah kanan (`col-md-4`) dilengkapi dengan interaksi dinamis.

* **Dynamic Payment Info (`showBankInfo`):**
Di bagian bawah file, terdapat script JavaScript:
```javascript
function showBankInfo() {
    const method = document.getElementById('paymentMethod').value;
    // if method == 'dana' -> show DANA number
    // if method == 'bca' -> show BCA number
}

```


Fungsi ini mendeteksi perubahan pada *Dropdown* pembayaran. Jika user memilih "DANA", nomor DANA admin otomatis muncul. Jika memilih "Bank Transfer", nomor rekening muncul. Ini meningkatkan UX karena user tidak perlu menebak-nebak nomor tujuan transfer.
* **Data Submission:**
Form mengirimkan data kritis (`total_price`, `fullname`, `address`, `payment_method`) ke `TransController.php` untuk diproses masuk ke database transaksi dan mengurangi stok barang.

Tahap akhir transaksi: Pembayaran oleh user dan pelaporan untuk admin.

### A. Checkout Logic (User)
* **Data Persistence:**
    * Saat user menekan tombol "Bayar", data dipindahkan dari *Session* (Temporary) ke tabel **`transactions`** di Database (Permanent).
    * Query: `INSERT INTO transactions (user_id, total_price, ...)`.
* **State Reset:** Setelah data berhasil disimpan, keranjang dikosongkan (`unset($_SESSION['cart'])`) agar siap untuk transaksi berikutnya.

### B. Financial Reporting Module (`views/admin/report.php`)

<img width="2048" height="14824" alt="code laporan" src="https://github.com/user-attachments/assets/bf6e1b61-b523-49a0-8545-0db26296f13d" />


<img width="959" height="459" alt="admin laporan" src="https://github.com/user-attachments/assets/820adaf0-b457-486a-bae9-3708bdd3345e" />

*(Gambar:inancial Reporting Module *

Halaman ini menggabungkan manipulasi data relasional (*Relational Database Management*) dengan visualisasi grafis tingkat lanjut menggunakan HTML5 Canvas.

#### 1. Backend Logic: Relational Data Fetching (SQL JOIN)

Fitur inti dari halaman ini adalah menampilkan siapa membeli apa. Karena tabel `transactions` hanya menyimpan `user_id` (angka), kita memerlukan teknik **SQL JOIN**.

* **Complex Query Construction:**
```sql
SELECT t.*, u.fullname 
FROM transactions t 
JOIN users u ON t.user_id = u.id 
ORDER BY t.created_at DESC

```


Query ini menggabungkan dua tabel secara *real-time*.
* *Tanpa Join:* Admin hanya melihat "Pembeli: ID 5".
* *Dengan Join:* Admin melihat "Pembeli: Ahmad" (Data diambil dari tabel users berdasarkan ID yang cocok).



#### 2. Visual Engineering: "The Digital Rain" Animation

Latar belakang hujan kode berwarna emas bukanlah video (yang berat), melainkan animasi prosedural (*Procedural Animation*) yang dirender oleh browser.

* **HTML5 Canvas API:**
Di bagian bawah kode, terdapat tag `<canvas id="matrix">`. JavaScript mengambil alih elemen ini untuk menggambar grafis 2D.
* **Matrix Logic:**
* **Character Set:** Script mendefinisikan string karakter (katakana, latin, angka) yang akan dijatuhkan.
* **Falling Column Algorithm:** Script membagi layar menjadi kolom-kolom selebar font (misal 14px).
* **Frame Loop:** Fungsi `draw()` dipanggil berulang kali (setiap 35ms) untuk menghapus layar sedikit (efek *trail*) dan menggambar karakter baru di posisi Y yang lebih rendah. Ini menciptakan ilusi hujan kode tanpa henti.



#### 3. Data Presentation & Formatting

Tabel laporan dirancang agar mudah dibaca (*human-readable*).

* **Currency Formatting:**
Menggunakan fungsi PHP `number_format($row['total_price'], 0, ',', '.')` untuk mengubah angka mentah `75000` menjadi format mata uang standar Indonesia `Rp 75.000`.
* **Status Badges:**
Menggunakan logika kondisional untuk memberikan warna pada status.
```html
<span class="badge bg-success">BERHASIL</span>

```


Lencana hijau memberikan indikator visual cepat bahwa transaksi aman dan selesai.

#### 4. Utility Feature: Print Capabilities

Tombol kuning "CETAK LAPORAN" di pojok kanan atas bukan sekadar hiasan.

* **Window Print Trigger:**
Tombol tersebut terhubung dengan fungsi JavaScript `window.print()`.
* **CSS Media Query (`@media print`):**
Di dalam CSS (biasanya di `<style>`), terdapat aturan khusus agar saat dicetak, elemen yang tidak perlu (seperti Navbar, Sidebar, dan background hitam Matrix) disembunyikan. Yang tertulis di kertas nanti hanya **Tabel Putih Bersih** demi menghemat tinta printer.
---

## 7. User Profile Management

Personalisasi akun pengguna (`views/user/profile.php`).

<img width="2048" height="14900" alt="code profil" src="https://github.com/user-attachments/assets/3ac5599b-ce02-4041-9ee6-f50e8ee79422" />


<img width="959" height="471" alt="profileee" src="https://github.com/user-attachments/assets/bc85b27f-126f-4841-a360-14cd3721c091" />

*(Gambar: Halaman Profil)*

Halaman ini menerapkan **Two-Way Data Binding** sederhana: mengambil data dari database untuk ditampilkan di formulir, dan mengirimkan perubahan kembali ke server dengan validasi keamanan.

#### 1. Backend Logic: Data Retrieval & Session Binding

Saat halaman dimuat, sistem tidak membiarkan formulir kosong.

* **Data Pre-filling:**
```php
$id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

```


Sistem mengambil data terbaru berdasarkan ID pengguna yang sedang login.
* *Fungsi:* Input formulir (Nama, HP, Alamat) otomatis terisi (`value="<?= $user['fullname'] ?>"`) sehingga pengguna hanya perlu mengedit bagian yang ingin diubah saja.



#### 2. Security Logic: Conditional Password Update

Fitur keamanan cerdas diterapkan pada kolom password.

* **Smart Update Algorithm:**
Sistem mendeteksi apakah pengguna ingin mengganti password atau tidak.
```php
if (!empty($_POST['password'])) {
    // Jika kolom diisi: Hash password baru & Update semua kolom
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "UPDATE users SET ... password=:p ...";
} else {
    // Jika kosong: Update biodata saja, password lama tetap aman
    $sql = "UPDATE users SET ..."; // Tanpa kolom password
}

```


Logika ini mencegah ketidaksengajaan pengguna menimpa password mereka dengan *string* kosong.

#### 3. Image Handling: Upload & Session Sync

Fitur ganti foto profil melibatkan sinkronisasi antara File Server, Database, dan Session.

* **File Upload Processing:**
Jika ada file baru yang diunggah, script memindahkannya ke `assets/images/users/` dan menyimpan nama filenya ke database.
* **Session Synchronization:**
```php
$_SESSION['fullname'] = $fullname;
$_SESSION['photo'] = $new_photo;

```


Setelah update database berhasil, script langsung memperbarui variabel `$_SESSION`.
* *Hasil:* Nama dan foto di pojok kanan atas Navbar langsung berubah seketika tanpa perlu Logout-Login ulang.



#### 4. Frontend Experience: Real-time Preview

Di bagian bawah kode (JavaScript), terdapat fitur interaktif untuk meningkatkan UX.

* **FileReader API (Client-side Preview):**
```javascript
const oFReader = new FileReader();
oFReader.readAsDataURL(image.files[0]);
oFReader.onload = function(oFREvent) { ...src = oFREvent.target.result; }

```


Saat pengguna memilih file foto dari komputer, script ini membacanya dan langsung mengganti tampilan gambar profil di layar secara instan (*Instant Feedback*), sebelum tombol "Simpan" ditekan.

---

<div align="center">
  <h3>🔒 Penutup</h3>
  <p>Royal Commerce adalah bukti implementasi teknik pemrograman web modern yang menggabungkan logika backend yang kuat, keamanan data, dan estetika frontend kelas atas.</p>
  <p><strong>Copyright © 2026 Rizky Maulana. All Rights Reserved.</strong></p>
</div>
