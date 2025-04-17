-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 10 Nis 2025, 09:24:41
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
-- Tablo için tablo yapısı `etkinlikler`
--

CREATE TABLE `etkinlikler` (
  `id` int(11) NOT NULL,
  `e_id` int(11) NOT NULL,
  `top_id` int(11) NOT NULL,
  `b_tarih` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

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

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `etkinlik_bilgi`
--

CREATE TABLE `etkinlik_bilgi` (
  `id` int(11) NOT NULL,
  `isim` varchar(100) NOT NULL,
  `metin` text NOT NULL,
  `gorsel` text NOT NULL,
  `tarih` datetime NOT NULL,
  `b_durum` int(11) NOT NULL DEFAULT 0,
  `y_durum` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `topluluklar`
--

CREATE TABLE `topluluklar` (
  `id` int(11) NOT NULL,
  `isim` varchar(100) NOT NULL,
  `gorsel` text NOT NULL,
  `vizyon` text NOT NULL,
  `misyon` text NOT NULL,
  `tuzuk` text NOT NULL,
  `kurulus` datetime NOT NULL,
  `durum` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `topluluk_yonetim`
--

CREATE TABLE `topluluk_yonetim` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `top_id` int(11) NOT NULL,
  `r_id` int(11) NOT NULL,
  `tarih` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `uyeler`
--

CREATE TABLE `uyeler` (
  `id` int(11) NOT NULL,
  `ogr_id` int(11) NOT NULL,
  `top_id` int(11) NOT NULL,
  `tarih` datetime NOT NULL,
  `durum` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
