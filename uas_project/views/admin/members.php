<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota - Royal Commerce</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* ================= 1. BACKGROUND 4D: ROYAL GYROSCOPE ================= */
        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at center, #050505 0%, #1a1a2e 100%);
            color: white;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            /* Mencegah scrollbar muncul karena elemen 3D */
            position: relative;
        }

        /* Container 3D */
        .gyroscope-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            perspective: 1000px;
            /* Kunci efek 4D */
            z-index: -1;
            pointer-events: none;
            opacity: 0.6;
            /* Transparan agar tidak mengganggu teks */
        }

        /* Cincin Emas */
        .ring {
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            border: 1px solid rgba(255, 215, 0, 0.4);
            /* Garis Emas Halus */
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.1);
            transform-style: preserve-3d;
        }

        /* Animasi Putaran 3D */
        .ring-1 {
            width: 100%;
            height: 100%;
            margin-left: -50%;
            margin-top: -50%;
            border-color: rgba(255, 215, 0, 0.2);
            animation: rotate-3d-1 30s infinite linear;
        }

        .ring-2 {
            width: 80%;
            height: 80%;
            margin-left: -40%;
            margin-top: -40%;
            border: 2px solid rgba(255, 215, 0, 0.3);
            animation: rotate-3d-2 25s infinite linear reverse;
        }

        .ring-3 {
            width: 60%;
            height: 60%;
            margin-left: -30%;
            margin-top: -30%;
            border: 1px dashed rgba(255, 215, 0, 0.5);
            /* Garis putus-putus */
            animation: rotate-3d-3 20s infinite linear;
        }

        /* Inti Bersinar di Tengah */
        .core {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            background: #FFD700;
            border-radius: 50%;
            box-shadow: 0 0 50px 20px rgba(255, 215, 0, 0.3);
            animation: pulse-core 4s infinite ease-in-out;
        }

        @keyframes rotate-3d-1 {
            0% {
                transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg);
            }

            100% {
                transform: rotateX(360deg) rotateY(180deg) rotateZ(360deg);
            }
        }

        @keyframes rotate-3d-2 {
            0% {
                transform: rotateX(60deg) rotateY(0deg);
            }

            100% {
                transform: rotateX(60deg) rotateY(360deg);
            }
        }

        @keyframes rotate-3d-3 {
            0% {
                transform: rotateY(0deg) rotateX(90deg);
            }

            100% {
                transform: rotateY(360deg) rotateX(90deg);
            }
        }

        @keyframes pulse-core {

            0%,
            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.8;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.5);
                opacity: 0.4;
            }
        }

        /* ================= 2. TATA LETAK & DESIGN (TETAP SAMA) ================= */

        /* Agar tabel bisa di-scroll meskipun body overflow hidden */
        .main-content {
            height: 100vh;
            overflow-y: auto;
            position: relative;
            z-index: 10;
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.02);
            /* Sangat Bening */
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 215, 0, 0.1);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
        }

        .table {
            --bs-table-bg: transparent !important;
            --bs-table-color: white !important;
            margin-bottom: 0;
        }

        thead th {
            color: #FFD700 !important;
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid rgba(255, 215, 0, 0.3) !important;
            padding-bottom: 20px !important;
        }

        tbody tr {
            background: rgba(20, 20, 30, 0.4) !important;
            border-bottom: 10px solid transparent;
            background-clip: padding-box;
            transition: all 0.4s ease;
        }

        tbody tr:hover {
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.15), rgba(20, 20, 30, 0.9)) !important;
            transform: scale(1.02) translateX(10px);
            border-left: 5px solid #FFD700;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        td {
            vertical-align: middle;
            padding: 20px !important;
            border: none !important;
            color: #f0f0f0;
        }

        .profile-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid rgba(255, 215, 0, 0.5);
            padding: 2px;
            transition: 0.3s;
        }

        tbody tr:hover .profile-img {
            border-color: #FFD700;
            transform: rotate(10deg) scale(1.1);
        }

        .member-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            margin-bottom: 4px;
        }

        .member-username {
            color: #aaa;
            font-size: 0.85rem;
            font-style: italic;
        }

        .contact-link {
            text-decoration: none;
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.05);
            transition: 0.3s;
        }

        .contact-link:hover {
            background: #25D366;
            color: white;
            box-shadow: 0 0 15px rgba(37, 211, 102, 0.4);
        }

        .badge-premium {
            background: linear-gradient(45deg, #FFD700, #FDB931);
            color: black;
            font-weight: bold;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
            font-size: 0.75rem;
            letter-spacing: 1px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .total-badge {
            background: rgba(255, 215, 0, 0.05);
            border: 1px solid #FFD700;
            color: #FFD700;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class="gyroscope-container">
        <div class="ring ring-1"></div>
        <div class="ring ring-2"></div>
        <div class="ring ring-3"></div>
        <div class="core"></div>
    </div>

    <div class="main-content">

        <?php require_once 'views/layout/navbar.php'; ?>

        <div class="container mt-5 mb-5">

            <div class="page-header">
                <div>
                    <h2 class="fw-bold mb-1"
                        style="color: #FFD700; font-family: 'Playfair Display', serif; text-shadow: 0 0 20px rgba(255,215,0,0.3);">
                        <i class="bi bi-crown me-2"></i>Daftar Keanggotaan
                    </h2>
                    <p class="text-white-50 mb-0 small">Member eksklusif Royal Commerce</p>
                </div>

                <div class="total-badge">
                    <?php
                    if (!class_exists('Database')) {
                        $configPath = __DIR__ . '/../../config/Database.php';
                        if (file_exists($configPath))
                            require_once $configPath;
                    }
                    $db_obj = new Database();
                    $conn = $db_obj->getConnection();

                    $total = 0;
                    if ($conn) {
                        $total = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
                    }
                    ?>
                    TOTAL: <?= $total ?> MEMBER
                </div>
            </div>

            <div class="glass-container">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th width="10%">Profil</th>
                                <th width="30%">Identitas</th>
                                <th width="25%">Kontak</th>
                                <th width="20%">Domisili</th>
                                <th width="15%" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($conn):
                                $stmt = $conn->query("SELECT * FROM users WHERE role = 'user' ORDER BY fullname ASC");
                                if ($stmt->rowCount() > 0):
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <tr>
                                            <td>
                                                <img src="<?= BASE_URL ?>/assets/images/profiles/<?= !empty($row['photo']) ? $row['photo'] : 'default.jpg' ?>"
                                                    class="profile-img" alt="Foto Profil">
                                            </td>
                                            <td>
                                                <div class="member-name"><?= htmlspecialchars($row['fullname']) ?></div>
                                                <div class="member-username">@<?= htmlspecialchars($row['username']) ?></div>
                                            </td>
                                            <td>
                                                <a href="https://wa.me/<?= htmlspecialchars($row['phone']) ?>" target="_blank"
                                                    class="contact-link">
                                                    <i class="bi bi-whatsapp"></i>
                                                    <?= !empty($row['phone']) ? $row['phone'] : 'Tidak Ada' ?>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="text-white-50">
                                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                                    <?= !empty($row['address']) ? htmlspecialchars($row['address']) : 'Belum diisi' ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-premium">
                                                    <i class="bi bi-star-fill me-1"></i> PREMIUM
                                                </span>
                                            </td>
                                        </tr>
                                    <?php
                                    endwhile;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-people display-1 opacity-25 d-block mb-3"
                                                style="color: #FFD700;"></i>
                                            <span class="text-white-50">Belum ada anggota yang terdaftar.</span>
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>