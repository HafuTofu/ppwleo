-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 26, 2024 at 06:40 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

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
-- Table structure for table `cart`
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
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`ID_cart`, `ID_user`, `ID_produk`, `qty`, `total_harga`, `checkorno`) VALUES
(41, NULL, NULL, 1, NULL, 'unchecked'),
(42, NULL, NULL, 1, NULL, 'unchecked'),
(43, NULL, NULL, 1, NULL, 'unchecked'),
(44, NULL, NULL, 1, NULL, 'unchecked'),
(45, NULL, NULL, 1, NULL, 'unchecked'),
(46, NULL, NULL, 1, NULL, 'unchecked'),
(47, NULL, NULL, 1, NULL, 'unchecked'),
(48, NULL, NULL, 1, NULL, 'unchecked'),
(49, NULL, NULL, 1, NULL, 'unchecked'),
(50, NULL, NULL, 1, NULL, 'unchecked'),
(51, NULL, NULL, 1, NULL, 'unchecked'),
(52, NULL, NULL, 1, NULL, 'unchecked'),
(53, NULL, NULL, 1, NULL, 'unchecked'),
(54, NULL, NULL, 1, NULL, 'unchecked'),
(55, NULL, NULL, 1, NULL, 'unchecked'),
(56, NULL, NULL, 1, NULL, 'unchecked'),
(57, NULL, NULL, 1, NULL, 'unchecked'),
(58, NULL, NULL, 1, NULL, 'unchecked'),
(59, NULL, NULL, 1, NULL, 'unchecked'),
(60, NULL, NULL, 1, NULL, 'unchecked'),
(61, NULL, NULL, 1, NULL, 'unchecked'),
(62, NULL, NULL, 1, NULL, 'unchecked'),
(63, NULL, NULL, 1, NULL, 'unchecked'),
(64, NULL, NULL, 1, NULL, 'unchecked'),
(65, NULL, NULL, 1, NULL, 'unchecked'),
(66, NULL, NULL, 1, NULL, 'unchecked'),
(67, NULL, NULL, 1, NULL, 'unchecked'),
(68, NULL, NULL, 1, NULL, 'unchecked'),
(69, NULL, NULL, 1, NULL, 'unchecked'),
(70, NULL, NULL, 1, NULL, 'unchecked'),
(71, NULL, NULL, 1, NULL, 'unchecked'),
(72, NULL, NULL, 1, NULL, 'unchecked'),
(73, NULL, NULL, 1, NULL, 'unchecked'),
(74, NULL, NULL, 1, NULL, 'unchecked'),
(75, NULL, NULL, 1, NULL, 'unchecked'),
(76, NULL, NULL, 1, NULL, 'unchecked'),
(77, NULL, NULL, 1, NULL, 'unchecked'),
(78, NULL, NULL, 1, NULL, 'unchecked'),
(79, NULL, NULL, 1, NULL, 'unchecked'),
(80, NULL, NULL, 1, NULL, 'unchecked'),
(81, NULL, NULL, 1, NULL, 'unchecked'),
(82, 39, 64, 2, 3400000, 'checked'),
(83, 39, 67, 1, 329000, 'checked'),
(84, 39, 72, 2, 50000, 'checked'),
(85, 39, 61, 2, 1440000, 'checked');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
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
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `ID_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`ID_kategori`, `nama_kategori`) VALUES
(1, 'Gaming'),
(2, 'Food'),
(4, 'Clothes'),
(6, 'Beauty');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

DROP TABLE IF EXISTS `produk`;
CREATE TABLE IF NOT EXISTS `produk` (
  `ID_produk` int NOT NULL AUTO_INCREMENT,
  `ID_kategori` int DEFAULT NULL,
  `nama` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `deskripsi` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `foto` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stok` int DEFAULT NULL,
  `terjual` int DEFAULT NULL,
  `waktuditambahkan` date NOT NULL,
  `statusproduk` enum('available','unavailable') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'available',
  `ID_discount` int DEFAULT NULL,
  PRIMARY KEY (`ID_produk`),
  KEY `nama` (`nama`),
  KEY `ID_kategori` (`ID_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`ID_produk`, `ID_kategori`, `nama`, `deskripsi`, `harga`, `foto`, `stok`, `terjual`, `waktuditambahkan`, `statusproduk`, `ID_discount`) VALUES
(59, 4, 'Mercedes F1 2024 Team Driver Tee White', 'The official 2024 Replica Team Driver T-shirt, as worn by Lewis and George. Features the same tonal hexagonal print seen throughout the 2024 Team collection alongside all the official team and sponsor branding from the 2024 Formula 1 season.', 838100, 'e1e1d3d40573127e9ee0480caf1283d6.png', 1, 0, '0000-00-00', 'available', NULL),
(60, 4, 'Mercedes F1 2024 Team Polo Black', 'The official 2024 Replica Team Polo, with traditional button collar placket. Features the same tonal hexagonal print seen throughout the 2024 Team collection alongside all the official team and sponsor branding from the 2024 Formula 1 season.', 1204000, 'f616c83f2f0f188265c7004d81d45723.png', 1, 0, '0000-00-00', 'available', NULL),
(61, 4, 'Oracle Red Bull SIM Racing Jersey', 'Get a fresh look for your next race in the new Oracle Red Bull Sim Racing Jersey by New Era. Featuring team and partner branding, together with a dynamic new design,  just like your favourite Sim Racing and Esports heroes.', 720000, 'f1290186a5d0b1ceab27f4e77c0c5d68.png', 8, 2, '0000-00-00', 'available', NULL),
(62, 4, 'Oracle Red Bull Racing Sport Hoodie', 'Sport a fresh look from the track to the pitch in this comfortable Oracle Red Bull Racing pullover hoodie by Castore, featuring team branding on the chest, statement yellow stripes, and a classic kangaroo pocket to keep your hands cosy!', 1420000, 'ec1f53aa1e538b2c3b581674c6585857.png', 27, 0, '0000-00-00', 'available', NULL),
(63, 4, 'Scuderia Ferrari Team Varsity Jacket Men', 'This iconic piece embodies the spirit of one of the most prestigious teams in the world, combining a passion for innovation with bold design. It proudly displays the emblemati.', 1720000, '85a46a5de17b19ac7eb29cacce9cbb04.png', 23, 0, '0000-00-00', 'available', NULL),
(64, 4, 'T1 Worlds 2024 Jacket', 'Feel like a Champions by using this jacket.', 1700000, 'dd7536794b63bf90eccfd37f9b147d7f.png', 17, 2, '0000-00-00', 'available', NULL),
(65, 4, 'Scuderia Ferrari Race MT7 Tee', 'This race-inspired t-shirt oozes Scuderia Ferrari class, style, and performance. The regular-fit men shirt features a prominent Scuderia Ferrari shield on the front along with track-inspired piping on the shoulders.', 415000, '7b8b965ad4bca0e41ab51de7b31363a1.png', 17, 0, '0000-00-00', 'available', NULL),
(67, 1, 'Mousepad G440', 'Permukaan yang keras menghadirkan gesekan rendah dan pergerakan mouse yang cepat dan mulus. Alas karet tetap di tempatnya untuk gaming yang menegangkan. Tekstur permukaannya dioptimalkan untuk mouse Logitech G dan kinerja puncak gaming.', 329000, 'dfcf28d0734569a6a693bc8194de62bf.png', 142, 1, '0000-00-00', 'available', NULL),
(68, 1, 'Mousepad G840', 'Full desktop gaming mouse pad dengan ruang untuk mengonfigurasikan penataan sesuai yang kamu inginkan. Tekstur permukaannya sudah disetel untuk mouse Logitech G. Alat karet tetap di tempatnya agar kamu bisa fokus dan mengontrol game.', 699000, 'a5f3c6a11b03839d46af9fb43c97c188.png', 144, 0, '0000-00-00', 'available', NULL),
(69, 1, 'PRO X 2 Lightspeed', 'Dirancang bersama pemain pro untuk level kompetisi tertinggi.', 3999000, 'b99834bc19bbad24580b3adfa04fb947.png', 145, 0, '0000-00-00', 'available', NULL),
(70, 1, 'Logitech G733', 'Gaming headset wireless didesain untuk kinerja dan kenyamanan. Dilengkapi dengan surround sound, filter suara, dan pencahayaan terbaik yang kamu perlukan untuk terlihat, terdengar, dan bermain dengan lebih keren.', 1749000, 'afb990cbf2d0e0a9ae75ba2f2f3bb013.png', 134, 0, '0000-00-00', 'available', NULL),
(71, 2, 'Samyang Hot Chicken Ramen Cheese ', 'Mie Goreng asal Korea dengan cita rasa yang pedas namun ada sensasi rasa keju yang nikmat untuk disantap.', 25000, 'f95b70fdc3088560732a5ac135644506.png', 190, 0, '0000-00-00', 'available', NULL),
(72, 2, 'Samyang Extra Hot Chicken Flavor Ramen ', 'Samyang extra Hot Chicken Ramen Produk Mie Instant berkualitas dari Samyang dengan rasa extra hot chicken yang bikin mata jadi melek.', 25000, '348dd9e974848a61e8abe2dffcfcce93.png', 17, 2, '0000-00-00', 'available', NULL),
(73, 2, 'SAMYANG HOT CHIKEN RAMEN CARBONARA', 'Samyang Hot Chiken Ramen Carbonara Pack 130 Grmie Instan Dengan Cita Rasa Pedas Yang Gurih Dan Lezat.', 25000, '6bc1af258475ec126d1be66e6bee873a.png', 59, 0, '0000-00-00', 'available', NULL),
(74, 2, 'Samyang Hot Chicken Ramen', 'Samyang Hot Chicken Ramen Produk Mie Instant berkualitas kehigienisannya sudah teruji cocok dikonsumsi untuk kalian yang sendiri.', 23000, '7215ee9c7d9dc229d2921a40e899ec5f.png', 64, 0, '0000-00-00', 'available', NULL),
(75, 2, 'SAMYANG HOT CHICKEN RAMEN JJAJANG', 'Samyang Hot Chicken Ramen Jjajang, mi instan pedas dengan saus hitam khas Korea yang lezat.', 25000, '32b401960154e86d27ba06c6b9d3408c.png', 37, 0, '0000-00-00', 'available', NULL),
(76, 6, 'Kahf Invisible Matte Sunscreen Stick SPF 50 PA++++ 22 g', 'Kahf Invisibile Matte Sunscreen Stick 22 g merupakan sunscreen stick dengan SPF 50 PA++++ yang melindungi kulit dari sinar UV untuk menjaga kecerahan dan menenangkan kemerahan kulit.', 90000, '4dcde376fbc212f73c0b00b7909fc4cf.png', 52, 0, '0000-00-00', 'available', NULL),
(77, 6, 'Kahf Oil and Acne Care Face Wash 100 ml - MEN FACIAL WASH', 'Kahf Oil and Acne Care Face Wash merupakan sabun pembersih wajah dengan kandungan HydroBalance dan Pure Cleanse menjadikan wajah bersih dan lembap secara menyeluruh. Diperkaya dengan ekstrak Mediterranean Sage dan French Cypressnya yang dapat membantu melawan jerawat. Cocok digunakan untuk jenis kulit wajah berminyak dan berjerawat.', 34000, '853ae90f0351324bd73ea615e6487517.png', 37, 0, '0000-00-00', 'available', NULL),
(78, 6, 'Kahf Skin Energizing & Brightening Face Wash 100 ml - MEN FACIAL WASH', 'Kahf Skin Energizing and Brightening Face Wash merupakan sabun pembersih wajah dengan kombinasi HydroBalanceTM dan Pure Cleanse yang dapat menjadikan kulit wajah bersih dan lembap secara menyeluruh hingga ke pori-pori. Diperkaya dengan ekstrak Moroccan Mint dan Mediterranean GrapefruiT.', 34000, 'edb907361219fb8d50279eabab0b83b1.png', 29, 0, '0000-00-00', 'available', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

DROP TABLE IF EXISTS `rating`;
CREATE TABLE IF NOT EXISTS `rating` (
  `ID_rating` int NOT NULL AUTO_INCREMENT,
  `komentar` varchar(255) NOT NULL,
  `ID_transaksi` int NOT NULL,
  `rate` int NOT NULL,
  PRIMARY KEY (`ID_rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `ID_transaksi` int NOT NULL AUTO_INCREMENT,
  `ID_user` int NOT NULL,
  `order_status` enum('Confirmed','Packing Process','Delivering','Delivered','Need Rate','Done','Canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Confirmed',
  `timestamp` datetime NOT NULL,
  `total_pembelian` int NOT NULL,
  `pengiriman` enum('ekonomi','regular','express','priority') NOT NULL DEFAULT 'ekonomi',
  `payment` enum('COD','Virtual Account','','') NOT NULL,
  `hargaongkir` int NOT NULL,
  PRIMARY KEY (`ID_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`ID_transaksi`, `ID_user`, `order_status`, `timestamp`, `total_pembelian`, `pengiriman`, `payment`, `hargaongkir`) VALUES
(1, 39, 'Canceled', '2024-12-26 08:33:38', 5219000, 'ekonomi', 'COD', 16500);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

DROP TABLE IF EXISTS `transaction_details`;
CREATE TABLE IF NOT EXISTS `transaction_details` (
  `ID_detail` int NOT NULL AUTO_INCREMENT,
  `ID_transaksi` int NOT NULL,
  `ID_cart` int NOT NULL,
  PRIMARY KEY (`ID_detail`),
  KEY `ID_transaksi` (`ID_transaksi`),
  KEY `ID_cart` (`ID_cart`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`ID_detail`, `ID_transaksi`, `ID_cart`) VALUES
(7, 1, 82),
(8, 1, 83),
(9, 1, 84),
(10, 1, 85);

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

DROP TABLE IF EXISTS `userdata`;
CREATE TABLE IF NOT EXISTS `userdata` (
  `ID_user` int NOT NULL AUTO_INCREMENT,
  `Username` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gender` enum('Rather not say','Female','Male') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Rather not say',
  `fotouser` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pfp.png',
  `status` enum('active','blocked') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fullname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`ID_user`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userdata`
--

INSERT INTO `userdata` (`ID_user`, `Username`, `Email`, `Password`, `gender`, `fotouser`, `status`, `address`, `fullname`, `phone`) VALUES
(3, 'admin', 'admin@gmail.com', 'qwerty', 'Rather not say', 'pfp.png', 'active', 'asd', '', ''),
(39, 'asdwadw', 'asdw@asdsw', '12345', 'Rather not say', 'monitor.jpg', 'active', 'Jl. Johar Baru IV A Gang L RT 4 RW 5 Nomor 10 A-B', 'Hafizh Laththuf Muhammad', '082110869384');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `ID_user` int NOT NULL,
  `ID_produk` int NOT NULL,
  KEY `USERWL` (`ID_user`),
  KEY `PRODUKWL` (`ID_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`ID_user`, `ID_produk`) VALUES
(39, 60);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `produk_cart` FOREIGN KEY (`ID_produk`) REFERENCES `produk` (`ID_produk`),
  ADD CONSTRAINT `user_cart` FOREIGN KEY (`ID_user`) REFERENCES `userdata` (`ID_user`);

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`ID_kategori`) REFERENCES `kategori` (`ID_kategori`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `PRODUKWL` FOREIGN KEY (`ID_produk`) REFERENCES `produk` (`ID_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `USERWL` FOREIGN KEY (`ID_user`) REFERENCES `userdata` (`ID_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
