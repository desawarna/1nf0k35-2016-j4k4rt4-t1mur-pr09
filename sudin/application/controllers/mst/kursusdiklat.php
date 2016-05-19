<?php
class Kursusdiklat extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/kursusdiklat_model');
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

		$rows_all = $this->kursusdiklat_model->get_data();


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

		$rows = $this->kursusdiklat_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_kursus'			=> $act->id_kursus,
				'nama_kursus'		=> $act->nama_kursus,
				'jenis'				=> $act->jenis,
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
		$data['title_form'] = "Master Data - Peg Kursus/Diklat";
		// $data= $this->kursusdiklat_model->get_data();
		// var_dump($data);
		// exit();

		$data['content'] = $this->parser->parse("mst/kursusdiklat/show",$data,true);

		$this->template->show($data,"home");
	}


	function add(){
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('id_kursus', 'ID Kursus', 'trim|required');
        $this->form_validation->set_rules('nama_kursus', 'Nama Kursus', 'trim|required');
        $this->form_validation->set_rules('jenis', 'Jenis', 'trim|required');
        
			if($this->form_validation->run()== FALSE){
				$data['title_group'] = "Parameter";
				$data['title_form']="Tambah Kursus/Diklat Pegawai";
				$data['action']="add";
				$data['kode']="";

			
				$data['content'] = $this->parser->parse("mst/kursusdiklat/form",$data,true);
				$this->template->show($data,"home");
			}elseif($this->kursusdiklat_model->insert_entry() == 1){
				$this->session->set_flashdata('alert', 'Save data successful...');
				redirect(base_url()."mst/kursusdiklat/");
			}else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."mst/kursusdiklat/add");
			}

	}

	function edit($id=0)
	{
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('id_kursus', 'ID Kursus', 'trim|required');
        $this->form_validation->set_rules('nama_kursus', 'Nama Kursus', 'trim|required');
        $this->form_validation->set_rules('jenis', 'Jenis', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->kursusdiklat_model->get_data_row($id); 
			// var_dump($data);
			// exit();
			$data['title_group'] = "Parameter";
			$data['title_form']="Ubah Kursus/Diklat Pegawai";
			$data['action']="edit";
			$data['id']=$id;

		
			$data['content'] = $this->parser->parse("mst/kursusdiklat/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->kursusdiklat_model->update_entry($id)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."mst/kursusdiklat/".$this->input->post('kode'));
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."mst/kursusdiklat/edit/".$id);
		}
	}

	function dodel($id=0){
		$this->authentication->verify('mst','del');

		if($this->kursusdiklat_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
			redirect(base_url()."mst/kursusdiklat");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/kursusdiklat");
		}
	}
}
