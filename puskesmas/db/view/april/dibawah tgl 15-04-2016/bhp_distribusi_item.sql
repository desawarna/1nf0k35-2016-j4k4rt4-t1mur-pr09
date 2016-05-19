-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2016 at 12:12 PM
-- Server version: 5.6.26
-- PHP Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epus_prog_3205`
--

-- --------------------------------------------------------

--
-- Structure for view `bhp_distribusi_item`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bhp_distribusi_item` AS select `inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai` AS `id_mst_inv_barang_habispakai`,`inv_inventaris_habispakai_distribusi_item`.`batch` AS `batch`,`inv_inventaris_habispakai_distribusi`.`tgl_distribusi` AS `tgl_distribusi`,`inv_inventaris_habispakai_distribusi`.`code_cl_phc` AS `code_cl_phc` from (`inv_inventaris_habispakai_distribusi_item` join `inv_inventaris_habispakai_distribusi` on((`inv_inventaris_habispakai_distribusi_item`.`id_inv_inventaris_habispakai_distribusi` = `inv_inventaris_habispakai_distribusi`.`id_inv_inventaris_habispakai_distribusi`)));

--
-- VIEW  `bhp_distribusi_item`
-- Data: None
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
