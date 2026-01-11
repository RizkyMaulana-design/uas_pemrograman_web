<?php
// Cek Session
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/login");
    exit();
}

// Koneksi Database (Anti-Error)
if (!class_exists('Database')) {
    $configPath = __DIR__ . '/../../config/Database.php';
    if (file_exists($configPath))
        require_once $configPath;
}

$db_obj = new Database();
$conn = $db_obj->getConnection();

// Ambil Data User Terbaru
$id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle Update Profil & Upload Foto
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Default foto adalah foto lama
    $photoName = $user['photo'];

    // 1. LOGIKA UPLOAD FOTO
    if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['new_photo']['name'];
        $filetype = $_FILES['new_photo']['type'];
        $filesize = $_FILES['new_photo']['size'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Cek ekstensi & ukuran (max 2MB)
        if (in_array($ext, $allowed) && $filesize <= 2000000) {
            // Buat nama file unik: waktu_random.jpg
            $newName = time() . '_' . rand(1000, 9999) . '.' . $ext;
            // Tentukan lokasi simpan
            $targetDir = __DIR__ . '/../../assets/images/profiles/';

            // Pastikan folder ada (optional, buat jaga-jaga)
            if (!is_dir($targetDir))
                mkdir($targetDir, 0777, true);

            if (move_uploaded_file($_FILES['new_photo']['tmp_name'], $targetDir . $newName)) {
                // Sukses upload, pakai nama baru
                $photoName = $newName;

                // Update Session biar foto di pojok kanan atas langsung berubah
                $_SESSION['photo'] = $newName;
            } else {
                $message = "<div class='alert alert-danger'>Gagal mengupload gambar. Cek permission folder.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning'>Format harus JPG/PNG dan Max 2MB!</div>";
        }
    }

    // 2. LOGIKA UPDATE DATABASE
    // Jika tidak ada error upload sebelumnya (message kosong)
    if (empty($message) || strpos($message, 'alert-danger') === false) {
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET fullname=:f, phone=:p, address=:a, password=:pass, photo=:img WHERE id=:id";
            $params = [':f' => $fullname, ':p' => $phone, ':a' => $address, ':pass' => $hash, ':img' => $photoName, ':id' => $id];
        } else {
            $sql = "UPDATE users SET fullname=:f, phone=:p, address=:a, photo=:img WHERE id=:id";
            $params = [':f' => $fullname, ':p' => $phone, ':a' => $address, ':img' => $photoName, ':id' => $id];
        }

        $update = $conn->prepare($sql);
        if ($update->execute($params)) {
            $message = "<div class='alert alert-success'>Profil & Foto berhasil diperbarui!</div>";
            // Refresh data user agar tampilan terupdate
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['fullname'] = $user['fullname'];
        } else {
            $message = "<div class='alert alert-danger'>Gagal memperbarui database.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Royal Commerce</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* ================= BACKGROUND 4D (ROYAL AURORA) ================= */
        body {
            font-family: 'Poppins', sans-serif;
            background: #050505;
            color: white;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .aurora-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            background: #050505;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.5;
            animation: float-aurora 20s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: #FFD700;
            top: -10%;
            right: -10%;
            animation-duration: 25s;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: #4b0082;
            bottom: -10%;
            left: -10%;
            animation-duration: 30s;
        }

        @keyframes float-aurora {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(30px, -50px) scale(1.1);
            }
        }

        /* ================= CARD PROFILE ================= */
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            color: #FFD700;
            margin-bottom: 30px;
            text-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
        }

        /* FOTO PROFIL DENGAN TOMBOL UPLOAD */
        .profile-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 15px;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #FFD700;
            padding: 3px;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
            transition: 0.3s;
        }

        /* Tombol Kamera Kecil */
        .upload-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: #FFD700;
            color: black;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid #000;
            transition: 0.3s;
            z-index: 10;
        }

        .upload-btn:hover {
            background: white;
            transform: scale(1.1);
        }

        /* Input File Disembunyikan */
        #fileInput {
            display: none;
        }

        /* Form Styling */
        .form-label {
            color: #ccc;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .form-control {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 10px;
            padding: 12px 15px;
        }

        .form-control:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: #FFD700;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.2);
            color: white;
            outline: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .btn-gold {
            background: linear-gradient(45deg, #FFD700, #FDB931);
            color: black;
            font-weight: bold;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            width: 100%;
            margin-top: 20px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
            transition: 0.3s;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.6);
        }
    </style>
</head>

<body>

    <div class="aurora-container">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
    </div>

    <?php require_once 'views/layout/navbar.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <div class="glass-card">
                    <h2 class="text-center page-title">Profil Pengguna</h2>

                    <?= $message ?>

                    <form method="POST" action="" enctype="multipart/form-data">

                        <div class="profile-section">
                            <div class="profile-wrapper">
                                <img src="<?= BASE_URL ?>/assets/images/profiles/<?= !empty($user['photo']) ? $user['photo'] : 'default.jpg' ?>"
                                    class="profile-img" id="previewImg" alt="Profile">

                                <label for="fileInput" class="upload-btn" title="Ganti Foto">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                <input type="file" name="new_photo" id="fileInput" accept="image/*">
                            </div>

                            <h4 class="fw-bold text-white mb-0"><?= htmlspecialchars($user['fullname']) ?></h4>
                            <small class="text-white-50">Klik ikon kamera untuk ganti foto</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="fullname" class="form-control"
                                value="<?= htmlspecialchars($user['fullname']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp / HP</label>
                            <input type="text" name="phone" class="form-control"
                                value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Pengiriman</label>
                            <textarea name="address" class="form-control" rows="3"
                                placeholder="Alamat lengkap..."><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>

                        <hr class="border-secondary my-4">

                        <div class="mb-3">
                            <label class="form-label text-warning"><i class="bi bi-lock-fill me-1"></i> Ganti Password
                                (Opsional)</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Kosongkan jika tidak ingin mengganti">
                        </div>

                        <button type="submit" class="btn-gold">
                            <i class="bi bi-save me-2"></i> SIMPAN PERUBAHAN
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('fileInput');
        const previewImg = document.getElementById('previewImg');

        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result; // Ganti gambar jadi preview
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>