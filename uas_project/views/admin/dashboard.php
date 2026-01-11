<?php
// Koneksi Database
$db = new Database();
$conn = $db->getConnection();

// Data Statistik
$total_produk = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_transaksi = $conn->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
$total_member = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$omset = $conn->query("SELECT SUM(total_price) FROM transactions")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Center - Royal Commerce</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Orbitron:wght@400;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* ================= 1. BACKGROUND LUAR ANGKASA (4D) ================= */
        body {
            font-family: 'Poppins', sans-serif;
            /* Latar belakang Deep Space */
            background: radial-gradient(ellipse at bottom, #090A0F 0%, #1B2735 100%);
            color: white;
            min-height: 100vh;
            overflow-x: hidden;
            margin: 0;
            position: relative;
        }

        /* Container untuk Bintang & Meteor (Fixed di belakang) */
        #space-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            /* Wajib di belakang konten */
            pointer-events: none;
            overflow: hidden;
        }

        /* Animasi Bintang (Starfield) */
        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
        }

        /* Ribuan titik bintang menggunakan shadow */
        #stars-sm {
            width: 1px;
            height: 1px;
            background: transparent;
            box-shadow: 1745px 434px #FFF, 671px 970px #FFF, 1897px 578px #FFF, 1389px 246px #FFF, 1623px 821px #FFF, 1942px 102px #FFF, 732px 419px #FFF, 1589px 1822px #FFF, 1084px 665px #FFF, 1254px 1300px #FFF, 1822px 1254px #FFF, 109px 1877px #FFF, 466px 1035px #FFF, 1543px 1765px #FFF, 908px 234px #FFF, 820px 332px #FFF, 2000px 1500px #FFF, 150px 1200px #FFF, 400px 500px #FFF, 1200px 800px #FFF;
            animation: twinkle 4s infinite;
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0.9;
            }

            50% {
                opacity: 0.3;
            }
        }

        /* Animasi Meteor (Shooting Stars) */
        .meteor {
            position: absolute;
            top: 50%;
            left: 50%;
            height: 2px;
            background: linear-gradient(-45deg, #fff, rgba(0, 0, 255, 0));
            filter: drop-shadow(0 0 6px #fff);
            animation: tail 3000ms ease-in-out infinite, shooting 3000ms ease-in-out infinite;
            opacity: 0;
        }

        .meteor::before {
            content: '';
            position: absolute;
            top: calc(50% - 1px);
            right: 0;
            height: 2px;
            background: linear-gradient(-45deg, rgba(0, 0, 255, 0), #fff, rgba(0, 0, 255, 0));
            transform: translateX(50%) rotateZ(45deg);
            border-radius: 100%;
            animation: shining 3000ms ease-in-out infinite;
        }

        /* Posisi Meteor Acak */
        .meteor:nth-child(1) {
            top: 10%;
            left: 20%;
            animation-delay: 0s;
        }

        .meteor:nth-child(2) {
            top: 30%;
            left: 80%;
            animation-delay: 2s;
        }

        .meteor:nth-child(3) {
            top: 60%;
            left: 10%;
            animation-delay: 4s;
        }

        .meteor:nth-child(4) {
            top: 80%;
            left: 70%;
            animation-delay: 6s;
        }

        @keyframes tail {
            0% {
                width: 0;
            }

            30% {
                width: 100px;
            }

            100% {
                width: 0;
            }
        }

        @keyframes shooting {
            0% {
                transform: translateX(0);
                opacity: 1;
            }

            100% {
                transform: translateX(1000px) translateY(1000px);
                opacity: 0;
            }
        }

        /* ================= 2. FITUR CANGGIH (DIPERTAHANKAN) ================= */

        /* Kartu 3D Tilt */
        .tilt-card {
            background: rgba(16, 26, 46, 0.75);
            /* Sedikit transparan agar bintang terlihat */
            border: 1px solid rgba(0, 229, 255, 0.2);
            border-radius: 20px;
            padding: 30px;
            position: relative;
            transform-style: preserve-3d;
            transform: perspective(1000px);
            transition: transform 0.1s;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        }

        /* Efek Hover Glow */
        .tilt-card:hover {
            border-color: rgba(0, 229, 255, 0.8);
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.2);
        }

        /* Jam Hologram */
        .hologram-clock {
            font-family: 'Orbitron', sans-serif;
            font-size: 3rem;
            color: #00e5ff;
            text-shadow: 0 0 20px rgba(0, 229, 255, 0.8);
            letter-spacing: 3px;
        }

        .date-display {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Efek Mengetik */
        .typing-container {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid #FFD700;
            animation: blink-caret 0.75s step-end infinite;
            font-family: 'Courier New', monospace;
            color: #FFD700;
            font-weight: bold;
            font-size: 1.3rem;
        }

        @keyframes blink-caret {

            from,
            to {
                border-color: transparent
            }

            50% {
                border-color: #FFD700;
            }
        }

        /* Tombol Neon */
        .btn-neon {
            background: transparent;
            border: 1px solid #00e5ff;
            color: #00e5ff;
            padding: 8px 25px;
            border-radius: 50px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .btn-neon:hover {
            background: #00e5ff;
            color: black;
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.8);
        }

        /* Font Angka Sci-Fi */
        .stat-value {
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body>
    <div id="space-container">
        <div id="stars-sm" class="stars"></div>
        <span class="meteor"></span>
        <span class="meteor"></span>
        <span class="meteor"></span>
        <span class="meteor"></span>
    </div>

    <?php require_once 'views/layout/navbar.php'; ?>

    <div class="container mt-5 pb-5">

        <div class="row mb-5 text-center">
            <div class="col-md-12">
                <div class="hologram-clock" id="digital-clock">00:00:00</div>
                <div class="date-display" id="date-display">...</div>

                <div class="mt-4">
                    <div class="p-4 d-inline-block"
                        style="background: rgba(0,0,0,0.5); border: 1px solid #FFD700; border-radius: 15px; backdrop-filter: blur(5px);">
                        <p class="mb-0 text-white-50 small mb-2 text-uppercase">System Message:</p>
                        <h3 class="typing-container" id="typing-text"></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-md-4">
                <div class="tilt-card text-center h-100" data-tilt>
                    <i class="bi bi-box-seam text-info" style="font-size: 3rem;"></i>
                    <h1 class="stat-value mt-2 display-4 text-white"><?= $total_produk ?></h1>
                    <p class="text-white-50 text-uppercase small">Unit Barang</p>
                    <a href="<?= BASE_URL ?>/admin_products" class="btn-neon">Kelola Data</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="tilt-card text-center h-100" data-tilt>
                    <i class="bi bi-cpu text-warning" style="font-size: 3rem;"></i>
                    <h1 class="stat-value mt-2 display-4 text-white"><?= $total_transaksi ?></h1>
                    <p class="text-white-50 text-uppercase small">Total Transaksi</p>
                    <a href="<?= BASE_URL ?>/admin_report" class="btn-neon">Cetak Laporan</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="tilt-card text-center h-100" data-tilt>
                    <i class="bi bi-person-bounding-box text-success" style="font-size: 3rem;"></i>
                    <h1 class="stat-value mt-2 display-4 text-white"><?= $total_member ?></h1>
                    <p class="text-white-50 text-uppercase small">User Terdaftar</p>
                    <a href="<?= BASE_URL ?>/admin_members" class="btn-neon">Database User</a>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <div class="tilt-card d-flex justify-content-between align-items-center flex-wrap" data-tilt
                    style="border-color: #FFD700;">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning rounded-circle p-3 me-3 text-dark shadow">
                            <i class="bi bi-wallet2 fs-3 fw-bold"></i>
                        </div>
                        <div>
                            <h5 class="text-white-50 mb-0">Total Pendapatan (Omset)</h5>
                            <h2 class="stat-value text-warning fw-bold mb-0">Rp
                                <?= number_format($omset, 0, ',', '.') ?>
                            </h2>
                        </div>
                    </div>
                    <div class="text-end mt-3 mt-md-0">
                        <span class="badge bg-dark border border-success text-success px-3 py-2 rounded-pill">
                            ● SYSTEM ONLINE
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // 1. JAM DIGITAL
        function updateClock() {
            const now = new Date();
            document.getElementById('digital-clock').textContent = now.toLocaleTimeString('en-US', { hour12: false });
            document.getElementById('date-display').textContent = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }).toUpperCase();
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 2. EFEK MENGETIK
        const textToType = "Selamat Datang, <?= strtoupper($_SESSION['fullname']) ?> .";
        const typingElement = document.getElementById('typing-text');
        let index = 0;
        function typeWriter() {
            if (index < textToType.length) {
                typingElement.innerHTML += textToType.charAt(index);
                index++;
                setTimeout(typeWriter, 100);
            }
        }
        setTimeout(typeWriter, 1000);

        // 3. EFEK 3D TILT (Interaktif Mouse)
        document.querySelectorAll('[data-tilt]').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const rotateX = ((y - rect.height / 2) / rect.height) * -15; // Miring Vertikal
                const rotateY = ((x - rect.width / 2) / rect.width) * 15;   // Miring Horizontal
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>