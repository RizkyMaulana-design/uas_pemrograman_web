<div align="center">

  <img src="https://img.shields.io/badge/STATUS-COMPLETED-gold?style=for-the-badge&logo=appveyor" />
  <img src="https://img.shields.io/badge/VERSION-1.0.0-black?style=for-the-badge" />
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" />

  <br />
  <br />

  <h1>👑 ROYAL COMMERCE</h1>
  <p><strong>A Masterpiece of E-Commerce Web Engineering</strong></p>
  
  <p>
    <em>"Experience the future of transaction management with a touch of luxury and 4-Dimensional aesthetics."</em>
  </p>

  <br />

  <table align="center">
    <tr>
      <td align="center" width="150"><strong>NAMA</strong></td>
      <td align="center" width="150"><strong>NIM</strong></td>
      <td align="center" width="150"><strong>KELAS</strong></td>
      <td align="center" width="200"><strong>MATA KULIAH</strong></td>
    </tr>
    <tr>
      <td align="center">Rizky Maulana</td>
      <td align="center">312410430</td>
      <td align="center">TI 24 A 3</td>
      <td align="center">Pemrograman Web</td>
    </tr>
  </table>

  <br />
</div>

---

## 💎 Project Overview (Gambaran Proyek)

**Royal Commerce** bukan sekadar aplikasi E-Commerce biasa. Ini adalah sebuah sistem manajemen transaksi dan katalog produk yang dibangun dengan pendekatan **User Experience (UX) Imersif**. 

Project ini menggabungkan logika pemrograman backend yang kuat (PHP Native & PDO) dengan antarmuka frontend yang revolusioner menggunakan konsep **"Royal 4D UI"**. Setiap halaman dirancang memiliki "jiwa" dan filosofi tersendiri, mulai dari efek *Hyperspace* saat login hingga hujan data keuangan (*Digital Rain*) pada laporan transaksi.

Tujuan utama dari kode ini adalah mendemonstrasikan kemampuan **CRUD (Create, Read, Update, Delete)** yang dibalut dalam keamanan sistem (Authentication) dan keindahan visual.

---

## 🚀 Key Features & Architectural Logic

Berikut adalah penjelasan teknis dari setiap modul yang telah dibangun:

### 1. 🔐 Secure Authentication System
* **Fitur:** Login & Register Multi-role (Admin & User).
* **Logika Coding:** Menggunakan `password_hash()` dan `password_verify()` untuk keamanan tingkat tinggi. Session management digunakan untuk memproteksi halaman agar tidak bisa diakses tanpa login (Blocking Access).
* **Visual:** Dilengkapi dengan **Splash Screen "Hyperspace"**—animasi transisi luar angkasa sebelum masuk ke dashboard.

### 2. 🌌 Immersive Dashboard Experience
* **Fitur:** Papan kendali utama yang membedakan hak akses Admin dan User.
* **Logika Coding:** Routing dinamis pada `index.php` yang mengarahkan user berdasarkan `role` database.
* **Visual:**
    * **Admin:** Tema *Infinite Space* (Luar Angkasa).
    * **User:** Katalog produk dengan efek *Glassmorphism*.

### 3. 📦 Advanced Product Management (CRUD)
* **Fitur:** Menambah, Mengedit, dan Menghapus barang tanpa reload halaman (Ajax-like experience via Modal).
* **Logika Coding:**
    * Menggunakan satu file view (`products.php`) yang cerdas untuk menangani operasi Add dan Edit sekaligus.
    * Sistem upload gambar otomatis ke folder `assets/images/products/` dengan validasi ekstensi dan *unique naming*.
    * Manipulasi Database menggunakan **PDO Prepared Statements** untuk mencegah SQL Injection.

### 4. 👤 Dynamic User Profile
* **Fitur:** Manajemen identitas pengguna, update alamat, ganti password, dan upload foto profil.
* **Logika Coding:** Mengambil data spesifik berdasarkan Session ID. Fitur upload foto dilengkapi dengan *Real-time Preview* menggunakan JavaScript sebelum data dikirim ke server.
* **Visual:** Tema **"Royal Aurora"**—latar belakang bola cahaya emas dan ungu yang bergerak lembut (Breathing Animation).

### 5. 📊 Transaction Reports
* **Fitur:** Laporan keuangan otomatis yang menarik data penjualan dari database.
* **Visual:** Tema **"Digital Finance Rain"**—Efek hujan matriks simbol mata uang (Rp, $, %) berwarna emas yang mensimulasikan aliran data keuntungan (Parallax Effect).

---

## 🎨 The "Royal 4D" Design Philosophy

Proyek ini mengimplementasikan konsep desain 4 Dimensi yang unik di setiap halamannya:

| Halaman | Konsep Visual | Filosofi |
| :--- | :--- | :--- |
| **Login/Splash** | *Hyperspace Jump* | Kecepatan & Transisi menuju sistem masa depan. |
| **Dashboard** | *Infinite Cosmos* | Ruang tanpa batas untuk mengelola bisnis. |
| **Produk** | *Neural Network* | Konektivitas data yang saling terhubung. |
| **Member** | *Gyroscope Rings* | Komunitas yang presisi dan eksklusif. |
| **Profil** | *Royal Aurora* | Aura personal yang tenang dan mewah. |
| **Laporan** | *Digital Gold Rain* | Kemakmuran dan aliran data yang deras. |

---

## 🛠️ Technology Stack

* **Backend:** PHP 8 (Native), MySQL (MariaDB).
* **Frontend:** HTML5, CSS3 (Keyframes Animation), Bootstrap 5.
* **Scripting:** JavaScript (DOM Manipulation, Modal Logic).
* **Database Driver:** PDO (PHP Data Objects).
* **Server:** Apache (XAMPP).

---

## 💻 Installation Guide

Ikuti langkah ini untuk menjalankan Royal Commerce di mesin lokal Anda:

1.  **Clone / Download** repository ini.
2.  Pindahkan folder ke `htdocs` (misal: `C:\xampp\htdocs\uas_project`).
3.  Buat Database baru di phpMyAdmin dengan nama **`uas_web`** (atau sesuaikan dengan config).
4.  Import file `database.sql` (jika ada) atau buat tabel sesuai struktur MVC.
5.  Atur konfigurasi database di `config/Database.php`.
6.  Buka browser dan akses:
    ```
    http://localhost/uas_project/login
    ```
7.  **Akun Demo:**
    * **Admin:** Username: `admin`, Password: `admin123`
    * **User:** Silakan register akun baru.

---

<div align="center">
  <br />
  <p><em>Dibuat dengan dedikasi tinggi untuk memenuhi Tugas Akhir Mata Kuliah Pemrograman Web.</em></p>
  <p>Copyright © 2026 <strong>Rizky Maulana</strong>. All Rights Reserved.</p>
  <img src="https://img.shields.io/badge/Built%20With-Passion-red?style=for-the-badge" />
</div>
