<?php
class Target_penerimaan_model extends CI_Model {

    var $tb    = '';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
	function delete_target(){
		$this->db->where('code_mst_keu_rekening', $this->input->post('id'));
		$this->db->where('tahun', $this->input->post('tahun'));
		$this->db->delete('keu_bku_penerimaan_target');
	}
	
    function get_data()
    {
 		$this->db->select('*');		
		$query = $this->db->get($this->tb);		
		return $query->result_array();
    } 
	
	function get_rekening_by_type($tipe)
    {
 		$this->db->select('*');		
		$this->db->where('tipe',$tipe);
		$query = $this->db->get('mst_keu_rekening');		
		return $query->result_array();
    } 
	
	function get_rekening_all()
    {
 		$this->db->select('*');			
		$this->db->join("keu_bku_penerimaan_target","mst_keu_rekening.code = keu_bku_penerimaan_target.code_mst_keu_rekening and tahun = '".$this->session->userdata('bku_penerimaan_tahun')."' ",'left');
		$this->db->where("tahun");
		$query = $this->db->get('mst_keu_rekening');		
		return $query->result_array();
    } 
	
	function target_add()
    {
		$data=array(
			'code_mst_keu_rekening' => $this->input->post('kode_rekening'),
			'tahun' => $this->input->post('tahun'),
			'target' => $this->input->post('target'),
		); 		
		return $query = $this->db->insert('keu_bku_penerimaan_target', $data);		
		
    } 
	
	public function get_item($start=0, $limit = 999999, $options=array())
    {
		if(empty($this->session->userdata('bku_penerimaan_bulan')) ){			
			$this->session->set_userdata('bku_penerimaan_bulan', date('m'));
		}
		
		if(empty($this->session->userdata('bku_penerimaan_tahun')) ){			
			$this->session->set_userdata('bku_penerimaan_tahun', date('Y'));
		
		} 
		
		
		$bulan_a=$this->session->userdata('bku_penerimaan_tahun')."-".($this->session->userdata('bku_penerimaan_bulan')-1);
		$bulan_b=$this->session->userdata('bku_penerimaan_tahun')."-".$this->session->userdata('bku_penerimaan_bulan');		
		$tahun =$this->session->userdata('bku_penerimaan_tahun');
		$q = "	SELECT
					code, kode_rekening, uraian, tahun, target, 
					tb_a.jumlah as input_a, tb_b.jumlah as input_b, (tb_a.jumlah+tb_b.jumlah) as total_input,
					tb_a.jumlah as output_a, tb_b.jumlah as output_b, (tb_a.jumlah+tb_b.jumlah) as total_output,
					(tb_a.jumlah+tb_b.jumlah) as total_akhir

				FROM
					keu_bku_penerimaan_target
				inner join mst_keu_rekening on mst_keu_rekening.`code`=keu_bku_penerimaan_target.code_mst_keu_rekening
				LEFT JOIN 
					(select * from keu_bku_penerimaan_rekap_rekening where periode=?) as tb_a 
					using(code_mst_keu_rekening)
				LEFT JOIN
					(select * from keu_bku_penerimaan_rekap_rekening where periode=?) as tb_b
					using(code_mst_keu_rekening)
				where 
					tahun = ?
				";
		$query = $this->db->query($q, array($bulan_a, $bulan_b, $tahun));		
        return $query->result();
    }
	
	
}
