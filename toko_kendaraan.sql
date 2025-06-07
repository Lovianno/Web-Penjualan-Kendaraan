-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 07, 2025 at 01:09 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_kendaraan`
--

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` int NOT NULL,
  `nama` varchar(150) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `merek` varchar(100) NOT NULL,
  `tipe` varchar(100) NOT NULL,
  `tahun` int NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `harga` bigint NOT NULL,
  `status` tinyint NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kendaraan`
--

INSERT INTO `kendaraan` (`id`, `nama`, `kategori`, `merek`, `tipe`, `tahun`, `deskripsi`, `gambar`, `harga`, `status`, `user_id`) VALUES
(1, 'Avanza G', 'Mobil', 'Toyota', 'MPV', 2021, 'Mobil keluarga irit dan nyaman', '6830265731207.jpg', 210000000, 1, 2),
(2, 'Beat Street', 'Motor', 'Honda', 'Skuter', 2022, 'Motor matic irit BBM dan lincah', '6830265fd05ca.jpg', 17000000, 1, 1),
(3, 'Vario 160', 'Motor', 'Honda', 'Skuter', 2023, 'Desain sporty dan bertenaga', '683025034e589.jpg', 26500000, 1, 1),
(4, 'Xpander Cross', 'Mobil', 'Mitsubishi', 'SUV', 2022, 'SUV keluarga tangguh dan nyaman', '683026b6b82e5.jpg', 310000000, 1, 1),
(5, 'Fortuner VRZ', 'Mobil', 'Toyota', 'SUV', 2023, 'SUV premium dengan fitur lengkap', '68302692ada1f.jpg', 590000000, 1, 1),
(6, 'Brio Satya', 'Mobil', 'Honda', 'Hatchback', 2021, 'Mobil kecil irit dan cocok di perkotaan', '6830266808e3f.jpg', 165000000, 1, 1),
(7, 'Ninja 250', 'Motor', 'Kawasaki', 'Sport', 2022, 'Motor sport dengan performa tinggi', '683024d290b0a.jpg', 69000000, 1, 1),
(8, 'Yaris GR', 'Mobil', 'Toyota', 'Hatchback', 2023, 'Mobil sporty dengan mesin turbo', '683026cbb448f.jpg', 450000000, 1, 1),
(9, 'CB150X', 'Motor', 'Honda', 'Sport Touring', 2022, 'Motor touring tangguh dan nyaman', '6830267203f62.jpg', 32000000, 1, 1),
(10, 'Ertiga Hybrid', 'Mobil', 'Suzuki', 'MPV', 2023, 'Mobil MPV hybrid ramah lingkungan', '6830268acad6b.jpg', 280000000, 1, 1),
(11, 'Aerox 155', 'Motor', 'Yamaha', 'Skuter Sport', 2021, 'Skuter sport untuk anak muda', '6830264f93bec.jpg', 24500000, 1, 1),
(12, 'CR-V Turbo', 'Mobil', 'Honda', 'SUV', 2023, 'SUV premium turbocharged', '683026832fb3c.jpg', 550000000, 1, 1),
(13, 'Raize GR', 'Mobil', 'Toyota', 'SUV', 2022, 'SUV compact dengan desain stylish', '683024c5bdb92.jpg', 265000000, 1, 1),
(15, 'XSR 155', 'Motor', 'Yamaha', 'Cafe Racer', 2022, 'Gaya retro dengan mesin modern', '683026c0e969c.jpg', 36500000, 1, 1),
(16, 'Innova Zenix', 'Mobil', 'Toyota', 'MPV Hybrid', 2023, 'MPV hybrid terbaru dari Toyota', '6830269c6ca5d.jpg', 460000000, 1, 1),
(17, 'Civic RS', 'Mobil', 'Honda', 'Sedan Sport', 2023, 'Sedan premium dan sporty', '68302679ebf0d.jpg', 600000000, 1, 1),
(18, 'Scoopy', 'Motor', 'Honda', 'Skuter Retro', 2022, 'Motor retro stylish dan irit', '683024dc8bcf6.jpg', 22500000, 1, 1),
(19, 'XL7 Alpha', 'Mobil', 'Suzuki', 'SUV', 2021, 'SUV stylish dan tangguh', '683026ab67721.jpg', 305000000, 1, 1),
(21, 'Seal', 'Mobil', 'BYD', 'EV', 2024, 'Mobil Listrik ramah lingkungan', '6830106d1a9d0.jpg', 650000000, 1, 1),
(22, 'GT3 RS', 'Mobil', 'Porsche', 'Sport', 2024, 'Kenceng', '68306de66386e.jpg', 500000000, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `no_telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `password`, `no_telepon`) VALUES
(1, 'Lovianno', 'admin@gmail.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '085335599874'),
(2, 'naufal', 'mitra@gmail.com', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '085974174541'),
(3, 'Andrean', 'andre@gmail.com', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', '082132642548'),
(4, 'Feri', 'feri@gmail.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', '0874658447569');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD CONSTRAINT `kendaraan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
