-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2020 at 07:11 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecat_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `altbaslik`
--

CREATE TABLE `altbaslik` (
  `altBaslik_no` int(11) NOT NULL,
  `altBaslik_ad` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `altBaslik_imgPath` varchar(75) COLLATE utf8_turkish_ci DEFAULT NULL,
  `alt_ref` int(11) DEFAULT NULL,
  `altBaslik_imgTeknik` varchar(75) COLLATE utf8_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `altbaslik`
--

INSERT INTO `altbaslik` (`altBaslik_no`, `altBaslik_ad`, `altBaslik_imgPath`, `alt_ref`, `altBaslik_imgTeknik`) VALUES
(27, '90°Derece', './dosyalar/90°Derece1.png', 11, './dosyalar/90°Derece2.jpg'),
(28, '45 derece', './dosyalar/45 derece1.jpg', 11, './dosyalar/45 derece2.png'),
(32, 'yüzey freze', './dosyalar/yüzey freze1.PNG', 15, './dosyalar/yüzey freze2.png');

-- --------------------------------------------------------

--
-- Table structure for table `altkatalog`
--

CREATE TABLE `altkatalog` (
  `alt_no` int(11) NOT NULL,
  `alt_ad` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `ana_ref` int(11) DEFAULT NULL,
  `altkatalog_img` varchar(75) COLLATE utf8_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `anakatalog`
--

CREATE TABLE `anakatalog` (
  `ana_no` int(11) NOT NULL,
  `ana_ad` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `ana_imgPath` varchar(75) COLLATE utf8_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kesici_uclar`
--

CREATE TABLE `kesici_uclar` (
  `kesici_uclar_no` int(11) NOT NULL,
  `kesici_uclar_ad` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `kesici_uclar_imgPath` varchar(75) COLLATE utf8_turkish_ci DEFAULT NULL,
  `baslik_ref` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `kesici_uclar`
--

INSERT INTO `kesici_uclar` (`kesici_uclar_no`, `kesici_uclar_ad`, `kesici_uclar_imgPath`, `baslik_ref`) VALUES
(6, 'kesici uç', './dosyalar/yedekParca/kesiciUclar/kesici uç.png', 27),
(7, 'elmas', './dosyalar/yedekParca/kesiciUclar/elmas.png', 27),
(8, 'armut', './dosyalar/armut.jpg', 32);

-- --------------------------------------------------------

--
-- Table structure for table `urun`
--

CREATE TABLE `urun` (
  `urun_no` int(11) NOT NULL,
  `urun_ad` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `alt_ref` int(11) NOT NULL,
  `baslik_ref` int(11) NOT NULL,
  `kesici_uclar_ref` int(11) NOT NULL,
  `urun_imgPath` varchar(75) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_RIGHT` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LEFT` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_A` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_APMX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_B` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_BD` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CBL` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CBR` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CDX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CNT` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CUTDIA` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CW` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DAXIN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DAXX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DB` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DC` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCN_DCX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCON` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCONMS` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCONWS` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCSFMS` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DMIN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_F` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_FIG` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_H` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HBH` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HBKW` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HBL` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HF` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HF_H` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HTPRM` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_I` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_KAPR` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_KULLANILAN_VIDA` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_KWW` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_L` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_L1` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LB` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LF` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LH` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LPR` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LS` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LU` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LU_OHN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_MHD` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_MINR` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_OAH` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_OAL` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_OAW` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_OHN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_RE` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_TD` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_TDZ` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_THID` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_THL` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_THUB` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_TYPE` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_Z` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_ZEFP` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_WB` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_WB2` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_WF` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_WTHPRM` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urun_ozellikleri`
--

CREATE TABLE `urun_ozellikleri` (
  `ana_ref` int(11) DEFAULT NULL,
  `urunOzellik_ad` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `urun_RIGHT` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LEFT` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_A` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_APMX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_B` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_BD` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CBL` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CBR` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CDX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CNT` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CUTDIA` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_CW` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DAXIN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DAXX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DB` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DC` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCN_DCX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCON` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCONMS` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCONWS` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCSFMS` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DCX` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DMIN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_DN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_F` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_FIG` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_H` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HBH` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HBKW` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HBL` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HF` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HF_H` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_HTPRM` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_I` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_KAPR` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_KULLANILAN_VIDA` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_KWW` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_L` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_L1` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LB` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LF` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LH` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LPR` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LS` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LU` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_LU_OHN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_MHD` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_MINR` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_OAH` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_OAL` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_OAW` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_OHN` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_RE` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_TD` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_TDZ` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_THID` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_THL` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_THUB` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_TYPE` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_Z` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_ZEFP` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_WB` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_WB2` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_WF` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0',
  `urun_WTHPRM` varchar(15) COLLATE utf8_turkish_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urun_yedek`
--

CREATE TABLE `urun_yedek` (
  `urunYedek_no` int(20) NOT NULL,
  `parca_ref` int(11) DEFAULT NULL,
  `parca_Tisim` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `urun_ref` int(20) DEFAULT NULL,
  `image_ref` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `urun_yedek`
--

INSERT INTO `urun_yedek` (`urunYedek_no`, `parca_ref`, `parca_Tisim`, `urun_ref`, `image_ref`) VALUES
(3, 3, 'blablabla', 14, 12),
(44, 3, 'blablabla', 21, 12),
(64, 3, '80-T08999', 20, 12),
(68, 5, '3008-M3x78911', 20, 13),
(72, 12, 'alyan', 20, 16),
(73, 7, 'sekman', 20, 11),
(74, 3, '80-T08999', 16, 12),
(85, 8, '80-T0155', 16, 10);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Kullanici Adi',
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Sifre',
  `created_at` datetime DEFAULT NULL COMMENT 'Olusturulma Tarihi',
  `updated_at` datetime DEFAULT NULL COMMENT 'Guncellenme Tarihi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='User Tablosu';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `created_at`, `updated_at`) VALUES
('admin', '84a380aaf8e16ea16252b2e211e5e5fb', '2020-02-09 17:03:11', '2020-02-09 17:03:11'),
('zahid', 'dc3454ff7798617b7209241e44b15819', '2020-02-08 14:39:33', '2020-02-08 14:39:33');

-- --------------------------------------------------------

--
-- Table structure for table `yedekparca_images`
--

CREATE TABLE `yedekparca_images` (
  `image_no` int(11) NOT NULL,
  `parca_ref` int(11) DEFAULT NULL,
  `image_path` varchar(100) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `yedekparca_images`
--

INSERT INTO `yedekparca_images` (`image_no`, `parca_ref`, `image_path`) VALUES
(10, 8, './dosyalar/yedekParca/Anahtar/Anahtar1.PNG'),
(11, 7, './dosyalar/yedekParca/Sekman/Sekman1.PNG'),
(12, 3, './dosyalar/yedekParca/Sıkma Vidası/Sıkma Vidası1.PNG'),
(13, 5, './dosyalar/yedekParca/Tork anahtarlar/Tork anahtarlar1.PNG'),
(14, 5, './dosyalar/yedekParca/Tork anahtarlar/Tork anahtarlar2.png'),
(15, 5, './dosyalar/yedekParca/Tork anahtarlar/Tork anahtarlar3.png'),
(16, 12, './dosyalar/yedekParca/Alyan/Alyan1.PNG');

-- --------------------------------------------------------

--
-- Table structure for table `yedek_parca`
--

CREATE TABLE `yedek_parca` (
  `parca_no` int(11) NOT NULL,
  `parca_adi` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Dumping data for table `yedek_parca`
--

INSERT INTO `yedek_parca` (`parca_no`, `parca_adi`) VALUES
(3, 'Sıkma Vidası'),
(5, 'Tork anahtarlar'),
(7, 'Sekman'),
(8, 'Anahtar'),
(12, 'Alyan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `altbaslik`
--
ALTER TABLE `altbaslik`
  ADD PRIMARY KEY (`altBaslik_no`);

--
-- Indexes for table `altkatalog`
--
ALTER TABLE `altkatalog`
  ADD PRIMARY KEY (`alt_no`);

--
-- Indexes for table `anakatalog`
--
ALTER TABLE `anakatalog`
  ADD PRIMARY KEY (`ana_no`);

--
-- Indexes for table `kesici_uclar`
--
ALTER TABLE `kesici_uclar`
  ADD PRIMARY KEY (`kesici_uclar_no`);

--
-- Indexes for table `urun`
--
ALTER TABLE `urun`
  ADD PRIMARY KEY (`urun_no`);

--
-- Indexes for table `urun_ozellikleri`
--
ALTER TABLE `urun_ozellikleri`
  ADD PRIMARY KEY (`urunOzellik_ad`);

--
-- Indexes for table `urun_yedek`
--
ALTER TABLE `urun_yedek`
  ADD PRIMARY KEY (`urunYedek_no`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `updated_at` (`updated_at`);

--
-- Indexes for table `yedekparca_images`
--
ALTER TABLE `yedekparca_images`
  ADD PRIMARY KEY (`image_no`);

--
-- Indexes for table `yedek_parca`
--
ALTER TABLE `yedek_parca`
  ADD PRIMARY KEY (`parca_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `altbaslik`
--
ALTER TABLE `altbaslik`
  MODIFY `altBaslik_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `altkatalog`
--
ALTER TABLE `altkatalog`
  MODIFY `alt_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `anakatalog`
--
ALTER TABLE `anakatalog`
  MODIFY `ana_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `kesici_uclar`
--
ALTER TABLE `kesici_uclar`
  MODIFY `kesici_uclar_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `urun`
--
ALTER TABLE `urun`
  MODIFY `urun_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `urun_yedek`
--
ALTER TABLE `urun_yedek`
  MODIFY `urunYedek_no` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `yedekparca_images`
--
ALTER TABLE `yedekparca_images`
  MODIFY `image_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `yedek_parca`
--
ALTER TABLE `yedek_parca`
  MODIFY `parca_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
