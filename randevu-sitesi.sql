-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 30 Kas 2021, 04:37:36
-- Sunucu sürümü: 10.4.17-MariaDB
-- PHP Sürümü: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `randevu-sitesi`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanici`
--

CREATE TABLE `kullanici` (
  `id` int(11) NOT NULL,
  `sifre` varchar(50) NOT NULL,
  `ad_soyad` varchar(50) NOT NULL,
  `ogrenci_numara` varchar(8) NOT NULL,
  `telefon_numara` varchar(11) NOT NULL,
  `yetkiler` varchar(6) NOT NULL,
  `e-posta` varchar(50) NOT NULL,
  `sifirlama_kodu` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `kullanici`
--

INSERT INTO `kullanici` (`id`, `sifre`, `ad_soyad`, `ogrenci_numara`, `telefon_numara`, `yetkiler`, `e-posta`, `sifirlama_kodu`) VALUES
(1, 'admin_sifre', 'admin', 'admin', '1', '1', '1', '');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `odalar`
--

CREATE TABLE `odalar` (
  `id` int(11) NOT NULL,
  `oda_isim` varchar(50) NOT NULL,
  `oda_aktif` int(1) NOT NULL,
  `1` int(1) NOT NULL,
  `2` int(1) NOT NULL,
  `3` int(1) NOT NULL,
  `4` int(1) NOT NULL,
  `5` int(1) NOT NULL,
  `6` int(1) NOT NULL,
  `7` int(1) NOT NULL,
  `8` int(1) NOT NULL,
  `9` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `randevular`
--

CREATE TABLE `randevular` (
  `id` int(11) NOT NULL,
  `ogrenci_id` int(11) NOT NULL,
  `oda_id` int(11) NOT NULL,
  `saat` varchar(5) NOT NULL,
  `tarih` varchar(20) NOT NULL,
  `pasif` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `kullanici`
--
ALTER TABLE `kullanici`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ogrenci_numara` (`ogrenci_numara`,`telefon_numara`),
  ADD UNIQUE KEY `e-posta` (`e-posta`);

--
-- Tablo için indeksler `odalar`
--
ALTER TABLE `odalar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `randevular`
--
ALTER TABLE `randevular`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `kullanici`
--
ALTER TABLE `kullanici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `odalar`
--
ALTER TABLE `odalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `randevular`
--
ALTER TABLE `randevular`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
