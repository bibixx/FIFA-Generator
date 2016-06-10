-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 11 Cze 2016, 00:05
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
  `title` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `players` text COLLATE utf8_unicode_ci NOT NULL,
  `rounds` text COLLATE utf8_unicode_ci NOT NULL,
  `fixtures` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Zrzut danych tabeli `tournaments`
--

INSERT INTO `tournaments` (`id`, `title`, `type`, `players`, `rounds`, `fixtures`) VALUES
(1, 'tytuł', 'League', '[{"players":["Bartek", "Ignacy"],"club":"Legia+Warszawa"},{"players":["Kuba"],"club":"FC+Barcelona"},{"players":["Ignacy"],"club":"FC+Bayern"},{"players":["Micha%C5%82"],"club":"Arsenal+Londyn"}]', '[[[3,0],[1,2]],[[0,2],[3,1]],[[1,0],[2,3]]]', '[[[1,100],[3,3]],[[2,5],[5,4]],[[7,8],[5,10]]]'),
(2, 'tytuł', 'League', '[{"players":[""],"club":""},{"players":[""],"club":""},{"players":[""],"club":""},{"players":[""],"club":""}]', '[[[1,0],[2,3]],[[3,0],[1,2]],[[0,2],[3,1]]]', '[[[],[]],[[],[]],[[],[]]]'),
(3, 'tytuł', 'League', '[{"players":[""],"club":""},{"players":[""],"club":""},{"players":[""],"club":""},{"players":[""],"club":""}]', '[[[3,0],[1,2]],[[0,2],[3,1]],[[1,0],[2,3]]]', '[[[],[]],[[],[]],[[],[]]]');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
