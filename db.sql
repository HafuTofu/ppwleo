-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Waktu pembuatan: 13 Des 2024 pada 21.00
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
  PRIMARY KEY (`ID_cart`),
  KEY `user_cart` (`ID_user`),
  KEY `produk_cart` (`ID_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cart`
--

INSERT INTO `cart` (`ID_cart`, `ID_user`, `ID_produk`, `qty`, `total_harga`) VALUES
(1, 39, 56, 2, 2181804),
(2, 39, 57, 1, 1090902);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `ID_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`ID_kategori`, `nama_kategori`) VALUES
(1, 'Gaming'),
(2, 'Food'),
(3, 'Topup'),
(4, 'Clothes');

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
  PRIMARY KEY (`ID_produk`),
  KEY `nama` (`nama`),
  KEY `ID_kategori` (`ID_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`ID_produk`, `ID_kategori`, `nama`, `deskripsi`, `harga`, `foto`, `stok`, `terjual`, `waktuditambahkan`) VALUES
(56, 1, 'Kentut', 'Kentut bau basmah', 1090902, '7b774effe4a349c6dd82ad4f4f21d34c.jpeg', 110, 0, '2024-12-13'),
(57, 1, 'Kentut', 'Kentut bau basmah', 1090902, 'dd7536794b63bf90eccfd37f9b147d7f.jpeg', 1000, 0, '2024-12-13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE IF NOT EXISTS `ratings` (
  `ID_rating` int NOT NULL AUTO_INCREMENT,
  `ID_produk` int NOT NULL,
  `ID_user` int NOT NULL,
  `rate` smallint NOT NULL,
  `ID_transaksi` int NOT NULL,
  PRIMARY KEY (`ID_rating`),
  UNIQUE KEY `ID_transaksi` (`ID_transaksi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `ID_transaksi` int NOT NULL AUTO_INCREMENT,
  `ID_produk` int NOT NULL,
  `ID_user` int NOT NULL,
  `order_status` enum('Waiting Confirmation','On Process','On Delivery','Delivered','Rate This Product') NOT NULL DEFAULT 'Waiting Confirmation',
  PRIMARY KEY (`ID_transaksi`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(3, 'admin', 'admin@gmail.com', 'qwerty', 'Rather not say', 'pfp.png', 'active', '', '', ''),
(39, 'asdwadw', 'asdw@asdsw', '123', 'Rather not say', 'monitor.jpg', 'active', '', '', '');

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
(39, 57),
(39, 56);

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
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`ID_kategori`) REFERENCES `kategori` (`ID_kategori`);

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
