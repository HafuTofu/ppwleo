-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 25 Des 2024 pada 18.41
-- Versi server: 9.1.0
-- Versi PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cig`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `ID_cart` int NOT NULL AUTO_INCREMENT,
  `ID_user` int DEFAULT NULL,
  `ID_produk` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `checkorno` enum('checked','unchecked','','') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unchecked',
  PRIMARY KEY (`ID_cart`),
  KEY `user_cart` (`ID_user`),
  KEY `produk_cart` (`ID_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cart`
--

INSERT INTO `cart` (`ID_cart`, `ID_user`, `ID_produk`, `qty`, `total_harga`, `checkorno`) VALUES
(39, 39, 58, 6, 740740734, 'unchecked'),
(40, 39, 57, 1, 1000000, 'unchecked'),
(42, 3, 58, 1, 123456789, 'checked'),
(43, 3, 63, 2, 17000000, 'checked'),
(44, 3, 57, 1, 1000000, 'checked'),
(45, 3, 63, 2, 17000000, 'checked'),
(46, 3, 57, 1, 1000000, 'checked'),
(47, 3, 58, 1, 123456789, 'checked');

-- --------------------------------------------------------

--
-- Struktur dari tabel `discounts`
--

DROP TABLE IF EXISTS `discounts`;
CREATE TABLE IF NOT EXISTS `discounts` (
  `ID_discount` int NOT NULL AUTO_INCREMENT,
  `amount` tinyint NOT NULL,
  `discountprice` int NOT NULL,
  PRIMARY KEY (`ID_discount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `ID_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`ID_kategori`, `nama_kategori`) VALUES
(1, 'Gaming'),
(2, 'Food'),
(3, 'Top-Up'),
(4, 'Clothes'),
(5, 'Adid');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

DROP TABLE IF EXISTS `produk`;
CREATE TABLE IF NOT EXISTS `produk` (
  `ID_produk` int NOT NULL AUTO_INCREMENT,
  `ID_kategori` int DEFAULT NULL,
  `nama` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `foto` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `stok` int DEFAULT NULL,
  `terjual` int DEFAULT NULL,
  `waktuditambahkan` date NOT NULL,
  `statusproduk` enum('available','unavailable') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'available',
  `ID_discount` int DEFAULT NULL,
  PRIMARY KEY (`ID_produk`),
  KEY `nama` (`nama`),
  KEY `ID_kategori` (`ID_kategori`),
  KEY `ID_discount` (`ID_discount`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`ID_produk`, `ID_kategori`, `nama`, `deskripsi`, `harga`, `foto`, `stok`, `terjual`, `waktuditambahkan`, `statusproduk`, `ID_discount`) VALUES
(57, 2, 'Apalah', 'Test1', 1000000, '7b774effe4a349c6dd82ad4f4f21d34c.jpeg', 999, 0, '2024-12-13', 'available', NULL),
(58, 4, 'A Chill Guy', 'Just A Chill Guy *sfx: Chill Guy Theme Music*', 123456789, '8d39dd7eef115ea6975446ef4082951f.jpg', 9997, 0, '2024-12-13', 'available', NULL),
(63, 1, 'Razer BurninSun Ergo Chair', 'Gaming Chair with Built-in Lumbar Support\r\n\r\n- Fully sculpted lumbar support\r\n- Multi-layered synthetic leather\r\n- High density foam cushions\r\n', 8500000, 'adf8db9586d0065d236cb8bf50bf2e5f.jpg', 96, 0, '0000-00-00', 'available', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `rating`
--

DROP TABLE IF EXISTS `rating`;
CREATE TABLE IF NOT EXISTS `rating` (
  `ID_rating` int NOT NULL AUTO_INCREMENT,
  `komentar` varchar(255) NOT NULL,
  `ID_transaksi` int NOT NULL,
  `rate` int NOT NULL,
  `ID_produk` int NOT NULL,
  PRIMARY KEY (`ID_rating`),
  KEY `ID_produk` (`ID_produk`),
  KEY `ID_transaksi` (`ID_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `ID_transaksi` int NOT NULL AUTO_INCREMENT,
  `ID_user` int NOT NULL,
  `order_status` enum('Confirmed','Packing Process','Delivering','Delivered','Need Rate','Done.','Canceled') NOT NULL DEFAULT 'Confirmed',
  `timestamp` datetime NOT NULL,
  `total_pembelian` int NOT NULL,
  `pengiriman` enum('ekonomi','regular','express','priority') NOT NULL DEFAULT 'ekonomi',
  `payment` enum('COD','Virtual Account') NOT NULL,
  `hargaongkir` int NOT NULL,
  PRIMARY KEY (`ID_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`ID_transaksi`, `ID_user`, `order_status`, `timestamp`, `total_pembelian`, `pengiriman`, `payment`, `hargaongkir`) VALUES
(1, 3, 'Confirmed', '2024-12-26 01:25:45', 140456789, 'ekonomi', 'COD', 16500),
(2, 3, 'Confirmed', '2024-12-26 01:36:13', 18000000, 'ekonomi', 'Virtual Account', 16500),
(3, 3, 'Confirmed', '2024-12-26 01:38:04', 124456789, 'ekonomi', 'COD', 16500);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction_details`
--

DROP TABLE IF EXISTS `transaction_details`;
CREATE TABLE IF NOT EXISTS `transaction_details` (
  `ID_detail` int NOT NULL AUTO_INCREMENT,
  `ID_transaksi` int NOT NULL,
  `ID_cart` int NOT NULL,
  PRIMARY KEY (`ID_detail`),
  KEY `ID_transaksi` (`ID_transaksi`),
  KEY `ID_cart` (`ID_cart`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `transaction_details`
--

INSERT INTO `transaction_details` (`ID_detail`, `ID_transaksi`, `ID_cart`) VALUES
(1, 0, 42),
(2, 0, 43),
(3, 0, 44),
(4, 0, 45),
(5, 3, 46),
(6, 3, 47);

-- --------------------------------------------------------

--
-- Struktur dari tabel `userdata`
--

DROP TABLE IF EXISTS `userdata`;
CREATE TABLE IF NOT EXISTS `userdata` (
  `ID_user` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` enum('Rather not say','Female','Male') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Rather not say',
  `fotouser` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pfp.png',
  `status` enum('active','blocked') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fullname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_user`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `userdata`
--

INSERT INTO `userdata` (`ID_user`, `Username`, `Email`, `Password`, `gender`, `fotouser`, `status`, `address`, `fullname`, `phone`) VALUES
(3, 'admin', 'admin@gmail.com', 'qwerty', 'Rather not say', 'pfp.png', 'active', 'asdasdw', 'atmin', '08211081209'),
(39, 'asdwadw', 'asdw@asdsw', '12345', 'Rather not say', 'monitor.jpg', 'active', 'Jl. Johar Baru IV A Gang L RT 4 RW 5 Nomor 10 A-B', 'Hafizh Laththuf Muhammad', '082110869384');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `ID_user` int NOT NULL,
  `ID_produk` int NOT NULL,
  KEY `USERWL` (`ID_user`),
  KEY `PRODUKWL` (`ID_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `wishlist`
--

INSERT INTO `wishlist` (`ID_user`, `ID_produk`) VALUES
(39, 58),
(39, 57),
(3, 58),
(3, 63),
(3, 57);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `produk_cart` FOREIGN KEY (`ID_produk`) REFERENCES `produk` (`ID_produk`),
  ADD CONSTRAINT `user_cart` FOREIGN KEY (`ID_user`) REFERENCES `userdata` (`ID_user`);

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`ID_kategori`) REFERENCES `kategori` (`ID_kategori`),
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`ID_discount`) REFERENCES `discounts` (`ID_discount`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `PRODUKWL` FOREIGN KEY (`ID_produk`) REFERENCES `produk` (`ID_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `USERWL` FOREIGN KEY (`ID_user`) REFERENCES `userdata` (`ID_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
