-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2026 at 06:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `created_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `firstname`, `lastname`, `created_on`) VALUES
(1, 'admin', '$2y$10$7/g/hl/Iq8sc9jF0hH3T/.XOJz1iqtr/irtBP8p0yxA5pM7CJwQqq', 'admin', 'Project', '2026-01-18');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `title` text NOT NULL,
  `author` varchar(150) NOT NULL,
  `publisher` varchar(150) NOT NULL,
  `publish_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `isbn`, `title`, `author`, `publisher`, `publish_date`, `status`) VALUES
(7, '56', '6', '6', '6', '2025-05-04', 1),
(8, '586', '555', '55', '55', '2026-02-10', 1),
(9, '555', '555', 'wffffff', '555', '0005-05-05', 1),
(10, '188', 'DAA', 'Dhaval', 'Krish', '2026-04-05', 1),
(11, '24445', 'ikigai', 'hector garcia', 'ikka dukka studio', '2016-04-01', 1),
(12, '123', 'abc', 'abc', 'abc', '2026-04-04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_requests`
--

CREATE TABLE `book_requests` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `request_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_requests`
--

INSERT INTO `book_requests` (`id`, `student_id`, `book_id`, `request_date`, `status`) VALUES
(1, 18, 11, '2026-04-16 23:58:16', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `date_borrow` date NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow`
--

INSERT INTO `borrow` (`id`, `student_id`, `book_id`, `date_borrow`, `status`) VALUES
(1, 18, 9, '2026-04-01', 0),
(2, 18, 7, '2026-04-01', 0),
(3, 18, 8, '2026-04-01', 0),
(4, 18, 11, '2026-04-08', 0),
(5, 18, 11, '2026-04-11', 0),
(6, 18, 8, '2026-04-11', 0),
(7, 18, 9, '2026-04-11', 1),
(8, 18, 9, '2026-04-11', 1),
(9, 18, 10, '2026-04-11', 0),
(10, 18, 8, '2026-04-11', 1),
(11, 18, 7, '2026-04-11', 0),
(12, 18, 7, '2026-04-11', 0),
(13, 18, 11, '2026-04-11', 0),
(14, 18, 11, '2026-04-11', 0),
(15, 18, 12, '2026-04-11', 0),
(16, 18, 12, '2026-04-11', 0),
(17, 18, 11, '2026-04-16', 0),
(18, 18, 11, '2026-04-16', 0),
(19, 18, 7, '2026-04-16', 0),
(20, 18, 8, '2026-04-16', 1),
(21, 18, 11, '2026-04-10', 1),
(22, 19, 7, '2026-04-10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `payment_for` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `penalty_id` int(11) DEFAULT NULL,
  `transaction_ref` varchar(100) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Completed',
  `paid_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `payment_for`, `amount`, `payment_method`, `penalty_id`, `transaction_ref`, `status`, `paid_at`) VALUES
(1, 18, 'membership', 200.00, 'debit_card', NULL, '222', 'Completed', '2026-04-16 23:50:12'),
(2, 18, 'security_deposit', 2330.00, 'upi', NULL, '0', 'Completed', '2026-04-19 08:40:59'),
(3, 19, 'late_fine', 20.00, 'upi', 1, '222', 'Completed', '2026-04-19 09:31:54');

-- --------------------------------------------------------

--
-- Table structure for table `penalties`
--

CREATE TABLE `penalties` (
  `id` int(11) NOT NULL,
  `borrow_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date NOT NULL,
  `late_days` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'Unpaid',
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penalties`
--

INSERT INTO `penalties` (`id`, `borrow_id`, `student_id`, `book_id`, `due_date`, `return_date`, `late_days`, `amount`, `status`, `paid_at`, `created_at`) VALUES
(1, 22, 19, 7, '2026-04-17', '2026-04-19', 2, 20.00, 'Paid', '2026-04-19 09:31:54', '2026-04-19 09:30:32');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `date_return` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`id`, `student_id`, `book_id`, `date_return`) VALUES
(1, 18, 7, '2026-04-01'),
(2, 18, 11, '2026-04-08'),
(3, 18, 11, '2026-04-11'),
(4, 18, 9, '2026-04-11'),
(5, 18, 9, '2026-04-11'),
(6, 18, 8, '2026-04-11'),
(10, 18, 10, '2026-04-11'),
(11, 18, 7, '2026-04-11'),
(12, 18, 7, '2026-04-11'),
(13, 18, 11, '2026-04-11'),
(16, 18, 7, '2026-04-11'),
(17, 18, 9, '2026-04-11'),
(18, 18, 8, '2026-04-11'),
(19, 18, 11, '2026-04-11'),
(20, 18, 12, '2026-04-11'),
(21, 18, 12, '2026-04-11'),
(22, 18, 11, '2026-04-16'),
(23, 18, 8, '2026-04-16'),
(24, 18, 7, '2026-04-18'),
(25, 18, 11, '2026-04-18'),
(26, 18, 11, '2026-04-19'),
(27, 19, 7, '2026-04-19');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `created_on` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `mobile` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `firstname`, `lastname`, `created_on`, `email`, `password`, `gender`, `mobile`) VALUES
(18, 'Krish', 'Thummar', '2026-03-23', 'krishthumar6506@gmail.com', '$2y$10$UMNUtQJk.ei5wj.IO52xeeLThridY6GU.H0YGHjkoZr1KK3eJzQ8e', 'Male', '0989865440'),
(19, 'Krish', 'Thummar', '2026-04-02', 'kthummar467@gmail.com', '$2y$10$MULLHfyMx8wf00.7lZu9bOBmfESUTamVwxb030mA.97JthUwP9g4a', 'Male', '4564564566'),
(20, 'khushaal', 'vamja', '2026-04-03', 'kvamja751@rku.ac.in', '$2y$10$ERiOS8DQflGwKBQlGgHEru6mBau2XmoCcqsSn.JJLV.f2xZApI6ey', 'Male', '9016891845'),
(21, 'Avani', 'Rathod', '2026-04-03', 'avanirathod1111@gmail.com', '$2y$10$B3ySGwa2GHtZvpkySExmau92N1wBRZ5dN7vUs2bj0OYZrXRKvYAeW', 'Female', '1234567890'),
(22, 'Aastha', 'Patel', '2026-04-03', 'apatel785@rku.ac.in', '$2y$10$I.5p.aZ1WG9HFvV.JRwmrOQv6W1c6yogHFyKTuUzTMs2MNaglKuZW', 'Female', '9876543210'),
(23, 'dhaval', 'kanjariya', '2026-04-08', 'dkanjariya00@gmail.com', '$2y$10$ZDcdGRJEd69FEmTBrh8FoOhnjzBn9v9gaRQcJZai096Dp93EDEAuS', 'Male', '1234567890'),
(26, 'page', 'turner', '2026-04-19', 'pageturner899@gmail.com', '$2y$10$f.hdWmGbGiTtFZLeMlnDe.WrqW868B7nCcR381.RUCmmx9TAJZsYW', 'Male', '9898654408');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn_unique` (`isbn`);

--
-- Indexes for table `book_requests`
--
ALTER TABLE `book_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penalties`
--
ALTER TABLE `penalties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `book_requests`
--
ALTER TABLE `book_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penalties`
--
ALTER TABLE `penalties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
