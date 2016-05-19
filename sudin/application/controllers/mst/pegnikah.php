<?php
class Pegnikah extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/pegnikah_model');
	}
	function json(){
		$this->authentication->verify('mst','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$this->db->like($field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows_all = $this->pegnikah_model->get_data();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$this->db->like($field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows = $this->pegnikah_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'kode'		=> $act->kode,
				'value'		=> $act->value,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function index(){
		$this->authentication->verify('mst','edit');
		$data['title_group'] = "Parameter";
		$data['title_form'] = "Master Data - Peg Nikah";
		// $data= $this->pegnikah_model->get_data();
		// var_dump($data);
		// exit();

		$data['content'] = $this->parser->parse("mst/pegnikah/show",$data,true);

		$this->template->show($data,"home");
	}


	function add(){
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('kode', 'Kode Status', 'trim|required');
        $this->form_validation->set_rules('value', 'Value', 'trim|required');
        
			if($this->form_validation->run()== FALSE){
				$data['title_group'] = "Parameter";
				$data['title_form']="Tambah Status Perkawinan Pegawai";
				$data['action']="add";
				$data['kode']="";

			
				$data['content'] = $this->parser->parse("mst/pegnikah/form",$data,true);
				$this->template->show($data,"home");
			}elseif($this->pegnikah_model->insert_entry() == 1){
				$this->session->set_flashdata('alert', 'Save data successful...');
				redirect(base_url()."mst/pegnikah/");
			}else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."mst/pegnikah/add");
			}

	}

	function edit($kode=0)
	{
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('kode', 'Kode Status', 'trim|required');
        $this->form_validation->set_rules('value', 'Value', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->pegnikah_model->get_data_row($kode); 
			// var_dump($data);
			// exit();
			$data['title_group'] = "Parameter";
			$data['title_form']="Ubah Data Penghargaan Pegawai";
			$data['action']="edit";
			$data['kode']=$kode;

		
			$data['content'] = $this->parser->parse("mst/pegnikah/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->pegnikah_model->update_entry($kode)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."mst/pegnikah/");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."mst/pegnikah/edit/".$kode);
		}
	}

	function dodel($kode=0){
		$this->authentication->verify('mst','del');

		if($this->pegnikah_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."mst/pegnikah");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/pegnikah");
		}
	}
}
