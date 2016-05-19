<?php
class Invbaranghabispakai_model extends CI_Model {

    var $tabel       = 'mst_inv_barang_habispakai_jenis';
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
	function jenisbarang(){
		$this->db->select('*');
		$this->db->where('tipe','keadaan_barang');
		$q = $this->db->get('mst_inv_pilihan');
		return $q;
	}

	function get_data_detail($start=0, $limit=9999999, $options=array()){
		$data = array();
		$this->db->select("mst_inv_barang_habispakai_jenis.uraian as jenisuraian,mst_inv_barang_habispakai.*,mst_inv_pilihan.value as value");
		$this->db->join('mst_inv_barang_habispakai_jenis',"mst_inv_barang_habispakai_jenis.id_mst_inv_barang_habispakai_jenis=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis");
		$this->db->join('mst_inv_pilihan',"mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code and mst_inv_pilihan.tipe='satuan_bhp'",'left');
	    $query = $this->db->get('mst_inv_barang_habispakai',$limit,$start);
    	return $query->result();
	}

	function get_data_detail_edit($kode){
		$data = array();
		$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai",$kode);
		$this->db->select("mst_inv_barang_habispakai_jenis.uraian as jenisuraian,mst_inv_barang_habispakai.*,mst_inv_pilihan.value as nama_satuan");
		$this->db->join('mst_inv_barang_habispakai_jenis',"mst_inv_barang_habispakai_jenis.id_mst_inv_barang_habispakai_jenis=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis");
		$this->db->join('mst_inv_pilihan',"mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code and mst_inv_pilihan.tipe='satuan_bhp'",'left');
	    $query = $this->db->get('mst_inv_barang_habispakai');
    	if ($query->num_rows() > 0){
			$data = $query->row_array();
		}
		$query->free_result();    
		return $data;
	}
	
    function get_data($start=0,$limit=999999,$options=array())
    {
    	$this->db->select('mst_inv_barang_habispakai_jenis.*,COUNT(id_mst_inv_barang_habispakai) AS jumlah');
    	$this->db->join('mst_inv_barang_habispakai','mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis=mst_inv_barang_habispakai_jenis.id_mst_inv_barang_habispakai_jenis','left');
    	$this->db->group_by('mst_inv_barang_habispakai_jenis.id_mst_inv_barang_habispakai_jenis');
	    $query = $this->db->get($this->tabel,$limit,$start);
    	return $query->result();
    }

    
	
 	function get_data_row($kode){
		$data = array();
		$this->db->where("id_mst_inv_barang_habispakai_jenis",$kode);
		$query = $this->db->get_where($this->tabel);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}
		$query->free_result();    
		return $data;
	}


    function insert_entry()
    {
		$data['uraian']		 = $this->input->post('uraian');

		if($this->db->insert($this->tabel, $data)){
		 return 1;

		}else{
			return mysql_error();
		}
    }
	
    function update_entry($kode)
    {
		$data['uraian']		= $this->input->post('uraian');

		$this->db->where('id_mst_inv_barang_habispakai_jenis',$kode);
		if($this->db->update($this->tabel, $data)){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id)
	{
		$this->db->where('id_mst_inv_barang_habispakai_jenis',$id);
		return $this->db->delete($this->tabel);
	}
	function delete_entryitem($id)
	{
		$this->db->where('id_mst_inv_barang_habispakai',$id);
		return $this->db->delete('mst_inv_barang_habispakai');
	}
}