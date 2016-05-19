<?php
class Pegpendidikanjurusan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/pegpendidikanjurusan_model');
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

		$rows_all = $this->pegpendidikanjurusan_model->get_data();


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

		$rows = $this->pegpendidikanjurusan_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_jurusan'					=> $act->id_jurusan,
				'tingkat'						=> $act->tingkat,
				'nama_rumpun'					=> $act->nama_rumpun,
				'id_mst_peg_tingkatpendidikan'	=> $act->id_mst_peg_tingkatpendidikan,
				'id_mst_peg_rumpunpendidikan'	=> $act->id_mst_peg_rumpunpendidikan,
				'nama_jurusan'					=> $act->nama_jurusan,
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
		$data['title_form'] = "Master Data - Peg Jurusan Pendidikan";
		// $data= $this->pegpendidikanjurusan_model->get_data();
		// var_dump($data);
		// exit();

		$data['content'] = $this->parser->parse("mst/pegpendidikanjurusan/show",$data,true);

		$this->template->show($data,"home");
	}


	function add(){
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('id_jurusan', 'ID Jurusan', 'trim|required');
        $this->form_validation->set_rules('id_tingkatpendidikan', 'ID Tingkat Pendiidkan', 'trim|required');
        $this->form_validation->set_rules('id_rumpunpendidikan', 'ID Rumpun Pendidikan', 'trim|required');
        $this->form_validation->set_rules('nama_jurusan', 'Nama Jurusan', 'trim|required');
        
			if($this->form_validation->run()== FALSE){
				$data['title_group'] = "Parameter";
				$data['title_form']="Tambah Jurusan Pendidikan Pegawai";
				$data['action']="add";
				$data['kode']="";
				$data['id_tingkatpendidikan'] = $this->pegpendidikanjurusan_model->get_id_tingkat('id_tingkat');
				$data['id_rumpunpendidikan'] = $this->pegpendidikanjurusan_model->get_id_rumpun('id_rumpun');

			
				$data['content'] = $this->parser->parse("mst/pegpendidikanjurusan/form",$data,true);
				$this->template->show($data,"home");
			}elseif($this->pegpendidikanjurusan_model->insert_entry() == 1){
				$this->session->set_flashdata('alert', 'Save data successful...');
				redirect(base_url()."mst/pegpendidikanjurusan/");
			}else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."mst/pegpendidikanjurusan/add");
			}

	}

	function edit($id=0)
	{
		$this->authentication->verify('mst','add');

         $this->form_validation->set_rules('id_jurusan', 'ID Jurusan', 'trim|required');
        $this->form_validation->set_rules('id_tingkatpendidikan', 'ID Tingkat Pendiidkan', 'trim|required');
        $this->form_validation->set_rules('id_rumpunpendidikan', 'ID Rumpun Pendidikan', 'trim|required');
        $this->form_validation->set_rules('nama_jurusan', 'Nama Jurusan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->pegpendidikanjurusan_model->get_data_row($id); 

			$data['title_group'] = "Parameter";
			$data['title_form']="Ubah Jurusan Pendidikan Pegawai";
			$data['action']="edit";
			$data['id']=$id;
			$data['id_tingkatpendidikan'] = $this->pegpendidikanjurusan_model->get_id_tingkat('id_tingkat');
			$data['id_rumpunpendidikan'] = $this->pegpendidikanjurusan_model->get_id_rumpun('id_rumpun');

		
			$data['content'] = $this->parser->parse("mst/pegpendidikanjurusan/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->pegpendidikanjurusan_model->update_entry($id)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."mst/pegpendidikanjurusan/".$this->input->post('kode'));
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."mst/pegpendidikanjurusan/edit/".$id);
		}
	}

	function dodel($id=0){
		$this->authentication->verify('mst','del');

		if($this->pegpendidikanjurusan_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
			redirect(base_url()."mst/pegpendidikanjurusan");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/pegpendidikanjurusan");
		}
	}
}
