<?php // Bagian <head> sama seperti login.php ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYYN Commerce</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="/uas_project/assets/css/style.css">

    <style>
        body {
            background: linear-gradient(-45deg, #0f0c29, #302b63, #24243e, #000000) !important;
            background-size: 400% 400%;
            animation: deepOcean 15s ease infinite;
            color: #fff;
            min-height: 100vh;
        }

        @keyframes deepOcean {
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

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<div class="container d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="glass-card p-5 animate-fade-in" style="width: 550px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-warning" style="letter-spacing: 2px;">JOIN MEMBERSHIP</h2>
            <p class="text-white-50">Daftar untuk mulai belanja barang mewah</p>
        </div>

        <form action="<?= BASE_URL ?>/controllers/AuthController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="register">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label small">Nama Lengkap</label>
                    <input type="text" name="fullname" class="form-control bg-transparent border-secondary text-white"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small">Username</label>
                    <input type="text" name="username" class="form-control bg-transparent border-secondary text-white"
                        required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small">Password</label>
                <input type="password" name="password" class="form-control bg-transparent border-secondary text-white"
                    required>
            </div>
            <div class="mb-3">
                <label class="form-label small">Nomor WhatsApp</label>
                <input type="number" name="phone" class="form-control bg-transparent border-secondary text-white"
                    required>
            </div>
            <div class="mb-4">
                <label class="form-label small">Alamat Lengkap</label>
                <textarea name="address" class="form-control bg-transparent border-secondary text-white" rows="2"
                    required></textarea>
            </div>
            <button type="submit" class="btn btn-luxury w-100 py-2 fw-bold mb-3">DAFTAR SEKARANG</button>
            <p class="text-center small text-white-50">Sudah punya akun? <a href="<?= BASE_URL ?>/login"
                    class="text-warning text-decoration-none fw-bold">Login</a></p>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>