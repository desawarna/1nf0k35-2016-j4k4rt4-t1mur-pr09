-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2016 at 06:42 AM
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
-- Structure for view `bhp_kondisi`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bhp_kondisi` AS select `inv_inventaris_habispakai_kondisi`.`batch` AS `batch`,`inv_inventaris_habispakai_kondisi`.`code_cl_phc` AS `code_cl_phc`,`inv_inventaris_habispakai_kondisi`.`id_inv_inventaris_habispakai_opname` AS `id_inv_inventaris_habispakai_opname`,`inv_inventaris_habispakai_kondisi`.`id_mst_inv_barang_habispakai` AS `id_mst_inv_barang_habispakai`,(select `c`.`jml_rusak` from `inv_inventaris_habispakai_kondisi` `c` where ((`c`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_kondisi`.`id_mst_inv_barang_habispakai`) and (`c`.`batch` = `inv_inventaris_habispakai_kondisi`.`batch`) and (`c`.`code_cl_phc` = `inv_inventaris_habispakai_kondisi`.`code_cl_phc`)) order by `c`.`tgl_update` desc limit 1) AS `jml_rusak`,(select `c`.`jml_tdkdipakai` from `inv_inventaris_habispakai_kondisi` `c` where ((`c`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_kondisi`.`id_mst_inv_barang_habispakai`) and (`c`.`batch` = `inv_inventaris_habispakai_kondisi`.`batch`) and (`c`.`code_cl_phc` = `inv_inventaris_habispakai_kondisi`.`code_cl_phc`)) order by `c`.`tgl_update` desc limit 1) AS `jml_tdkdipakai`,(select `h`.`tgl_update` from `inv_inventaris_habispakai_kondisi` `h` where ((`h`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_kondisi`.`id_mst_inv_barang_habispakai`) and (`h`.`batch` = `inv_inventaris_habispakai_kondisi`.`batch`) and (`h`.`code_cl_phc` = `inv_inventaris_habispakai_kondisi`.`code_cl_phc`)) order by `h`.`tgl_update` desc limit 1) AS `tgl_update`,`mst_inv_barang_habispakai`.`uraian` AS `uraian`,(ifnull((select `inv_inventaris_habispakai_opname_item`.`jml_akhir` from (`inv_inventaris_habispakai_opname_item` join `inv_inventaris_habispakai_opname` on((`inv_inventaris_habispakai_opname`.`id_inv_inventaris_habispakai_opname` = `inv_inventaris_habispakai_opname_item`.`id_inv_inventaris_habispakai_opname`))) where ((`inv_inventaris_habispakai_opname_item`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_kondisi`.`id_mst_inv_barang_habispakai`) and (`inv_inventaris_habispakai_opname_item`.`batch` = `inv_inventaris_habispakai_kondisi`.`batch`)) order by `inv_inventaris_habispakai_opname`.`tgl_opname` desc limit 1),0) + ifnull((select sum(`inv_inventaris_habispakai_distribusi_item`.`jml`) from (`inv_inventaris_habispakai_distribusi_item` left join `inv_inventaris_habispakai_distribusi` on((`inv_inventaris_habispakai_distribusi`.`id_inv_inventaris_habispakai_distribusi` = `inv_inventaris_habispakai_distribusi_item`.`id_inv_inventaris_habispakai_distribusi`))) where ((`inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_kondisi`.`id_mst_inv_barang_habispakai`) and (`inv_inventaris_habispakai_distribusi_item`.`batch` = `inv_inventaris_habispakai_kondisi`.`batch`) and (`inv_inventaris_habispakai_distribusi`.`tgl_distribusi` > ifnull((select `b`.`tgl_opname` from (`inv_inventaris_habispakai_opname_item` `a` join `inv_inventaris_habispakai_opname` `b` on((`a`.`id_inv_inventaris_habispakai_opname` = `b`.`id_inv_inventaris_habispakai_opname`))) where ((`a`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai`) and (`a`.`batch` = `inv_inventaris_habispakai_distribusi_item`.`batch`)) order by `b`.`tgl_opname` desc limit 1),'0000-00-00')))),0)) AS `jml_asli`,`mst_inv_barang_habispakai`.`id_mst_inv_barang_habispakai_jenis` AS `id_mst_inv_barang_habispakai_jenis`,`mst_inv_barang_habispakai`.`pilihan_satuan` AS `pilihan_satuan`,(select `inv_inventaris_habispakai_opname_item`.`harga` from `inv_inventaris_habispakai_opname_item` where ((`inv_inventaris_habispakai_opname_item`.`id_inv_inventaris_habispakai_opname` = `inv_inventaris_habispakai_kondisi`.`id_inv_inventaris_habispakai_opname`) and (`inv_inventaris_habispakai_opname_item`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_kondisi`.`id_mst_inv_barang_habispakai`) and (`inv_inventaris_habispakai_opname_item`.`batch` = `inv_inventaris_habispakai_kondisi`.`batch`))) AS `harga` from (`inv_inventaris_habispakai_kondisi` join `mst_inv_barang_habispakai` on((`mst_inv_barang_habispakai`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_kondisi`.`id_mst_inv_barang_habispakai`))) group by `inv_inventaris_habispakai_kondisi`.`id_mst_inv_barang_habispakai`,`inv_inventaris_habispakai_kondisi`.`batch`,`inv_inventaris_habispakai_kondisi`.`code_cl_phc`;

--
-- VIEW  `bhp_kondisi`
-- Data: None
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
