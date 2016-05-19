<?php
class Peglisting_model extends CI_Model {

    var $tabel    = 'mst_peg_listing';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->order_by('id_listing','asc');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

 	function get_data_row($id){
		$data = array();
		$options = array('id_listing' => $id);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, array('id_listing'=>$data));
    }

    function insert_entry()
    {
		$data['nama_listing']=$this->input->post('nama_listing');

		if($this->getSelectedData($this->tabel,$data['id'])->num_rows() > 0) {
			return 0;
		}else{
			if($this->db->insert($this->tabel, $data)){
			//$id= mysql_insert_id();
				return 1; 
			}else{
				return mysql_error();
			}
			
		}

    }

    function update_entry($id)
    {
		$data['nama_listing']=$this->input->post('nama_listing');
		
		if($this->db->update($this->tabel, $data, array("id_listing"=>$id))){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id)
	{
		$this->db->where('id_listing',$id);

		return $this->db->delete($this->tabel);
	}
}