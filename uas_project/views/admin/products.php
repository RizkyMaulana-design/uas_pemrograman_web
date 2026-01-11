<?php
// 1. KEAMANAN & KONEKSI
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: " . BASE_URL . "/login");
    exit();
}

if (!class_exists('Database')) {
    $configPath = __DIR__ . '/../../config/Database.php';
    if (file_exists($configPath))
        require_once $configPath;
}

$db_obj = new Database();
$conn = $db_obj->getConnection();

// ================= 2. LOGIKA PHP (TAMBAH, EDIT, HAPUS) =================

$message = "";

// A. LOGIKA TAMBAH & EDIT
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $action = $_POST['action'];

    // Siapkan Gambar Default / Lama
    $imageName = isset($_POST['old_image']) ? $_POST['old_image'] : 'default_product.jpg';

    // Cek apakah ada upload gambar baru?
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $newName = 'prod_' . time() . '.' . $ext;
            $targetDir = __DIR__ . '/../../assets/images/products/';

            // Buat folder jika belum ada
            if (!is_dir($targetDir))
                mkdir($targetDir, 0777, true);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $newName)) {
                $imageName = $newName;
            }
        }
    }

    try {
        if ($action == 'add') {
            // QUERY TAMBAH
            $sql = "INSERT INTO products (name, description, price, stock, image) VALUES (:n, :d, :p, :s, :img)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':n' => $name, ':d' => $desc, ':p' => $price, ':s' => $stock, ':img' => $imageName]);
            $message = "<div class='alert alert-success'>Barang berhasil ditambahkan!</div>";

        } elseif ($action == 'edit') {
            // QUERY EDIT
            $id = $_POST['id'];
            $sql = "UPDATE products SET name=:n, description=:d, price=:p, stock=:s, image=:img WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':n' => $name, ':d' => $desc, ':p' => $price, ':s' => $stock, ':img' => $imageName, ':id' => $id]);
            $message = "<div class='alert alert-success'>Data barang berhasil diperbarui!</div>";
        }
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Error Database: " . $e->getMessage() . "</div>";
    }
}

// B. LOGIKA HAPUS
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    header("Location: index.php?url=admin_products"); // Redirect bersih
    exit();
}

// C. AMBIL SEMUA DATA (Untuk Tabel)
$products = $conn->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Barang - Royal Commerce</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        /* BACKGROUND 4D (NEURAL NETWORK / KONEKTIVITAS) */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #050505;
            color: white;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Container Glass Mewah */
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 215, 0, 0.1);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            margin-top: 20px;
        }

        /* Tabel Styling */
        .table {
            --bs-table-bg: transparent !important;
            --bs-table-color: white !important;
        }

        thead th {
            color: #FFD700;
            border-bottom: 2px solid rgba(255, 215, 0, 0.3) !important;
            font-family: 'Playfair Display', serif;
            text-transform: uppercase;
        }

        tbody tr {
            transition: 0.3s;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        tbody tr:hover {
            background: rgba(255, 215, 0, 0.05) !important;
            transform: scale(1.01);
        }

        td {
            vertical-align: middle;
        }

        /* Gambar Produk Kecil */
        .thumb-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #FFD700;
        }

        /* Tombol Aksi */
        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            transition: 0.3s;
        }

        .btn-edit {
            background: rgba(255, 215, 0, 0.2);
            color: #FFD700;
        }

        .btn-edit:hover {
            background: #FFD700;
            color: black;
        }

        .btn-del {
            background: rgba(255, 0, 0, 0.2);
            color: #ff6b6b;
        }

        .btn-del:hover {
            background: #ff6b6b;
            color: white;
        }

        /* Modal Custom (Gelap & Emas) */
        .modal-content {
            background: #1a1a1a;
            border: 1px solid #FFD700;
            color: white;
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 215, 0, 0.2);
        }

        .modal-footer {
            border-top: 1px solid rgba(255, 215, 0, 0.2);
        }

        .form-control {
            background: #0f0f0f;
            border: 1px solid #333;
            color: white;
        }

        .form-control:focus {
            background: #0f0f0f;
            border-color: #FFD700;
            color: white;
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.2);
        }

        .btn-gold {
            background: #FFD700;
            color: black;
            font-weight: bold;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.4);
        }

        .btn-gold:hover {
            background: #ffdb1a;
            box-shadow: 0 0 25px rgba(255, 215, 0, 0.6);
        }
    </style>
</head>

<body>

    <?php require_once 'views/layout/navbar.php'; ?>

    <div class="container mt-5 mb-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold" style="color: #FFD700; font-family: 'Playfair Display', serif;">
                    <i class="bi bi-box-seam me-2"></i>Data Barang
                </h2>
                <p class="text-white-50 mb-0">Kelola katalog produk Royal Commerce</p>
            </div>
            <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#modalProduct" onclick="resetForm()">
                <i class="bi bi-plus-lg me-1"></i> TAMBAH BARANG
            </button>
        </div>

        <?= $message ?>

        <div class="glass-panel">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Info Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($products) > 0): ?>
                            <?php foreach ($products as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <?php
                                        $img = !empty($row['image']) ? $row['image'] : 'default_product.jpg';
                                        ?>
                                        <img src="<?= BASE_URL ?>/assets/images/products/<?= $img ?>" class="thumb-img"
                                            alt="Produk">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-white"><?= htmlspecialchars($row['name']) ?></div>
                                        <small class="text-white-50 text-truncate d-block" style="max-width: 250px;">
                                            <?= htmlspecialchars($row['description']) ?>
                                        </small>
                                    </td>
                                    <td class="text-warning fw-bold">Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge <?= $row['stock'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $row['stock'] ?> Pcs
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn-action btn-edit me-1" data-bs-toggle="modal"
                                            data-bs-target="#modalProduct" onclick="editProduct(
                                                '<?= $row['id'] ?>', 
                                                '<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>', 
                                                '<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>', 
                                                '<?= $row['price'] ?>', 
                                                '<?= $row['stock'] ?>',
                                                '<?= $img ?>'
                                            )">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>

                                        <a href="index.php?url=admin_products&delete_id=<?= $row['id'] ?>"
                                            class="btn-action btn-del"
                                            onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-white-50">Belum ada barang di katalog.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="modal fade" id="modalProduct" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle" style="color: #FFD700;">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="prodId">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="old_image" id="oldImage">

                        <div class="mb-3">
                            <label class="form-label text-white-50">Nama Barang</label>
                            <input type="text" name="name" id="prodName" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-50">Deskripsi</label>
                            <textarea name="description" id="prodDesc" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label text-white-50">Harga (Rp)</label>
                                <input type="number" name="price" id="prodPrice" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-white-50">Stok</label>
                                <input type="number" name="stock" id="prodStock" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-50">Gambar Produk</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1" id="imgInfo">*Biarkan kosong jika tidak ingin
                                mengganti gambar saat edit.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm rounded-pill"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-gold btn-sm rounded-pill px-4">SIMPAN DATA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fungsi untuk Reset Form saat klik "Tambah"
        function resetForm() {
            document.getElementById('modalTitle').innerText = "Tambah Barang Baru";
            document.getElementById('formAction').value = "add";
            document.getElementById('prodId').value = "";
            document.getElementById('prodName').value = "";
            document.getElementById('prodDesc').value = "";
            document.getElementById('prodPrice').value = "";
            document.getElementById('prodStock').value = "";
            document.getElementById('imgInfo').style.display = 'none';
        }

        // Fungsi untuk Isi Form saat klik "Edit"
        function editProduct(id, name, desc, price, stock, oldImg) {
            document.getElementById('modalTitle').innerText = "Edit Barang";
            document.getElementById('formAction').value = "edit";
            document.getElementById('prodId').value = id;
            document.getElementById('prodName').value = name;
            document.getElementById('prodDesc').value = desc;
            document.getElementById('prodPrice').value = price;
            document.getElementById('prodStock').value = stock;
            document.getElementById('oldImage').value = oldImg;
            document.getElementById('imgInfo').style.display = 'block';
        }
    </script>
</body>

</html>