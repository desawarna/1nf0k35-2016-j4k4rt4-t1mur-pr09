<?php
class Invpilihan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/invpilihan_model');
	}
	function filter(){
		if($_POST) {
			if($this->input->post('tipe') != '') {
				$this->session->set_userdata('filter_tipe_pilihan',$this->input->post('tipe'));
			}
		}
	}
	function check(){
		 $id['code'] = $_POST['kode_pilihan'];  
		 echo $find = $this->invpilihan_model->getChecking('mst_inv_pilihan',$id)->num_rows();
    }
	/*function tipearray(){
		$query = $this->db->query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='mst_inv_pilihan' AND COLUMN_NAME='tipe'")->result();
		foreach ($query as $key) {
			$tipepilihan = $key->COLUMN_TYPE ;
		}
		$sub_pilihan = substr($tipepilihan,5,-1);
		return $arrayName = array('komponen','status_pengadaan','status_inventaris','satuan','status_hak','keadaan_barang','status_barang','asal_usul','bahan','kons_tingkat','kons_beton','penggunaan');
	}*/
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
		if($this->session->userdata('filter_tipe_pilihan') != '') {
			$this->db->where('tipe',$this->session->userdata('filter_tipe_pilihan'));
		}

		$rows_all = $this->invpilihan_model->get_data();


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
		if($this->session->userdata('filter_tipe_pilihan') != '') {
			$this->db->where('tipe',$this->session->userdata('filter_tipe_pilihan'));
		}
		$rows = $this->invpilihan_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_pilihan'		=> $act->id_pilihan,
				'tipe'				=> $act->tipe,
				'code'				=> $act->code,
				'value'			=> $act->value,
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
		$data['title_form'] = "Master Data - Inv Pilihan";
		//$data['tipe'] =$this->tipearray();
		$data['tipe'] = $this->invpilihan_model->pilihan_enums('mst_inv_pilihan','tipe');
		$data['content'] = $this->parser->parse("mst/inv_pilihan/show",$data,true);


		$this->template->show($data,"home");
	}	
	function add(){
		$this->authentication->verify('mst','add');


        $this->form_validation->set_rules('tipe', 'Tipe Pilihan', 'trim|required');
        $this->form_validation->set_rules('kode', 'Kode Pilihan', 'trim|required');
        $this->form_validation->set_rules('value', 'Value Pilihan', 'trim|required');
        
			if($this->form_validation->run()== FALSE){
				$data['title_group'] = "Parameter";
				$data['title_form']="Tambah Tipe Pilihan";
				$data['action']="add";
				$data['kode']="";

				//$data['tipe'] =$this->tipearray();
				$data['tipe'] = $this->invpilihan_model->pilihan_enums('mst_inv_pilihan','tipe');
				$data['content'] = $this->parser->parse("mst/inv_pilihan/form",$data,true);
				$this->template->show($data,"home");
			}elseif($this->invpilihan_model->insert_entry() == 1){
				$this->session->set_flashdata('alert', 'Save data successful...');
				redirect(base_url()."mst/invpilihan/");
			}else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."mst/invpilihan/add");
			}

	}
	function edit($kode=0)
	{
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('tipe', 'Tipe Pilihan', 'trim|required');
        $this->form_validation->set_rules('kode', 'Kode Pilihan', 'trim|required');
        $this->form_validation->set_rules('value', 'Value Pilihan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->invpilihan_model->get_data_row($kode); 

			$data['title_group'] = "Parameter";
			$data['title_form']="Ubah Inv Tipe Pilihan";
			$data['action']="edit";
			$data['kode']=$kode;

			//$data['tipe'] =$this->tipearray();
			$data['tipe'] = $this->invpilihan_model->pilihan_enums('mst_inv_pilihan','tipe');
			$data['content'] = $this->parser->parse("mst/inv_pilihan/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->invpilihan_model->update_entry($kode)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."mst/invpilihan/edit/".$kode);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."mst/invpilihan/edit/".$kode);
		}
	}

	function dodel($kode=0){
		$this->authentication->verify('mst','del');

		if($this->invpilihan_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."mst/invpilihan");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/invpilihan");
		}
	}
}
