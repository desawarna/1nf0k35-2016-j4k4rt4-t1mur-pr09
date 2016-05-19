<?php
class Invpilihan_model extends CI_Model {

    var $tabel    = 'mst_inv_pilihan';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
    public function getChecking($table,$data)
    {
        return $this->db->get_where($table, $data);
    }
    function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->order_by('value','asc');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }

 	function get_data_row($id){
		$data = array();
		$options = array('id_pilihan' => $id);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, array('id_pilihan'=>$data));
    }


   function insert_entry()
    {
		$data['tipe']=$this->input->post('tipe');
		$data['code']=$this->input->post('kode');
		$data['value']=$this->input->post('value');
		$data['jumlah_muncul']=0;

		if($this->getSelectedData($this->tabel,$data['id_pilihan'])->num_rows() > 0) {
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
    function pilihan_enums($table , $field){
	$query = "SHOW COLUMNS FROM ".$table." LIKE '$field'";
	 $row = $this->db->query("SHOW COLUMNS FROM ".$table." LIKE '$field'")->row()->Type;  
	 $regex = "/'(.*?)'/";
	        preg_match_all( $regex , $row, $enum_array );
	        $enum_fields = $enum_array[1];
	        foreach ($enum_fields as $key=>$value)
	        {
	            $enums[$value] = $value; 
	        }
	        return $enums;
	}


    function update_entry($kode)
    {	
		$data['tipe']=$this->input->post('tipe');
		$data['code']=$this->input->post('kode');
		$data['value']=$this->input->post('value');
		$data['jumlah_muncul']=0;
		
		if($this->db->update($this->tabel, $data, array("id_pilihan"=>$kode))){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($kode)
	{
		$this->db->where('id_pilihan',$kode);

		return $this->db->delete($this->tabel);
	}
}