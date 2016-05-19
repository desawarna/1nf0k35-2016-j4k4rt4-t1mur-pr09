<?php
class Drh_pedidikan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('kepegawaian/drh_model');
		$this->load->model('mst/puskesmas_model');
	}

//CRUD Pendidikan
	function json_pendidikan_formal($id){
		$this->authentication->verify('kepegawaian','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'ijazah_tgl') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}
				elseif($field == 'cpns') {
					$value = $value == 'Ya' ? 1 : 0;
					$this->db->where('pegawai_pendidikan.status_pendidikan_cpns',$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows_all = $this->drh_model->get_data_pendidikan_formal($id);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'ijazah_tgl') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}
				elseif($field == 'cpns') {
					$value = $value == 'Ya' ? 1 : 0;
					$this->db->where('pegawai_pendidikan.status_pendidikan_cpns',$value);
				}else{
					$this->db->like($field,$value);
				}

			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows = $this->drh_model->get_data_pendidikan_formal($id,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_pegawai'	=> $act->id_pegawai,
				'id_mst_peg_jurusan' => $act->id_mst_peg_jurusan,
				'sekolah_nama'	=> $act->sekolah_nama,
				'sekolah_lokasi'=> $act->sekolah_lokasi,
				'ijazah_tgl'	=> $act->ijazah_tgl,
				'nama_jurusan'	=> $act->nama_jurusan,
				'deskripsi'		=> $act->deskripsi,
				'cpns'			=> $act->cpns,
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

	function json_pendidikan_fungsional($id){
		$this->authentication->verify('kepegawaian','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_diklat') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}
				elseif($field == 'nama_diklat') {
					$this->db->like('pegawai_diklat.'.$field,$value);
				}
				elseif($field == 'jenis_diklat') {
					$this->db->like('mst_peg_diklat.nama_diklat',$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows_all = $this->drh_model->get_data_pendidikan_fungsional($id);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_diklat') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}
				elseif($field == 'nama_diklat') {
					$this->db->like('pegawai_diklat.'.$field,$value);
				}
				elseif($field == 'jenis_diklat') {
					$this->db->like('mst_peg_diklat.nama_diklat',$value);
				}else{
					$this->db->like($field,$value);
				}

			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows = $this->drh_model->get_data_pendidikan_fungsional($id,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_pegawai'		=> $act->id_pegawai,
				'mst_peg_id_diklat' => $act->mst_peg_id_diklat,
				'jenis_diklat' 		=> $act->jenis_diklat,
				'tipe'				=> ucwords($act->tipe),
				'nama_diklat'		=> $act->nama_diklat,
				'tgl_diklat'		=> $act->tgl_diklat,
				'nomor_sertifikat'	=> $act->nomor_sertifikat,
				'lama_diklat'		=> $act->lama_diklat,
				'instansi'			=> $act->instansi,
				'penyelenggara'		=> $act->penyelenggara,
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

	function json_pendidikan_struktural($id){
		$this->authentication->verify('kepegawaian','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_diklat') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}
				elseif($field == 'nama_diklat') {
					$this->db->like('pegawai_diklat.'.$field,$value);
				}
				elseif($field == 'jenis_diklat') {
					$this->db->like('mst_peg_diklat.nama_diklat',$value);
				}else{
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows_all = $this->drh_model->get_data_pendidikan_struktural($id);

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_diklat') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}
				elseif($field == 'nama_diklat') {
					$this->db->like('pegawai_diklat.'.$field,$value);
				}
				elseif($field == 'jenis_diklat') {
					$this->db->like('mst_peg_diklat.nama_diklat',$value);
				}else{
					$this->db->like($field,$value);
				}

			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$rows = $this->drh_model->get_data_pendidikan_struktural($id,$this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_pegawai'		=> $act->id_pegawai,
				'mst_peg_id_diklat' => $act->mst_peg_id_diklat,
				'jenis_diklat' 		=> $act->jenis_diklat,
				'tipe'				=> $act->tipe,
				'nama_diklat'		=> $act->nama_diklat,
				'tgl_diklat'		=> $act->tgl_diklat,
				'nomor_sertifikat'	=> $act->nomor_sertifikat,
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

	function biodata_pendidikan($pageIndex,$id){
		$data = array();
		$data['id']=$id;

		switch ($pageIndex) {
			case 1:
				die($this->parser->parse("kepegawaian/drh/form_pendidikan_formal",$data));
				break;
			case 2:
				die($this->parser->parse("kepegawaian/drh/form_pendidikan_struktural",$data));
				break;
			default:
				die($this->parser->parse("kepegawaian/drh/form_pendidikan_fungsional",$data));
				break;
		}

	}

	function biodata_pendidikan_formal_add($id){
        $this->form_validation->set_rules('sekolah_nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('id_jurusan', 'Program Studi', 'trim|required');
        $this->form_validation->set_rules('id_tingkat', 'Tingkat Pendidikan', 'trim|required');
        $this->form_validation->set_rules('id_rumpun', 'Rumpun Pendidikan', 'trim|required');
        $this->form_validation->set_rules('ijazah_tgl', '', 'trim');
        $this->form_validation->set_rules('ijazah_no', '', 'trim');
        $this->form_validation->set_rules('sekolah_lokasi', '', 'trim');
        $this->form_validation->set_rules('gelar_depan', '', 'trim');
        $this->form_validation->set_rules('gelar_belakang', '', 'trim');
        $this->form_validation->set_rules('status_pendidikan_cpns', '', 'trim');

		$data['id']				= $id;
	    $data['action']			= "add";
		$data['alert_form'] 	= '';
		$data['kode_rumpun'] 	= $this->drh_model->get_kode_rumpun();

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("kepegawaian/drh/form_pendidikan_formal_form",$data));
		}elseif($this->drh_model->insert_entry_pendidikan_formal($id)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_pendidikan_formal_form",$data));
	}

	function biodata_pendidikan_formal_edit($id="",$id_jurusan=0){
        $this->form_validation->set_rules('sekolah_nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('id_jurusan', 'Program Studi', 'trim|required');
        $this->form_validation->set_rules('id_tingkat', 'Tingkat Pendidikan', 'trim|required');
        $this->form_validation->set_rules('id_rumpun', 'Rumpun Pendidikan', 'trim|required');
        $this->form_validation->set_rules('ijazah_tgl', '', 'trim');
        $this->form_validation->set_rules('ijazah_no', '', 'trim');
        $this->form_validation->set_rules('sekolah_lokasi', '', 'trim');
        $this->form_validation->set_rules('gelar_depan', '', 'trim');
        $this->form_validation->set_rules('gelar_belakang', '', 'trim');
        $this->form_validation->set_rules('status_pendidikan_cpns', '', 'trim');

		if($this->form_validation->run()== FALSE){
			$data 						= $this->drh_model->get_data_pendidikan_formal_edit($id,$id_jurusan);
			$data['jurusan'] 			= $this->drh_model->get_rumpun_tingkat($id_jurusan);
			$data['kode_rumpun'] 		= $this->drh_model->get_kode_rumpun();
			$data['id']					= $id;
			$data['id_jurusan']			= $id_jurusan;
			$data['action']				= "edit";
			$data['alert_form'] 		= '';
			$data['disable']			= "disable";
			die($this->parser->parse("kepegawaian/drh/form_pendidikan_formal_form",$data));
			
		}elseif($this->drh_model->update_entry_pendidikan_formal($id,$id_jurusan)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}
		die($this->parser->parse("kepegawaian/drh/form_pendidikan_formal_form",$data));
	}

	function biodata_pendidikan_formal_del($id="",$id_jurusan=0){
		$this->authentication->verify('kepegawaian','del');

		if($this->drh_model->delete_entry_pendidikan_formal($id,$id_jurusan)){
			die ("OK");
		} else {
			die ("Error");
		}
	}

	function biodata_pendidikan_struktural_del($id="",$id_diklat=0){
		$this->authentication->verify('kepegawaian','del');

		if($this->drh_model->delete_entry_pendidikan_struktural($id,$id_diklat)){
			die ("OK");
		} else {
			die ("Error");
		}
	}

	function biodata_pendidikan_struktural_add($id){
        $this->form_validation->set_rules('nama_diklat', 'Nama Diklat Struktural', 'trim|required');
        $this->form_validation->set_rules('mst_peg_id_diklat', 'Jenis Diklat', 'trim|required');
        $this->form_validation->set_rules('tgl_diklat', '', 'trim');
        $this->form_validation->set_rules('nomor_sertifikat', '', 'trim');

		$data['id']			 =$id;
	    $data['action']		 = "add";
		$data['alert_form']  = '';
		$data['kode_diklat'] = $this->drh_model->get_kode_diklat('struktural');

		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("kepegawaian/drh/form_pendidikan_struktural_form",$data));
		}elseif($this->drh_model->insert_entry_pendidikan_struktural($id)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_pendidikan_struktural_form",$data));
	}

	function biodata_pendidikan_struktural_edit($id="",$id_diklat=0){
   		$this->form_validation->set_rules('nama_diklat', 'Nama Diklat Struktural', 'trim|required');
        $this->form_validation->set_rules('mst_peg_id_diklat', 'Jenis Diklat', 'trim|required');
        $this->form_validation->set_rules('tgl_diklat', '', 'trim');
        $this->form_validation->set_rules('nomor_sertifikat', '', 'trim');

		if($this->form_validation->run()== FALSE){
			$data 				 = $this->drh_model->get_data_pendidikan_struktural_edit($id,$id_diklat);
			$data['kode_diklat'] = $this->drh_model->get_kode_diklat('struktural');
			$data['action']		 = "edit";
			$data['id']			 = $id;
			$data['id_diklat']	 = $id_diklat;
			$data['alert_form']  = '';
			$data['disable']	 = "disable";

			die($this->parser->parse("kepegawaian/drh/form_pendidikan_struktural_form",$data));

		}elseif($this->drh_model->update_entry_pendidikan_struktural($id,$id_diklat)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_pendidikan_struktural_form",$data));
	}


	function biodata_pendidikan_fungsional_add($id){
        $this->form_validation->set_rules('nama_diklat', 'Nama Diklat Struktural', 'trim|required');
        $this->form_validation->set_rules('mst_peg_id_diklat', 'Jenis Diklat', 'trim|required');
        $this->form_validation->set_rules('tgl_diklat', '', 'trim');
        $this->form_validation->set_rules('lama_diklat', 'Lamanya Diklat', 'trim|required');
        $this->form_validation->set_rules('tipe', '', 'trim');
        $this->form_validation->set_rules('nomor_sertifikat', '', 'trim');
        $this->form_validation->set_rules('instansi', '', 'trim');
        $this->form_validation->set_rules('penyelenggara', '', 'trim');

		if($this->form_validation->run()== FALSE){
	    	$data['action']		 = "add";
			$data['id']			 = $id;
			$data['alert_form']  = '';
			$data['kode_diklat'] = $this->drh_model->get_kode_diklat('fungsional');
			die($this->parser->parse("kepegawaian/drh/form_pendidikan_fungsional_form",$data));
		}elseif($this->drh_model->insert_entry_pendidikan_fungsional($id)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_pendidikan_fungsional_form",$data));
	}

	function biodata_pendidikan_fungsional_edit($id="",$id_diklat=0){
        $this->form_validation->set_rules('nama_diklat', 'Nama Diklat Struktural', 'trim|required');
        $this->form_validation->set_rules('mst_peg_id_diklat', 'Jenis Diklat', 'trim|required');
        $this->form_validation->set_rules('tgl_diklat', '', 'trim');
        $this->form_validation->set_rules('lama_diklat', 'Lamanya Diklat', 'trim|required');
        $this->form_validation->set_rules('tipe', '', 'trim');
        $this->form_validation->set_rules('nomor_sertifikat', '', 'trim');
        $this->form_validation->set_rules('instansi', '', 'trim');
        $this->form_validation->set_rules('penyelenggara', '', 'trim');

		if($this->form_validation->run()== FALSE){
			$data 				 = $this->drh_model->get_data_pendidikan_fungsional_edit($id,$id_diklat);
			$data['kode_diklat'] = $this->drh_model->get_kode_diklat('fungsional');
			$data['action']		 = "edit";
			$data['id']			 = $id;
			$data['id_diklat']	 = $id_diklat;
			$data['alert_form']  = '';
			die($this->parser->parse("kepegawaian/drh/form_pendidikan_fungsional_form",$data));

		}elseif($this->drh_model->update_entry_pendidikan_fungsional($id,$id_diklat)){
			die("OK");
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_pendidikan_fungsional_form",$data));
	}

	function biodata_pendidikan_fungsional_del($id="",$id_diklat=0){
		$this->authentication->verify('kepegawaian','del');

		if($this->drh_model->delete_entry_pendidikan_fungsional($id,$id_diklat)){
			die ("OK");
		} else {
			die ("Error");
		}
	}

	function get_tingkat($id_tingkat=0){
		$id_rumpun = $this->input->post('id_rumpun');

		$tingkat = $this->drh_model->get_tingkat($id_rumpun);
		echo '<option value="">-</option>';
		foreach($tingkat as $tkt) :
			echo $select = $tkt->id_tingkat == $id_tingkat ? 'selected' : '';
			echo '<option value="'.$tkt->id_tingkat.'" '.$select.'>' . $tkt->deskripsi.' </option>';
		endforeach;
	}

	function get_jurusan($id_jurusan=0){
		$id_rumpun = $this->input->post('id_rumpun');
		$id_tingkat = $this->input->post('id_tingkat');

		$jurusan = $this->drh_model->get_jurusan($id_rumpun,$id_tingkat);
		echo '<option value="">-</option>';
		foreach($jurusan as $jur) :
			echo $select = $jur->id_jurusan == $id_jurusan ? 'selected' : '';
			echo '<option value="'.$jur->id_jurusan.'" '.$select.'>' . $jur->nama_jurusan.' </option>';
		endforeach;
	}

	function autocomplite_sekolah(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("query=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->select("distinct sekolah_nama", false);
		$this->db->like("sekolah_nama",$search);
		$this->db->order_by('sekolah_nama','asc');
		$this->db->limit(20,0);
		$sekolah= $this->db->get("pegawai_pendidikan")->result();
		foreach ($sekolah as $q) {
			$sekolahs[] = array(
				'sekolah_nama' => $q->sekolah_nama 
			);
		}
		echo json_encode($sekolahs);
	}

}