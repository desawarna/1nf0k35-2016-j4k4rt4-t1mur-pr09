<?php
class Lap_mutasibarang_model extends CI_Model {

    var $tabel       = 'mst_inv_ruangan';
    var $t_puskesmas = 'cl_phc';
	var $lang	     = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    function get_laporan_inv($start=0, $limit=9999999, $options=array()){
    	$this->db->group_by('barang_kembar_inv');
    	$this->db->select("'Dikes',inv_inventaris_barang.id_mst_inv_barang,mst_inv_barang.uraian,inv_inventaris_barang.tanggal_diterima,IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,2))='02',inv_inventaris_barang_b.merek_type,'-') AS merek,'pkt',inv_inventaris_barang.harga,COUNT(id_inventaris_distribusi) AS jml_bertambah, (COUNT(id_inventaris_distribusi) * inv_inventaris_barang.harga) AS jml_harga,(SELECT COUNT(b.id_inventaris_barang) FROM inv_inventaris_barang b WHERE b.id_mst_inv_barang = inv_inventaris_barang.id_mst_inv_barang AND tanggal_dihapus IS NOT NULL) AS jml_berkurang,'pkt',((SELECT COUNT(b.id_inventaris_barang) FROM inv_inventaris_barang b WHERE b.id_mst_inv_barang = inv_inventaris_barang.id_mst_inv_barang AND tanggal_dihapus IS NOT NULL) * inv_inventaris_barang.harga) AS jumlahharga,IFNULL(inv_inventaris_barang.keterangan_pengadaan,'-') AS keterangan,inv_inventaris_distribusi.*,(SELECT a.tgl_distribusi FROM inv_inventaris_distribusi a WHERE a.id_inventaris_distribusi = inv_inventaris_distribusi.id_inventaris_distribusi ORDER BY a.tgl_distribusi ASC LIMIT 1) AS tgl_distribusi,IF((LEFT(inv_inventaris_barang.id_mst_inv_barang, 2) = '01'), luas_a.value, IF((LEFT(inv_inventaris_barang.id_mst_inv_barang, 2) = '02'), luas_b.value, IF((LEFT(inv_inventaris_barang.id_mst_inv_barang, 2) = '05'), luas_e.value, '-'))) AS satuan,",false);
    	$this->db->join("inv_inventaris_barang",'inv_inventaris_barang.id_inventaris_barang = inv_inventaris_distribusi.id_inventaris_barang','left');
    	$this->db->join("mst_inv_barang",'inv_inventaris_barang.id_mst_inv_barang = mst_inv_barang.code','left');
    	$this->db->join("inv_inventaris_barang_a",'inv_inventaris_barang_a.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang','left');
    	$this->db->join("inv_inventaris_barang_b",'inv_inventaris_barang_b.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang','left');
    	$this->db->join("inv_inventaris_barang_e",'inv_inventaris_barang_e.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang','left');
    	$this->db->join("mst_inv_pilihan luas_a","inv_inventaris_barang_a.pilihan_satuan_barang = luas_a.code AND luas_a.tipe = 'satuan'",'left');
    	$this->db->join("mst_inv_pilihan luas_b","inv_inventaris_barang_a.pilihan_satuan_barang = luas_b.code AND luas_b.tipe = 'satuan'",'left');
    	$this->db->join("mst_inv_pilihan luas_e","inv_inventaris_barang_a.pilihan_satuan_barang = luas_e.code AND luas_e.tipe = 'satuan'",'left');
    	$query = $this->db->get('inv_inventaris_distribusi');
        return $query->result();
    }
}