<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Royal Commerce</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Orbitron:wght@500&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* ================= 1. BACKGROUND 4D: DIGITAL FINANCE RAIN ================= */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #050505;
            color: white;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            position: relative;
        }

        /* Canvas untuk Animasi Matrix Emas */
        #finance-matrix {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(circle at bottom, #1a1a2e 0%, #000000 100%);
        }

        /* ================= 2. DESAIN TABEL GLASS (MEWAH) ================= */

        .glass-container {
            background: rgba(16, 26, 46, 0.8);
            /* Gelap agar kontras dengan teks emas */
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 215, 0, 0.2);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
            position: relative;
            z-index: 10;
        }

        /* Reset Table Bootstrap */
        .table {
            --bs-table-bg: transparent !important;
            --bs-table-color: white !important;
            margin-bottom: 0;
        }

        /* Header Tabel */
        thead th {
            color: #FFD700 !important;
            /* Emas */
            font-family: 'Orbitron', sans-serif;
            /* Font Sci-Fi/Tech */
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid rgba(255, 215, 0, 0.4) !important;
            padding-bottom: 20px !important;
        }

        /* Baris Transaksi */
        tbody tr {
            background: rgba(255, 255, 255, 0.02) !important;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Efek Hover Baris */
        tbody tr:hover {
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.1), rgba(16, 26, 46, 0.9)) !important;
            transform: scale(1.01) translateX(5px);
            border-left: 4px solid #FFD700;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }

        td {
            vertical-align: middle;
            padding: 15px !important;
            border: none !important;
            color: #e0e0e0;
        }

        /* Highlight Data Penting */
        .txt-date {
            font-family: 'Orbitron', sans-serif;
            color: #aaa;
            font-size: 0.9rem;
        }

        .txt-money {
            font-family: 'Orbitron', sans-serif;
            color: #FFD700;
            font-weight: bold;
            font-size: 1rem;
            letter-spacing: 1px;
        }

        .txt-customer {
            font-weight: 600;
            color: white;
            font-size: 1.05rem;
        }

        /* Badge Status & Metode */
        .badge-method {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .badge-success-custom {
            background: rgba(25, 135, 84, 0.2);
            border: 1px solid #198754;
            color: #75b798;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.8rem;
            box-shadow: 0 0 10px rgba(25, 135, 84, 0.2);
        }

        /* Judul & Tombol Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn-print {
            background: linear-gradient(45deg, #FFD700, #FDB931);
            color: black;
            font-weight: bold;
            border: none;
            padding: 10px 30px;
            border-radius: 50px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.9rem;
        }

        .btn-print:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.6);
        }

        /* --- MODE CETAK (PRINT) --- */
        @media print {

            #finance-matrix,
            .page-header button,
            .navbar {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
            }

            .glass-container {
                background: white !important;
                box-shadow: none !important;
                border: 1px solid #000 !important;
                color: black !important;
            }

            table {
                color: black !important;
            }

            th,
            td {
                color: black !important;
                border-bottom: 1px solid #000 !important;
            }

            .txt-money {
                color: black !important;
            }
        }
    </style>
</head>

<body>

    <canvas id="finance-matrix"></canvas>

    <?php require_once 'views/layout/navbar.php'; ?>

    <div class="container mt-5 mb-5">

        <div class="page-header">
            <div>
                <h2 class="fw-bold mb-1"
                    style="color: #FFD700; font-family: 'Orbitron', sans-serif; text-shadow: 0 0 15px rgba(255,215,0,0.3);">
                    <i class="bi bi-file-earmark-bar-graph me-2"></i>LAPORAN TRANSAKSI
                </h2>
                <p class="text-white-50 mb-0 small">Ringkasan riwayat penjualan & arus kas</p>
            </div>

            <button onclick="window.print()" class="btn-print">
                <i class="bi bi-printer me-2"></i> CETAK LAPORAN
            </button>
        </div>

        <div class="glass-container">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="25%">Nama Pembeli</th>
                            <th width="15%">Metode</th>
                            <th width="20%">Total Harga</th>
                            <th width="20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fix Error Database Connection
                        if (!class_exists('Database')) {
                            $configPath = __DIR__ . '/../../config/Database.php';
                            if (file_exists($configPath))
                                require_once $configPath;
                        }

                        $db_obj = new Database();
                        $conn = $db_obj->getConnection();

                        if ($conn):
                            // Join table transactions dengan users untuk dapat nama pembeli
                            $query = "SELECT t.*, u.fullname FROM transactions t 
                                      JOIN users u ON t.user_id = u.id 
                                      ORDER BY t.created_at DESC";
                            $stmt = $conn->query($query);
                            $no = 1;

                            if ($stmt->rowCount() > 0):
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <tr>
                                        <td class="text-white-50 text-center"><?= $no++ ?></td>
                                        <td>
                                            <div class="txt-date">
                                                <?= date('d/m/Y', strtotime($row['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="txt-customer"><?= htmlspecialchars($row['fullname']) ?></div>
                                        </td>
                                        <td>
                                            <span class="badge-method"><?= htmlspecialchars($row['payment_method']) ?></span>
                                        </td>
                                        <td>
                                            <div class="txt-money">Rp <?= number_format($row['total_price'], 0, ',', '.') ?></div>
                                        </td>
                                        <td>
                                            <span class="badge-success-custom">
                                                <i class="bi bi-check-circle-fill me-1"></i> BERHASIL
                                            </span>
                                        </td>
                                    </tr>
                                                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-receipt display-1 opacity-25 d-block mb-3" style="color: #FFD700;"></i>
                                        <span class="text-white-50">Belum ada data transaksi.</span>
                                    </td>
                                </tr>
                                                        <?php
                            endif;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const canvas = document.getElementById("finance-matrix");
        const ctx = canvas.getContext("2d");

        let width, height;
        let drops = [];

        // Simbol Keuangan & Data
        const chars = "Rp$%01+*#€£¥";

        function resize() {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
            initDrops();
        }

        // Inisialisasi Tetesan (3 Layer Kedalaman)
        function initDrops() {
            drops = [];
            // Layer 1: Belakang (Kecil, Lambat, Redup) -> Jauh
            // Layer 2: Tengah (Sedang)
            // Layer 3: Depan (Besar, Cepat, Terang) -> Dekat (4D Effect)

            const layers = [
                { count: 50, speed: 2, size: 10, color: "rgba(255, 215, 0, 0.3)" }, // Jauh
                { count: 30, speed: 4, size: 16, color: "rgba(255, 215, 0, 0.6)" }, // Tengah
                { count: 15, speed: 7, size: 24, color: "rgba(255, 215, 0, 1)" }    // Dekat
            ];

            layers.forEach(layer => {
                for (let i = 0; i < layer.count; i++) {
                    drops.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        text: chars[Math.floor(Math.random() * chars.length)],
                        speed: layer.speed,
                        size: layer.size,
                        color: layer.color
                    });
                }
            });
        }

        function draw() {
            // Efek Jejak (Trail)
            ctx.fillStyle = "rgba(0, 0, 0, 0.1)"; // Semakin kecil alpha, semakin panjang jejaknya
            ctx.fillRect(0, 0, width, height);

            drops.forEach(drop => {
                ctx.fillStyle = drop.color;
                ctx.font = drop.size + "px 'Orbitron', monospace";
                ctx.fillText(drop.text, drop.x, drop.y);

                // Gerakkan ke bawah
                drop.y += drop.speed;

                // Reset jika keluar layar (dengan sedikit acak agar tidak pola berulang)
                if (drop.y > height && Math.random() > 0.95) {
                    drop.y = -20;
                    drop.x = Math.random() * width;
                    drop.text = chars[Math.floor(Math.random() * chars.length)];
                }
            });

            requestAnimationFrame(draw);
        }

        window.addEventListener('resize', resize);
        resize();
        draw();
    </script>
</body>

</html>