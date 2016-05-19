<?php
class Pegpendidikantingkat_model extends CI_Model {

    var $tabel    = 'mst_peg_tingkatpendidikan';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->order_by('id_tingkat','asc');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

 	function get_data_row($id){
		$data = array();
		$options = array('id_tingkat' => $id);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, array('id_tingkat'=>$data));
    }

    function insert_entry()
    {
		$data['id_tingkat']=$this->input->post('id_tingkat');
		$data['deskripsi']=$this->input->post('deskripsi');
		$data['tingkat']=$this->input->post('tingkat');

		if($this->getSelectedData($this->tabel,$data['id_tingkat'])->num_rows() > 0) {
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
		$data['id_tingkat']=$this->input->post('id_tingkat');
		$data['deskripsi']=$this->input->post('deskripsi');
		$data['tingkat']=$this->input->post('tingkat');

		if($this->db->update($this->tabel, $data, array("id_tingkat"=>$id))){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id)
	{
		$this->db->where('id_tingkat',$id);

		return $this->db->delete($this->tabel);
	}
}