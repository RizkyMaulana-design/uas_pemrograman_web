<?php
session_start();

// DEFINISI BASE_URL (Sesuaikan nama folder jika berbeda)
define('BASE_URL', 'http://localhost/uas_project');

require_once 'config/Database.php';

// Routing Sederhana
$url = isset($_GET['url']) ? $_GET['url'] : 'login';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$params = explode('/', $url);
$page = $params[0];

// --- LOGIKA 1: JIKA SUDAH LOGIN, JANGAN IZINKAN AKSES HALAMAN LOGIN/REGISTER ---
if (isset($_SESSION['user_id']) && ($page == 'login' || $page == 'register')) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: " . BASE_URL . "/admin_dashboard");
    } else {
        header("Location: " . BASE_URL . "/catalog");
    }
    exit;
}

// --- LOGIKA 2: KEAMANAN (JIKA BELUM LOGIN, LEMPAR KE LOGIN) ---
// Kecuali halaman login dan register
if (!isset($_SESSION['user_id']) && $page != 'login' && $page != 'register') {
    header("Location: " . BASE_URL . "/login");
    exit;
}

// Switch Page Modular
switch ($page) {
    // Auth Routes
    case 'login':
        require_once 'views/login.php';
        break;
    case 'register':
        require_once 'views/register.php';
        break;

    // Rute Admin
    case 'admin_dashboard':
        require_once 'views/admin/dashboard.php';
        break;
    case 'admin_products':
        require_once 'views/admin/products.php';
        break;
    case 'admin_report':
        require_once 'views/admin/report.php';
        break;
    case 'admin_members':
        require_once 'views/admin/members.php';
        break;

    // Rute User & Umum
    case 'catalog':
        require_once 'views/user/catalog.php';
        break;
    case 'cart':
        require_once 'views/user/cart.php';
        break;
    case 'profile':
        require_once 'views/user/profile.php';
        break;
    case 'about':
        require_once 'views/about.php';
        break;

    // Logout
    case 'logout':
        session_destroy();
        header("Location: " . BASE_URL . "/login");
        exit;
        break;

    // Default (Halaman Tidak Ditemukan)
    default:
        if (isset($_SESSION['user_id'])) {
            // Jika sudah login tapi salah alamat, kembalikan sesuai role
            if ($_SESSION['role'] == 'admin') {
                header("Location: " . BASE_URL . "/admin_dashboard");
            } else {
                header("Location: " . BASE_URL . "/catalog");
            }
        } else {
            // Jika belum login dan salah alamat, suruh login
            require_once 'views/login.php';
        }
        break;
}