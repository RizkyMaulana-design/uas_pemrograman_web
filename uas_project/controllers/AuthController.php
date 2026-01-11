<?php
// Aktifkan error reporting sementara
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// PERBAIKAN: Gunakan jalur absolut agar file Database pasti ketemu
$db_path = __DIR__ . '/../config/Database.php';

if (file_exists($db_path)) {
    require_once $db_path;
} else {
    die("<h1>ERROR: File Database tidak ditemukan!</h1><p>Sistem mencari di: $db_path</p>");
}

class AuthController
{
    private $conn;

    public function __construct()
    {
        // Cek koneksi database
        try {
            $db = new Database();
            $this->conn = $db->getConnection();
        } catch (Exception $e) {
            die("<h1>Gagal Koneksi Database</h1>" . $e->getMessage());
        }
    }

    public function register($fullname, $username, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (fullname, username, password, role, photo) VALUES (:f, :u, :p, 'user', 'default.jpg')");
            $stmt->execute([
                ':f' => $fullname,
                ':u' => $username,
                ':p' => $hash
            ]);
            // Jika sukses, lempar ke login
            header("Location: ../index.php?url=login&status=registered");
            exit();
        } catch (PDOException $e) {
            // Jika username kembar, lempar balik ke register
            if ($e->getCode() == 23000) {
                header("Location: ../index.php?url=register&error=exists");
            } else {
                echo "<h1>Error Database:</h1>" . $e->getMessage();
                die(); // Stop script untuk melihat error
            }
        }
    }

    public function login($username, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :u");
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['photo'] = $user['photo'];

            // --- PERUBAHAN UNTUK SPLASH SCREEN ---
            // Kita arahkan ke file splash.php dulu untuk efek loading mewah
            header("Location: ../views/splash.php");
            // -------------------------------------

            exit();
        } else {
            // Jika login gagal
            header("Location: ../index.php?url=login&error=1");
            exit();
        }
    }
}

// EKSEKUSI
$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'register') {
        $auth->register($_POST['fullname'], $_POST['username'], $_POST['password']);
    } elseif ($action == 'login') {
        $auth->login($_POST['username'], $_POST['password']);
    }
} else {
    // JIKA DIAKSES LANGSUNG (GET), TAMPILKAN PESAN INI BUKAN PUTIH POLOS
    echo "<h1 style='color:red; text-align:center; margin-top:20%;'>🚫 AKSES DITOLAK</h1>";
    echo "<p style='text-align:center;'>Anda tidak boleh membuka file controller ini secara langsung.</p>";
    echo "<p style='text-align:center;'><a href='../index.php?url=register'>Kembali ke Halaman Register</a></p>";
}