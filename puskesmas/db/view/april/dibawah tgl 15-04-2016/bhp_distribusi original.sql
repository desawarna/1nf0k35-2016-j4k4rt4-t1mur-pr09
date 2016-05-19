-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2016 at 08:07 AM
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
-- Structure for view `bhp_distribusi`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bhp_distribusi` AS select `inv_inventaris_habispakai_pembelian_item`.`id_inv_hasbispakai_pembelian` AS `id_inv_hasbispakai_pembelian`,`inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai` AS `id_mst_inv_barang_habispakai`,`inv_inventaris_habispakai_pembelian_item`.`batch` AS `batch`,`inv_inventaris_habispakai_pembelian_item`.`code_cl_phc` AS `code_cl_phc`,`inv_inventaris_habispakai_pembelian_item`.`harga` AS `harga`,`inv_inventaris_habispakai_pembelian_item`.`tgl_kadaluarsa` AS `tgl_kadaluarsa`,`inv_inventaris_habispakai_pembelian_item`.`tgl_update` AS `tgl_update`,`mst_inv_barang_habispakai`.`uraian` AS `uraian`,`mst_inv_barang_habispakai`.`id_mst_inv_barang_habispakai_jenis` AS `id_mst_inv_barang_habispakai_jenis`,((`inv_inventaris_habispakai_pembelian_item`.`jml` - `inv_inventaris_habispakai_pembelian_item`.`jml_rusak`) - ifnull(`inv_inventaris_habispakai_distribusi_item`.`jml`,0)) AS `jumlah` from (((`inv_inventaris_habispakai_pembelian` left join `inv_inventaris_habispakai_pembelian_item` on(((`inv_inventaris_habispakai_pembelian`.`id_inv_hasbispakai_pembelian` = `inv_inventaris_habispakai_pembelian_item`.`id_inv_hasbispakai_pembelian`) and (`inv_inventaris_habispakai_pembelian`.`pilihan_status_pembelian` = '2')))) left join `inv_inventaris_habispakai_distribusi_item` on(((`inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`) and (`inv_inventaris_habispakai_pembelian_item`.`batch` = `inv_inventaris_habispakai_distribusi_item`.`batch`)))) join `mst_inv_barang_habispakai` on((`mst_inv_barang_habispakai`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`))) where (((`inv_inventaris_habispakai_pembelian_item`.`jml` - `inv_inventaris_habispakai_pembelian_item`.`jml_rusak`) - ifnull(`inv_inventaris_habispakai_distribusi_item`.`jml`,0)) > 0);

--
-- VIEW  `bhp_distribusi`
-- Data: None
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
