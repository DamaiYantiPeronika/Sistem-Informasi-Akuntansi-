-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2025 at 10:18 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tobing`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id_akun` int(11) NOT NULL,
  `nama_akun` varchar(150) NOT NULL,
  `no_akun` int(20) NOT NULL,
  `saldo_normal` varchar(150) NOT NULL,
  `jenis_akun` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id_akun`, `nama_akun`, `no_akun`, `saldo_normal`, `jenis_akun`) VALUES
(21, 'Kas', 1011, 'Debit', 'Aktiva Lancar'),
(22, 'Piutang Usaha', 1012, 'Debit', 'Aktiva Lancar'),
(23, 'Persediaan Barang Dagang', 1013, 'Debit', 'Aktiva Lancar'),
(24, 'Bahan Habis Pakai', 1014, 'Debit', 'Aktiva Lancar'),
(25, 'Kendaraan', 1021, 'Debit', 'Aktiva Tetap'),
(26, 'Akumulasi Penyusutan Kendaraan', 1022, 'Debit', 'Aktiva Tetap'),
(27, 'Utang Usaha', 2011, 'Kredit', 'Kewajiban'),
(28, 'Utang Gaji', 2012, 'Kredit', 'Kewajiban'),
(29, 'Modal Dana ', 3011, 'Kredit', 'Modal'),
(30, 'Prive ', 3012, 'Kredit', 'Modal'),
(31, 'Ikhtisar L/R', 3013, 'Kredit', 'Modal'),
(32, 'Penjualan Barang Dagang', 4011, 'Kredit', 'Pendapatan'),
(33, 'Harga Pokok Penjualan ', 5011, 'Kredit', 'HPP'),
(34, 'Beban Gaji', 6011, 'Debit', 'Beban'),
(35, 'Beban Bahan Habis Pakai', 6012, 'Debit', 'Beban'),
(36, 'Beban Penyusutan Kendaraan', 6013, 'Debit', 'Beban'),
(37, 'Beban Pemeliharaan', 6014, 'Debit', 'Beban'),
(38, 'Beban Listrik', 6015, 'Debit', 'Beban');

-- --------------------------------------------------------

--
-- Table structure for table `barangkeluar`
--

CREATE TABLE `barangkeluar` (
  `id` int(11) NOT NULL,
  `id_databarang` int(11) NOT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `tanggal_keluar` date NOT NULL,
  `jumlah` int(11) NOT NULL,
  `hpp` decimal(10,0) NOT NULL,
  `harga_jual` decimal(10,0) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `payment` enum('cash','credit') NOT NULL,
  `status` enum('Lunas','Belum Lunas') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Lunas',
  `keterangan` varchar(150) NOT NULL,
  `bukti` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barangkeluar`
--

INSERT INTO `barangkeluar` (`id`, `id_databarang`, `id_customer`, `tanggal_keluar`, `jumlah`, `hpp`, `harga_jual`, `total`, `payment`, `status`, `keterangan`, `bukti`) VALUES
(27, 9, 2, '2024-12-05', 260, '49667', '54633', '14204580', 'cash', 'Lunas', 'Bayar Tanggal 10/12/2024', ''),
(28, 9, 3, '2024-12-07', 300, '49667', '54633', '16389900', 'cash', 'Lunas', '-', ''),
(29, 9, 4, '2024-12-12', 560, '49667', '54633', '30594480', 'cash', 'Lunas', '-', ''),
(30, 9, 3, '2024-12-15', 600, '49667', '54633', '32779800', 'cash', 'Lunas', '-', ''),
(31, 9, 2, '2024-12-17', 250, '50413', '55455', '13863750', 'cash', 'Lunas', '-', ''),
(32, 9, 4, '2024-12-23', 450, '50413', '55455', '24954750', 'cash', 'Lunas', '-', ''),
(33, 9, 1, '2024-12-27', 600, '50413', '55455', '33273000', 'cash', 'Lunas', '-', ''),
(34, 9, 3, '2024-12-30', 550, '50413', '55455', '30500250', 'cash', 'Lunas', '-', ''),
(35, 10, 1, '2025-08-04', 78, '70084', '77092', '6013176', 'cash', 'Lunas', '-', 'fd5ea9e4ead1fe54bab7137a19b7cdfd.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `barangmasuk`
--

CREATE TABLE `barangmasuk` (
  `id` int(11) NOT NULL,
  `id_databarang` int(11) NOT NULL,
  `id_supplier` int(11) DEFAULT NULL,
  `tanggal_masuk` date NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_beli` decimal(10,0) NOT NULL,
  `total` decimal(10,0) NOT NULL,
  `payment` enum('cash','credit') NOT NULL,
  `status` enum('Lunas','Belum Lunas') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Lunas',
  `keterangan` varchar(150) NOT NULL,
  `bukti` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `barangmasuk`
--

INSERT INTO `barangmasuk` (`id`, `id_databarang`, `id_supplier`, `tanggal_masuk`, `jumlah`, `harga_beli`, `total`, `payment`, `status`, `keterangan`, `bukti`) VALUES
(33, 9, 2, '2024-12-01', 850, '51000', '43350000', 'cash', 'Lunas', '-', ''),
(34, 9, 3, '2024-12-09', 1700, '49000', '83300000', 'credit', 'Lunas', 'Bayar Tangal 12/12/2024', ''),
(35, 9, 4, '2024-12-20', 1200, '52000', '62400000', 'cash', 'Lunas', '-', ''),
(38, 10, 1, '2025-08-04', 500, '57000', '28500000', 'cash', 'Lunas', '-', ''),
(39, 10, 1, '2025-08-04', 789, '78909', '62259201', 'cash', 'Lunas', '-', ''),
(40, 10, 2, '2025-08-04', 70, '78909', '5523630', 'cash', 'Lunas', '-', ''),
(41, 10, 2, '2025-08-04', 78, '56760', '4427280', 'cash', 'Lunas', '-', '');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(50) NOT NULL,
  `nama_customer` varchar(150) NOT NULL,
  `alamat_customer` varchar(150) NOT NULL,
  `nomor_telepon` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama_customer`, `alamat_customer`, `nomor_telepon`) VALUES
(1, 'CV. Sadariana', 'Dumai', '0823-6789-3142'),
(2, 'Agus Jawa', 'Bundaran', '0823-5698-7458'),
(3, 'Rino', 'Perawang', '0812-8863-4562'),
(4, 'Imron', 'Kerinci', '0813-0743-5128');

-- --------------------------------------------------------

--
-- Table structure for table `databarang`
--

CREATE TABLE `databarang` (
  `id_databarang` int(11) NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `databarang`
--

INSERT INTO `databarang` (`id_databarang`, `kode_barang`, `nama_barang`) VALUES
(8, 'BRG0001', 'Bawang Merah Kupas'),
(9, 'BRG0002', 'Bawang Merah'),
(10, 'BRG0003', 'Bawang Putih'),
(11, 'BRG0004', 'Bawang Bombay'),
(12, 'BRG0005', 'Cengkeh'),
(13, 'BRG0006', 'Kemiri'),
(14, 'BRG0007', 'Bawang Goreng');

-- --------------------------------------------------------

--
-- Table structure for table `penutuphistori`
--

CREATE TABLE `penutuphistori` (
  `id` int(11) NOT NULL,
  `no_trsk` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `jumlah` double DEFAULT 0,
  `jenis_saldo` enum('debit','kredit') DEFAULT NULL,
  `total_debit` double DEFAULT 0,
  `total_kredit` double DEFAULT 0,
  `bulan` varchar(2) DEFAULT NULL,
  `tahun` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saldoawal`
--

CREATE TABLE `saldoawal` (
  `id_saldoawal` int(11) NOT NULL,
  `id_akun` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `jenis_saldo` enum('debit','kredit') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `saldoawal`
--

INSERT INTO `saldoawal` (`id_saldoawal`, `id_akun`, `jumlah`, `jenis_saldo`) VALUES
(1, 21, '85000000.00', 'debit'),
(2, 22, '10000000.00', 'debit'),
(3, 23, '25000000.00', 'debit'),
(4, 24, '2000000.00', 'debit'),
(5, 25, '37000000.00', 'debit'),
(6, 26, '10000000.00', 'kredit'),
(7, 27, '8000000.00', 'kredit'),
(8, 28, '2000000.00', 'kredit'),
(9, 29, '142000000.00', 'kredit'),
(10, 30, '3000000.00', 'debit');

-- --------------------------------------------------------

--
-- Table structure for table `stokbarang`
--

CREATE TABLE `stokbarang` (
  `id_stok` int(11) NOT NULL,
  `id_databarang` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `harga_rata2` decimal(10,0) NOT NULL,
  `harga_jual` decimal(10,0) NOT NULL,
  `sisa` int(11) NOT NULL,
  `lead_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `stokbarang`
--

INSERT INTO `stokbarang` (`id_stok`, `id_databarang`, `stok`, `harga_rata2`, `harga_jual`, `sisa`, `lead_time`) VALUES
(7, 9, -3570, '50413', '55455', 350, NULL),
(8, 10, -678, '70084', '77092', 350, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(150) NOT NULL,
  `alamat_supplier` varchar(250) NOT NULL,
  `nomor_telepon` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat_supplier`, `nomor_telepon`) VALUES
(1, 'CV. Sada Riana', 'Dumai', '0823-6789-3142'),
(2, 'Tupang Ganda', 'Dumai', '0845-6789-3207'),
(3, 'Sinalsal Ks', 'Siantar', '0812-6425-3840'),
(4, 'Karo-Karo', 'Tarutung', '0898-7865-0432'),
(5, 'UD. Rita', 'Sidikalang', '0813-9671-2322');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `no_trsk` varchar(50) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `id_akun` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `jenis_saldo` enum('debit','kredit') NOT NULL,
  `id_barangmasuk` int(11) DEFAULT NULL,
  `id_barangkeluar` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `tanggal`, `no_trsk`, `keterangan`, `id_akun`, `jumlah`, `jenis_saldo`, `id_barangmasuk`, `id_barangkeluar`) VALUES
(605, '2024-12-01', 'BT-241201001', 'Pembelian Tunai Persediaan Bawang Merah', 23, '43350000.00', 'debit', NULL, NULL),
(606, '2024-12-01', 'BT-241201001', 'Pembelian Tunai Persediaan Bawang Merah', 21, '43350000.00', 'kredit', NULL, NULL),
(609, '2024-12-20', 'BT-241220001', 'Pembelian Tunai Persediaan Bawang Merah', 23, '62400000.00', 'debit', NULL, NULL),
(610, '2024-12-20', 'BT-241220001', 'Pembelian Tunai Persediaan Bawang Merah', 21, '62400000.00', 'kredit', NULL, NULL),
(611, '2024-12-12', 'T-241212001', 'Pelunasan Hutang - Sinalsal Ks (2024-12-09)', 27, '83300000.00', 'debit', NULL, NULL),
(612, '2024-12-12', 'T-241212001', 'Pelunasan Hutang - Sinalsal Ks (2024-12-09)', 21, '83300000.00', 'kredit', NULL, NULL),
(613, '2024-12-10', 'T-241210001', 'PELUNASAN PIUTANG - Agus Jawa (2024-12-05)', 21, '14204580.00', 'debit', NULL, NULL),
(614, '2024-12-10', 'T-241210001', 'PELUNASAN PIUTANG - Agus Jawa (2024-12-05)', 22, '14204580.00', 'kredit', NULL, NULL),
(619, '2024-12-07', 'JT-241207001', 'Penjualan Tunai Persediaan Bawang Merah', 21, '16389900.00', 'debit', NULL, NULL),
(620, '2024-12-07', 'JT-241207001', 'Penjualan Tunai Persediaan Bawang Merah', 32, '16389900.00', 'kredit', NULL, NULL),
(621, '2024-12-07', 'JT-241207001', 'Penjualan Tunai Persediaan Bawang Merah', 33, '14899909.09', 'debit', NULL, NULL),
(622, '2024-12-07', 'JT-241207001', 'Penjualan Tunai Persediaan Bawang Merah', 23, '14899909.09', 'kredit', NULL, NULL),
(623, '2024-12-12', 'JT-241212001', 'Penjualan Tunai Persediaan Bawang Merah', 21, '30594480.00', 'debit', NULL, NULL),
(624, '2024-12-12', 'JT-241212001', 'Penjualan Tunai Persediaan Bawang Merah', 32, '30594480.00', 'kredit', NULL, NULL),
(625, '2024-12-12', 'JT-241212001', 'Penjualan Tunai Persediaan Bawang Merah', 33, '27813163.64', 'debit', NULL, NULL),
(626, '2024-12-12', 'JT-241212001', 'Penjualan Tunai Persediaan Bawang Merah', 23, '27813163.64', 'kredit', NULL, NULL),
(631, '2024-12-15', 'JT-241215001', 'penjualan tunai persediaan bawang merah', 21, '32779800.00', 'debit', NULL, NULL),
(632, '2024-12-15', 'JT-241215001', 'penjualan tunai persediaan bawang merah', 32, '32779800.00', 'kredit', NULL, NULL),
(633, '2024-12-15', 'JT-241215001', 'penjualan tunai persediaan bawang merah', 33, '29799818.18', 'debit', NULL, NULL),
(634, '2024-12-15', 'JT-241215001', 'penjualan tunai persediaan bawang merah', 23, '29799818.18', 'kredit', NULL, NULL),
(635, '2024-12-17', 'JT-241217001', 'Penjualan Tunai Persediaan Bawang Merah', 21, '13863750.00', 'debit', NULL, NULL),
(636, '2024-12-17', 'JT-241217001', 'Penjualan Tunai Persediaan Bawang Merah', 32, '13863750.00', 'kredit', NULL, NULL),
(637, '2024-12-17', 'JT-241217001', 'Penjualan Tunai Persediaan Bawang Merah', 33, '12603409.09', 'debit', NULL, NULL),
(638, '2024-12-17', 'JT-241217001', 'Penjualan Tunai Persediaan Bawang Merah', 23, '12603409.09', 'kredit', NULL, NULL),
(639, '2024-12-23', 'JT-241223001', 'Penjualan Tunai Persediaan Bawang Merah', 21, '24954750.00', 'debit', NULL, NULL),
(640, '2024-12-23', 'JT-241223001', 'Penjualan Tunai Persediaan Bawang Merah', 32, '24954750.00', 'kredit', NULL, NULL),
(641, '2024-12-23', 'JT-241223001', 'Penjualan Tunai Persediaan Bawang Merah', 33, '22686136.36', 'debit', NULL, NULL),
(642, '2024-12-23', 'JT-241223001', 'Penjualan Tunai Persediaan Bawang Merah', 23, '22686136.36', 'kredit', NULL, NULL),
(643, '2024-12-27', 'JT-241227001', 'Penjualan Tunai Persediaan Bawang Merah', 21, '33273000.00', 'debit', NULL, NULL),
(644, '2024-12-27', 'JT-241227001', 'Penjualan Tunai Persediaan Bawang Merah', 32, '33273000.00', 'kredit', NULL, NULL),
(645, '2024-12-27', 'JT-241227001', 'Penjualan Tunai Persediaan Bawang Merah', 33, '30248181.82', 'debit', NULL, NULL),
(646, '2024-12-27', 'JT-241227001', 'Penjualan Tunai Persediaan Bawang Merah', 23, '30248181.82', 'kredit', NULL, NULL),
(647, '2024-12-30', 'JT-241230001', 'Penjualan Tunai Persediaan Bawang Merah', 21, '30500250.00', 'debit', NULL, NULL),
(648, '2024-12-30', 'JT-241230001', 'Penjualan Tunai Persediaan Bawang Merah', 32, '30500250.00', 'kredit', NULL, NULL),
(649, '2024-12-30', 'JT-241230001', 'Penjualan Tunai Persediaan Bawang Merah', 33, '27727500.00', 'debit', NULL, NULL),
(650, '2024-12-30', 'JT-241230001', 'Penjualan Tunai Persediaan Bawang Merah', 23, '27727500.00', 'kredit', NULL, NULL),
(661, '2024-12-09', 'BK-241209001', 'pembelian kredit persediaan bawang merah', 23, '83300000.00', 'debit', NULL, NULL),
(662, '2024-12-09', 'BK-241209001', 'pembelian kredit persediaan bawang merah', 27, '83300000.00', 'kredit', NULL, NULL),
(679, '2024-12-05', 'JT-241205001', 'penjualan kredit persediaan bawang merah', 22, '14204580.00', 'debit', NULL, NULL),
(680, '2024-12-05', 'JT-241205001', 'penjualan kredit persediaan bawang merah', 32, '14204580.00', 'kredit', NULL, NULL),
(681, '2024-12-05', 'JT-241205001', 'penjualan kredit persediaan bawang merah', 33, '12913254.55', 'debit', NULL, NULL),
(682, '2024-12-05', 'JT-241205001', 'penjualan kredit persediaan bawang merah', 23, '12913254.55', 'kredit', NULL, NULL),
(683, '2024-12-12', 'T-241212007', 'Beban Pemeliharaan Kendaraan ', 37, '300000.00', 'debit', NULL, NULL),
(684, '2024-12-12', 'T-241212007', 'Beban Pemeliharaan Kendaraan ', 21, '300000.00', 'kredit', NULL, NULL),
(685, '2024-12-18', 'T-241218001', 'Pembelian karung', 24, '200000.00', 'debit', NULL, NULL),
(686, '2024-12-18', 'T-241218001', 'Pembelian karung', 21, '200000.00', 'kredit', NULL, NULL),
(687, '2024-12-20', 'T-241220003', 'Pemeliharaan Kendaraan', 37, '400000.00', 'debit', NULL, NULL),
(688, '2024-12-20', 'T-241220003', 'Pemeliharaan Kendaraan', 21, '400000.00', 'kredit', NULL, NULL),
(689, '2024-12-27', 'T-241227005', 'Beban Listrik bulan Desember', 38, '450000.00', 'debit', NULL, NULL),
(690, '2024-12-27', 'T-241227005', 'Beban Listrik bulan Desember', 21, '450000.00', 'kredit', NULL, NULL),
(691, '2024-12-29', 'T-241229001', 'Penyusutan Kendaraan', 36, '525000.00', 'debit', NULL, NULL),
(692, '2024-12-29', 'T-241229001', 'Penyusutan Kendaraan', 26, '525000.00', 'kredit', NULL, NULL),
(695, '2024-12-01', 'T-241201003', 'setoran dana', 21, '200000000.00', 'debit', NULL, NULL),
(696, '2024-12-01', 'T-241201003', 'setoran dana', 29, '200000000.00', 'kredit', NULL, NULL),
(697, '2024-12-02', 'T-241202001', 'pembelian karung', 24, '350000.00', 'debit', NULL, NULL),
(698, '2024-12-02', 'T-241202001', 'pembelian karung', 21, '350000.00', 'kredit', NULL, NULL),
(699, '2024-12-03', 'T-241203001', 'pembayaran gaji karyawan', 34, '2200000.00', 'debit', NULL, NULL),
(700, '2024-12-03', 'T-241203001', 'pembayaran gaji karyawan', 21, '2200000.00', 'kredit', NULL, NULL),
(701, '2025-07-29', 'JT-250729001', 'Penjualan Tunai Persediaan Bawang Merah', 21, '1500000.00', 'debit', NULL, NULL),
(702, '2025-07-29', 'JT-250729001', 'Penjualan Tunai Persediaan Bawang Merah', 32, '1500000.00', 'kredit', NULL, NULL),
(703, '2025-07-29', 'JT-250729001', 'Penjualan Tunai Persediaan Bawang Merah', 33, '1363636.36', 'debit', NULL, NULL),
(704, '2025-07-29', 'JT-250729001', 'Penjualan Tunai Persediaan Bawang Merah', 23, '1363636.36', 'kredit', NULL, NULL),
(705, '2025-07-29', 'T-250729005', 'Pembelian Persediaan Bawang Merah', 23, '1500000.00', 'debit', NULL, NULL),
(706, '2025-07-29', 'T-250729005', 'Pembelian Persediaan Bawang Merah', 21, '1500000.00', 'kredit', NULL, NULL),
(721, '2025-08-11', 'JP-2025707', 'Jurnal Penutup periode Tahun 2025', 32, '1500000.00', 'debit', NULL, NULL),
(722, '2025-08-11', 'JP-2025707', 'Jurnal Penutup periode Tahun 2025', 31, '1500000.00', 'kredit', NULL, NULL),
(723, '2025-08-11', 'JP-2025707', 'Jurnal Penutup periode Tahun 2025', 31, '1363636.36', 'debit', NULL, NULL),
(724, '2025-08-11', 'JP-2025707', 'Jurnal Penutup periode Tahun 2025', 33, '1363636.36', 'kredit', NULL, NULL),
(725, '2025-08-11', 'JP-2025707', 'Jurnal Penutup periode Tahun 2025', 29, '3000000.00', 'debit', NULL, NULL),
(726, '2025-08-11', 'JP-2025707', 'Jurnal Penutup periode Tahun 2025', 30, '3000000.00', 'kredit', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `image` varchar(150) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `image`, `password`, `role_id`, `is_active`, `date_created`) VALUES
(6, 'Budiono Siregar', 'budi@gmail.com', 'default1.png', '$2y$10$bTrIGqLho19X.N/qY47eKeIMoi.MTI6nauj0IWTsrhNz/2KmDkfMG', 2, 1, 1747223977),
(7, 'Damai Yanti Peronika', 'damai@gmail.com', 'default.jpg', '$2y$10$GYSCh9X5L64Xo7L51o9ebOgjzi/PeMP4RaFYyaPPbBGd0D.aImGTS', 1, 1, 1747497760);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `role` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role`) VALUES
(1, 'Admin'),
(2, 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id_akun`),
  ADD UNIQUE KEY `no_akun` (`no_akun`);

--
-- Indexes for table `barangkeluar`
--
ALTER TABLE `barangkeluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_databarang` (`id_databarang`),
  ADD KEY `fk_id_customer` (`id_customer`);

--
-- Indexes for table `barangmasuk`
--
ALTER TABLE `barangmasuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_databarang` (`id_databarang`),
  ADD KEY `fk_id_supplier` (`id_supplier`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `databarang`
--
ALTER TABLE `databarang`
  ADD PRIMARY KEY (`id_databarang`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `penutuphistori`
--
ALTER TABLE `penutuphistori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_trsk` (`no_trsk`);

--
-- Indexes for table `saldoawal`
--
ALTER TABLE `saldoawal`
  ADD PRIMARY KEY (`id_saldoawal`),
  ADD KEY `id_akun` (`id_akun`);

--
-- Indexes for table `stokbarang`
--
ALTER TABLE `stokbarang`
  ADD PRIMARY KEY (`id_stok`),
  ADD KEY `id_databarang` (`id_databarang`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_akun` (`id_akun`),
  ADD KEY `fk_transaksi_barangkeluar` (`id_barangkeluar`),
  ADD KEY `fk_transaksi_barangmasuk` (`id_barangmasuk`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `barangkeluar`
--
ALTER TABLE `barangkeluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `barangmasuk`
--
ALTER TABLE `barangmasuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `databarang`
--
ALTER TABLE `databarang`
  MODIFY `id_databarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `penutuphistori`
--
ALTER TABLE `penutuphistori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saldoawal`
--
ALTER TABLE `saldoawal`
  MODIFY `id_saldoawal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stokbarang`
--
ALTER TABLE `stokbarang`
  MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=727;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barangkeluar`
--
ALTER TABLE `barangkeluar`
  ADD CONSTRAINT `barangkeluar_ibfk_1` FOREIGN KEY (`id_databarang`) REFERENCES `databarang` (`id_databarang`),
  ADD CONSTRAINT `fk_id_customer` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `barangmasuk`
--
ALTER TABLE `barangmasuk`
  ADD CONSTRAINT `barangmasuk_ibfk_1` FOREIGN KEY (`id_databarang`) REFERENCES `databarang` (`id_databarang`),
  ADD CONSTRAINT `fk_id_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `saldoawal`
--
ALTER TABLE `saldoawal`
  ADD CONSTRAINT `saldoawal_ibfk_1` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`);

--
-- Constraints for table `stokbarang`
--
ALTER TABLE `stokbarang`
  ADD CONSTRAINT `stokbarang_ibfk_1` FOREIGN KEY (`id_databarang`) REFERENCES `databarang` (`id_databarang`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_barangkeluar` FOREIGN KEY (`id_barangkeluar`) REFERENCES `barangkeluar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_barangmasuk` FOREIGN KEY (`id_barangmasuk`) REFERENCES `barangmasuk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
