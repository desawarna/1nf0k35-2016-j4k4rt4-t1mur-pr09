<?php
class Agama extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/agama_model');
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

		$rows_all = $this->agama_model->get_data();


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

		$rows = $this->agama_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
		$data['title_form'] = "Master Data - Agama";

		$data['content'] = $this->parser->parse("mst/agama/show",$data,true);

		$this->template->show($data,"home");
	}


	function add(){
		$this->authentication->verify('mst','add');


        $this->form_validation->set_rules('kode', 'Kode Agama', 'trim|required');
        $this->form_validation->set_rules('value', 'Nama Agama', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] = "Parameter";
			$data['title_form']="Tambah Agama";
			$data['action']="add";
			$data['kode']="";

		
			$data['content'] = $this->parser->parse("mst/agama/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->agama_model->insert_entry()==1){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."mst/agama/");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."mst/agama/add");
		}
	}

	function edit($kode=0)
	{
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('value', 'Nama Agama', 'trim|required');
        $this->form_validation->set_rules('kode', 'Kode Agama', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->agama_model->get_data_row($kode); 

			$data['title_group'] = "Parameter";
			$data['title_form']="Ubah Agama";
			$data['action']="edit";
			$data['kode']=$kode;

		
			$data['content'] = $this->parser->parse("mst/agama/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->agama_model->update_entry($kode)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."mst/agama/edit/".$this->input->post('kode'));
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."mst/agama/edit/".$kode);
		}
	}

	function dodel($kode=0){
		$this->authentication->verify('mst','del');

		if($this->agama_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."mst/agama");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/agama");
		}
	}
}
