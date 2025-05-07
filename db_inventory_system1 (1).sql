-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 02:53 AM
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
-- Database: `db_inventory_system1`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `user_id` int(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'employee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`user_id`, `user_name`, `password`, `role`) VALUES
(19, 'admin', '$2y$10$TdJxUSzbshCtOyKZgBn1DOfWmeMNRWygTYnvpbbq5UwI7qzB1LHfW', 'admin'),
(20, 'employee1', '$2y$10$5lX0i3U64MGDAoqbYGxYuOXl3IicgMMGTRHq/TDjGTHHcR2.lQCYe', 'employee');

-- --------------------------------------------------------

--
-- Table structure for table `tb_deleted_orders`
--

CREATE TABLE `tb_deleted_orders` (
  `order_id` int(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `order_date` date NOT NULL,
  `status` int(255) NOT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_inventory`
--

CREATE TABLE `tb_inventory` (
  `product_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `main_category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_inventory`
--

INSERT INTO `tb_inventory` (`product_id`, `name`, `price`, `category`, `quantity`, `size`, `image_url`, `main_category`) VALUES
(10095, 'GI-PIPES1', '260.00', 'GI-PIPES', '23', '½', 'uploads/pr5.jpg', 'STEEL'),
(10096, 'GI-PIPES2', '360.00', 'GI-PIPES', '14', '¾', 'uploads/pr5.jpg', 'STEEL'),
(10097, 'GI-PIPES3', '480.00', 'GI-PIPES', '13', '1', 'uploads/pr5.jpg', 'STEEL'),
(10098, 'GI-PIPES4', '540.00', 'GI-PIPES', '15', '1¼', 'uploads/pr5.jpg', 'STEEL'),
(10099, 'GI-PIPES5', '780.00', 'GI-PIPES', '13', '1 ½', 'uploads/pr5.jpg', 'STEEL'),
(10100, 'GI-PIPES6', '1150.00', 'GI-PIPES', '16', '2', 'uploads/pr5.jpg', 'STEEL'),
(10101, 'ANGLE BAR1', '350.00', 'ANGLE BAR', '9', '1x1', 'uploads/23.-SS-ANGLE-BAR.jpg', 'STEEL'),
(10102, 'ANGLE BAR2', '480.00', 'ANGLE BAR', '15', '1 ½ x 1 ½', 'uploads/23.-SS-ANGLE-BAR.jpg', 'STEEL'),
(10103, 'ANGLE BAR3', '590.00', 'ANGLE BAR', '16', '2x2', 'uploads/23.-SS-ANGLE-BAR.jpg', 'STEEL'),
(10104, 'ANGLE BAR4 (GREEN)', '700.00', 'ANGLE BAR', '19', '2x2', 'uploads/23.-SS-ANGLE-BAR.jpg', 'STEEL'),
(10106, 'ANGLE BAR5 (GREEN)', '420.00', 'ANGLE BAR', '14', '1x1', 'uploads/23.-SS-ANGLE-BAR.jpg', 'STEEL'),
(10107, 'ANGLE BAR6 (GREEN)', '580.00', 'ANGLE BAR', '15', '1 ½ x 1 ½', 'uploads/23.-SS-ANGLE-BAR.jpg', 'STEEL'),
(10108, 'PURLINS1 (1.2)', '360.00', 'PURLINS', '18', '2x3', 'uploads/steel-purlins-min.jpg', 'STEEL'),
(10109, 'PURLINS2 (1.5)', '460.00', 'PURLINS', '20', '2x3', 'uploads/steel-purlins-min.jpg', 'STEEL'),
(10110, 'PURLINS3 (1.2)', '420.00', 'PURLINS', '15', '2x4', 'uploads/steel-purlins-min.jpg', 'STEEL'),
(10111, 'PURLINS4 (1.5)', '520.00', 'PURLINS', '24', '2x4', 'uploads/steel-purlins-min.jpg', 'STEEL'),
(10112, 'PURLINS5 (1.2)', '620.00', 'PURLINS', '15', '2x6', 'uploads/steel-purlins-min.jpg', 'STEEL'),
(10113, 'PURLINS6 (1.5)', '640.00', 'PURLINS', '18', '2x6', 'uploads/steel-purlins-min.jpg', 'STEEL'),
(10114, 'STEEL MATTING1', '640.00', 'STEEL MATTING', '10', '6', 'uploads/steelmatting.png', 'STEEL'),
(10115, 'STEEL MATTING2', '420.00', 'STEEL MATTING', '9', '4', 'uploads/steelmatting.png', 'STEEL'),
(10116, 'FLAT BAR(RED1)', '250.00', 'FLAT BAR', '16', '1', 'uploads/flat4.jpg', 'STEEL'),
(10117, 'FLAT BAR(RED2)', '390.00', 'FLAT BAR', '9', '1 ½', 'uploads/flat4.jpg', 'STEEL'),
(10118, 'FLAT BAR(RED3)', '460.00', 'FLAT BAR', '13', '2', 'uploads/flat4.jpg', 'STEEL'),
(10119, 'PLYWOOD', '420.00', 'MARINE PLYWOOD', '20', '4ft x 8ft', 'uploads/plywood.jpg', 'WOOD'),
(10128, 'ANGLE BAR10', '460', 'ANGLE BAR', '14', '2x4', 'uploads/1742605649_23.-SS-ANGLE-BAR.jpg', 'STEEL'),
(10147, 'ANGLE BAR11', '460', 'ANGLE BAR', '15', '2', 'uploads/1742605649_23.-SS-ANGLE-BAR.jpg', 'STEEL');

-- --------------------------------------------------------

--
-- Table structure for table `tb_orders`
--

CREATE TABLE `tb_orders` (
  `order_id` int(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_orders`
--

INSERT INTO `tb_orders` (`order_id`, `customer_name`, `product_name`, `quantity`, `order_date`, `status`, `size`, `deleted_at`, `amount_paid`) VALUES
(195, 'Jake Lim', 'GI-PIPES1', '3', '2025-03-03', 'Pending', '½', NULL, 0.00),
(196, 'Jake Lim', 'GI-PIPES2', '2', '2025-03-03', 'Pending', '¾', NULL, 0.00),
(197, 'Jake Lim', 'ANGLE BAR1', '4', '2025-03-03', 'Pending', '1x1', NULL, 0.00),
(205, 'Lourdes Dizon', 'FLAT BAR(RED)', '3', '2025-03-04', 'Pending', '1', NULL, 0.00),
(206, 'Lourdes Dizon', 'FLAT BAR(RED1)', '3', '2025-03-03', 'Pending', '1', NULL, 0.00),
(208, 'Rafael Santos', 'STEEL MATTING2', '2', '2025-03-04', 'Pending', '4', NULL, 0.00),
(209, 'Rafael Santos', 'ANGLE BAR5 (GREEN)', '3', '2025-03-04', 'Pending', '1x1', NULL, 0.00),
(211, 'Antonio Garcia', 'GI-PIPES2', '3', '2025-03-05', 'Pending', '¾', NULL, 0.00),
(212, 'Antonio Garcia', 'ANGLE BAR2', '2', '2025-03-05', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(213, 'Antonio Garcia', 'ANGLE BAR4 (GREEN)', '2', '2025-03-05', 'Pending', '2x2', NULL, 0.00),
(214, 'Isabel Ramos', 'ANGLE BAR4 (GREEN)', '2', '2025-03-05', 'Pending', '2x2', NULL, 0.00),
(215, 'Isabel Ramos', 'ANGLE BAR5 (GREEN)', '2', '2025-03-05', 'Pending', '1x1', NULL, 0.00),
(216, 'Eduardo Velasco', 'PURLINS1 (1.2)', '2', '2025-03-07', 'Pending', '2x3', NULL, 0.00),
(217, 'Eduardo Velasco', 'PURLINS2 (1.5)', '2', '2025-03-07', 'Pending', '2x3', NULL, 0.00),
(218, 'Eduardo Velasco', 'PURLINS3 (1.2)', '1', '2025-03-07', 'Pending', '2x4', NULL, 0.00),
(219, 'Carlos Aguilar', 'PURLINS4 (1.5)', '4', '2025-03-06', 'Pending', '2x4', NULL, 0.00),
(222, 'Fernando Castro', 'PURLINS1 (1.2)', '1', '2025-03-07', 'Pending', '2x3', NULL, 0.00),
(223, 'Fernando Castro', 'PURLINS5 (1.2)', '3', '2025-03-07', 'Pending', '2x6', NULL, 0.00),
(225, 'Vicente Navarro', 'GI-PIPES4', '2', '2025-03-07', 'Pending', '1¼', NULL, 0.00),
(226, 'Nicolas Castillo', 'STEEL MATTING1', '2', '2025-03-10', 'Pending', '6', NULL, 0.00),
(227, 'Nicolas Castillo', 'STEEL MATTING2', '3', '2025-03-10', 'Pending', '4', NULL, 0.00),
(228, 'Alfredo Garcia', 'ANGLE BAR4 (GREEN)', '2', '2025-03-11', 'Pending', '2x2', NULL, 0.00),
(229, 'Alfredo Garcia', 'ANGLE BAR5 (GREEN)', '1', '2025-03-11', 'Pending', '1x1', NULL, 0.00),
(230, 'Alfredo Garcia', 'ANGLE BAR6 (GREEN)', '1', '2025-03-11', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(231, 'Antonio De Vera', 'ANGLE BAR4 (GREEN)', '5', '2025-03-11', 'Pending', '2x2', NULL, 0.00),
(232, 'Emilio Reyes', 'ANGLE BAR6 (GREEN)', '3', '2025-03-12', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(233, 'Emilio Reyes', 'ANGLE BAR5 (GREEN)', '2', '2025-03-12', 'Pending', '1x1', NULL, 0.00),
(234, 'Roberto Ramos', 'GI-PIPES1', '5', '2025-03-13', 'Pending', '½', NULL, 0.00),
(235, 'Benigno Cruz', 'FLAT BAR(RED1)', '3', '2025-03-13', 'Pending', '1', NULL, 0.00),
(236, 'Benigno Cruz', 'FLAT BAR(RED2)', '2', '2025-03-13', 'Pending', '1 ½', NULL, 0.00),
(237, 'Benigno Cruz', 'ANGLE BAR11', '2', '2025-03-13', 'Pending', '2', NULL, 0.00),
(238, 'Felipe Santos', 'ANGLE BAR2', '3', '2025-03-14', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(239, 'Jose Alvarado', 'ANGLE BAR6 (GREEN)', '2', '2025-03-14', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(240, 'Jose Alvarado', 'GI-PIPES3', '2', '2025-03-14', 'Pending', '1', NULL, 0.00),
(241, 'Jose Alvarado', 'STEEL MATTING1', '1', '2025-03-14', 'Pending', '6', NULL, 0.00),
(242, 'Teodoro Santos', 'GI-PIPES6', '2', '2025-03-17', 'Pending', '2', NULL, 0.00),
(243, 'Nicolas Aquino', 'PURLINS1 (1.2)', '3', '2025-03-17', 'Pending', '2x3', NULL, 0.00),
(244, 'Nicolas Aquino', 'PURLINS5 (1.2)', '3', '2025-03-17', 'Pending', '2x6', NULL, 0.00),
(246, 'Pablo Reyes', 'PURLINS1 (1.2)', '1', '2025-03-18', 'Pending', '2x3', NULL, 0.00),
(247, 'Pablo Reyes', 'PURLINS2 (1.5)', '2', '2025-03-18', 'Pending', '2x3', NULL, 0.00),
(248, 'Pablo Reyes', 'PURLINS3 (1.2)', '1', '2025-03-18', 'Pending', '2x4', NULL, 0.00),
(249, 'Alfonso Pangilinan', 'GI-PIPES3', '1', '2025-03-19', 'Pending', '1', NULL, 0.00),
(250, 'Alfonso Pangilinan', 'GI-PIPES4', '2', '2025-03-19', 'Pending', '1¼', NULL, 0.00),
(251, 'Alfonso Pangilinan', 'GI-PIPES6', '1', '2025-03-19', 'Pending', '2', NULL, 0.00),
(252, 'Felix Ramos', 'ANGLE BAR5 (GREEN)', '2', '2025-03-20', 'Pending', '1x1', NULL, 0.00),
(253, 'Felix Ramos', 'GI-PIPES4', '2', '2025-03-20', 'Pending', '1¼', NULL, 0.00),
(254, 'Ernesto Garcia', 'GI-PIPES6', '2', '2025-03-21', 'Pending', '2', NULL, 0.00),
(255, 'Ernesto Garcia', 'GI-PIPES5', '2', '2025-03-21', 'Pending', '1 ½', NULL, 0.00),
(256, 'Oscar Santos', 'GI-PIPES1', '2', '2025-03-21', 'Pending', '½', NULL, 0.00),
(257, 'Santiago Perez', 'FLAT BAR(RED3)', '2', '2025-03-24', 'Pending', '2', NULL, 0.00),
(258, 'Santiago Perez', 'FLAT BAR(RED2)', '2', '2025-03-24', 'Pending', '1 ½', NULL, 0.00),
(259, 'Santiago Perez', 'FLAT BAR(RED1)', '1', '2025-03-24', 'Pending', '1', NULL, 0.00),
(261, 'William Reyes', 'PURLINS1 (1.2)', '2', '2025-03-24', 'Pending', '2x3', NULL, 0.00),
(262, 'William Reyes', 'PURLINS2 (1.5)', '2', '2025-03-24', 'Pending', '2x3', NULL, 0.00),
(263, 'Victor Lopez', 'STEEL MATTING1', '1', '2025-03-25', 'Pending', '6', NULL, 0.00),
(264, 'Victor Lopez', 'STEEL MATTING2', '1', '2025-03-25', 'Pending', '4', NULL, 0.00),
(265, 'Leopoldo Castillo', 'GI-PIPES1', '3', '2025-03-25', 'Pending', '½', NULL, 0.00),
(266, 'Patricio Samson', 'PURLINS2 (1.5)', '2', '2025-03-26', 'Pending', '2x3', NULL, 0.00),
(267, 'Jacinto Gutierrez', 'GI-PIPES4', '1', '2025-03-27', 'Pending', '1¼', NULL, 0.00),
(269, 'Emilio David', 'GI-PIPES3', '2', '2025-03-28', 'Pending', '1', NULL, 0.00),
(270, 'Dominic Cunanan', 'ANGLE BAR1', '2', '2025-03-28', 'Pending', '1x1', NULL, 0.00),
(271, 'Maria Pineda', 'GI-PIPES5', '2', '2025-03-31', 'Pending', '1 ½', NULL, 0.00),
(272, 'Maria Pineda', 'ANGLE BAR2', '2', '2025-03-31', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(273, 'Mark Guevarra', 'GI-PIPES1', '4', '2025-04-01', 'Pending', '½', NULL, 0.00),
(274, 'Kristine De Leon', 'ANGLE BAR1', '3', '2025-04-01', 'Pending', '1x1', NULL, 0.00),
(275, 'Kristine De Leon', 'ANGLE BAR2', '2', '2025-04-01', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(276, 'Juan Manalansan', 'ANGLE BAR10', '2', '2025-04-02', 'Pending', '2x4', NULL, 0.00),
(277, 'Juan Manalansan', 'ANGLE BAR11', '2', '2025-04-02', 'Pending', '2', NULL, 0.00),
(278, 'Juan Manalansan', 'FLAT BAR(RED1)', '2', '2025-04-02', 'Pending', '1', NULL, 0.00),
(279, 'Rafael Manabat', 'ANGLE BAR6 (GREEN)', '3', '2025-04-03', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(280, 'Rafael Manabat', 'ANGLE BAR4 (GREEN)', '2', '2025-04-03', 'Pending', '2x2', NULL, 0.00),
(282, 'Daniel Datu', 'PURLINS2 (1.5)', '4', '2025-04-04', 'Pending', '2x3', NULL, 0.00),
(283, 'Anthony Soriano', 'GI-PIPES1', '2', '2025-04-04', 'Pending', '½', NULL, 0.00),
(284, 'Anthony Soriano', 'GI-PIPES4', '2', '2025-04-04', 'Pending', '1¼', NULL, 0.00),
(285, 'Gabriel Lingad', 'GI-PIPES4', '3', '2025-04-07', 'Pending', '1¼', NULL, 0.00),
(286, 'Gabriel Lingad', 'GI-PIPES5', '2', '2025-04-07', 'Pending', '1 ½', NULL, 0.00),
(289, 'Jerome Cunanan', 'ANGLE BAR1', '3', '2025-04-07', 'Pending', '1x1', NULL, 0.00),
(290, 'Patricia Salonga', 'PURLINS1 (1.2)', '3', '2025-04-08', 'Pending', '2x3', NULL, 0.00),
(291, 'Patricia Salonga', 'PURLINS2 (1.5)', '3', '2025-04-08', 'Pending', '2x3', NULL, 0.00),
(292, 'Francis Yambao', 'STEEL MATTING1', '3', '2025-04-09', 'Pending', '6', NULL, 0.00),
(293, 'Catherine Pangilinan', 'ANGLE BAR5 (GREEN)', '2', '2025-04-09', 'Pending', '1x1', NULL, 0.00),
(294, 'Catherine Pangilinan', 'ANGLE BAR4 (GREEN)', '1', '2025-04-09', 'Pending', '2x2', NULL, 0.00),
(295, 'Victor Manlapig', 'FLAT BAR(RED1)', '2', '2025-04-10', 'Pending', '1', NULL, 0.00),
(296, 'Victor Manlapig', 'FLAT BAR(RED2)', '2', '2025-04-10', 'Pending', '1 ½', NULL, 0.00),
(297, 'Victor Manlapig', 'FLAT BAR(RED3)', '1', '2025-04-10', 'Pending', '2', NULL, 0.00),
(298, 'Charlene Paguio', 'STEEL MATTING2', '2', '2025-04-11', 'Pending', '4', NULL, 0.00),
(299, 'Charlene Paguio', 'PURLINS6 (1.5)', '1', '2025-04-11', 'Pending', '2x6', NULL, 0.00),
(300, 'Jomar Lugtu', 'PURLINS1 (1.2)', '3', '2025-04-14', 'Pending', '2x3', NULL, 0.00),
(301, 'Jomar Lugtu', 'PURLINS2 (1.5)', '2', '2025-04-14', 'Pending', '2x3', NULL, 0.00),
(305, 'Rina Balingit', 'GI-PIPES2', '3', '2025-04-14', 'Pending', '¾', NULL, 0.00),
(306, 'Marites Sampang', 'PURLINS1 (1.2)', '2', '2025-04-16', 'Pending', '2x3', NULL, 0.00),
(307, 'Marites Sampang', 'PURLINS2 (1.5)', '2', '2025-04-16', 'Pending', '2x3', NULL, 0.00),
(308, 'Alvin Payumo', 'ANGLE BAR1', '2', '2025-04-16', 'Pending', '1x1', NULL, 0.00),
(309, 'Alvin Payumo', 'ANGLE BAR4 (GREEN)', '1', '2025-04-16', 'Pending', '2x2', NULL, 0.00),
(310, 'Alvin Payumo', 'ANGLE BAR5 (GREEN)', '1', '2025-04-16', 'Pending', '1x1', NULL, 0.00),
(311, 'Bryan Macapagal', 'FLAT BAR(RED3)', '2', '2025-04-17', 'Pending', '2', NULL, 0.00),
(312, 'Bryan Macapagal', 'FLAT BAR(RED2)', '1', '2025-04-17', 'Pending', '1 ½', NULL, 0.00),
(313, 'Kenneth Gueco', 'PURLINS1 (1.2)', '2', '2025-04-18', 'Pending', '2x3', NULL, 0.00),
(314, 'Lea Canlas', 'ANGLE BAR1', '1', '2025-04-18', 'Pending', '1x1', NULL, 0.00),
(315, 'Lea Canlas', 'ANGLE BAR2', '1', '2025-04-18', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(316, 'Lea Canlas', 'ANGLE BAR3', '1', '2025-04-18', 'Pending', '2x2', NULL, 0.00),
(317, 'Samuel Mallari', 'ANGLE BAR11', '1', '2025-04-21', 'Pending', '2', NULL, 0.00),
(318, 'Samuel Mallari', 'ANGLE BAR10', '1', '2025-04-21', 'Pending', '2x4', NULL, 0.00),
(319, 'Jeffrey Lacap', 'GI-PIPES1', '2', '2025-04-22', 'Pending', '½', NULL, 0.00),
(320, 'Jeffrey Lacap', 'GI-PIPES2', '1', '2025-04-22', 'Pending', '¾', NULL, 0.00),
(321, 'Jeffrey Lacap', 'GI-PIPES3', '1', '2025-04-22', 'Pending', '1', NULL, 0.00),
(322, 'Jeffrey Lacap', 'GI-PIPES4', '1', '2025-04-22', 'Pending', '1¼', NULL, 0.00),
(323, 'Nathaniel Ocampo', 'ANGLE BAR1', '2', '2025-04-22', 'Pending', '1x1', NULL, 0.00),
(324, 'Miguel Salalila', 'FLAT BAR(RED1)', '2', '2025-04-23', 'Pending', '1', NULL, 0.00),
(325, 'Miguel Salalila', 'FLAT BAR(RED2)', '2', '2025-04-23', 'Pending', '1 ½', NULL, 0.00),
(328, 'Adrian Dela Pena', 'GI-PIPES5', '2', '2025-04-24', 'Pending', '1 ½', NULL, 0.00),
(329, 'Adrian Dela Pena', 'GI-PIPES6', '1', '2025-04-24', 'Pending', '2', NULL, 0.00),
(330, 'Christian Dayrit', 'ANGLE BAR4 (GREEN)', '2', '2025-04-24', 'Pending', '2x2', NULL, 0.00),
(331, 'Christian Dayrit', 'ANGLE BAR3', '1', '2025-04-24', 'Pending', '2x2', NULL, 0.00),
(332, 'Christian Dayrit', 'ANGLE BAR6 (GREEN)', '1', '2025-04-24', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(333, 'Patrick Pangilinan', 'PURLINS5 (1.2)', '2', '2025-04-28', 'Pending', '2x6', NULL, 0.00),
(334, 'Patrick Pangilinan', 'PURLINS6 (1.5)', '2', '2025-04-28', 'Pending', '2x6', NULL, 0.00),
(335, 'Michael Mallari', 'GI-PIPES2', '2', '2025-04-28', 'Pending', '¾', NULL, 0.00),
(336, 'Michael Mallari', 'GI-PIPES3', '2', '2025-04-28', '', '1', NULL, 0.00),
(337, 'Angelo Manalansan', 'ANGLE BAR11', '1', '2025-04-30', 'Pending', '2', NULL, 0.00),
(338, 'Angelo Manalansan', 'ANGLE BAR10', '1', '2025-04-30', 'Pending', '2x4', NULL, 0.00),
(339, 'Jomar David', 'GI-PIPES3', '2', '2025-05-01', 'Pending', '1', NULL, 0.00),
(340, 'Jomar David', 'GI-PIPES2', '1', '2025-05-01', 'Pending', '¾', NULL, 0.00),
(341, 'Joshua Dela Pena', 'GI-PIPES5', '2', '2025-05-02', 'Pending', '1 ½', NULL, 0.00),
(342, 'Joshua Dela Pena', 'ANGLE BAR1', '1', '2025-05-02', 'Pending', '1x1', NULL, 0.00),
(343, 'Joshua Dela Pena', 'ANGLE BAR3', '1', '2025-05-02', 'Pending', '2x2', NULL, 0.00),
(344, 'Jerome Lugtu', 'ANGLE BAR6 (GREEN)', '2', '2025-05-05', 'Pending', '1 ½ x 1 ½', NULL, 0.00),
(345, 'Jerome Lugtu', 'ANGLE BAR5 (GREEN)', '2', '2025-05-05', 'Pending', '1x1', NULL, 0.00),
(346, 'Marvin Tayag', 'ANGLE BAR1', '2', '2025-05-05', 'Pending', '1x1', NULL, 0.00),
(347, 'Marvin Tayag', 'GI-PIPES5', '1', '2025-05-05', '', '1 ½', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `tb_order_items`
--

CREATE TABLE `tb_order_items` (
  `item_id` int(255) NOT NULL,
  `order_id` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_settings`
--

CREATE TABLE `tb_settings` (
  `id` int(255) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_date`, `amount`) VALUES
(1, '2025-01-15', 200.00),
(2, '2025-02-11', 500.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tb_deleted_orders`
--
ALTER TABLE `tb_deleted_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tb_orders`
--
ALTER TABLE `tb_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tb_order_items`
--
ALTER TABLE `tb_order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tb_settings`
--
ALTER TABLE `tb_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_deleted_orders`
--
ALTER TABLE `tb_deleted_orders`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;

--
-- AUTO_INCREMENT for table `tb_inventory`
--
ALTER TABLE `tb_inventory`
  MODIFY `product_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10167;

--
-- AUTO_INCREMENT for table `tb_orders`
--
ALTER TABLE `tb_orders`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;

--
-- AUTO_INCREMENT for table `tb_order_items`
--
ALTER TABLE `tb_order_items`
  MODIFY `item_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_settings`
--
ALTER TABLE `tb_settings`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_order_items`
--
ALTER TABLE `tb_order_items`
  ADD CONSTRAINT `tb_order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tb_order_items` (`item_id`),
  ADD CONSTRAINT `tb_order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tb_order_items` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
