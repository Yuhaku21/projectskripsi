-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jun 2024 pada 09.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wisata`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria_smart`
--

CREATE TABLE `kriteria_smart` (
  `id` int(100) NOT NULL,
  `nama_wisata` varchar(200) NOT NULL,
  `keindahan` int(100) NOT NULL,
  `kebersihan` int(100) NOT NULL,
  `fasilitas` int(100) NOT NULL,
  `harga` int(100) NOT NULL,
  `jarak` int(100) NOT NULL,
  `keamanan` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria_smart`
--

INSERT INTO `kriteria_smart` (`id`, `nama_wisata`, `keindahan`, `kebersihan`, `fasilitas`, `harga`, `jarak`, `keamanan`) VALUES
(12, 'Selong Belanak', 4, 4, 4, 5, 5, 3),
(13, 'Pantai Kuta', 4, 3, 3, 5, 4, 3),
(14, 'Bukit Merese', 5, 4, 3, 3, 5, 4),
(15, 'Pantai Semeti', 5, 5, 2, 3, 4, 2),
(16, 'Pantai Tanjung Aan', 4, 3, 4, 3, 4, 4),
(18, 'Pantai Mawun', 5, 5, 5, 3, 5, 4),
(20, 'Pantai Mawi', 4, 3, 2, 5, 5, 3),
(22, 'Desa Sade', 5, 4, 4, 1, 3, 4);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kriteria_smart`
--
ALTER TABLE `kriteria_smart`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kriteria_smart`
--
ALTER TABLE `kriteria_smart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
