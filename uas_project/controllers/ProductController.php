<?php
require_once '../config/Database.php';

class ProductController
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // --- 1. TAMBAH BARANG ---
    public function addProduct($name, $price, $stock, $desc, $file)
    {
        // Upload Gambar
        $target_dir = "../assets/images/products/";
        $filename = time() . "_" . basename($file["name"]); // Nama unik pakai waktu
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Jika gambar terupload, simpan data ke DB
            $query = "INSERT INTO products (name, price, stock, description, image) VALUES (:name, :price, :stock, :desc, :img)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':desc', $desc);
            $stmt->bindParam(':img', $filename);

            if ($stmt->execute()) {
                header("Location: ../index.php?url=admin_products&msg=added");
            }
        } else {
            echo "Gagal upload gambar.";
        }
    }

    // --- 2. HAPUS BARANG ---
    public function deleteProduct($id)
    {
        // Ambil nama gambar dulu untuk dihapus dari folder
        $stmt = $this->conn->prepare("SELECT image FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Hapus file gambar lama
            if (file_exists("../assets/images/products/" . $product['image'])) {
                unlink("../assets/images/products/" . $product['image']);
            }

            // Hapus data dari DB
            $query = "DELETE FROM products WHERE id = :id";
            $delStmt = $this->conn->prepare($query);
            $delStmt->bindParam(':id', $id);
            $delStmt->execute();
        }
        header("Location: ../index.php?url=admin_products&msg=deleted");
    }

    // --- 3. EDIT BARANG ---
    public function updateProduct($id, $name, $price, $stock, $desc, $file)
    {
        // Cek apakah user upload gambar baru?
        if (!empty($file['name'])) {
            // LOGIKA GANTI GAMBAR (Sama kayak add, tapi hapus yg lama dulu)
            // ... (Untuk UAS sederhana, kita buat update data teks saja dulu agar tidak rumit)
            // Kalau kamu mau fitur ganti gambar, logikanya mirip delete + add.

            // Upload Gambar Baru
            $target_dir = "../assets/images/products/";
            $filename = time() . "_" . basename($file["name"]);
            move_uploaded_file($file["tmp_name"], $target_dir . $filename);

            $query = "UPDATE products SET name=:name, price=:price, stock=:stock, description=:desc, image=:img WHERE id=:id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':img', $filename);
        } else {
            // Jika tidak ganti gambar
            $query = "UPDATE products SET name=:name, price=:price, stock=:stock, description=:desc WHERE id=:id";
            $stmt = $this->conn->prepare($query);
        }

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':desc', $desc);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        header("Location: ../index.php?url=admin_products&msg=updated");
    }
}

// --- ROUTING AKSI ---
$controller = new ProductController();

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $controller->addProduct($_POST['name'], $_POST['price'], $_POST['stock'], $_POST['desc'], $_FILES['image']);
    } elseif ($_POST['action'] == 'update') {
        $controller->updateProduct($_POST['id'], $_POST['name'], $_POST['price'], $_POST['stock'], $_POST['desc'], $_FILES['image']);
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $controller->deleteProduct($_GET['id']);
}
?>