<?php
class Target_penerimaan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('keuangan/target_penerimaan_model');
	}
	

	function index(){
		$this->authentication->verify('keuangan','add');
		
		$data['title_group'] = "BKU Penerimaan Pembantu";
		$data['title_form'] = "BKU Penerimaan Pembantu";
	
			
		$data['content'] = $this->parser->parse("keuangan/target_penerimaan/target_penerimaan",$data,true);						
		
		$this->template->show($data,"home");	
	}
	
	public function pop_rekening($data_setor='x')
	{	
		if($data_setor == 'x'){
			$data['action']			= "add";		
			$this->form_validation->set_rules('tahun', 'Tahun', 'trim|required');
			$this->form_validation->set_rules('kode_rekening', 'Kode Rekening', 'trim|required');
			$this->form_validation->set_rules('target', 'Target', 'trim|required');
		

			if($this->form_validation->run()== FALSE){
				$data['setor']			= 0;
				$data['notice']			= validation_errors();				
				$data['list_rekening'] 	= $this->target_penerimaan_model->get_rekening_all();

				die($this->parser->parse('keuangan/target_penerimaan/pop_rekening', $data));
			}else{	
				
				if($this->target_penerimaan_model->target_add()){
					die("OK|");
				}else{
					die("Error|Proses data gagal");
				}	
			
				
			}
		}else{
			#setor disini
			$data['notice']			= "";
			$data['setor']			= 1;
			$data['list_sts'] 		= $this->bku_penerimaan_model->get_sts_no_setor();
			$data['list_rekening'] 	= $this->bku_penerimaan_model->get_rekening_all();
			#data_setor => 22400=.=6|2015-11-17|0~1|2015-11-18|0~
			
			$d1 					= explode('x', $data_setor);
			$data['total'] 			= $d1[0];
			$data['list_detail'] 	= $d1[1];
			$data['uraian'] 		= 'Telah disetor ke Bank';
			die($this->parser->parse('keuangan/bku/pop_pembantu_add', $data));
		}
		
	}
		
	
	public function api_target_penerimaan()
	{
		$data	  	= array();
		$filter 	= array();
		$filterLike = array();
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_pengadaan') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		$rows_all = $this->target_penerimaan_model->get_item();
		
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'date_received' || $field == 'date_accepted') {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		
		$activity = $this->target_penerimaan_model->get_item($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$no=1;
		foreach($activity as $act) {
			
			$data[] = array(
				'no'			=> $no++,
				'code' 			=> $act->code,				
				'kode_rekening' => $act->kode_rekening,
				'uraian'		=> $act->uraian,				
				'tahun'			=> $act->tahun,
				'target'		=> floatval($act->target),
				'input_a'		=> floatval($act->input_a),								
				'input_b'		=> floatval($act->input_b),								
				'total_input'	=> floatval($act->input_a + $act->input_b),								
				'output_a'		=> floatval($act->output_a),								
				'output_b'		=> floatval($act->output_b),								
				'total_output'	=> floatval($act->output_a + $act->output_b),								
				'total_akhir'	=> floatval($act->output_a + $act->output_b)				
			);
		}

		$json = array(
			'TotalRows' => sizeof($rows_all),
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	
	
	function delete_target(){
		$this->authentication->verify('keuangan','del');
		$this->target_penerimaan_model->delete_target();
	}
	
	function set_filter_bulan(){
		$this->session->set_userdata('bku_penerimaan_bulan', $this->input->post('bulan'));		
	}
	
	function set_filter_tahun(){		
		$this->session->set_userdata('bku_penerimaan_tahun', $this->input->post('tahun'));		
	}
	
	
	
}