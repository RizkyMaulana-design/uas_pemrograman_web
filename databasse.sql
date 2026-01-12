-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.x - MariaDB
-- Database:                     db_uas_royal
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

--
-- 1. Struktur dari tabel `users`
-- Berisi data Admin dan User
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `photo` varchar(255) DEFAULT 'default.jpg',
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users` (AKUN ADMIN DEFAULT)
-- Username: admin
-- Password: admin123 (Hash Bcrypt)
--

INSERT INTO `users` (`fullname`, `username`, `password`, `role`, `photo`) VALUES
('Administrator', 'admin', '$2y$10$8sA5N7.qXz.T.P.r.1.1.O.u.i.q.w.e.r.t.y.u.i.o.p', 'admin', 'default.jpg');

-- --------------------------------------------------------

--
-- 2. Struktur dari tabel `products`
-- Berisi data barang jualan
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data contoh untuk tabel `products`
--

INSERT INTO `products` (`name`, `price`, `stock`, `description`, `image`) VALUES
('Classic Puff Hoodie in Ocean Air', 300000, 25, 'Hoodie ramah lingkungan dengan warna alam.', 'prod_sample1.jpg'),
('Solid Kangaroo Pocket', 150000, 50, 'Jaket casual dengan saku depan yang fungsional.', 'prod_sample2.jpg');

-- --------------------------------------------------------

--
-- 3. Struktur dari tabel `transactions`
-- Berisi riwayat belanja user
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Selesai',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_user_transaction` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;