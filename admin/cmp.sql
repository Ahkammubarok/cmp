-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 05, 2025 at 12:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cmp`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id_akun` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(300) NOT NULL,
  `level` enum('admin','operator_barang','operator_mahasiswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id_akun`, `nama`, `username`, `email`, `password`, `level`) VALUES
(13, 'ahkam', 'ahkam', 'ahkam.mubarok@gmail.com', '$2y$10$WD9qNKEaVDimfMjXDeIQ6euyQv2zGNZ4WyWtI8nZGvz5wOF2b8GQy', 'admin'),
(16, 'Mubarok', 'admin', 'admin123@gmail.com', '$2y$10$hJLFXDl5sWrda3iSYMEw4OLmCVrCllZbyvmOwgSdLXrsiKMkbWfk6', 'admin'),
(18, 'faeyza', 'fefey', 'fey@s.com', '$2y$10$4157sweg3DOi87u43zT2R./tVa5ILmgmTSGzy6eNwyTsjulxa6WcK', 'operator_barang'),
(23, 'user', 'user', 'ahkam.mubarok@gmail.com', '$2y$10$sWIV0DD74MOWKHvALv77wuXbceSIiAP0j3gEKuqzpe4yRjcEY3WOO', 'operator_mahasiswa'),
(27, 'Mubarok', 'mubarok', 'sensasi.strike24@gmail.com', '$2y$10$dczy/QumZx67jJg1w9Y8ieBpccofbdc/cqGZOzEAXSLRL9aJCQn7u', 'admin'),
(28, 'Mahdalena', 'mahdalena', 'mahdalena@gmail.com', '$2y$10$ehcjEJmEH/k2gvgFjMQP2.jXIa.wvHDY3J6qOt5jpoOu84ZtJKlai', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `jumlah` varchar(50) NOT NULL,
  `harga` varchar(50) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `kode_barang`, `nama`, `id_kategori`, `jumlah`, `harga`, `tanggal`) VALUES
(1, 'MON-DEL-001', 'Dell 27 4K UHD Monitor - S2721QS', 2, '1', '12000000', '2025-03-08 17:51:51'),
(2, 'MON-DEL-002', 'Dell UltraSharp 27 4K USB-C Hub Monitor - U2723QE', 2, '14', '7000000', '2025-03-08 17:52:15'),
(3, 'LAP-THI-001', 'ThinkPad X1 Extreme Gen 5', 1, '4', '80000000', '2025-03-08 17:53:05'),
(4, 'LAP-THI-002', 'ThinkPad P15v Gen 3 (Intel)', 1, '2', '50000000', '2025-03-08 17:53:26'),
(6, 'MON-DEL-003', 'Dell 24566', 2, '123', '12', '2025-03-12 07:11:46');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `jk` varchar(10) NOT NULL,
  `telepon` varchar(30) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(30) NOT NULL,
  `foto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `nama`, `prodi`, `jk`, `telepon`, `alamat`, `email`, `foto`) VALUES
(62, 'dsdsds', 'Teknik Informatika', 'Laki-laki', '08119233435', '&lt;p&gt;wewewe&lt;/p&gt;', 'ahkam.mubarok@gmail.com', '67d3f21da84f3.jpg'),
(63, 'Fefey', 'Teknik Informatika', 'Laki-laki', '08119233435', '&lt;p&gt;tes&lt;/p&gt;', 'ahkam.mubarok@gmail.com', '67d3f3910c43d.png'),
(64, 'sdgdfsfds', 'Sistem Informasi', 'Laki-laki', '08119233435', '&lt;p&gt;wdwdwd&lt;/p&gt;', 'ahkam.mubarok@gmail.com', '67d3f5a4c111c.png'),
(65, 's', 'Sistem Informasi', 'Laki-laki', '1', '&lt;p&gt;jakarta bagian bogor&lt;/p&gt;', 'ucup@gmail.com', '67d78f1e358f4.png');

-- --------------------------------------------------------

--
-- Table structure for table `misi`
--

CREATE TABLE `misi` (
  `id_misi` int(11) NOT NULL,
  `judul_misi` varchar(50) NOT NULL,
  `isi_misi` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `misi`
--

INSERT INTO `misi` (`id_misi`, `judul_misi`, `isi_misi`, `time`) VALUES
(1, 'Misi Sempurna', 'isi misi 2024', '2025-03-06 02:58:17'),
(2, 'Misi Kemanusiaan', '<p>tahun2025</p>', '2025-03-06 02:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `sejarah`
--

CREATE TABLE `sejarah` (
  `id_sejarah` int(11) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `isi` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sejarah`
--

INSERT INTO `sejarah` (`id_sejarah`, `judul`, `isi`, `time`) VALUES
(2, 'Sejarah Berdiri', '<p>Berdiri pada tahun 198012</p>', '2025-03-05 16:29:08'),
(11, 'sejarah islam', '<p>abad ke 1</p>', '2025-03-05 17:15:14');

-- --------------------------------------------------------

--
-- Table structure for table `visi`
--

CREATE TABLE `visi` (
  `id_visi` int(11) NOT NULL,
  `judul_visi` varchar(50) NOT NULL,
  `isi_visi` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visi`
--

INSERT INTO `visi` (`id_visi`, `judul_visi`, `isi_visi`, `time`) VALUES
(2, 'VISI KEehidupan', '<p>semnagat</p>', '2025-03-05 16:56:23'),
(3, 'visi kepemimpinan', '<p>pemimpin yang hebat</p>', '2025-03-05 17:15:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id_akun`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD KEY `barang_ibfk_1` (`id_kategori`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`);

--
-- Indexes for table `misi`
--
ALTER TABLE `misi`
  ADD PRIMARY KEY (`id_misi`);

--
-- Indexes for table `sejarah`
--
ALTER TABLE `sejarah`
  ADD PRIMARY KEY (`id_sejarah`);

--
-- Indexes for table `visi`
--
ALTER TABLE `visi`
  ADD PRIMARY KEY (`id_visi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `misi`
--
ALTER TABLE `misi`
  MODIFY `id_misi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sejarah`
--
ALTER TABLE `sejarah`
  MODIFY `id_sejarah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `visi`
--
ALTER TABLE `visi`
  MODIFY `id_visi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
