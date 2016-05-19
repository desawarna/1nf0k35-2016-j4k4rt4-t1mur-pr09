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
-- Structure for view `bhp_distribusi_opname`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bhp_distribusi_opname` AS select `inv_inventaris_habispakai_distribusi`.`id_inv_inventaris_habispakai_distribusi` AS `id_inv_inventaris_habispakai_distribusi`,`inv_inventaris_habispakai_distribusi`.`code_cl_phc` AS `code_cl_phc`,`inv_inventaris_habispakai_distribusi`.`jenis_bhp` AS `jenis_bhp`,`inv_inventaris_habispakai_distribusi`.`bln_periode` AS `bln_periode`,`inv_inventaris_habispakai_distribusi`.`tgl_distribusi` AS `tgl_distribusi`,`inv_inventaris_habispakai_distribusi`.`nomor_dokumen` AS `nomor_dokumen`,`inv_inventaris_habispakai_distribusi`.`penerima_nama` AS `penerima_nama`,`inv_inventaris_habispakai_distribusi`.`penerima_nip` AS `penerima_nip`,`inv_inventaris_habispakai_distribusi`.`keterangan` AS `keterangan`,`inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai` AS `id_mst_inv_barang_habispakai`,`inv_inventaris_habispakai_distribusi_item`.`batch` AS `batch`,`mst_inv_barang_habispakai`.`uraian` AS `uraian`,`mst_inv_barang_habispakai`.`id_mst_inv_barang_habispakai_jenis` AS `id_mst_inv_barang_habispakai_jenis`,`inv_inventaris_habispakai_distribusi_item`.`jml` AS `jml_distribusi`,`inv_inventaris_habispakai_opname_item`.`jml_akhir` AS `jml_opname`,`inv_inventaris_habispakai_opname_item`.`harga` AS `harga_opname`,`inv_inventaris_habispakai_pembelian_item`.`harga` AS `harga_beli`,`inv_inventaris_habispakai_pembelian_item`.`tgl_update` AS `tgl_beli`,`inv_inventaris_habispakai_opname`.`tgl_opname` AS `tgl_opname`,if((ifnull(`inv_inventaris_habispakai_opname`.`tgl_opname`,'0000-00-00') > curdate()),ifnull(`inv_inventaris_habispakai_opname_item`.`jml_akhir`,0),ifnull(`inv_inventaris_habispakai_distribusi_item`.`jml`,0)) AS `jmlawal`,if((ifnull(`inv_inventaris_habispakai_opname`.`tgl_opname`,'0000-00-00') > curdate()),ifnull(`inv_inventaris_habispakai_opname_item`.`harga`,0),ifnull(`inv_inventaris_habispakai_pembelian_item`.`harga`,0)) AS `harga` from (((((`inv_inventaris_habispakai_distribusi` left join `inv_inventaris_habispakai_distribusi_item` on((`inv_inventaris_habispakai_distribusi`.`id_inv_inventaris_habispakai_distribusi` = `inv_inventaris_habispakai_distribusi_item`.`id_inv_inventaris_habispakai_distribusi`))) join `mst_inv_barang_habispakai` on((`mst_inv_barang_habispakai`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai`))) left join `inv_inventaris_habispakai_opname_item` on(((`inv_inventaris_habispakai_opname_item`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai`) and (`inv_inventaris_habispakai_opname_item`.`batch` = `inv_inventaris_habispakai_distribusi_item`.`batch`)))) left join `inv_inventaris_habispakai_opname` on((`inv_inventaris_habispakai_opname`.`id_inv_inventaris_habispakai_opname` = `inv_inventaris_habispakai_opname_item`.`id_inv_inventaris_habispakai_opname`))) left join `inv_inventaris_habispakai_pembelian_item` on(((`inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai`) and (`inv_inventaris_habispakai_pembelian_item`.`batch` = `inv_inventaris_habispakai_distribusi_item`.`batch`)))) group by `inv_inventaris_habispakai_distribusi_item`.`batch`,`inv_inventaris_habispakai_distribusi_item`.`id_mst_inv_barang_habispakai`;

--
-- VIEW  `bhp_distribusi_opname`
-- Data: None
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
