-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 25 Nis 2025, 14:12:46
-- Sunucu sürümü: 10.4.27-MariaDB
-- PHP Sürümü: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `topluluklar`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `etkinlik_basvuru`
--

CREATE TABLE `etkinlik_basvuru` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `tarih` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `etkinlik_basvuru`
--

INSERT INTO `etkinlik_basvuru` (`id`, `u_id`, `e_id`, `tarih`) VALUES
(1, 1, 1, '2025-04-17 14:55:28');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `etkinlik_bilgi`
--

CREATE TABLE `etkinlik_bilgi` (
  `id` int(11) NOT NULL,
  `isim` varchar(100) NOT NULL,
  `bilgi` varchar(200) NOT NULL,
  `metin` text NOT NULL,
  `gorsel` text NOT NULL,
  `tarih` datetime NOT NULL,
  `t_id` int(11) NOT NULL,
  `b_durum` int(11) NOT NULL DEFAULT 0,
  `y_durum` int(11) NOT NULL DEFAULT 0,
  `p_durum` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `etkinlik_bilgi`
--

INSERT INTO `etkinlik_bilgi` (`id`, `isim`, `bilgi`, `metin`, `gorsel`, `tarih`, `t_id`, `b_durum`, `y_durum`, `p_durum`) VALUES
(1, 'asd', '', 'asd', 'rektörluk.png', '2025-04-10 09:31:04', 1, 1, 0, 0),
(2, 'yeni', 'awdsdadwdwdawdaa', 'wqw3wt4e5yrutyukıljhgf', '1744894422_Ekran Görüntüsü (9).png', '2025-04-23 10:57:37', 1, 1, 0, 1),
(3, 'baslik1', 'qwertyuıopğASDFGHJKLŞ', 'awdawdawdawd', '1744886960_Ekran Görüntüsü (5).png', '2025-05-01 13:46:00', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ogrenci_bilgi`
--

CREATE TABLE `ogrenci_bilgi` (
  `id` int(11) NOT NULL,
  `numara` varchar(11) NOT NULL,
  `tc` varchar(11) NOT NULL,
  `isim` varchar(100) NOT NULL,
  `soyisim` varchar(100) NOT NULL,
  `fak_ad` varchar(100) NOT NULL,
  `bol_ad` varchar(100) NOT NULL,
  `prog_ad` varchar(100) NOT NULL,
  `sınıf` int(11) NOT NULL,
  `kay_tar` date NOT NULL,
  `ogrenim_durum` varchar(100) NOT NULL,
  `ogrenim_tip` varchar(10) NOT NULL,
  `ayr_tar` date DEFAULT NULL,
  `tel` varchar(11) NOT NULL,
  `tel2` varchar(11) NOT NULL,
  `eposta` varchar(100) NOT NULL,
  `eposta2` varchar(100) NOT NULL,
  `program_tip` varchar(30) NOT NULL,
  `durum` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `ogrenci_bilgi`
--

INSERT INTO `ogrenci_bilgi` (`id`, `numara`, `tc`, `isim`, `soyisim`, `fak_ad`, `bol_ad`, `prog_ad`, `sınıf`, `kay_tar`, `ogrenim_durum`, `ogrenim_tip`, `ayr_tar`, `tel`, `tel2`, `eposta`, `eposta2`, `program_tip`, `durum`) VALUES
(5, '21100011066', '27373692908', 'ENES EREN', 'SEVEN', 'MÜHENDİSLİK FAKÜLTESİ', 'BİLGİSAYAR MÜHENDİSLİĞİ', 'BİLGİSAYAR MÜHENDİSLİĞİ', 4, '2021-09-04', 'Aktif (E-Devletten Kayıt Yapan)', 'Lisans', NULL, '5349746825', '5349746825', 'eneseren2526@gmail.com', '21100011066@ogr.erbakan.edu.tr', 'Birinci Öğretim', 'Aktif');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personel`
--

CREATE TABLE `personel` (
  `id` int(11) NOT NULL,
  `isim` varchar(100) NOT NULL,
  `tc` varchar(11) NOT NULL,
  `tip` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `personel`
--

INSERT INTO `personel` (`id`, `isim`, `tc`, `tip`) VALUES
(1, 'Hasan', '61336044120', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `rol` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `rol`
--

INSERT INTO `rol` (`id`, `rol`) VALUES
(1, 'Üye'),
(2, 'Başkan'),
(3, 'Başkan Yard');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `topluluklar`
--

CREATE TABLE `topluluklar` (
  `id` int(11) NOT NULL,
  `isim` varchar(100) NOT NULL,
  `gorsel` text NOT NULL,
  `slogan` text NOT NULL,
  `vizyon` text NOT NULL,
  `misyon` text NOT NULL,
  `tuzuk` text NOT NULL,
  `kurulus` datetime NOT NULL,
  `durum` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `topluluklar`
--

INSERT INTO `topluluklar` (`id`, `isim`, `gorsel`, `slogan`, `vizyon`, `misyon`, `tuzuk`, `kurulus`, `durum`) VALUES
(1, 'Bilişim Topluluğu', 'pp.png', 'Geleceği Beraber Şekillendireceğiz', 'yok', 'o da yok', 'kullanılmıyor', '2017-04-02 10:25:32', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `uyeler`
--

CREATE TABLE `uyeler` (
  `id` int(11) NOT NULL,
  `ogr_id` int(11) NOT NULL,
  `top_id` int(11) NOT NULL,
  `rol` int(11) NOT NULL DEFAULT 1,
  `tarih` date NOT NULL,
  `belge` text NOT NULL,
  `durum` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `uyeler`
--

INSERT INTO `uyeler` (`id`, `ogr_id`, `top_id`, `rol`, `tarih`, `belge`, `durum`) VALUES
(7, 5, 1, 1, '2025-04-25', '27373692908_2025-04-25_12-08-31.pdf', 1);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `etkinlik_basvuru`
--
ALTER TABLE `etkinlik_basvuru`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `etkinlik_bilgi`
--
ALTER TABLE `etkinlik_bilgi`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `ogrenci_bilgi`
--
ALTER TABLE `ogrenci_bilgi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numara` (`numara`),
  ADD UNIQUE KEY `tc` (`tc`);

--
-- Tablo için indeksler `personel`
--
ALTER TABLE `personel`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `topluluklar`
--
ALTER TABLE `topluluklar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `uyeler`
--
ALTER TABLE `uyeler`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `etkinlik_basvuru`
--
ALTER TABLE `etkinlik_basvuru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `etkinlik_bilgi`
--
ALTER TABLE `etkinlik_bilgi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `ogrenci_bilgi`
--
ALTER TABLE `ogrenci_bilgi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `personel`
--
ALTER TABLE `personel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `topluluklar`
--
ALTER TABLE `topluluklar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `uyeler`
--
ALTER TABLE `uyeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
