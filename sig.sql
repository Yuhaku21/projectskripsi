-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jun 2024 pada 09.30
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
-- Struktur dari tabel `sig`
--

CREATE TABLE `sig` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sig`
--

INSERT INTO `sig` (`id`, `nama`, `latitude`, `longitude`) VALUES
(7, 'Selong Belanak', -8.87303490, 116.16243840),
(8, 'Pantai Mawun', -8.90214480, 116.22416170),
(9, 'Bukit Merese', -8.91392990, 116.31643140),
(10, 'Pantai Tanjung Aan', -8.91046740, 116.31916850),
(11, 'Pantai Semeti', -8.89150410, 116.15623000),
(12, 'Pantai Kuta', -8.89475160, 116.28317030),
(13, 'Pantai Mawi', -8.88333140, 116.13936360),
(15, 'Desa Sade', -8.83930290, 116.28941220),
(16, 'Desa Sade', -8.83930290, 116.28941220);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `sig`
--
ALTER TABLE `sig`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `sig`
--
ALTER TABLE `sig`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
