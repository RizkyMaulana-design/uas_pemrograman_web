<?php
session_start();
require_once '../config/Database.php';

class TransController
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // --- 1. MASUKKAN KE KERANJANG ---
    public function addToCart($id)
    {
        // Jika keranjang belum ada, buat baru
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Jika barang sudah ada, tambah jumlahnya
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]++;
        } else {
            $_SESSION['cart'][$id] = 1;
        }

        header("Location: ../index.php?url=cart");
    }

    // --- 2. HAPUS DARI KERANJANG ---
    public function removeCart($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: ../index.php?url=cart");
    }

    // --- 3. PROSES CHECKOUT (PEMBAYARAN) ---
    public function checkout($user_id, $total, $method, $address, $phone)
    {
        try {
            $this->conn->beginTransaction();

            // A. Simpan Data Transaksi (Header)
            $query = "INSERT INTO transactions (user_id, total_price, payment_method, status) VALUES (:uid, :total, :method, 'Selesai')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':uid' => $user_id, ':total' => $total, ':method' => $method]);
            $trans_id = $this->conn->lastInsertId();

            // B. (Opsional) Simpan Detail Barang jika punya tabel detail
            // Untuk UAS sederhana, header saja sudah cukup, tapi kita kurangi stok barang
            foreach ($_SESSION['cart'] as $prod_id => $qty) {
                $upd = $this->conn->prepare("UPDATE products SET stock = stock - :qty WHERE id = :id");
                $upd->execute([':qty' => $qty, ':id' => $prod_id]);
            }

            // C. Kosongkan Keranjang & Commit
            unset($_SESSION['cart']);
            $this->conn->commit();

            // Redirect ke halaman sukses (atau kembali ke katalog dengan notif)
            header("Location: ../index.php?url=catalog&msg=success_transaction&id=$trans_id");

        } catch (Exception $e) {
            $this->conn->rollBack();
            echo "Gagal Transaksi: " . $e->getMessage();
        }
    }
}

// --- ROUTING LOGIC ---
$cart = new TransController();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add') {
        $cart->addToCart($_GET['id']);
    } elseif ($_GET['action'] == 'remove') {
        $cart->removeCart($_GET['id']);
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'checkout') {
    $cart->checkout(
        $_SESSION['user_id'],
        $_POST['total_price'],
        $_POST['payment_method'],
        $_POST['address'],
        $_POST['phone']
    );
}
?>