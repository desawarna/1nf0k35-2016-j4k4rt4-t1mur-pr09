<?php
class Lap_pengadaan_model extends CI_Model {

    var $tabel       = 'mst_inv_ruangan';
    var $t_puskesmas = 'cl_phc';
	var $lang	     = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
	function get_pilihan_kondisi(){
		$this->db->select('code as id, value as val');
		$this->db->where('tipe','keadaan_barang');
		$q = $this->db->get('mst_inv_pilihan');
		return $q;
	}
	function get_data($start=0,$limit=999999,$options=array()){
        $this->db->select("inv_inventaris_barang.id_inventaris_barang,inv_inventaris_barang.id_mst_inv_barang,inv_inventaris_barang.nama_barang,inv_inventaris_barang.harga,inv_inventaris_barang.barang_kembar_proc,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang) AS jumlah,
                        inv_inventaris_barang.harga AS hargasatuan,
                        inv_pengadaan.keterangan,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang)*inv_inventaris_barang.harga AS totalharga,
                        inv_inventaris_barang.keterangan_pengadaan,mst_inv_pilihan.value,inv_inventaris_barang.tanggal_diterima,
                        inv_inventaris_barang.waktu_dibuat,inv_inventaris_barang.terakhir_diubah,inv_inventaris_barang.pilihan_status_invetaris,
                        inv_pengadaan.*");
        $this->db->join('inv_pengadaan', "inv_inventaris_barang.id_pengadaan = inv_pengadaan.id_pengadaan",'right');
        $this->db->join('mst_inv_pilihan', "inv_inventaris_barang.pilihan_status_invetaris=mst_inv_pilihan.code and mst_inv_pilihan.tipe='status_inventaris'");
        $this->db->where('inv_pengadaan.tgl_pengadaan >=', $this->input->post('filter_tanggal'));
		$this->db->where('inv_pengadaan.tgl_pengadaan <=', $this->input->post('filter_tanggal1'));
		$this->db->where('inv_inventaris_barang.id_pengadaan != ', '0');
        $this->db->group_by("inv_inventaris_barang.barang_kembar_proc");
        $query =$this->db->get('inv_inventaris_barang',$limit,$start);
        return $query->result();
    }
}