<?php
class Drh extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('kepegawaian/drh_model');
		$this->load->model('mst/puskesmas_model');
	}
	function json(){
		$this->authentication->verify('kepegawaian','show');


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

		$rows_all = $this->drh_model->get_data();


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

		$rows = $this->drh_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'nip_nit'		=> $act->nip_nit,
				'nip_lama'		=> $act->nip_lama,
				'nip_baru'		=> $act->nip_baru,
				'nrk'			=> $act->nrk,
				'karpeg'		=> $act->karpeg,
				'nit'			=> $act->nit,
				'nit_phl'		=> $act->nit_phl,
				'gelar'			=> $act->gelar,
				'nama'			=> $act->nama,
				'tar_sex'		=> $act->tar_sex,
				'tgl_lhr'		=> $act->tgl_lhr,
				'tmp_lahir'		=> $act->tmp_lahir,
				'kode_mst_agama'=> $act->kode_mst_agama,
				'kode_mst_nikah'=> $act->kode_mst_nikah,
				'tar_npwp'		=> $act->tar_npwp,
				'tar_npwp_tgl'	=> $act->tar_npwp_tgl,
				'ktp'			=> $act->ktp,
				'goldar'		=> $act->goldar,
				'code_cl_phc'	=> $act->code_cl_phc,
				'status_masuk'	=> $act->status_masuk,
				'view'		=> 1,
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
		$this->authentication->verify('kepegawaian','edit');
		$data['title_group'] = "Parameter";
		$data['title_form'] = "Master Data - Peg Daftar Riwayat Hidup";
		// $data= $this->drh_model->get_data();
		// var_dump($data);
		// exit();

		$data['content'] = $this->parser->parse("kepegawaian/drh/show",$data,true);

		$this->template->show($data,"home");
	}

//crud pegawai
	function add(){
		$this->authentication->verify('kepegawaian','add');

        $this->form_validation->set_rules('nip_nit', 'Nip / Nit', 'trim|required');
        $this->form_validation->set_rules('nip_lama', 'Nip Lama', 'trim|');
        $this->form_validation->set_rules('nip_baru', 'Nip Baru', 'trim|');
        $this->form_validation->set_rules('nrk', 'nrk', 'trim|');
        $this->form_validation->set_rules('karpeg', 'Kerpeg', 'trim|');
        $this->form_validation->set_rules('nit', 'Nit', 'trim|');
        $this->form_validation->set_rules('nit_phl', 'Nit Phl', 'trim|');
        $this->form_validation->set_rules('gelar', 'Gelar', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('tar_sex', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lhr', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('tmp_lahir', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('kode_mst_agama', 'kode agama', 'trim|required');
        $this->form_validation->set_rules('kode_mst_nikah', 'Kode Nikah', 'trim|required');
        $this->form_validation->set_rules('tar_npwp', 'Tar NPWP', 'trim|required');
        $this->form_validation->set_rules('tar_npwp_tgl', 'Tar NPWP Tanggal', 'trim|required');
        $this->form_validation->set_rules('ktp', 'No Ktp', 'trim|required');
        $this->form_validation->set_rules('goldar', 'Golongan Darah', 'trim|required');
        $this->form_validation->set_rules('code_cl_phc', 'KOde puskesmas', 'trim|required');
        $this->form_validation->set_rules('status_masuk', 'Status Masuk', 'trim|required');

			if($this->form_validation->run()== FALSE){
				$data['title_group'] = "Parameter";
				$data['title_form']="Tambah Daftar Riwayat Hidup Pegawai";
				$data['action']="add";
				$data['kode']="";
				$data['kode_ag'] = $this->drh_model->get_kode_agama('kode_ag');
				$data['kode_nk'] = $this->drh_model->get_kode_nikah('kode_nk');

				$kodepuskesmas = $this->session->userdata('puskesmas');
				if(substr($kodepuskesmas, -2)=="01"){
					$this->db->like('code','P'.substr($kodepuskesmas,0,7));
				}else{
					$this->db->like('code','P'.$kodepuskesmas);
				}
				$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
				// var_dump($data['kodepuskesmas']);
				// exit();
				$data['form_tambahan'] = "";
				$data['content'] = $this->parser->parse("kepegawaian/drh/form",$data,true);
				$this->template->show($data,"home");
			}elseif($this->drh_model->insert_entry() == 1){
				$this->session->set_flashdata('alert', 'Save data successful...');
				redirect(base_url().'kepegawaian/drh/edit'. $id.'/'.$this->input->post('nip_nit'));
			}else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."kepegawaian/drh/add");
			}

	}

	function edit($id=0)
	{
		$this->authentication->verify('kepegawaian','add');

        $this->form_validation->set_rules('nip_nit', 'Nip / Nit', 'trim|required');
        $this->form_validation->set_rules('nip_lama', 'Nip Lama', 'trim|');
        $this->form_validation->set_rules('nip_baru', 'Nip Baru', 'trim|');
        $this->form_validation->set_rules('nrk', 'nrk', 'trim|');
        $this->form_validation->set_rules('karpeg', 'Kerpeg', 'trim|');
        $this->form_validation->set_rules('nit', 'Nit', 'trim|');
        $this->form_validation->set_rules('nit_phl', 'Nit Phl', 'trim|');
        $this->form_validation->set_rules('id_jurusan', 'ID Jurusan', 'trim|required');
        $this->form_validation->set_rules('gelar', 'Gelar', 'trim|required');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('tar_sex', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tgl_lhr', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('tmp_lahir', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('kode_kepegawaian_agama', 'kode agama', 'trim|required');
        $this->form_validation->set_rules('kode_kepegawaian_nikah', 'Kode Nikah', 'trim|required');
        $this->form_validation->set_rules('tar_npwp', 'Tar NPWP', 'trim|required');
        $this->form_validation->set_rules('tar_npwp_tgl', 'Tar NPWP Tanggal', 'trim|required');
        $this->form_validation->set_rules('ktp', 'No Ktp', 'trim|required');
        $this->form_validation->set_rules('goldar', 'Golongan Darah', 'trim|required');
        $this->form_validation->set_rules('code_cl_phc', 'KOde puskesmas', 'trim|required');
        $this->form_validation->set_rules('status_masuk', 'Status Masuk', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->drh_model->get_data_row($id); 
			// var_dump($data);
			// exit();
			$data['title_group'] = "Parameter";
			$data['title_form']="Ubah Data Pegawai";
			$data['action']="edit";
			$data['id']=$id;
			$data['alamat'] = $this->drh_model->get_data_alamat($id);
			$data['kode_ag'] = $this->drh_model->get_kode_agama('kode_ag');
			$data['kode_nk'] = $this->drh_model->get_kode_nikah('kode_nk');

			$kodepuskesmas = $this->session->userdata('puskesmas');
				if(substr($kodepuskesmas, -2)=="01"){
					$this->db->like('code','P'.substr($kodepuskesmas,0,7));
				}else{
					$this->db->like('code','P'.$kodepuskesmas);
				}
				$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
			
			$data['form_tambahan'] = $this->parser->parse("kepegawaian/drh/form_tambahan",$data,true);
			$data['content'] = $this->parser->parse("kepegawaian/drh/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->drh_model->update_entry($id)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."kepegawaian/drh/".$this->input->post('id'));
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."kepegawaian/drh/edit/".$id);
		}
	}

	function dodel($id=0){
		$this->authentication->verify('kepegawaian','del');

		if($this->drh_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
			redirect(base_url()."kepegawaian/drh");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."kepegawaian/drh");
		}
	}

// CRUD ALAMAT

	function kota($kode_provinsi="",$kode_kota="")
	{
		$data['code_cl_district'] = "<option>-</option>";
		$kota = $this->drh_model->get_kota($kode_provinsi);	
		if (is_array($kota) || is_object($kota))
		{	
			foreach($kota as $x => $y){
				$data['code_cl_district'] .= "<option value='".$x."' ";
				if($kode_kota == $x) $data['code_cl_district'] .="selected";
				$data['code_cl_district'] .=">".$y."</option>";
			}
		}

		header('Content-type: text/X-JSON');
		echo json_encode($data);
		exit;
	}
	
	function kecamatan($kode_kota="",$kode_kec="")
	{
		$data['code_cl_kec'] = "<option>-</option>";
		$kecamatan = $this->drh_model->get_kecamatan($kode_kota);
		if (is_array($kecamatan) || is_object($kecamata))
		{		
			foreach($kecamatan as $x=>$y){
				$data['code_cl_kec'] .= "<option value='".$x."' ";
				if($kode_kec == $x) $data['code_cl_kec'] .="selected";
				$data['code_cl_kec'] .=">".$y."</option>";
			}
		}

		header('Content-type: text/X-JSON');
		echo json_encode($data);
		exit;
	}
	
	function desa($kode_kec="",$kode_desa="")
	{
		$data['code_cl_village'] = "<option>-</option>";
		$desa = $this->drh_model->get_desa($kode_kec);	
		if (is_array($desa) || is_object($desa))
		{	
			foreach($desa as $x=>$y){
				$data['code_cl_village'] .= "<option value='".$x."' ";
				if($kode_desa == $x) $data['code_cl_village'] .="selected";
				$data['code_cl_village'] .=">".$y."</option>";
			}
		}

		header('Content-type: text/X-JSON');
		echo json_encode($data);
		exit;
	}
// Alamat
	function get_urut_alamat($id="")
    {
    	$this->db->select('max(urut) as id');
    	$this->db->where('nip_nit',$id);
    	$jum = $this->db->get('pegawai_alamat')->row();
    	
    	if (empty($jum)){
    		return 1;
    	}else {
			return $jum->id+1;
    	}

	}

	function add_alamat($id="")
	{
		$this->authentication->verify('kepegawaian','add');

		$data['id']		= $id;
		$data['title_group'] = "Parameter";
		$data['title_form']="Tambah Alamat Pegawai";
		$data['action']="add_alamat";

		
		$this->form_validation->set_rules('nip_nit', 'NIP / NIT', 'trim|required');
		// $this->form_validation->set_rules('urut', 'No Urut Alamat', 'trim|required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('rt', 'RT', 'trim|required');
		$this->form_validation->set_rules('rw', 'RW', 'trim|required');
		$this->form_validation->set_rules('code_cl_province', 'Propinsi', 'trim|required');
		$this->form_validation->set_rules('code_cl_district', 'Kota', 'trim|required');
		$this->form_validation->set_rules('code_cl_kec', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('code_cl_village', 'Kelurahan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			// $data['urut'] 			= $this->drh_model->get_data_alamat('urut');
			$data['notice']			= validation_errors();
			if(!isset($propinsi)){
              	$propinsi  = set_value('propinsi');
              	$data['propinsi']  = set_value('propinsi');
            }
			if(!isset($kota)){
				$kota = set_value('kota');
              	$data['kota']  = set_value('kota');
            }
			if(!isset($kecamatan)){
              	$data['kecamatan']  = set_value('kecamatan');
            }
			if(!isset($desa)){
              	$data['desa']  = set_value('desa');
            }
			$data['provinsi_option']	= $this->drh_model->provinsi_option($propinsi);
			
			$data['content'] = $this->parser->parse("kepegawaian/drh/form_alamat",$data,true);
			die($this->parser->parse('kepegawaian/drh/form_alamat', $data,true));
		}else{
			$values = array(
				'nip_nit'=>$id,
				'urut' => $this->get_urut_alamat($this->input->post('nip_nit')),
				'alamat' => $this->input->post('alamat'),
				'rt' => $this->input->post('rt'),
				'rw' => $this->input->post('rw'),
				'code_cl_province' => $this->input->post('code_cl_province'),
				'code_cl_district' => $this->input->post('code_cl_district'),
				'code_cl_kec' => $this->input->post('code_cl_kec'),
				'code_cl_village' => $this->input->post('code_cl_village')
			);
			if($this->db->insert('pegawai_alamat', $values)){
				$key['nip_nit'] = $id;
        		$this->db->update("pegawai",$key);

				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
		}

	}

	function edit_alamat($id=0,$urut="")
	{
		$this->authentication->verify('kepegawaian','add');

		// $data['id']		= $id;
		$data['urut']	= $urut;
		$data['title_group'] = "Parameter";
		$data['title_form']="Tambah Alamat Pegawai";
		$data['action']="edit_alamat";

		
		// $this->form_validation->set_rules('nip_nit', 'NIP / NIT', 'trim|required');
		// $this->form_validation->set_rules('urut', 'No Urut Alamat', 'trim|required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
		$this->form_validation->set_rules('rt', 'RT', 'trim|required');
		$this->form_validation->set_rules('rw', 'RW', 'trim|required');
		$this->form_validation->set_rules('code_cl_province', 'Propinsi', 'trim|required');
		$this->form_validation->set_rules('code_cl_district', 'Kota', 'trim|required');
		$this->form_validation->set_rules('code_cl_kec', 'Kecamatan', 'trim|required');
		$this->form_validation->set_rules('code_cl_village', 'Kelurahan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			// $data['urut'] 			= $this->drh_model->get_data_alamat('urut');
			$data['notice']			= validation_errors();
			$data = $this->drh_model->get_data_alamat_id($id,$urut);
			$data['urut']	= $urut;
			$data['action']="edit_alamat";
			$data['id'] = $id;
			// var_dump($data);
			// exit();
			$data['notice']			= validation_errors();
			if(!isset($propinsi)){
              	$propinsi  = set_value('propinsi');
              	$data['propinsi']  = set_value('propinsi');
            }
			if(!isset($kota)){
				$kota = set_value('kota');
              	$data['kota']  = set_value('kota');
            }
			if(!isset($kecamatan)){
              	$data['kecamatan']  = set_value('kecamatan');
            }
			if(!isset($desa)){
              	$data['desa']  = set_value('desa');
            }
			$data['provinsi_option']	= $this->drh_model->provinsi_option($propinsi);
			
			$data['content'] = $this->parser->parse("kepegawaian/drh/form_alamat_edit",$data,true);
			die($this->parser->parse('kepegawaian/drh/form_alamat_edit', $data,true));
		}else{
			$values = array(
				// 'nip_nit'=>$id,
				// 'urut' => $this->input->post('urut'),
				'alamat' => $this->input->post('alamat'),
				'rt' => $this->input->post('rt'),
				'rw' => $this->input->post('rw'),
				'code_cl_province' => $this->input->post('code_cl_province'),
				'code_cl_district' => $this->input->post('code_cl_district'),
				'code_cl_kec' => $this->input->post('code_cl_kec'),
				'code_cl_village' => $this->input->post('code_cl_village')
			);
			if($this->db->update('pegawai_alamat', $values,array('urut'=>$urut))){
				// $key['nip_nit'] = $id;
    //     		$this->db->update("pegawai_alamat",$key);

				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
		}

	}

	function dodel_alamat($id="",$urut=0){
		$this->authentication->verify('kepegawaian','del');

		if($this->drh_model->delete_entry_alamat($id,$urut)){
			$this->session->set_flashdata('alert','delete data ('.$id.')');
			redirect(base_url()."kepegawaian/drh/edit/".$id);
		} else {
			$this->session->set_flashdata('alert','delete data error');
			redirect(base_url()."kepegawaian/drh/edit/".$id);
		}
	}

//CRUD DIKLAT

	function add_diklat($id=""){
		$this->authentication->verify('kepegawaian','add');

		$data['id']		= $id;
		$data['title_group'] = "Parameter";
		$data['title_form']="Tambah Data Diklat Pegawai";
		$data['action']="add_diklat";

		
		// $this->form_validation->set_rules('nip_nit', 'NIP / NIT', 'trim|required');
		// $this->form_validation->set_rules('urut', 'No Urut Alamat', 'trim|required');
		$this->form_validation->set_rules('id_mst_peg_kursus', 'Jenis Diklat', 'trim|required');
		$this->form_validation->set_rules('nama_diklat', 'Nama Diklat', 'trim|required');
		$this->form_validation->set_rules('lama_diklat', 'Lama Diklat', 'trim|required');
		$this->form_validation->set_rules('tgl_diklat', 'Tanggal Diklat', 'trim|required');
		$this->form_validation->set_rules('tar_penyelenggara', 'Penyelenggara', 'trim|required');

		if($this->form_validation->run()== FALSE){
			// $data['urut'] 			= $this->drh_model->get_data_alamat('urut');
			$data['notice']			   = validation_errors();
			$data['peg_kursus'] = $this->drh_model->get_data_diklat1($id);

			// var_dump($data['id_mst_peg_kursus']);
			// exit();
			$data['content'] = $this->parser->parse("kepegawaian/drh/form_diklat",$data,true);
			// echo "string";
			die($this->parser->parse('kepegawaian/drh/form_diklat', $data,true));
		}else{
			$values = array(
				// 'nip_nit'=>$id,
				// 'urut' => $this->input->post('urut'),
				'id_mst_peg_kursus' => $this->input->post('id_mst_peg_kursus'),
				'nip_nit' => $id,
				'nama_diklat' => $this->input->post('nama_diklat'),
				'lama_diklat' => $this->input->post('lama_diklat'),
    			'tgl_diklat' => date("Y-m-d",strtotime($this->input->post('tgl_diklat'))),
				'tar_penyelenggara' => $this->input->post('tar_penyelenggara')
			);
			if($this->db->insert('pegawai_diklat', $values)){
				$key['nip_nit'] = $id;
        		$this->db->update("pegawai_diklat",$key);

				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
		}
	}


	function edit_diklat($id="",$id_mst_peg_kursus=0){
		$this->authentication->verify('kepegawaian','add');

		$data['id']		= $id;
		$data['title_group'] = "Parameter";
		$data['title_form']="Tambah Data Diklat Pegawai";
		$data['action']="edit_diklat";

		
		// $this->form_validation->set_rules('nip_nit', 'NIP / NIT', 'trim|required');
		// $this->form_validation->set_rules('urut', 'No Urut Alamat', 'trim|required');
		$this->form_validation->set_rules('id_mst_peg_kursus', 'Jenis Diklat', 'trim|required');
		$this->form_validation->set_rules('nama_diklat', 'Nama Diklat', 'trim|required');
		$this->form_validation->set_rules('lama_diklat', 'Lama Diklat', 'trim|required');
		$this->form_validation->set_rules('tgl_diklat', 'Tanggal Diklat', 'trim|required');
		$this->form_validation->set_rules('tar_penyelenggara', 'Penyelenggara', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['notice']			   = validation_errors();
			$data = $this->drh_model->get_data_diklat_id($id,$id_mst_peg_kursus);
			// var_dump($data);
			// exit();
			$data['peg_kursus'] = $this->drh_model->get_data_diklat1($id);
			$data['action'] 	= "edit_diklat";
			$data['id_mst_peg_kursus'] = $id_mst_peg_kursus;
			$data['id'] = $id;
			
			$data['content'] = $this->parser->parse("kepegawaian/drh/form_diklat_edit",$data,true);
			// echo "string";
			die($this->parser->parse('kepegawaian/drh/form_diklat_edit', $data,true));
		}else{
			$values = array(
				// 'nip_nit'=>$id,
				// 'urut' => $this->input->post('urut'),
				'id_mst_peg_kursus' => $this->input->post('id_mst_peg_kursus'),
				// 'nip_nit' => $id,
				'nama_diklat' => $this->input->post('nama_diklat'),
				'lama_diklat' => $this->input->post('lama_diklat'),
    			'tgl_diklat' => date("Y-m-d",strtotime($this->input->post('tgl_diklat'))),
				'tar_penyelenggara' => $this->input->post('tar_penyelenggara')
			);
			if($this->db->update('pegawai_diklat', $values,array('id_mst_peg_kursus'=>$id_mst_peg_kursus))){
				// $key['nip_nit'] = $id;
    //     		$this->db->update("pegawai_diklat",$key);

				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
		}
	}

	function dodel_diklat($id="",$id_mst_peg_kursus=0){
		$this->authentication->verify('kepegawaian','del');

		if($this->drh_model->delete_entry_diklat($id,$id_mst_peg_kursus)){
			$this->session->set_flashdata('alert','delete data ('.$id.')');
			redirect(base_url()."kepegawaian/drh/edit/".$id);
		} else {
			$this->session->set_flashdata('alert','delete data error');
			redirect(base_url()."kepegawaian/drh/edit/".$id);
		}
	}

// DP3 Pegawai

		function add_dp3($id=""){
		$this->authentication->verify('kepegawaian','add');

		$data['id']		= $id;
		$data['title_group'] = "Parameter";
		$data['title_form']="Tambah Data Diklat Pegawai";
		$data['action']="add_dp3";

		
		// $this->form_validation->set_rules('nip_nit', 'NIP / NIT', 'trim|required');
		// $this->form_validation->set_rules('urut', 'No Urut Alamat', 'trim|required');
		$this->form_validation->set_rules('tahun', 'Tahun', 'trim|required');
		$this->form_validation->set_rules('setia', 'Kesetiaan', 'trim|required');
		$this->form_validation->set_rules('prestasi', 'Prestasi', 'trim|required');
		$this->form_validation->set_rules('tanggungjawab', 'Tanggung Jawab', 'trim|required');
		$this->form_validation->set_rules('taat', 'Ketaatan', 'trim|required');
		$this->form_validation->set_rules('jujur', 'Kejujuran', 'trim|required');
		$this->form_validation->set_rules('kerjasama', 'kerjasama', 'trim|required');
		$this->form_validation->set_rules('pimpin', 'Kepemimpinan', 'trim|required');
		$this->form_validation->set_rules('prakarsa', 'Prakarsa', 'trim|required');
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|');
		$this->form_validation->set_rules('ratarata', 'Rata-rata', 'trim|');

		if($this->form_validation->run()== FALSE){
			// $data['urut'] 			= $this->drh_model->get_data_alamat('urut');
			// $data['peg_kursus'] = $this->drh_model->get_data_diklat1($id);
			$data['notice']			   = validation_errors();
			$data['id']			=$id;
			// var_dump($data['id_mst_peg_kursus']);
			// exit();
			$data['content'] = $this->parser->parse("kepegawaian/drh/form_dp3",$data,true);
			// echo "string";
			die($this->parser->parse('kepegawaian/drh/form_dp3', $data,true));
		}else{
			$values = array(
				// 'nip_nit'=>$id,
				// 'urut' => $this->input->post('urut'),
				'nip_nit' => $id,
				'tahun' => $this->input->post('tahun'),
				'setia' => $this->input->post('setia'),
				'prestasi' => $this->input->post('prestasi'),
    			'tanggungjawab' => $this->input->post('tanggungjawab'),
				'taat' => $this->input->post('taat'),
				'jujur' => $this->input->post('jujur'),
				'kerjasama' => $this->input->post('kerjasama'),
				'pimpin' => $this->input->post('pimpin'),
				'prakarsa' => $this->input->post('prakarsa'),
				'jumlah' => $this->input->post('setia')+$this->input->post('prestasi')+$this->input->post('tanggungjawab')+$this->input->post('taat')+$this->input->post('jujur')+$this->input->post('kerjasama')+$this->input->post('pimpin')+$this->input->post('prakarsa'),
				'ratarata' => ($this->input->post('tahun')+$this->input->post('setia')+$this->input->post('prestasi')+$this->input->post('tanggungjawab')+$this->input->post('taat')+$this->input->post('jujur')+$this->input->post('kerjasama')+$this->input->post('pimpin')+$this->input->post('prakarsa'))*2,
			);
			if($this->db->insert('pegawai_dp3', $values)){
				$key['nip_nit'] = $id;
        		$this->db->update("pegawai_dp3",$key);

				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
		}
	}


	// function edit_diklat($id="",$id_mst_peg_kursus=0){
	// 	$this->authentication->verify('kepegawaian','add');

	// 	$data['id']		= $id;
	// 	$data['title_group'] = "Parameter";
	// 	$data['title_form']="Tambah Data Diklat Pegawai";
	// 	$data['action']="edit_diklat";

		
	// 	// $this->form_validation->set_rules('nip_nit', 'NIP / NIT', 'trim|required');
	// 	// $this->form_validation->set_rules('urut', 'No Urut Alamat', 'trim|required');
	// 	$this->form_validation->set_rules('id_mst_peg_kursus', 'Jenis Diklat', 'trim|required');
	// 	$this->form_validation->set_rules('nama_diklat', 'Nama Diklat', 'trim|required');
	// 	$this->form_validation->set_rules('lama_diklat', 'Lama Diklat', 'trim|required');
	// 	$this->form_validation->set_rules('tgl_diklat', 'Tanggal Diklat', 'trim|required');
	// 	$this->form_validation->set_rules('tar_penyelenggara', 'Penyelenggara', 'trim|required');

	// 	if($this->form_validation->run()== FALSE){
	// 		$data['notice']			   = validation_errors();
	// 		$data = $this->drh_model->get_data_diklat_id($id,$id_mst_peg_kursus);
	// 		// var_dump($data);
	// 		// exit();
	// 		$data['peg_kursus'] = $this->drh_model->get_data_diklat1($id);
	// 		$data['action'] 	= "edit_diklat";
	// 		$data['id_mst_peg_kursus'] = $id_mst_peg_kursus;
	// 		$data['id'] = $id;
			
	// 		$data['content'] = $this->parser->parse("kepegawaian/drh/form_diklat_edit",$data,true);
	// 		// echo "string";
	// 		die($this->parser->parse('kepegawaian/drh/form_diklat_edit', $data,true));
	// 	}else{
	// 		$values = array(
	// 			// 'nip_nit'=>$id,
	// 			// 'urut' => $this->input->post('urut'),
	// 			'id_mst_peg_kursus' => $this->input->post('id_mst_peg_kursus'),
	// 			// 'nip_nit' => $id,
	// 			'nama_diklat' => $this->input->post('nama_diklat'),
	// 			'lama_diklat' => $this->input->post('lama_diklat'),
 //    			'tgl_diklat' => date("Y-m-d",strtotime($this->input->post('tgl_diklat'))),
	// 			'tar_penyelenggara' => $this->input->post('tar_penyelenggara')
	// 		);
	// 		if($this->db->update('pegawai_diklat', $values,array('id_mst_peg_kursus'=>$id_mst_peg_kursus))){
	// 			// $key['nip_nit'] = $id;
 //    //     		$this->db->update("pegawai_diklat",$key);

	// 			die("OK|");
	// 		}else{
	// 			die("Error|Proses data gagal");
	// 		}
	// 	}
	// }

	// function dodel_diklat($id="",$id_mst_peg_kursus=0){
	// 	$this->authentication->verify('kepegawaian','del');

	// 	if($this->drh_model->delete_entry_diklat($id,$id_mst_peg_kursus)){
	// 		$this->session->set_flashdata('alert','delete data ('.$id.')');
	// 		redirect(base_url()."kepegawaian/drh/edit/".$id);
	// 	} else {
	// 		$this->session->set_flashdata('alert','delete data error');
	// 		redirect(base_url()."kepegawaian/drh/edit/".$id);
	// 	}
	// }

}