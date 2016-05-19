<?php
class Admin_model extends CI_Model {

    var $tabel     = 'app_theme';

    function __construct() {
        parent::__construct();
    }
    
 	function get_theme($id){
		$data = array();
		$options = array('id_theme' => $id);
		$query = $this->db->get_where($this->tabel,$options,1);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	function get_data_perkecamatan($id_data_keluarga, $jk){
    	$this->db->like('id_data_keluarga',$id_data_keluarga);
    	$this->db->where('id_pilihan_kelamin',$jk);
		$data = $this->db->get('data_keluarga_anggota')->result_array();

		return count($data);
    }
	function get_data_kecamatan(){
    	$kode_kecamatan = substr($this->session->userdata('puskesmas'),0,4);
    	$this->db->order_by("(SELECT SUM(harga) AS nilai FROM inv_inventaris_barang INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 WHERE inv_inventaris_distribusi.id_cl_phc RLIKE (SELECT a.code FROM cl_kec a WHERE a.code = cl_kec.code))",'desc');
		$query = $this->db->like('code',$kode_kecamatan);
		$data = $query->get('cl_kec',10)->result_array();

		return $data;
    }
	function get_inv_barang(){
		$loginpuskesmas = $this->session->userdata('puskesmas');
		$query = $this->db->query("SELECT SUM(jml) AS jml, SUM(nilai) AS nilai FROM ((SELECT COUNT(id_inventaris_barang) AS jml,SUM(harga) AS nilai FROM inv_inventaris_barang WHERE id_pengadaan=0 and code_cl_phc like ".'"%'.'P'.$loginpuskesmas.'%"'.")UNION(SELECT 
        COUNT(inv_inventaris_barang.id_inventaris_barang) AS jml, SUM(inv_inventaris_barang.harga) AS nilai  FROM  inv_inventaris_barang
		join inv_inventaris_distribusi on inv_inventaris_distribusi.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang and inv_inventaris_distribusi.status=1  INNER JOIN inv_pengadaan ON inv_pengadaan.id_pengadaan = inv_inventaris_barang.id_pengadaan
        AND inv_inventaris_barang.code_cl_phc  like ".'"%'.'P'.$loginpuskesmas.'%"'.")) AS aset");

		return $query->result();
	}

	function get_inv_barang1(){
		$loginpuskesmas = $this->session->userdata('puskesmas');
		$query = $this->db->query("SELECT COUNT(id_mst_inv_ruangan) as jml from mst_inv_ruangan where code_cl_phc like ".'"%'.'P'.$loginpuskesmas.'%"'."");

		return $query->result();
	}

	function get_jum_aset($kode = 0){
		$pusk = 'P'.$this->session->userdata('puskesmas');
		if ($kode!=0) {
			$dbwhere = " and inv_inventaris_distribusi.id_cl_phc like ".'"%P'.$kode.'%"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, COUNT(inv_inventaris_barang.id_inventaris_barang) AS jml FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE (pilihan_keadaan_barang = 'B' || pilihan_keadaan_barang = 'KB') 
		$dbwhere
		GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");
		// $data = $query->result_array();
		// return $data['jml'];
		if($query->num_rows() > 0){
			foreach ($query->result() as $key) {
				$data = $key->jml;	
			}
		}else{
			$data = 0;
		}
		return $data;

	}

	function get_nilai_aset($kode = 0){
		$pusk = 'P'.$this->session->userdata('puskesmas');
		if ($kode!=0) {
			$dbwhere = " and inv_inventaris_distribusi.id_cl_phc like ".'"%P'.$kode.'%"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, SUM(harga) AS nilai FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE (pilihan_keadaan_barang = 'B' || pilihan_keadaan_barang = 'KB') 
		$dbwhere
		GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		if($query->num_rows() > 0){
			foreach ($query->result() as $key) {
				$data = $key->nilai;	
			}
		}else{
			$data = 0;
		}
		return $data;
	}

	function get_jum_aset1($kode = 0){
		$pusk = 'P'.$this->session->userdata('puskesmas');
		if ($kode!=0) {
			$dbwhere = " and inv_inventaris_distribusi.id_cl_phc like ".'"%P'.$kode.'%"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, COUNT(inv_inventaris_barang.id_inventaris_barang) AS jml FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang  AND inv_inventaris_distribusi.status=1
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE pilihan_keadaan_barang = 'RR'  
		$dbwhere
		GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		// $data = $query->result_array();
		// return $data['jml'];
		if($query->num_rows() > 0){
			foreach ($query->result() as $key) {
				$data = $key->jml;	
			}
		}else{
			$data = 0;
		}
		return $data;
	}

	function get_nilai_aset1($kode = 0){
		$pusk = 'P'.$this->session->userdata('puskesmas');
		if ($kode!=0) {
			$dbwhere = " and inv_inventaris_distribusi.id_cl_phc like ".'"%P'.$kode.'%"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, SUM(harga) AS nilai FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang  AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE pilihan_keadaan_barang = 'RR' 
		$dbwhere
		GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		if($query->num_rows() > 0){
			foreach ($query->result() as $key) {
				$data = $key->nilai;	
			}
		}else{
			$data = 0;
		}
		return $data;
	}


	function get_jum_aset2($kode = 0){
		$pusk = 'P'.$this->session->userdata('puskesmas');
		if ($kode!=0) {
			$dbwhere = " and inv_inventaris_distribusi.id_cl_phc like ".'"%P'.$kode.'%"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, COUNT(inv_inventaris_barang.id_inventaris_barang) AS jml FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE pilihan_keadaan_barang = 'RB'  
		$dbwhere
		GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		// $data = $query->result_array();
		// return $data['jml'];
		if($query->num_rows() > 0){
			foreach ($query->result() as $key) {
				$data = $key->jml;	
			}
		}else{
			$data = 0;
		}
		return $data;
	}

	function get_nilai_aset2($kode = 0){
		$pusk = 'P'.$this->session->userdata('puskesmas');
		if ($kode!=0) {
			$dbwhere = " and inv_inventaris_distribusi.id_cl_phc like ".'"%P'.$kode.'%"'."";
		}
		$query =  $this->db->query("SELECT id_cl_phc, SUM(harga) AS nilai FROM inv_inventaris_barang 
		INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
		LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code
		WHERE pilihan_keadaan_barang = 'RB'  
		$dbwhere
		GROUP BY inv_inventaris_distribusi.id_cl_phc ORDER BY 'value' asc");

		if($query->num_rows() > 0){
			foreach ($query->result() as $key) {
				$data = $key->nilai;	
			}
		}else{
			$data = 0;
		}
		return $data;
	}

	function get_jum_nilai_aset($kode = 0)
	{
		$pusk = 'P'.$this->session->userdata('puskesmas');
		if ($kode!=0) {
			$dbwhere = " where inv_inventaris_distribusi.id_cl_phc like ".'"%P'.$kode.'%"'."";
		}
		$query = $this->db->query("SELECT cl_phc.value,id_cl_phc, COUNT(inv_inventaris_barang.id_inventaris_barang) AS jml FROM inv_inventaris_barang 
			INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
			LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code 
			$dbwhere
			 ORDER BY 'value' asc");

		// $data = $query->result_array();
		// return $data['jml'];
		if($query->num_rows() > 0){
			foreach ($query->result() as $key) {
				$data = $key->jml;	
			}
		}else{
			$data = 0;
		}
		return $data;
	}

	function get_jum_nilai_aset2($kode = 0)
	{
		$pusk = 'P'.$this->session->userdata('puskesmas');
		if ($kode!=0) {
			$dbwhere = " where inv_inventaris_distribusi.id_cl_phc like ".'"%P'.$kode.'%"'."";
		}
		$query = $this->db->query("SELECT cl_phc.value,id_cl_phc, SUM(harga) AS nilai FROM inv_inventaris_barang 
			INNER JOIN inv_inventaris_distribusi ON inv_inventaris_barang.id_inventaris_barang=inv_inventaris_distribusi.id_inventaris_barang AND inv_inventaris_distribusi.status=1 
			LEFT JOIN cl_phc ON inv_inventaris_distribusi.id_cl_phc=cl_phc.code  
			$dbwhere
			 ORDER BY 'value' asc");

		if($query->num_rows() > 0){
			foreach ($query->result() as $key) {
				$data = $key->nilai;	
			}
		}else{
			$data = 0;
		}
		return $data;
	}

}