-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Agu 2024 pada 20.19
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pemancingan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ikan`
--

CREATE TABLE `ikan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `ukuran` decimal(5,2) DEFAULT NULL,
  `kolam_id` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `habitat` varchar(255) DEFAULT NULL,
  `makanan` varchar(255) DEFAULT NULL,
  `berat_rata_rata` decimal(5,2) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kapasitas` int(111) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kapasitas_ikan`
--

CREATE TABLE `kapasitas_ikan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `ukuran` decimal(5,2) DEFAULT NULL,
  `kolam_id` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kolam`
--

CREATE TABLE `kolam` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `jumlah_pengunjung` int(11) DEFAULT 0,
  `jumlah_ikan` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `jenis_laporan` enum('bulanan','triwulanan','tahunan') DEFAULT NULL,
  `periode_awal` date DEFAULT NULL,
  `periode_akhir` date DEFAULT NULL,
  `total_pengunjung` int(11) DEFAULT NULL,
  `total_ikan_ditangkap` int(11) DEFAULT NULL,
  `total_pendapatan` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_keuangan`
--

CREATE TABLE `laporan_keuangan` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `total_pemasukan` decimal(10,2) NOT NULL,
  `total_pengeluaran` decimal(10,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `laba` decimal(10,2) NOT NULL DEFAULT 0.00,
  `laba_kotor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `laba_bersih` decimal(10,2) NOT NULL DEFAULT 0.00,
  `biaya_operasional` decimal(10,2) DEFAULT 0.00,
  `biaya_non_operasional` decimal(10,2) DEFAULT 0.00,
  `pendapatan_non_operasional` decimal(10,2) DEFAULT 0.00,
  `total_harga` decimal(10,2) DEFAULT 0.00,
  `kode_pesanan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi`
--

CREATE TABLE `lokasi` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `jumlah_pengunjung` int(11) DEFAULT 0,
  `rekomendasi` varchar(255) DEFAULT 'Tidak ada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `momen`
--

CREATE TABLE `momen` (
  `id` int(11) NOT NULL,
  `kesan_pesan` text NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `objek_wisata`
--

CREATE TABLE `objek_wisata` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `kapasitas_pengunjung` int(11) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan_kuliner`
--

CREATE TABLE `pemesanan_kuliner` (
  `id` int(11) NOT NULL,
  `pengunjung_id` int(11) DEFAULT NULL,
  `kuliner_id` int(11) DEFAULT NULL,
  `tanggal_pemesanan` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT NULL,
  `pemesanan_id` int(11) DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan_tiket`
--

CREATE TABLE `pemesanan_tiket` (
  `id` int(11) NOT NULL,
  `pengunjung_id` int(11) DEFAULT NULL,
  `tanggal_pemesanan` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT 0.00,
  `kolam_id` int(11) DEFAULT NULL,
  `kolam` varchar(255) DEFAULT NULL,
  `status_pemesanan` enum('Dalam Proses','Disetujui','Ditolak') DEFAULT 'Dalam Proses',
  `status_nota` enum('Belum Dikirim','Dikirim') DEFAULT 'Belum Dikirim',
  `kode_pesanan` varchar(32) DEFAULT NULL,
  `jumlah_pengunjung` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemetaan_pemancingan`
--

CREATE TABLE `pemetaan_pemancingan` (
  `id` int(11) NOT NULL,
  `nama_kolam` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penangkapan_ikan`
--

CREATE TABLE `penangkapan_ikan` (
  `id` int(11) NOT NULL,
  `pengunjung_id` int(11) DEFAULT NULL,
  `ikan_id` int(11) DEFAULT NULL,
  `tanggal_penangkapan` date DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `pemasukan` decimal(10,2) DEFAULT 0.00,
  `biaya_operasional` decimal(10,2) DEFAULT 0.00,
  `biaya_non_operasional` decimal(10,2) DEFAULT 0.00,
  `pendapatan_non_operasional` decimal(10,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengunjung`
--

CREATE TABLE `pengunjung` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `umur` int(3) DEFAULT NULL,
  `role` enum('admin','pengunjung') DEFAULT NULL,
  `tanggal_daftar` date NOT NULL DEFAULT curdate(),
  `bulan_pemesanan_kuliner` varchar(7) DEFAULT NULL,
  `notifikasi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengunjung_kuliner`
--

CREATE TABLE `pengunjung_kuliner` (
  `id` int(11) NOT NULL,
  `pengunjung_id` int(11) NOT NULL,
  `nama_kuliner` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `bulan_pemesanan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil_objek_wisata`
--

CREATE TABLE `profil_objek_wisata` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `jam_buka` varchar(100) DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `kontak` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `wisata_kuliner`
--

CREATE TABLE `wisata_kuliner` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ikan`
--
ALTER TABLE `ikan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kolam_id` (`kolam_id`);

--
-- Indeks untuk tabel `kapasitas_ikan`
--
ALTER TABLE `kapasitas_ikan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kolam`
--
ALTER TABLE `kolam`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`);

--
-- Indeks untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `momen`
--
ALTER TABLE `momen`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `objek_wisata`
--
ALTER TABLE `objek_wisata`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pemesanan_kuliner`
--
ALTER TABLE `pemesanan_kuliner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengunjung_id` (`pengunjung_id`),
  ADD KEY `kuliner_id` (`kuliner_id`);

--
-- Indeks untuk tabel `pemesanan_tiket`
--
ALTER TABLE `pemesanan_tiket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD KEY `pemesanan_tiket_ibfk_1` (`pengunjung_id`),
  ADD KEY `fk_kolam` (`kolam_id`);

--
-- Indeks untuk tabel `pemetaan_pemancingan`
--
ALTER TABLE `pemetaan_pemancingan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penangkapan_ikan`
--
ALTER TABLE `penangkapan_ikan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ikan_id` (`ikan_id`),
  ADD KEY `penangkapan_ikan_ibfk_1` (`pengunjung_id`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengunjung`
--
ALTER TABLE `pengunjung`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengunjung_kuliner`
--
ALTER TABLE `pengunjung_kuliner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengunjung_id` (`pengunjung_id`);

--
-- Indeks untuk tabel `profil_objek_wisata`
--
ALTER TABLE `profil_objek_wisata`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `wisata_kuliner`
--
ALTER TABLE `wisata_kuliner`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `ikan`
--
ALTER TABLE `ikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kapasitas_ikan`
--
ALTER TABLE `kapasitas_ikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kolam`
--
ALTER TABLE `kolam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `momen`
--
ALTER TABLE `momen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `objek_wisata`
--
ALTER TABLE `objek_wisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pemesanan_kuliner`
--
ALTER TABLE `pemesanan_kuliner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pemesanan_tiket`
--
ALTER TABLE `pemesanan_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pemetaan_pemancingan`
--
ALTER TABLE `pemetaan_pemancingan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penangkapan_ikan`
--
ALTER TABLE `penangkapan_ikan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengunjung`
--
ALTER TABLE `pengunjung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengunjung_kuliner`
--
ALTER TABLE `pengunjung_kuliner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `profil_objek_wisata`
--
ALTER TABLE `profil_objek_wisata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `wisata_kuliner`
--
ALTER TABLE `wisata_kuliner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `ikan`
--
ALTER TABLE `ikan`
  ADD CONSTRAINT `ikan_ibfk_1` FOREIGN KEY (`kolam_id`) REFERENCES `pemetaan_pemancingan` (`id`);

--
-- Ketidakleluasaan untuk tabel `pemesanan_kuliner`
--
ALTER TABLE `pemesanan_kuliner`
  ADD CONSTRAINT `pemesanan_kuliner_ibfk_1` FOREIGN KEY (`pengunjung_id`) REFERENCES `pengunjung` (`id`),
  ADD CONSTRAINT `pemesanan_kuliner_ibfk_2` FOREIGN KEY (`kuliner_id`) REFERENCES `wisata_kuliner` (`id`);

--
-- Ketidakleluasaan untuk tabel `pemesanan_tiket`
--
ALTER TABLE `pemesanan_tiket`
  ADD CONSTRAINT `fk_kolam` FOREIGN KEY (`kolam_id`) REFERENCES `kolam` (`id`),
  ADD CONSTRAINT `pemesanan_tiket_ibfk_1` FOREIGN KEY (`pengunjung_id`) REFERENCES `pengunjung` (`id`);

--
-- Ketidakleluasaan untuk tabel `penangkapan_ikan`
--
ALTER TABLE `penangkapan_ikan`
  ADD CONSTRAINT `penangkapan_ikan_ibfk_1` FOREIGN KEY (`pengunjung_id`) REFERENCES `pengunjung` (`id`),
  ADD CONSTRAINT `penangkapan_ikan_ibfk_2` FOREIGN KEY (`ikan_id`) REFERENCES `ikan` (`id`);

--
-- Ketidakleluasaan untuk tabel `pengunjung_kuliner`
--
ALTER TABLE `pengunjung_kuliner`
  ADD CONSTRAINT `pengunjung_kuliner_ibfk_1` FOREIGN KEY (`pengunjung_id`) REFERENCES `pengunjung` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
