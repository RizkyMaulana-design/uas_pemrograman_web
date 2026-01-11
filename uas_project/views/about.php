<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - KYYN Commerce</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<body class="animate-fade-in">
    <?php require_once 'views/layout/navbar.php'; ?>

    <div class="container mt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="glass-card p-5 text-center shadow-lg" style="border: 1px solid rgba(212, 175, 55, 0.3);">

                    <h1 class="fw-bold text-warning mb-4 animate-slide-in"
                        style="letter-spacing: 5px; text-shadow: 0 0 15px rgba(212,175,55,0.5);">
                        <i class="bi bi-info-circle me-2"></i> TENTANG KYYN COMMERCE
                    </h1>

                    <div class="px-lg-5">
                        <p class="lead text-white-50 mb-4">
                            Aplikasi ini dikembangkan sebagai syarat pemenuhan **Tugas Akhir Semester (UAS)
                            Mata Kuliah Pemrograman Web**.
                            Kami berfokus pada penggabungan teknologi *Modular* dan *OOP* dengan antarmuka
                            yang eksklusif.
                        </p>
                    </div>

                    <hr class="my-5 border-secondary opacity-25">

                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <div class="p-3">
                                <h1 class="display-4 text-warning mb-3"><i class="bi bi-rocket-takeoff"></i></h1>
                                <h4 class="fw-bold">Cepat</h4>
                                <p class="small text-white-50">Sistem routing yang efisien dan transaksi
                                    real-time yang responsif.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h1 class="display-4 text-warning mb-3"><i class="bi bi-gem"></i></h1>
                                <h4 class="fw-bold">Mewah</h4>
                                <p class="small text-white-50">Desain antarmuka eksklusif dengan efek visual
                                    4 Dimensi dan Glassmorphism.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3">
                                <h1 class="display-4 text-warning mb-3"><i class="bi bi-shield-lock"></i></h1>
                                <h4 class="fw-bold">Aman</h4>
                                <p class="small text-white-50">Keamanan data terjamin dengan implementasi
                                    sistem login Role Admin & User.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-4">
                        <div class="glass-card d-inline-block px-4 py-2 border-warning border-opacity-25">
                            <span class="text-warning fw-bold">Developed by:</span>
                            <span class="text-white ms-2">Rizky Maulana - 31240430</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>