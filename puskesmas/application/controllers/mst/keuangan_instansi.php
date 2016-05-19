<?php
class Keuangan_instansi extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/keuinstansi_model');

	}

	function index(){
		$this->authentication->verify('mst','edit');
		$data['title_group'] = "Parameter";
		$data['title_form'] = "Master Data - Keu Instansi";

		$data['content'] = $this->parser->parse("mst/keuinstansi/show",$data,true);

		$this->template->show($data,"home");
	}

	function json_instansi(){
		$this->authentication->verify('mst','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lhr') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows_all = $this->keuinstansi_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lhr') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows = $this->keuinstansi_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'code'	   => $act->code,
				'nama'	   => $act->nama,
				'tlp'	   => $act->tlp,
				'alamat'   => $act->alamat,
				'status'   => $act->status,
				'kategori' => ucwords($act->kategori),
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

	function instansi_add(){
		$this->authentication->verify('mst','add');

    	$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('tlp', ' Telpon', 'trim|required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim');
        $this->form_validation->set_rules('kategori', 'Kategori', 'trim');

		$data['code']				= "";
	    $data['action']				= "add";
		$data['alert_form']		    = '';

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("mst/keuinstansi/form",$data));
		}elseif($this->keuinstansi_model->insert_entry()){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("mst/keuinstansi/form",$data));
	}

	function dodel($id=0){
		$this->authentication->verify('mst','del');

		if($this->keuinstansi_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

	function instansi_edit($id=0){
   		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('tlp', ' Telpon', 'trim|required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim');
        $this->form_validation->set_rules('kategori', 'Kategori', 'trim');

		if($this->form_validation->run()== FALSE){

			$data 						= $this->keuinstansi_model->get_data_instansi_edit($id);
			$data['notice']				= validation_errors();
			$data['action']				= "edit";
			$data['id']					= $id;
			$data['alert_form'] 		= '';
			$data['disable']			= "disable";
			die($this->parser->parse("mst/keuinstansi/form",$data));
		
		}elseif($this->keuinstansi_model->update_entry_instansi($id)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("mst/keuinstansi/form",$data));
	}

	function detail($id=0)
	{
		$this->authentication->verify('mst','add');

		$data = $this->keuinstansi_model->get_data_row($id); 
		$data['id']=$id;
		$data['nip']="1111 2222 3333 4444";
		$data['title_group'] = "Kepegawaian";
		$data['title_form']="Ubah Data Pegawai";

		$data['content'] = $this->parser->parse("mst/keuinstansi/form",$data,true);
		$this->template->show($data,"home");
	}

}
?>