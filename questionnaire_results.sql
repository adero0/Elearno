-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Paź 20, 2025 at 02:33 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adaptive_db`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `questionnaire_results`
--

CREATE TABLE `questionnaire_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `q1` tinyint(4) NOT NULL,
  `q2` tinyint(4) NOT NULL,
  `q3` tinyint(4) NOT NULL,
  `q4` tinyint(4) NOT NULL,
  `dominant_style` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `questionnaire_results`
--

INSERT INTO `questionnaire_results` (`id`, `user_id`, `q1`, `q2`, `q3`, `q4`, `dominant_style`, `created_at`) VALUES
(1, 1, 5, 3, 5, 5, 'wizualny', '2025-10-20 14:13:16');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `questionnaire_results`
--
ALTER TABLE `questionnaire_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questionnaire_results`
--
ALTER TABLE `questionnaire_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questionnaire_results`
--
ALTER TABLE `questionnaire_results`
  ADD CONSTRAINT `questionnaire_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
