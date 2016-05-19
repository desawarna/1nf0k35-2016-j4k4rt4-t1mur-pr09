<?php
class pegpendidikanrumpun_model extends CI_Model {

    var $tabel    = 'mst_peg_rumpunpendidikan';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->order_by('id_rumpun','asc');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

 	function get_data_row($id){
		$data = array();
		$options = array('id_rumpun' => $id);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, array('id_rumpun'=>$data));
    }

    function insert_entry()
    {
		$data['nama_rumpun']=$this->input->post('nama_rumpun');

		if($this->getSelectedData($this->tabel,$data['id_rumpun'])->num_rows() > 0) {
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
		$data['nama_rumpun']=$this->input->post('nama_rumpun');

		if($this->db->update($this->tabel, $data, array("id_rumpun"=>$id))){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id)
	{
		$this->db->where('id_rumpun',$id);

		return $this->db->delete($this->tabel);
	}
}