<?php
class Pegpendidikanjurusan_model extends CI_Model {

    var $tabel    = 'mst_peg_jurusan';
    var $t_tingkat= 'mst_peg_tingkatpendidikan';
    var $t_rumpun = 'mst_peg_rumpunpendidikan';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    

    function get_data($start=0,$limit=999999,$options=array())
    {
    	$this->db->select('*');
	    $this->db->from('mst_peg_jurusan');
	    $this->db->join('mst_peg_tingkatpendidikan', 'mst_peg_jurusan.id_mst_peg_tingkatpendidikan = mst_peg_tingkatpendidikan.id_tingkat', 'inner');
	    $this->db->join('mst_peg_rumpunpendidikan', 'mst_peg_jurusan.id_mst_peg_rumpunpendidikan = mst_peg_rumpunpendidikan.id_rumpun', 'inner');
	    $this->db->limit($limit,$start); 
	    $query = $this->db->get();
    	return $query->result();

    }

 	function get_data_row($id){
		$data = array();
		$options = array('id_jurusan' => $id);
		$query = $this->db->get_where($this->tabel,$options);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}

	function get_id_tingkat($id_tingkat){
		$this->db->select('*');
		$this->db->from('mst_peg_tingkatpendidikan');
		$query = $this->db->get();
		return $query->result();
	}

	function get_id_rumpun($id_rumpun){
		$this->db->select('*');
		$this->db->from('mst_peg_rumpunpendidikan');
		$query = $this->db->get();
		return $query->result();
	}

	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, array('id_jurusan'=>$data));
    }

    function insert_entry()
    {
    	$data['id_jurusan'] = $this->input->post('id_jurusan');
		$data['id_mst_peg_tingkatpendidikan']=$this->input->post('id_tingkatpendidikan');
		$data['id_mst_peg_rumpunpendidikan']=$this->input->post('id_rumpunpendidikan');
		$data['nama_jurusan']=$this->input->post('nama_jurusan');

		if($this->getSelectedData($this->tabel,$data['id_jurusan'])->num_rows() > 0) {
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
		$data['id_jurusan'] = $this->input->post('id_jurusan');
		$data['id_mst_peg_tingkatpendidikan']=$this->input->post('id_tingkatpendidikan');
		$data['id_mst_peg_rumpunpendidikan']=$this->input->post('id_rumpunpendidikan');
		$data['nama_jurusan']=$this->input->post('nama_jurusan');

		if($this->db->update($this->tabel, $data, array("id_jurusan"=>$id))){
			return true;
		}else{
			return mysql_error();
		}
    }

	function delete_entry($id)
	{
		$this->db->where('id_jurusan',$id);

		return $this->db->delete($this->tabel);
	}
}