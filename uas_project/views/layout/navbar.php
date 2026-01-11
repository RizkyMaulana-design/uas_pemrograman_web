<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<nav class="navbar navbar-expand-lg fixed-top navbar-dark"
    style="background: rgba(10, 10, 25, 0.85); backdrop-filter: blur(15px); border-bottom: 1px solid rgba(212, 175, 55, 0.4); z-index: 1050;">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase" href="<?= BASE_URL ?>/catalog"
            style="letter-spacing: 3px; color: #d4af37; text-shadow: 0 0 15px rgba(212,175,55,0.6);">
            <i class="bi bi-gem me-2"></i> ROYAL COMMERCE
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <?php if (isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item"><a class="nav-link text-white small px-3 hover-effect"
                                href="<?= BASE_URL ?>/admin_dashboard">DASHBOARD</a></li>
                        <li class="nav-item"><a class="nav-link text-white small px-3 hover-effect"
                                href="<?= BASE_URL ?>/admin_products">KELOLA BARANG</a></li>
                        <li class="nav-item"><a class="nav-link text-white small px-3 hover-effect"
                                href="<?= BASE_URL ?>/admin_members">ANGGOTA</a></li>
                        <li class="nav-item"><a class="nav-link text-white small px-3 hover-effect"
                                href="<?= BASE_URL ?>/admin_report">LAPORAN</a></li>

                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link text-white small px-3 hover-effect"
                                href="<?= BASE_URL ?>/catalog">KATALOG</a></li>
                        <li class="nav-item">
                            <a class="nav-link text-white small px-3 position-relative hover-effect"
                                href="<?= BASE_URL ?>/cart">
                                KERANJANG <i class="bi bi-cart3"></i>
                                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="font-size: 0.6rem;">
                                        <?= array_sum($_SESSION['cart']) ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link text-white small px-3 hover-effect" href="<?= BASE_URL ?>/about">TENTANG SAYA</a>
                    </li>

                    <li class="nav-item dropdown ms-lg-4 mt-3 mt-lg-0">
                        <a class="nav-link dropdown-toggle btn btn-outline-warning rounded-pill px-3 py-1 text-white d-flex align-items-center profile-btn"
                            href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                            <?php
                            $userPhoto = !empty($_SESSION['photo']) ? $_SESSION['photo'] : 'default.jpg';
                            ?>
                            <img src="<?= BASE_URL ?>/assets/images/profiles/<?= $userPhoto ?>"
                                class="rounded-circle me-2 profile-img-nav" alt="Profile">

                            <span class="small fw-bold">
                                <?= !empty($_SESSION['fullname']) ? explode(' ', $_SESSION['fullname'])[0] : 'User'; ?>
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 animate-slide-in luxury-dropdown"
                            aria-labelledby="navbarDropdown">
                            <li>
                                <div class="px-4 py-3 border-bottom border-secondary text-center">
                                    <p class="small text-white-50 mb-0">Role Akses:</p>
                                    <p class="small fw-bold text-warning mb-0 text-uppercase">
                                        <?= htmlspecialchars($_SESSION['role']) ?>
                                    </p>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item text-white py-2 px-4 item-gold" href="<?= BASE_URL ?>/profile">
                                    <i class="bi bi-person-circle me-3 text-warning"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider bg-secondary opacity-25">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger fw-bold py-2 px-4 item-danger"
                                    href="<?= BASE_URL ?>/logout">
                                    <i class="bi bi-power me-3"></i> KELUAR
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-luxury text-dark px-4 rounded-pill fw-bold shadow-sm"
                            href="<?= BASE_URL ?>/login">MASUK</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<style>
    .profile-img-nav {
        width: 28px;
        height: 28px;
        object-fit: cover;
        border: 1px solid #d4af37;
    }

    .profile-btn {
        border: 1px solid rgba(212, 175, 55, 0.5) !important;
        transition: 0.3s;
    }

    .profile-btn:hover {
        background: rgba(212, 175, 55, 0.2) !important;
        box-shadow: 0 0 10px rgba(212, 175, 55, 0.4);
    }

    .luxury-dropdown {
        background: rgba(20, 20, 35, 0.98) !important;
        backdrop-filter: blur(20px);
        min-width: 220px;
    }

    .item-gold:hover {
        background: rgba(212, 175, 55, 0.1) !important;
        color: #d4af37 !important;
        padding-left: 2rem !important;
        transition: 0.3s;
    }

    .item-danger:hover {
        background: rgba(220, 53, 69, 0.1) !important;
        color: #ff4d4d !important;
        transition: 0.3s;
    }

    .hover-effect {
        transition: 0.3s;
        letter-spacing: 1px;
    }

    .hover-effect:hover {
        color: #d4af37 !important;
        transform: translateY(-2px);
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-luxury {
        background: linear-gradient(45deg, #d4af37, #f7df85);
        border: none;
        transition: 0.3s;
    }

    .btn-luxury:hover {
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
    }
</style>

<div style="margin-top: 100px;"></div>