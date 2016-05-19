<?php
class Pegfungsional_model extends CI_Model {

    var $tabel    = 'mst_peg_fungsional';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->order_by('tar_id_fungsional','asc');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

 	function get_data_row($id){
		$data = array();
		$options = array('tar_id_fungsional' => $id);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, array('tar_id_fungsional'=>$data));
    }

    function insert_entry()
    {
		$data['tar_nama_fungsional']=$this->input->post('nama');
		$data['tar_jenis']=$this->input->post('jenis');

		if($this->getSelectedData($this->tabel,$data['tar_jenis'])->num_rows() > 0) {
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
		echo $data['tar_nama_fungsional']=$this->input->post('nama');
		echo $data['tar_jenis']=$this->input->post('jenis');

		if($this->db->update($this->tabel, $data, array("tar_id_fungsional"=>$id))){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id)
	{
		$this->db->where('tar_id_fungsional',$id);

		return $this->db->delete($this->tabel);
	}
}