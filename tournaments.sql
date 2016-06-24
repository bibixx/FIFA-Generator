-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 24 Cze 2016, 23:42
-- Wersja serwera: 10.1.13-MariaDB
-- Wersja PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `tournaments`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `title` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `players` text CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `rounds` text COLLATE utf8_unicode_ci NOT NULL,
  `fixtures` text COLLATE utf8_unicode_ci NOT NULL,
  `admin_token` char(6) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Zrzut danych tabeli `tournaments`
--

INSERT INTO `tournaments` (`id`, `title`, `created_at`, `type`, `players`, `rounds`, `fixtures`, `admin_token`) VALUES
(1, 'Mój pierwszy turniej', '2016-06-12 00:00:00', 'League', '[{"players":["Bartek"],"club":"Legia Warszawa"},{"players":["Kuba"],"club":"FC Barcelona"},{"players":["Ignacy"],"club":"FC Bayern"},{"players":["Micha\\u0142"],"club":"Arsenal Londyn"}]', '[[[3,0],[1,2]],[[0,2],[3,1]],[[1,0],[2,3]]]', '[[[1,11],[2,1]],[[4,1],[2,2]],[[1,1],[0,0]]]', '0b890c'),
(2, NULL, '2016-06-12 00:00:00', 'League', '[{"players":["Adam"],"club":""},{"players":["Mariusz"],"club":""},{"players":["Stefan"],"club":""},{"players":["Janusz"],"club":""}]', '[[[0,2],[3,1]],[[3,0],[1,2]],[[1,0],[2,3]]]', '[[[1,0],[]],[[],[]],[[],[]]]', '526da5'),
(3, 'Filler 4 3', '2016-06-24 18:09:59', 'League', '[{"players":["Ja"],"club":""},{"players":["Ty"],"club":""},{"players":["On"],"club":""},{"players":["Ona"],"club":""}]', '[[[1,0],[2,3]],[[0,2],[3,1]],[[3,0],[1,2]]]', '[[[1,1],[]],[[],[]],[[],[]]]', 'fa7320'),
(4, NULL, '2016-06-05 15:00:10', 'League', '[{"players":["a"],"club":""},{"players":["b"],"club":""},{"players":["c"],"club":""},{"players":["d"],"club":""},{"players":["e"],"club":""},{"players":["f"],"club":""},{"players":["g"],"club":""},{"players":["h"],"club":""},{"players":["i"],"club":""},{"players":["j"],"club":""}]', '[[[0,4],[5,3],[6,2],[7,1],[8,9]],[[1,0],[2,9],[3,8],[4,7],[5,6]],[[0,2],[3,1],[4,9],[5,8],[6,7]],[[5,0],[6,4],[7,3],[8,2],[9,1]],[[9,0],[1,8],[2,7],[3,6],[4,5]],[[3,0],[4,2],[5,1],[6,9],[7,8]],[[7,0],[8,6],[9,5],[1,4],[2,3]],[[0,6],[7,5],[8,4],[9,3],[1,2]],[[0,8],[9,7],[1,6],[2,5],[3,4]]]', '[[[12,4],[],[],[],[]],[[],[],[],[],[]],[[],[],[],[],[]],[[],[],[],[],[]],[[],[],[],[],[]],[[],[],[],[],[]],[[],[],[],[],[]],[[],[],[],[],[]],[[],[],[],[],[]]]', '19dfe6');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
