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
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL,
  `nama_wisata` varchar(255) NOT NULL,
  `keindahan` int(11) NOT NULL,
  `kebersihan` int(11) NOT NULL,
  `fasilitas` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `jarak` int(11) NOT NULL,
  `keamanan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama_wisata`, `keindahan`, `kebersihan`, `fasilitas`, `harga`, `jarak`, `keamanan`) VALUES
(27, 'Pantai Semeti', 5, 5, 2, 3, 4, 2),
(28, 'Pantai Tanjung Aan', 4, 3, 4, 3, 4, 4),
(30, 'Pantai Mawun', 5, 5, 5, 3, 5, 4),
(33, 'Desa Sade', 5, 4, 4, 1, 3, 4),
(34, 'Pantai Kuta', 4, 3, 3, 5, 4, 3),
(35, 'Bukit Merese', 5, 4, 3, 3, 5, 4),
(36, 'Pantai Mawi', 4, 3, 2, 5, 5, 3),
(37, 'Selong Belanak', 4, 4, 4, 5, 5, 3);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
