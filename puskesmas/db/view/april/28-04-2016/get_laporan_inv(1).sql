-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 28 Apr 2016 pada 06.51
-- Versi Server: 5.6.26
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
-- Struktur untuk view `get_laporan_inv`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `get_laporan_inv` AS select `mst_inv_barang`.`uraian` AS `uraian`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '01'),`inv_inventaris_barang_a`.`status_sertifikat_nomor`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '02'),ifnull(`inv_inventaris_barang_b`.`nomor_bpkb`,`inv_inventaris_barang_b`.`no_polisi`),if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '03'),ifnull(`inv_inventaris_barang_c`.`dokumen_nomor`,`inv_inventaris_barang_c`.`nomor_kode_tanah`),if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '04'),ifnull(`inv_inventaris_barang_d`.`dokumen_nomor`,`inv_inventaris_barang_d`.`nomor_kode_tanah`),if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '06'),`inv_inventaris_barang_f`.`dokumen_nomor`,'-'))))) AS `nobukti`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '02'),`bahan_b`.`value`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '03'),`bahan_c`.`value`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '05'),`bahan_e`.`value`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '06'),`bahan_f`.`value`,'-')))) AS `bahan`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '01'),`inv_inventaris_barang_a`.`luas`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '02'),`inv_inventaris_barang_b`.`ukuran_barang`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '03'),`inv_inventaris_barang_c`.`luas_lantai`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '04'),`inv_inventaris_barang_d`.`luas`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '05'),`inv_inventaris_barang_e`.`flora_fauna_ukuran`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '06'),`inv_inventaris_barang_f`.`luas`,'-')))))) AS `ukuran`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '01'),`luas_a`.`value`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '02'),`luas_b`.`value`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '05'),`luas_e`.`value`,'-'))) AS `satuan`,if((left(`inv_inventaris_barang`.`id_mst_inv_barang`,2) = '02'),`inv_inventaris_barang_b`.`merek_type`,'-') AS `merk`,`keadaan`.`value` AS `keadaan`,ifnull(`asal`.`value`,'-') AS `asal`,`inv_inventaris_barang`.`id_inventaris_barang` AS `id_inventaris_barang`,`inv_inventaris_barang`.`register` AS `register`,`inv_inventaris_barang`.`id_mst_inv_barang` AS `id_mst_inv_barang`,`inv_inventaris_barang`.`id_pengadaan` AS `id_pengadaan`,`inv_inventaris_barang`.`pilihan_keadaan_barang` AS `pilihan_keadaan_barang`,`inv_inventaris_barang`.`nama_barang` AS `nama_barang`,`inv_inventaris_barang`.`pilihan_komponen` AS `pilihan_komponen`,`inv_inventaris_barang`.`harga` AS `harga`,`inv_inventaris_barang`.`keterangan_pengadaan` AS `keterangan_pengadaan`,`inv_inventaris_barang`.`pilihan_status_invetaris` AS `pilihan_status_invetaris`,`inv_inventaris_barang`.`tanggal_pembelian` AS `tanggal_pembelian`,`inv_inventaris_barang`.`foto_barang` AS `foto_barang`,`inv_inventaris_barang`.`barang_kembar_proc` AS `barang_kembar_proc`,`inv_inventaris_barang`.`keterangan_inventory` AS `keterangan_inventory`,`inv_inventaris_barang`.`tanggal_pengadaan` AS `tanggal_pengadaan`,`inv_inventaris_barang`.`tanggal_diterima` AS `tanggal_diterima`,`inv_inventaris_barang`.`tanggal_dihapus` AS `tanggal_dihapus`,`inv_inventaris_barang`.`alasan_penghapusan` AS `alasan_penghapusan`,`inv_inventaris_barang`.`pilihan_asal` AS `pilihan_asal`,`inv_inventaris_barang`.`waktu_dibuat` AS `waktu_dibuat`,`inv_inventaris_barang`.`terakhir_diubah` AS `terakhir_diubah`,`inv_inventaris_barang`.`code_cl_phc` AS `code_cl_phc`,count(`inv_inventaris_barang`.`id_inventaris_barang`) AS `jumlah` from ((((((((((((((((`inv_inventaris_barang` left join `mst_inv_barang` on((`inv_inventaris_barang`.`id_mst_inv_barang` = `mst_inv_barang`.`code`))) left join `inv_inventaris_barang_a` on((`inv_inventaris_barang_a`.`id_inventaris_barang` = `inv_inventaris_barang`.`id_inventaris_barang`))) left join `inv_inventaris_barang_b` on((`inv_inventaris_barang_b`.`id_inventaris_barang` = `inv_inventaris_barang`.`id_inventaris_barang`))) left join `inv_inventaris_barang_c` on((`inv_inventaris_barang_c`.`id_inventaris_barang` = `inv_inventaris_barang`.`id_inventaris_barang`))) left join `inv_inventaris_barang_d` on((`inv_inventaris_barang_d`.`id_inventaris_barang` = `inv_inventaris_barang`.`id_inventaris_barang`))) left join `inv_inventaris_barang_e` on((`inv_inventaris_barang_e`.`id_inventaris_barang` = `inv_inventaris_barang`.`id_inventaris_barang`))) left join `inv_inventaris_barang_f` on((`inv_inventaris_barang_f`.`id_inventaris_barang` = `inv_inventaris_barang`.`id_inventaris_barang`))) left join `mst_inv_pilihan` `bahan_b` on(((convert(`inv_inventaris_barang_b`.`pilihan_bahan` using utf8) = `bahan_b`.`code`) and (`bahan_b`.`tipe` = 'bahan')))) left join `mst_inv_pilihan` `bahan_c` on(((convert(`inv_inventaris_barang_c`.`pilihan_kons_beton` using utf8) = `bahan_c`.`code`) and (`bahan_c`.`tipe` = 'kons_beton')))) left join `mst_inv_pilihan` `bahan_e` on(((convert(`inv_inventaris_barang_e`.`pilihan_budaya_bahan` using utf8) = `bahan_e`.`code`) and (`bahan_e`.`tipe` = 'bahan')))) left join `mst_inv_pilihan` `bahan_f` on(((convert(`inv_inventaris_barang_f`.`pilihan_konstruksi_beton` using utf8) = `bahan_f`.`code`) and (`bahan_f`.`tipe` = 'kons_beton')))) left join `mst_inv_pilihan` `luas_a` on(((convert(`inv_inventaris_barang_a`.`pilihan_satuan_barang` using utf8) = `luas_a`.`code`) and (`luas_a`.`tipe` = 'satuan')))) left join `mst_inv_pilihan` `luas_b` on(((convert(`inv_inventaris_barang_b`.`pilihan_satuan` using utf8) = `luas_b`.`code`) and (`luas_b`.`tipe` = 'satuan')))) left join `mst_inv_pilihan` `luas_e` on(((convert(`inv_inventaris_barang_e`.`pilihan_satuan` using utf8) = `luas_e`.`code`) and (`luas_e`.`tipe` = 'satuan')))) left join `mst_inv_pilihan` `keadaan` on(((convert(`inv_inventaris_barang`.`pilihan_keadaan_barang` using utf8) = `keadaan`.`code`) and (`keadaan`.`tipe` = 'keadaan_barang')))) left join `mst_inv_pilihan` `asal` on(((convert(`inv_inventaris_barang`.`pilihan_asal` using utf8) = `asal`.`code`) and (`asal`.`tipe` = 'asal_usul')))) group by `inv_inventaris_barang`.`barang_kembar_proc`;

--
-- VIEW  `get_laporan_inv`
-- Data: Tidak ada
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
