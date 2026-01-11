<?php
// Ambil data produk
$conn = (new Database())->getConnection();
// Fitur Pencarian Sederhana
$where = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $where = "WHERE name LIKE '%$search%'";
}
$stmt = $conn->query("SELECT * FROM products $where ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Katalog Mewah - KYYN Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>

    <?php require_once 'views/layout/navbar.php'; ?>

    <div class="container mt-4">

        <div class="glass-card p-4 mb-4 d-flex flex-wrap justify-content-between align-items-center">
            <h2 class="mb-0 text-white">🛍️ Katalog Eksklusif</h2>
            <form action="" method="GET" class="d-flex mt-2 mt-md-0">
                <input type="hidden" name="url" value="catalog">
                <input type="text" name="search" class="form-control bg-dark text-white border-secondary me-2"
                    placeholder="Cari barang impian...">
                <button type="submit" class="btn btn-luxury">Cari</button>
            </form>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success_transaction'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Pesanan Berhasil!</strong> Terima kasih telah berbelanja. Admin akan segera memproses pesanan Anda.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($products as $p): ?>
                <div class="col">
                    <div class="glass-card h-100 p-3 d-flex flex-column transition-hover">
                        <div style="height: 200px; overflow: hidden;" class="rounded mb-3">
                            <img src="<?= BASE_URL ?>/assets/images/products/<?= $p['image'] ?>"
                                class="w-100 h-100 object-fit-cover">
                        </div>

                        <h5 class="fw-bold text-truncate">
                            <?= $p['name'] ?>
                        </h5>
                        <p class="small text-white-50 flex-grow-1" style="max-height: 50px; overflow:hidden;">
                            <?= $p['description'] ?>
                        </p>
                        <h5 class="text-warning mb-3">Rp
                            <?= number_format($p['price'], 0, ',', '.') ?>
                        </h5>

                        <div class="d-grid gap-2">
                            <?php if ($p['stock'] > 0): ?>
                                <a href="controllers/TransController.php?action=add&id=<?= $p['id'] ?>" class="btn btn-luxury">
                                    <i class="bi bi-cart-plus"></i> Masukkan Keranjang
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>