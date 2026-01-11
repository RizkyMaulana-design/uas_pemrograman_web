<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Login - Luxury Experience</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* --- 1. SETTING BACKGROUND HIDUP (4D) --- */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(-45deg, #0f0c29, #302b63, #24243e, #1a1a2e);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            overflow: hidden;
            /* Agar partikel tidak scroll */
            position: relative;
        }

        /* Animasi Pergerakan Warna Latar */
        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* --- 2. PARTIKEL MELAYANG (EFEK KEDALAMAN/DIMENSI) --- */
        .circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            /* Di belakang form */
        }

        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(212, 175, 55, 0.2);
            /* Warna Emas Transparan */
            animation: floatUp 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
            /* Bulat */
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
            /* Efek Glowing */
        }

        /* Konfigurasi Ukuran & Posisi Partikel */
        .circles li:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }

        .circles li:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }

        .circles li:nth-child(3) {
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
        }

        .circles li:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }

        .circles li:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }

        .circles li:nth-child(6) {
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
        }

        .circles li:nth-child(7) {
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
        }

        .circles li:nth-child(8) {
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
        }

        .circles li:nth-child(9) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
        }

        .circles li:nth-child(10) {
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
        }

        @keyframes floatUp {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }

            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }

        /* --- 3. KARTU LOGIN (GLASSMORPHISM) --- */
        .glass-card {
            position: relative;
            z-index: 10;
            /* Di depan partikel */
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            border-left: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            width: 450px;
            padding: 40px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), 0 0 20px rgba(212, 175, 55, 0.2);
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        /* Kilauan Cahaya pada Kartu */
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: 0.5s;
        }

        .glass-card:hover::before {
            left: 100%;
        }

        /* --- 4. TYPOGRAPHY & INPUTS --- */
        .text-gold {
            background: linear-gradient(45deg, #FFD700, #FDB931, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(255, 215, 0, 0.3);
            font-weight: 800;
            letter-spacing: 2px;
        }

        .form-control {
            background: rgba(0, 0, 0, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff !important;
            border-radius: 10px;
            padding: 12px;
            transition: 0.3s;
        }

        .form-control:focus {
            background: rgba(0, 0, 0, 0.5) !important;
            border-color: #d4af37;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .input-group-text {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #d4af37;
            border-right: none;
        }

        /* --- 5. TOMBOL MEWAH --- */
        .btn-luxury {
            background: linear-gradient(90deg, #d4af37, #f7df85, #d4af37);
            background-size: 200%;
            border: none;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 50px;
            transition: 0.4s;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .btn-luxury:hover {
            background-position: right center;
            transform: scale(1.02);
            box-shadow: 0 5px 25px rgba(212, 175, 55, 0.6);
        }

        /* Link Custom */
        a {
            text-decoration: none;
            transition: 0.3s;
        }

        a:hover {
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }
    </style>
</head>

<body>

    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>

    <div class="glass-card">
        <div class="text-center mb-5">
            <h1 class="text-gold mb-2">KYYN LOGIN</h1>
            <p class="text-white-50" style="font-size: 0.9rem;">Sistem Login Role Admin & User</p>
            <div style="height: 2px; width: 50px; background: #d4af37; margin: 0 auto;"></div>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-white small text-center mb-4 fade show"
                role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Username atau Password salah!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'registered'): ?>
            <div class="alert alert-success border-0 bg-success bg-opacity-25 text-white small text-center mb-4 fade show"
                role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Registrasi Berhasil! Silakan Login.
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/controllers/AuthController.php" method="POST">
            <input type="hidden" name="action" value="login">

            <div class="mb-4">
                <label class="form-label text-white small fw-bold ms-1">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required
                        autocomplete="off">
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label text-white small fw-bold ms-1">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                        required>
                </div>
            </div>

            <button type="submit" class="btn btn-luxury w-100 py-3 mb-4">
                MASUK SEKARANG <i class="bi bi-arrow-right-short"></i>
            </button>

            <div class="text-center">
                <p class="small text-white-50 mb-0">Belum punya akun?
                    <a href="<?= BASE_URL ?>/register" class="text-warning fw-bold ms-1">Daftar Member Baru</a>
                </p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>