-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2019 at 08:41 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `masbuq_avoider`
--

-- --------------------------------------------------------

--
-- Table structure for table `masjid`
--

CREATE TABLE `masjid` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `timezone` varchar(100) NOT NULL,
  `latitude` text NOT NULL,
  `longitude` text NOT NULL,
  `kode_kota` varchar(3) NOT NULL,
  `jarak_azan_iqamah` int(2) NOT NULL,
  `waktu_mulai_iqamah` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `masjid`
--

INSERT INTO `masjid` (`id`, `nama`, `alamat`, `timezone`, `latitude`, `longitude`, `kode_kota`, `jarak_azan_iqamah`, `waktu_mulai_iqamah`) VALUES
(1, 'Masjid Ilman Hadid', 'Jl. Kanayakan No.21, Dago, Coblong, Kota Bandung, Jawa Barat 40135', 'Asia/Jakarta', '-6.8771298', '107.619862', '679', 15, '14:00:00'),
(2, 'Masjid Raya Al-Ihsan', 'Jl. Ir. H.Djuanda No.283, Dago, Coblong, Kota Bandung, Jawa Barat 40135', 'Asia/Jakarta', '-6.8792905', '107.6164539', '679', 17, '00:00:00'),
(3, 'Masjid Al-Latief', 'Jl. Kanayakan Blk. A, Dago, Coblong, Kota Bandung, Jawa Barat 40135', 'Asia/Jakarta', '-6.8793528', '107.6180042', '679', 20, '00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `masjid`
--
ALTER TABLE `masjid`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `masjid`
--
ALTER TABLE `masjid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
