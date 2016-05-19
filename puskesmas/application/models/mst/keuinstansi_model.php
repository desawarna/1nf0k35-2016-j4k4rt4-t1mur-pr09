<?php
class Keuinstansi_model extends CI_Model {

    var $tabel    = '';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }


    function insert_entry()
    {
        $data['nama']          = $this->input->post('nama');
        $data['tlp']		   = $this->input->post('tlp');
        $data['alamat']        = $this->input->post('alamat');
        $data['status']        = $this->input->post('status');
        $data['kategori']      = $this->input->post('kategori');
	
		if($this->db->insert('mst_inv_pbf', $data)){
			return 1;
		}else{
			return mysql_error();
		}
    }

 	function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->order_by('code','asc');
        $query = $this->db->get('mst_inv_pbf',$limit,$start);
        return $query->result();
    }

    function delete_entry($id)
	{
		$this->db->where('code',$id);

		return $this->db->delete('mst_inv_pbf');
	}

   function get_data_instansi_edit($id){
        $data = array();

        $this->db->select("*");
        $this->db->where("code",$id);
        $query = $this->db->get("mst_inv_pbf");
        if($query->num_rows()>0){
            $data = $query->row_array();
        }

        $query->free_result();
        return $data;
    }

    function update_entry_instansi($id)
    {
    	$data['nama']          = $this->input->post('nama');
        $data['tlp']		   = $this->input->post('tlp');
        $data['alamat']        = $this->input->post('alamat');
        $data['kategori']      = $this->input->post('kategori');
        $data['status']        = $this->input->post('status');

        $this->db->where('code',$id);

        if($this->db->update('mst_inv_pbf', $data)){
            return true; 
        }else{
            return mysql_error();
        }
    }

     	function get_data_row($id){
		$data = array();
		$options = array('code' => $id);
		$query = $this->db->get_where('mst_inv_pbf',$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}


}

