<?php
class Kecamatan_model extends CI_Model {

    var $tabel    = 'cl_kec';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    

    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->order_by('nama','asc');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

 	function get_data_row($id){
		$data = array();
		$options = array('code' => $id);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, array('code'=>$data));
    }
   function insert_entry()
    {
		$data['code']=$this->input->post('kode');
		$data['nama']=$this->input->post('value');

		if($this->getSelectedData($this->tabel,$data['code'])->num_rows() > 0) {
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

    function update_entry($kode)
    {
		$data['code']=$this->input->post('kode');
		$data['nama']=$this->input->post('value');

		if($this->db->update($this->tabel, $data, array("code"=>$kode))){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($kode)
	{
		$this->db->where('code',$kode);

		return $this->db->delete($this->tabel);
	}
}