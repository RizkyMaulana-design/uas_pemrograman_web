<?php
session_start();

// Cek jika tidak ada session login, tendang ke login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php?url=login");
    exit();
}

// Tentukan Tujuan Berdasarkan Role
$role = $_SESSION['role'];
$targetUrl = ($role == 'admin') ? '../index.php?url=admin_dashboard' : '../index.php?url=catalog';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memuat Sistem...</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            height: 100vh;
            background: #000;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', sans-serif;
            color: #FFD700;
        }

        /* Latar Belakang Hyperspace */
        .hyperspace {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, #1b2735 0%, #090a0f 100%);
            z-index: -1;
        }

        .star {
            position: absolute;
            background: white;
            width: 2px;
            height: 2px;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            animation: warp 3s infinite ease-in;
            opacity: 0;
        }

        @keyframes warp {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0;
            }

            20% {
                opacity: 1;
            }

            100% {
                transform: translate(-50%, -50%) scale(40);
                opacity: 0;
            }
        }

        /* Logo / Teks Tengah */
        .logo-container {
            text-align: center;
            z-index: 10;
            animation: pulse 2s infinite;
        }

        h1 {
            font-size: 3rem;
            margin: 0;
            letter-spacing: 5px;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
            background: linear-gradient(to bottom, #FFD700, #FDB931);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 3px;
            margin-top: 10px;
            font-size: 1.2rem;
        }

        /* Loading Bar Mewah */
        .progress-container {
            width: 300px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            margin-top: 30px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background: #FFD700;
            box-shadow: 0 0 15px #FFD700;
            animation: load 2.5s ease-in-out forwards;
            /* Durasi animasi loading */
        }

        @keyframes load {
            0% {
                width: 0%;
            }

            50% {
                width: 60%;
            }

            80% {
                width: 85%;
            }

            100% {
                width: 100%;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.5));
            }

            50% {
                transform: scale(1.02);
                filter: drop-shadow(0 0 25px rgba(255, 215, 0, 0.8));
            }
        }
    </style>
</head>

<body>

    <div class="hyperspace" id="space">
    </div>

    <div class="logo-container">
        <h1>ROYAL COMMERCE</h1>
        <p>AUTHENTICATING...</p>

        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>
    </div>

    <script>
        // 1. Logic Redirect Otomatis
        setTimeout(function () {
            window.location.href = "<?= $targetUrl ?>";
        }, 2800); // Redirect setelah 2.8 detik (sedikit setelah loading bar penuh)

        // 2. Efek Bintang (Hyperspace)
        const space = document.getElementById('space');
        const starCount = 100;

        for (let i = 0; i < starCount; i++) {
            let star = document.createElement('div');
            star.className = 'star';

            // Posisi acak arah ledakan
            let x = (Math.random() - 0.5) * window.innerWidth * 2;
            let y = (Math.random() - 0.5) * window.innerHeight * 2;

            star.style.setProperty('--x', x + 'px');
            star.style.setProperty('--y', y + 'px');

            // Durasi acak agar tidak serentak
            star.style.animationDuration = (Math.random() * 2 + 1) + 's';
            star.style.transform = `translate(${x}px, ${y}px)`; // Arah gerak css custom

            // Kustom keyframe dinamis untuk tiap bintang (optional simple approach)
            // Agar simpel, kita pakai CSS animation warp yang sudah ada, 
            // tapi kita sebar posisi awalnya sedikit.
            let angle = Math.random() * 360;
            let distance = Math.random() * 500;
            star.style.transform = `rotate(${angle}deg) translate(${distance}px)`;

            space.appendChild(star);
        }
    </script>
</body>

</html>