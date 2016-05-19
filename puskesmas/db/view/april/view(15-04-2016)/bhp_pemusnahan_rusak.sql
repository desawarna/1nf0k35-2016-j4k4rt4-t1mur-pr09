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
-- Structure for view `bhp_pemusnahan_rusak`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bhp_pemusnahan_rusak` AS select `mst_inv_barang_habispakai`.`uraian` AS `uraian`,`mst_inv_barang_habispakai`.`merek_tipe` AS `merek_tipe`,(ifnull((select `inv_inventaris_habispakai_opname_item`.`jml_akhir` from (`inv_inventaris_habispakai_opname_item` join `inv_inventaris_habispakai_opname` on((`inv_inventaris_habispakai_opname`.`id_inv_inventaris_habispakai_opname` = `inv_inventaris_habispakai_opname_item`.`id_inv_inventaris_habispakai_opname`))) where ((`inv_inventaris_habispakai_opname_item`.`batch` = `inv_inventaris_habispakai_pembelian_item`.`batch`) and (`inv_inventaris_habispakai_opname_item`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`) and (`inv_inventaris_habispakai_opname`.`tipe` = 'terimarusak') and (`inv_inventaris_habispakai_opname`.`tgl_opname` < `tglrusak`())) order by `inv_inventaris_habispakai_opname`.`tgl_opname` desc limit 1),0) + (select sum(`a`.`jml_rusak`) from (`inv_inventaris_habispakai_pembelian_item` `a` join `inv_inventaris_habispakai_pembelian` `b` on((`a`.`id_inv_hasbispakai_pembelian` = `b`.`id_inv_hasbispakai_pembelian`))) where ((`a`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`) and (`a`.`batch` = `inv_inventaris_habispakai_pembelian_item`.`batch`) and (`a`.`code_cl_phc` = `inv_inventaris_habispakai_pembelian_item`.`code_cl_phc`) and (`b`.`tgl_pembelian` > ifnull((select `d`.`tgl_opname` from (`inv_inventaris_habispakai_opname_item` `c` join `inv_inventaris_habispakai_opname` `d` on((`c`.`id_inv_inventaris_habispakai_opname` = `d`.`id_inv_inventaris_habispakai_opname`))) where ((`c`.`batch` = `inv_inventaris_habispakai_pembelian_item`.`batch`) and (`c`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`) and (`inv_inventaris_habispakai_pembelian`.`code_cl_phc` = `d`.`code_cl_phc`) and (`d`.`tipe` = 'terimarusak') and (`d`.`tgl_opname` < `tglrusak`())) order by `d`.`tgl_opname` desc limit 1),'0000-00-00')) and (`b`.`tgl_pembelian` <= `tglrusak`())))) AS `jumlahrusak`,sum(`inv_inventaris_habispakai_pembelian_item`.`jml_rusak`) AS `jml_rusak`,`inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai` AS `id_mst_inv_barang_habispakai`,`inv_inventaris_habispakai_pembelian_item`.`batch` AS `batch`,`inv_inventaris_habispakai_pembelian_item`.`tgl_kadaluarsa` AS `tgl_kadaluarsa`,`inv_inventaris_habispakai_pembelian`.`id_inv_hasbispakai_pembelian` AS `id_inv_hasbispakai_pembelian`,`inv_inventaris_habispakai_pembelian`.`id_mst_inv_barang_habispakai_jenis` AS `id_mst_inv_barang_habispakai_jenis`,`inv_inventaris_habispakai_pembelian`.`jenis_transaksi` AS `jenis_transaksi`,`inv_inventaris_habispakai_pembelian`.`mst_inv_pbf_code` AS `mst_inv_pbf_code`,`inv_inventaris_habispakai_pembelian`.`pilihan_status_pembelian` AS `pilihan_status_pembelian`,`inv_inventaris_habispakai_pembelian`.`pilihan_sumber_dana` AS `pilihan_sumber_dana`,`inv_inventaris_habispakai_pembelian`.`bln_periode` AS `bln_periode`,`inv_inventaris_habispakai_pembelian`.`thn_dana` AS `thn_dana`,`inv_inventaris_habispakai_pembelian`.`tgl_permohonan` AS `tgl_permohonan`,`inv_inventaris_habispakai_pembelian`.`tgl_pembelian` AS `tgl_pembelian`,`inv_inventaris_habispakai_pembelian`.`tgl_kwitansi` AS `tgl_kwitansi`,`inv_inventaris_habispakai_pembelian`.`nomor_kwitansi` AS `nomor_kwitansi`,`inv_inventaris_habispakai_pembelian`.`nomor_kontrak` AS `nomor_kontrak`,`inv_inventaris_habispakai_pembelian`.`jumlah_unit` AS `jumlah_unit`,`inv_inventaris_habispakai_pembelian`.`nilai_pembelian` AS `nilai_pembelian`,`inv_inventaris_habispakai_pembelian`.`keterangan` AS `keterangan`,`inv_inventaris_habispakai_pembelian`.`waktu_dibuat` AS `waktu_dibuat`,`inv_inventaris_habispakai_pembelian`.`terakhir_diubah` AS `terakhir_diubah`,`inv_inventaris_habispakai_pembelian`.`code_cl_phc` AS `code_cl_phc`,if((ifnull((select `inv_inventaris_habispakai_opname`.`tgl_opname` from (`inv_inventaris_habispakai_opname` left join `inv_inventaris_habispakai_opname_item` on((`inv_inventaris_habispakai_opname`.`id_inv_inventaris_habispakai_opname` = `inv_inventaris_habispakai_opname_item`.`id_inv_inventaris_habispakai_opname`))) where ((`inv_inventaris_habispakai_opname_item`.`batch` = `inv_inventaris_habispakai_pembelian_item`.`batch`) and (`inv_inventaris_habispakai_opname_item`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`)) order by `inv_inventaris_habispakai_opname`.`tgl_opname` desc limit 1),(curdate() + interval 1 day)) < curdate()),ifnull((select `inv_inventaris_habispakai_opname_item`.`harga` from (`inv_inventaris_habispakai_opname` left join `inv_inventaris_habispakai_opname_item` on((`inv_inventaris_habispakai_opname`.`id_inv_inventaris_habispakai_opname` = `inv_inventaris_habispakai_opname_item`.`id_inv_inventaris_habispakai_opname`))) where ((`inv_inventaris_habispakai_opname_item`.`batch` = `inv_inventaris_habispakai_pembelian_item`.`batch`) and (`inv_inventaris_habispakai_opname_item`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`)) order by `inv_inventaris_habispakai_opname`.`tgl_opname` desc limit 1),0),ifnull((select `inv_inventaris_habispakai_pembelian_item`.`harga` from (`inv_inventaris_habispakai_pembelian` `h` left join `inv_inventaris_habispakai_pembelian_item` `i` on((`h`.`id_inv_hasbispakai_pembelian` = `i`.`id_inv_hasbispakai_pembelian`))) where ((`i`.`batch` = `inv_inventaris_habispakai_pembelian_item`.`batch`) and (`i`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`)) order by `i`.`tgl_update` desc limit 1),0)) AS `hargaterakhir` from ((`inv_inventaris_habispakai_pembelian_item` join `inv_inventaris_habispakai_pembelian` on((`inv_inventaris_habispakai_pembelian_item`.`id_inv_hasbispakai_pembelian` = `inv_inventaris_habispakai_pembelian`.`id_inv_hasbispakai_pembelian`))) join `mst_inv_barang_habispakai` on((`mst_inv_barang_habispakai`.`id_mst_inv_barang_habispakai` = `inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`))) where (`inv_inventaris_habispakai_pembelian`.`id_mst_inv_barang_habispakai_jenis` = '8') group by `inv_inventaris_habispakai_pembelian`.`code_cl_phc`,`inv_inventaris_habispakai_pembelian_item`.`id_mst_inv_barang_habispakai`,`inv_inventaris_habispakai_pembelian_item`.`batch` having (`jumlahrusak` > 0);

--
-- VIEW  `bhp_pemusnahan_rusak`
-- Data: None
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
