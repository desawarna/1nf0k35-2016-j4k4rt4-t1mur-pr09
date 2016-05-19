<?php
class Bku_penerimaan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('keuangan/bku_penerimaan_model');
	}
			
	#function index(){
		#header("location:bku_penerimaan/pembantu");
	#}

	function index(){
		$this->authentication->verify('keuangan','add');
		
		$data['title_group'] = "BKU Penerimaan Pembantu";
		$data['title_form'] = "BKU Penerimaan Pembantu";
	
			
		$data['content'] = $this->parser->parse("keuangan/bku/pembantu",$data,true);						
		
		$this->template->show($data,"home");	
	}
	
	function get_rekening_by_type(){
		$data = $this->bku_penerimaan_model->get_rekening_by_type($this->input->post('tipe'));
		$txt ="";
		foreach($data as $r){
			$txt = $txt.$r['code']."#".$r['uraian']."~";
		}
		echo $txt;
	}
	function bku_delete(){
		$this->authentication->verify('keuangan','del');
		$this->bku_penerimaan_model->bku_delete();		
	}
	function bku_setor(){
		$this->authentication->verify('keuangan','add');
		$jenis = "sama";
		for($i=0; $i<count($this->input->post('data_all'))-1; $i++){
			$d = explode('z', $this->input->post('data_all')[$i]);
			$d2 = explode('z', $this->input->post('data_all')[$i+1]);
			if($d[2] != $d2[2]){
				$jenis = "beda";
				echo "beda";
			}
		}
		if($jenis == "sama"){
			$this->bku_penerimaan_model->bku_setor();
		}
		
	}
	public function pop_bku_add($data_setor='x')
	{	
		if($data_setor == 'x'){
			$data['action']			= "add";		
			$this->form_validation->set_rules('tgl', 'Tanggal', 'trim|required');
			$this->form_validation->set_rules('uraian', 'Uraian', 'trim|required');
			$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
			#if(empty($this->input->post('sts'))){
				$this->form_validation->set_rules('kode_rekening', 'Kode Rekening', 'trim|required');
			#}

			if($this->form_validation->run()== FALSE){
				$data['setor']			= 0;
				$data['notice']			= validation_errors();
				$data['list_sts'] 		= $this->bku_penerimaan_model->get_sts_no_setor();
				$data['list_rekening'] 	= $this->bku_penerimaan_model->get_rekening_all();

				die($this->parser->parse('keuangan/bku/pop_pembantu_add', $data));
			}else{	
				
				if($this->bku_penerimaan_model->bku_add()){
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
	
	
	public function api_data_bku()
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
		
		$rows_all = $this->bku_penerimaan_model->getItemBku();
		
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
		
		
		$activity = $this->bku_penerimaan_model->getItemBku($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$no=1;
		foreach($activity as $act) {
			$t = explode('-',$act->tgl);
			$tgl = $t[2].'-'.$t[1].'-'.$t[0];
			$data[] = array(
				'no'			=> $no++,
				'tgl' 			=> $tgl,				
				'uraian'   		=> $act->uraian,
				'kode_rekening'	=> $act->kode_rekening,				
				'catatan'		=> $act->catatan,
				'penerimaan'	=> $act->penerimaan,
				'pengeluaran'	=> $act->pengeluaran,				
				'delete'		=> $act->id_bku.'#'.$act->tgl,
				'status'		=> $act->is_setor,
				'id_bku'		=> $act->id_bku,
				'is_bku'		=> $act->is_bku,
				'tgl_id'		=> $act->tgl,
				
			);
		}

		$json = array(
			'TotalRows' => sizeof($rows_all),
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	
	function set_filter_bulan(){
		$this->session->set_userdata('bku_penerimaan_bulan', $this->input->post('bulan'));		
	}
	
	function set_filter_tahun(){		
		$this->session->set_userdata('bku_penerimaan_tahun', $this->input->post('tahun'));		
	}
	
	
	
}