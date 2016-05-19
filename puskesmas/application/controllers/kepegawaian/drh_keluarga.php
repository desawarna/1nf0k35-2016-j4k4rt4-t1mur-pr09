<?php
class Drh_keluarga extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('kepegawaian/drh_model');
		$this->load->model('mst/puskesmas_model');
	}

//CRUD Keluarga
	function json_ortu($id){
		$this->authentication->verify('kepegawaian','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lahir') {
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

		$rows_all = $this->drh_model->get_data_ortu($id);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lahir') {
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

		$rows = $this->drh_model->get_data_ortu($id,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'akta_menikah'  	=> $act->akta_menikah,
				'akta_meninggal'	=> $act->akta_meninggal,
				'akta_cerai'    	=> $act->akta_cerai,
				'urut'	  			=> $act->urut,
				'id_pegawai'		=> $act->id_pegawai,
				'nama_keluarga'		=> $act->nama_keluarga,
				'nama'				=> $act->nama,
				'jenis_kelamin'		=> $act->jenis_kelamin,
				'tgl_lahir'			=> $act->tgl_lahir,
				'code_cl_district'	=> $act->code_cl_district,
				'usia'			    => $act->usia,
				'bpjs'			    => $act->bpjs,
				'hidup'			    => $act->hidup,
				'status_pns'	    => $act->status_pns,
				'edit'		        => 1,
				'delete'	        => 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function json_pasangan($id){
		$this->authentication->verify('kepegawaian','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lahir') {
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

		$rows_all = $this->drh_model->get_data_pasangan($id);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lahir') {
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

		$rows = $this->drh_model->get_data_pasangan($id,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'urut'	  			=> $act->urut,
				'id_pegawai'		=> $act->id_pegawai,
				'nama_keluarga'		=> $act->nama_keluarga,
				'nama'				=> $act->nama,
				'jenis_kelamin'		=> $act->jenis_kelamin,
				'tgl_lahir'			=> $act->tgl_lahir,
				'code_cl_district'	=> $act->code_cl_district,
				'usia'				=> $act->usia,
				'bpjs'				=> $act->bpjs,
				'status_menikah'	=> $act->status_menikah,
				'pns'				=> $act->pns,
				'akta_menikah'		=> $act->akta_menikah,
				'akta_meninggal'	=> $act->akta_meninggal,
				'akta_cerai'		=> $act->akta_cerai,
				'tgl_menikah'		=> $act->tgl_menikah,
				'tgl_meninggal'		=> $act->tgl_meninggal,
				'tgl_cerai'			=> $act->tgl_cerai,
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

	function json_anak($id){
		$this->authentication->verify('kepegawaian','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lahir') {
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

		$rows_all = $this->drh_model->get_data_anak($id);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_lahir') {
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

		$rows = $this->drh_model->get_data_anak($id,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_pegawai'				   => $act->id_pegawai,
				'urut'	  				       => $act->urut,
				'nama'						   => $act->nama,
				'nama_keluarga'				   => $act->nama_keluarga,
				'jenis_kelamin'				   => $act->jenis_kelamin,
				'tgl_lahir'					   => $act->tgl_lahir,
				'usia'						   => $act->usia,
				'code_cl_district'	           => $act->code_cl_district,
				'edit'						   => 1,
				'delete'					   => 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function biodata_keluarga($pageIndex,$id){
		$data = array();
		$data['id']=$id;

		switch ($pageIndex) {
			case 1:
				die($this->parser->parse("kepegawaian/drh/form_keluarga_ortu",$data));
				break;
			case 2:
				die($this->parser->parse("kepegawaian/drh/form_keluarga_suamiistri",$data));
				break;
			default:
				die($this->parser->parse("kepegawaian/drh/form_keluarga_anak",$data));
				break;
		}
	}

	function biodata_keluarga_anak_add($id){
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('code_cl_district', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('status_pns', 'Status PNS', 'trim');
        $this->form_validation->set_rules('alasan_taksekolah', 'Alasan Tak Sekolah', 'trim');
        $this->form_validation->set_rules('bpjs', 'Nomor BPJS', 'trim');

		$data['id']					= $id;
	    $data['action']				= "add";
		$data['alert_form']		    = '';
		$data['kode_kel'] 			= $this->drh_model->get_kode_keluarga('anak');
		$data['kode_tingkat_pend'] 	= $this->drh_model->get_tingkat_pendidikan();


		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("kepegawaian/drh/form_keluarga_anak_form",$data));
		}elseif($this->drh_model->insert_entry_anak($id)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_keluarga_anak_form",$data));
	}

	function biodata_keluarga_ortu_add($id){
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('code_cl_district', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('status_pns', 'Status PNS', 'trim');
        $this->form_validation->set_rules('status_hidup', 'Status Hidup', 'trim');
        $this->form_validation->set_rules('bpjs', 'Nomor BPJS', 'trim');

		$data['id']					= $id;
	    $data['action']				= "add";
		$data['alert_form']		    = '';
		$data['kode_kel'] 			= $this->drh_model->get_kode_keluarga('ortu');

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("kepegawaian/drh/form_keluarga_ortu_form",$data));
		}elseif($this->drh_model->insert_entry_ortu($id)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_keluarga_ortu_form",$data));
	}


	function biodata_keluarga_pasangan_add($id){
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('code_cl_district', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('status_pns', 'Status PNS', 'trim');
        $this->form_validation->set_rules('status_hidup', 'Status Hidup', 'trim');
        $this->form_validation->set_rules('bpjs', 'Nomor BPJS', 'trim');
        $this->form_validation->set_rules('akta_menikah', 'Akta Menikah', 'trim');
        $this->form_validation->set_rules('akta_meninggal', 'Akta Meninggal', 'trim');
        $this->form_validation->set_rules('akta_cerai', 'Akta Cerai', 'trim');
        $this->form_validation->set_rules('tgl_menikah', 'Tanggal Menikah', 'trim');
        $this->form_validation->set_rules('tgl_cerai', 'Tanggal Cerai', 'trim');
        $this->form_validation->set_rules('tgl_meninggal', 'Tanggal Meninggal', 'trim');

		$data['id']					= $id;
	    $data['action']				= "add";
		$data['alert_form'] 		= '';
		$data['kode_kel'] 			= $this->drh_model->get_kode_keluarga('default');

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("kepegawaian/drh/form_keluarga_suamiistri_form",$data));
		}elseif($this->drh_model->insert_entry_pasangan($id)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_keluarga_suamiistri_form",$data));
	}

	function biodata_keluarga_ortu_edit($id="",$urut=0){

        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('code_cl_district', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('status_pns', 'Status PNS', 'trim');
        $this->form_validation->set_rules('status_hidup', 'Status Hidup', 'trim');
        $this->form_validation->set_rules('bpjs', 'Nomor BPJS', 'trim');
		
		if($this->form_validation->run()== FALSE){
			$data 						= $this->drh_model->get_data_ortu_edit($id,$urut);
 			$data['kode_kel'] 			= $this->drh_model->get_kode_keluarga('ortu');
			$data['notice']				= validation_errors();
			$data['action']				= "edit";
			$data['id']					= $id;
			$data['urut']				= $urut;
			$data['alert_form'] 		= '';
			$data['disable']			= "disable";
			die($this->parser->parse("kepegawaian/drh/form_keluarga_ortu_form",$data));
		
		}elseif($this->drh_model->update_entry_ortu($id,$urut)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_keluarga_ortu_form",$data));
	}


	function biodata_keluarga_anak_edit($id="",$urut=0){
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('code_cl_district', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('status_pns', 'Status PNS', 'trim');
        $this->form_validation->set_rules('alasan_taksekolah', 'Alasan Tak Sekolah', 'trim');
        $this->form_validation->set_rules('bpjs', 'Nomor BPJS', 'trim');

		if($this->form_validation->run()== FALSE){
			$data 						= $this->drh_model->get_data_anak_edit($id,$urut);
			$data['kode_tingkat_pend'] 	= $this->drh_model->get_tingkat_pendidikan();
 			$data['kode_kel'] 			= $this->drh_model->get_kode_keluarga('anak');
			$data['notice']				= validation_errors();
			$data['action']				= "edit";
			$data['id']					= $id;
			$data['urut']				= $urut;
			$data['alert_form'] 		= '';
			$data['disable']			= "disable";
			die($this->parser->parse("kepegawaian/drh/form_keluarga_anak_form",$data));
		
		}elseif($this->drh_model->update_entry_anak($id,$urut)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_keluarga_anak_form",$data));
	}

	function biodata_keluarga_pasangan_edit($id="",$urut=0){
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('code_cl_district', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('status_pns', 'Status PNS', 'trim');
        $this->form_validation->set_rules('status_hidup', 'Status Hidup', 'trim');
        $this->form_validation->set_rules('bpjs', 'Nomor BPJS', 'trim');
        $this->form_validation->set_rules('akta_menikah', 'Akta Menikah', 'trim');
        $this->form_validation->set_rules('akta_meninggal', 'Akta Meninggal', 'trim');
        $this->form_validation->set_rules('akta_cerai', 'Akta Cerai', 'trim');
        $this->form_validation->set_rules('tgl_menikah', 'Tanggal Menikah', 'trim');
        $this->form_validation->set_rules('tgl_cerai', 'Tanggal Cerai', 'trim');
        $this->form_validation->set_rules('tgl_meninggal', 'Tanggal Meninggal', 'trim');
        $this->form_validation->set_rules('status_menikah', ' Status Menikah', 'trim');

		if($this->form_validation->run()== FALSE){
			$data 						= $this->drh_model->get_data_pasangan_edit($id,$urut);
 			$data['kode_kel'] 			= $this->drh_model->get_kode_keluarga('default');
			$data['notice']				= validation_errors();
			$data['action']				= "edit";
			$data['id']					= $id;
			$data['urut']				= $urut;
			$data['alert_form'] 		= '';
			$data['disable']			= "disable";
			die($this->parser->parse("kepegawaian/drh/form_keluarga_suamiistri_form",$data));
		
		}elseif($this->drh_model->update_entry_pasangan($id,$urut)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_keluarga_suamiistri_form",$data));
	}


	function biodata_keluarga_ortu_del($id=0,$urut=0){

		 $this->authentication->verify('kepegawaian','del');

		 if($this->drh_model->delete_entry_ortu($id,$urut)){
		 	die ("OK");
		 } else {
		 	die ("Error");
		 }
	}

	function biodata_keluarga_anak_del($id=0,$urut=0){

		 $this->authentication->verify('kepegawaian','del');

		 if($this->drh_model->delete_entry_anak($id,$urut)){
		 	die ("OK");
		 } else {
		 	die ("Error");
		 }
	}
	
	function biodata_keluarga_pasangan_del($id=0,$urut=0){

		 $this->authentication->verify('kepegawaian','del');

		 if($this->drh_model->delete_entry_pasangan($id,$urut)){
		 	die ("OK");
		 } else {
		 	die ("Error");
		 }
	}


	//CRUD Keluarga - END
}