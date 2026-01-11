<?php
// Cek isi keranjang
if (empty($_SESSION['cart'])) {
    echo "<script>alert('Keranjang kosong! Silakan belanja dulu.'); window.location='" . BASE_URL . "/catalog';</script>";
    exit;
}

$conn = (new Database())->getConnection();

// Ambil detail barang berdasarkan ID yang ada di session
$ids = implode(',', array_keys($_SESSION['cart']));
$stmt = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Keranjang - Royal Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<body>

    <?php require_once 'views/layout/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="glass-card p-4">
                    <h3 class="mb-4">🛒 Keranjang Belanja</h3>
                    <table class="table table-dark table-borderless align-middle" style="background: transparent;">
                        <thead>
                            <tr class="border-bottom border-secondary">
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grandTotal = 0;
                            foreach ($cartItems as $item):
                                $qty = $_SESSION['cart'][$item['id']];
                                $subtotal = $item['price'] * $qty;
                                $grandTotal += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <?= $item['name'] ?>
                                    </td>
                                    <td>Rp
                                        <?= number_format($item['price'], 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <?= $qty ?>
                                    </td>
                                    <td class="text-warning">Rp
                                        <?= number_format($subtotal, 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <a href="controllers/TransController.php?action=remove&id=<?= $item['id'] ?>"
                                            class="btn btn-sm btn-outline-danger">x</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div class="glass-card p-4">
                    <h4 class="mb-3 text-warning">Checkout</h4>
                    <h2 class="mb-4">Rp
                        <?= number_format($grandTotal, 0, ',', '.') ?>
                    </h2>

                    <form action="controllers/TransController.php" method="POST">
                        <input type="hidden" name="action" value="checkout">
                        <input type="hidden" name="total_price" value="<?= $grandTotal ?>">

                        <div class="mb-3">
                            <label class="small">Nama Penerima</label>
                            <input type="text" class="form-control bg-dark text-white"
                                value="<?= $_SESSION['fullname'] ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small">No. HP</label>
                            <input type="text" name="phone" class="form-control bg-dark text-white"
                                value="<?= isset($_SESSION['phone']) ? $_SESSION['phone'] : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="small">Alamat Pengiriman</label>
                            <textarea name="address" class="form-control bg-dark text-white" rows="2"
                                required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="small">Metode Pembayaran</label>
                            <select name="payment_method" id="paymentMethod" class="form-select bg-dark text-white"
                                onchange="showBankInfo()" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="dana">DANA</option>
                                <option value="gopay">GoPay</option>
                                <option value="seabank">SeaBank</option>
                                <option value="bank">Transfer Bank (BCA/BRI/DLL)</option>
                            </select>
                        </div>

                        <div id="bankInfo" class="alert alert-info small" style="display:none;">
                        </div>

                        <button type="submit" class="btn btn-luxury w-100 mt-3"
                            onclick="return confirm('Proses pesanan sekarang?')">BAYAR SEKARANG</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showBankInfo() {
            const method = document.getElementById('paymentMethod').value;
            const infoDiv = document.getElementById('bankInfo');
            let content = '';

            if (method === '') {
                infoDiv.style.display = 'none';
                return;
            }

            if (method === 'bank') {
                content = "Silakan Transfer ke Rekening Seluruh Indonesia:<br><strong>312410430</strong> (A/N Admin)";
            } else if (method === 'seabank') {
                content = "Transfer SeaBank:<br><strong>085872051850</strong>";
            } else {
                // Dana & Gopay
                content = "Transfer " + method.toUpperCase() + ":<br><strong>085872051850</strong>";
            }

            infoDiv.innerHTML = content;
            infoDiv.style.display = 'block';
        }
    </script>
</body>

</html>