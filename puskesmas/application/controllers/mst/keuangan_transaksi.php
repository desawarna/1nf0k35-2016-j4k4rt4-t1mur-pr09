<?php
class Keuangan_transaksi extends CI_Controller {

 public function __construct(){
		parent::__construct();
		$this->load->model('mst/keutransaksi_model');
	}

	function index(){
		$this->authentication->verify('mst','edit');
		$data['title_group']   = "Keuangan";
		$data['title_form']    = "Kategori Transaksi";
		$data['ambildata'] 	   = $this->keutransaksi_model->get_data_kategori_transaksi();
		$data['kode_rekening'] = $this->keutransaksi_model->get_kode_rek();
		$data['content']       = $this->parser->parse("mst/keutransaksi/show",$data,true);		
		
		$this->template->show($data,"home");
	}

	function transaksi($pageIndex){
		$data = array();

		switch ($pageIndex) {
			case 1:
				$data['title_group']   = "Keuangan";
				$data['title_form']    = "Kategori Transaksi";

				die($this->parser->parse("mst/keutransaksi/kategori_transaksi",$data));

				break;
			case 2:
				$data['title_group']   = "Keuangan";
				$data['title_form']    = "Daftar Transaksi";
				
				die($this->parser->parse("mst/keutransaksi/daftar_transaksi",$data));

				break;
			case 3:
				$data['title_group']   = "Keuangan";
				$data['title_form']    = "Daftar Transaksi Otomatis";
				
				die($this->parser->parse("mst/keutransaksi/daftar_transaksi_otomatis",$data));

				break;
			default:
				$data['title_group']   = "Keuangan";
				$data['title_form']    = "Pengaturan Transaksi";
				
				die($this->parser->parse("mst/keutransaksi/penggunaan_transaksi",$data));
				break;
		}
	}

	function json_kategori_transaksi(){
		$this->authentication->verify('mst','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows_all = $this->keutransaksi_model->get_data_kategori_transaksi();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows = $this->keutransaksi_model->get_data_kategori_transaksi($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_mst_kategori_transaksi'	=> $act->id_mst_kategori_transaksi,
				'nama'					    => $act->nama,
				'deskripsi'					=> $act->deskripsi,
				'edit'						=> 1,
				'delete'					=> 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function delete_kategori_transaksi($id=0){
		$this->authentication->verify('mst','del');

		if($this->keutransaksi_model->delete_kategori_transaksi($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

	function kategori_transaksi_edit($id=0){
    	$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

		if($this->form_validation->run()== FALSE){

			$data 						= $this->keutransaksi_model->get_data_kategori_transaksi_edit($id);
			$data['template']			= $this->keutransaksi_model->get_data_template();
			$data['notice']				= validation_errors();
			$data['action']				= "edit";
			$data['id']					= $id;
			$data['alert_form'] 		= '';
			die($this->parser->parse("mst/keutransaksi/form_kategori_transaksi",$data));
		
		}elseif($this->keutransaksi_model->update_kategori_transaksi($id)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}
		die($this->parser->parse("mst/keutransaksi/form_kategori_transaksi",$data));
	}

	function kategori_transaksi_add(){
		$this->authentication->verify('mst','add');

    	$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

		$data['id_mst_kategori_transaksi']	= "";
		$data['template']					= $this->keutransaksi_model->get_data_template();
	    $data['action']						= "add";
		$data['alert_form']		    		= '';

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("mst/keutransaksi/form_kategori_transaksi",$data));
		}elseif($this->keutransaksi_model->insert_kategori_transaksi()){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}
		die($this->parser->parse("mst/keutransaksi/form_kategori_transaksi",$data));
	}

	function template_update($id=0){
		$this->authentication->verify('mst','edit');

    	$this->form_validation->set_rules('nilai', 'Template', 'trim');

	    $data['action']				= "edit";
		$data['alert_form']		    = '';
		$data['id']					= $id;

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("mst/keutransaksi/form_kategori_transaksi",$data));
		}elseif($this->keutransaksi_model->template_update($id)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}
		die($this->parser->parse("mst/keutransaksi/form_kategori_transaksi",$data));
	}

	function template_insert(){
		$this->authentication->verify('mst','add');

    	$this->form_validation->set_rules('nilai', 'Nilai', 'trim');
		
		$data['id_mst_kategori_transaksi_setting']	= "";
	    $data['action']								= "add";
		$data['alert_form']		   					= '';

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("mst/keutransaksi/form_kategori_transaksi",$data));
		}elseif($this->keutransaksi_model->template_insert()){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}
		die($this->parser->parse("mst/keutransaksi/form_kategori_transaksi",$data));

	}


}

