<?php
class Pegfungsional extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/pegfungsional_model');
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

		$rows_all = $this->pegfungsional_model->get_data();


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

		$rows = $this->pegfungsional_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'tar_id_fungsional'			=> $act->tar_id_fungsional,
				'tar_nama_fungsional'		=> $act->tar_nama_fungsional,
				'tar_jenis'					=> $act->tar_jenis,
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
		$data['title_form'] = "Master Data - Peg Fungsional";
		// $data= $this->pegfungsional_model->get_data();
		// var_dump($data);
		// exit();

		$data['content'] = $this->parser->parse("mst/pegfungsional/show",$data,true);

		$this->template->show($data,"home");
	}


	function add(){
		$this->authentication->verify('mst','add');


        $this->form_validation->set_rules('nama', 'Nama Fungsional', 'trim|required');
        $this->form_validation->set_rules('jenis', 'Jenis Fungsional', 'trim|required');
        
			if($this->form_validation->run()== FALSE){
				$data['title_group'] = "Parameter";
				$data['title_form']="Tambah Jabatan Fungsional Pegawai";
				$data['action']="add";
				$data['kode']="";

			
				$data['content'] = $this->parser->parse("mst/pegfungsional/form",$data,true);
				$this->template->show($data,"home");
			}elseif($this->pegfungsional_model->insert_entry() == 1){
				$this->session->set_flashdata('alert', 'Save data successful...');
				redirect(base_url()."mst/pegfungsional/");
			}else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."mst/pegfungsional/add");
			}

	}

	function edit($id=0)
	{
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('nama', 'Nama Fungsional', 'trim|required');
        $this->form_validation->set_rules('jenis', 'Jenis Fungsional', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->pegfungsional_model->get_data_row($id); 

			$data['title_group'] = "Parameter";
			$data['title_form']="Ubah Jabatan Fungsional Pegawai";
			$data['action']="edit";
			$data['id']=$id;

		
			$data['content'] = $this->parser->parse("mst/pegfungsional/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->pegfungsional_model->update_entry($id)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."mst/pegfungsional/".$this->input->post('kode'));
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."mst/pegfungsional/edit/".$id);
		}
	}

	function dodel($id=0){
		$this->authentication->verify('mst','del');

		if($this->pegfungsional_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
			redirect(base_url()."mst/pegfungsional");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/pegfungsional");
		}
	}
}
