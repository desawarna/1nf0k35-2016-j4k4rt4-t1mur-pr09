<?php
class Drh extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('kepegawaian/drh_model');
		$this->load->model('mst/puskesmas_model');
	}

	function index(){
		$this->authentication->verify('kepegawaian','edit');
		$data['title_group'] = "Master Data";
		$data['title_form'] = "Daftar Riwayat Hidup";

		$data['content'] = $this->parser->parse("kepegawaian/drh/show",$data,true);

		$this->template->show($data,"home");
	}

	function json(){
		$this->authentication->verify('kepegawaian','show');

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

		$rows_all = $this->drh_model->get_data();

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

		$rows = $this->drh_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_pegawai'	=> $act->id_pegawai,
				'nip_lama'		=> $act->nip_lama,
				'nip_baru'		=> $act->nip_baru,
				'nik'			=> $act->nik,
				'nama'			=> $act->gelar_depan.' '.$act->nama.' '.$act->gelar_belakang,
				'jenis_kelamin'	=> $act->jenis_kelamin,
				'tgl_lhr'		=> $act->tgl_lhr,
				'tmp_lahir'		=> $act->tmp_lahir,
				'kode_mst_agama'=> $act->kode_mst_agama,
				'kode_mst_nikah'=> $act->kode_mst_nikah,
				'usia'			=> $act->usia,
				'goldar'		=> $act->goldar,
				'code_cl_phc'	=> $act->code_cl_phc,
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

	function add(){
		$this->authentication->verify('kepegawaian','add');

        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
        $this->form_validation->set_rules('gelar_depan', 'Gelar Depan', 'trim');
        $this->form_validation->set_rules('gelar_belakang', 'Gelar Belakang', 'trim');
        $this->form_validation->set_rules('tgl_lhr', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('tmp_lahir', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('kode_mst_agama', 'Agama', 'trim');
        $this->form_validation->set_rules('kedudukan_hukum', 'Kedudukan Hukum', 'trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim');
        $this->form_validation->set_rules('npwp', 'NPWP', 'trim');
        $this->form_validation->set_rules('npwp_tgl', 'Tanggal NPWP', 'trim');
        $this->form_validation->set_rules('kartu_pegawai', 'Kartu Pegawai', 'trim');
        $this->form_validation->set_rules('goldar', 'Golongan Darah', 'trim');
        $this->form_validation->set_rules('kode_mst_nikah', 'Status Nikah', 'trim');

			if($this->form_validation->run()== FALSE){
				$data['title_group'] = "Kepegawaian";
				$data['title_form']="Tambah Daftar Riwayat Hidup Pegawai";
				$data['action']="add";
				$data['kode']="";
				$data['kode_ag'] = $this->drh_model->get_kode_agama('kode_ag');
				$data['kode_nk'] = $this->drh_model->get_kode_nikah('kode_nk');

				$data['form_tambahan'] = "";
				$data['content'] = $this->parser->parse("kepegawaian/drh/form",$data,true);
				$this->template->show($data,"home");
			}elseif($id_pegawai = $this->drh_model->insert_entry()){
				$this->session->set_flashdata('alert', 'Save data successful...');
				redirect(base_url().'kepegawaian/drh/detail/'. $id_pegawai);
			}else{
				$this->session->set_flashdata('alert_form', 'Save data failed...');
				redirect(base_url()."kepegawaian/drh/add");
			}

	}

	function detail($id=0)
	{
		$this->authentication->verify('kepegawaian','add');

		$data = $this->drh_model->get_data_row($id); 
		$data['id']=$id;
		$data['nip']="1111 2222 3333 4444";
		$data['title_group'] = "Kepegawaian";
		$data['title_form']="Ubah Data Pegawai";

		$data['content'] = $this->parser->parse("kepegawaian/drh/form_detail",$data,true);
		$this->template->show($data,"home");
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

	function biodata($pageIndex,$pegawai_id){
		$data = array();
		$data['id']=$pegawai_id;

		switch ($pageIndex) {
			case 1:
				$this->biodata_biodata($pegawai_id);

				break;
			case 2:
				die($this->parser->parse("kepegawaian/drh/form_keluarga",$data));

				break;
			case 3:
				die($this->parser->parse("kepegawaian/drh/form_pendidikan",$data));

				break;
			case 4:
				die($this->parser->parse("kepegawaian/drh/form_pangkat",$data));

				break;
			case 5:
				die($this->parser->parse("kepegawaian/drh/form_jabatan",$data));

				break;
			case 6:
				die($this->parser->parse("kepegawaian/drh/form_dp3",$data));

				break;
			case 7:
				die($this->parser->parse("kepegawaian/drh/form_penghargaan",$data));

				break;
			default:

				die($this->parser->parse("kepegawaian/drh/form_status",$data));
				break;
		}

	}

	function getphoto($id){
        $path = 'media/images/photos/'.$id; 
		if (is_dir($path)){
		  if ($dh = opendir($path)){
		    while (($file = readdir($dh)) !== false){
		    	if($file !="." && $file !=".."){
			      readfile($path.'/'.$file);
			      die();
		    	}
		    }
		    closedir($dh);
		  }
		}
      	
      	readfile('media/images/profile.jpeg');
	}

	function douploadphoto($id,$resize_width=0){
		$this->authentication->verify('kepegawaian','add');
        
        $path = 'media/images/photos/'.$id; 
        if(!file_exists($path)){
        	mkdir($path);
        }

       	$config['upload_path'] = $path;

		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '1000';

		$config['max_width']  = '10000';
		$config['max_height']  = '8000';

		$this->load->library('upload', $config);
	
		if (!$this->upload->do_upload('uploadfile')){
			echo $this->upload->display_errors();
		}	
		else
		{
			$data = $this->upload->data();

			if($resize_width>0){
				$resize['image_library'] = 'gd2';
				$resize['source_image'] = $data['full_path'];
				$resize['width'] = $resize_width;
			}else{
			    $resize['image_library'] = 'gd2';
				$resize['source_image'] = $data['full_path'];
			}

			$this->load->library('image_lib', $resize);

			$this->image_lib->resize();		

			if (is_dir($path)){
			  if ($dh = opendir($path)){
			    while (($file = readdir($dh)) !== false){
			    	if($data['file_name'] != $file && $file !="." && $file !=".."){
				      unlink($path.'/'.$file);
			    	}
			    }
			    closedir($dh);
			  }
			}

			echo "success | ".$data['file_name'];
		}
	}

	function biodata_biodata($id){
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('nik', 'NIK', 'trim|required');
        $this->form_validation->set_rules('gelar_depan', 'Gelar Depan', 'trim');
        $this->form_validation->set_rules('gelar_belakang', 'Gelar Belakang', 'trim');
        $this->form_validation->set_rules('tgl_lhr', 'Tanggal Lahir', 'trim');
        $this->form_validation->set_rules('tmp_lahir', 'Tempat Lahir', 'trim');
        $this->form_validation->set_rules('kode_mst_agama', 'Agama', 'trim');
        $this->form_validation->set_rules('kedudukan_hukum', 'Kedudukan Hukum', 'trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim');
        $this->form_validation->set_rules('npwp', 'NPWP', 'trim');
        $this->form_validation->set_rules('npwp_tgl', 'Tanggal NPWP', 'trim');
        $this->form_validation->set_rules('kartu_pegawai', 'Kartu Pegawai', 'trim');
        $this->form_validation->set_rules('goldar', 'Golongan Darah', 'trim');
        $this->form_validation->set_rules('kode_mst_nikah', 'Status Nikah', 'trim');

		$data = $this->drh_model->get_data_row($id); 

		$data['id']=$id;
		$data['kode_ag'] = $this->drh_model->get_kode_agama('kode_ag');
		$data['kode_nk'] = $this->drh_model->get_kode_nikah('kode_nk');
		$data['alert_form'] = '';

		if($this->form_validation->run() == FALSE){
			die($this->parser->parse("kepegawaian/drh/form_biodata",$data));
		}elseif($this->drh_model->update_entry($id)){
			$data = $this->drh_model->get_data_row($id); 
			$data['id']=$id;
			$data['kode_ag'] = $this->drh_model->get_kode_agama('kode_ag');
			$data['kode_nk'] = $this->drh_model->get_kode_nikah('kode_nk');
			$data['alert_form'] = 'Save data successful...';
		}else{
			$data['alert_form'] = 'Save data failed...';
		}

		die($this->parser->parse("kepegawaian/drh/form_biodata",$data));
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

	function autocomplite_kota(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("query=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->like("value",$search);
		$this->db->order_by('value','asc');
		$this->db->limit(20,0);
		$kota= $this->db->get("cl_district")->result();
		foreach ($kota as $q) {
			$kotas[] = array(
				'value' => $q->value 
			);
		}
		echo json_encode($kotas);
	}

	
// Alamat
	function get_urut_alamat($id="")
    {
    	$this->db->select('max(urut) as id');
    	$this->db->where('id_pegawai',$id);
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

		
		$this->form_validation->set_rules('id_pegawai', 'NIP / NIT', 'trim|required');
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
				'id_pegawai'=>$id,
				'urut' => $this->get_urut_alamat($this->input->post('id_pegawai')),
				'alamat' => $this->input->post('alamat'),
				'rt' => $this->input->post('rt'),
				'rw' => $this->input->post('rw'),
				'code_cl_province' => $this->input->post('code_cl_province'),
				'code_cl_district' => $this->input->post('code_cl_district'),
				'code_cl_kec' => $this->input->post('code_cl_kec'),
				'code_cl_village' => $this->input->post('code_cl_village')
			);
			if($this->db->insert('pegawai_alamat', $values)){
				$key['id_pegawai'] = $id;
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

		
		// $this->form_validation->set_rules('id_pegawai', 'NIP / NIT', 'trim|required');
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
				// 'id_pegawai'=>$id,
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
				// $key['id_pegawai'] = $id;
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

		
		// $this->form_validation->set_rules('id_pegawai', 'NIP / NIT', 'trim|required');
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
				// 'id_pegawai'=>$id,
				// 'urut' => $this->input->post('urut'),
				'id_mst_peg_kursus' => $this->input->post('id_mst_peg_kursus'),
				'id_pegawai' => $id,
				'nama_diklat' => $this->input->post('nama_diklat'),
				'lama_diklat' => $this->input->post('lama_diklat'),
    			'tgl_diklat' => date("Y-m-d",strtotime($this->input->post('tgl_diklat'))),
				'tar_penyelenggara' => $this->input->post('tar_penyelenggara')
			);
			if($this->db->insert('pegawai_diklat', $values)){
				$key['id_pegawai'] = $id;
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

		
		// $this->form_validation->set_rules('id_pegawai', 'NIP / NIT', 'trim|required');
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
				// 'id_pegawai'=>$id,
				// 'urut' => $this->input->post('urut'),
				'id_mst_peg_kursus' => $this->input->post('id_mst_peg_kursus'),
				// 'id_pegawai' => $id,
				'nama_diklat' => $this->input->post('nama_diklat'),
				'lama_diklat' => $this->input->post('lama_diklat'),
    			'tgl_diklat' => date("Y-m-d",strtotime($this->input->post('tgl_diklat'))),
				'tar_penyelenggara' => $this->input->post('tar_penyelenggara')
			);
			if($this->db->update('pegawai_diklat', $values,array('id_mst_peg_kursus'=>$id_mst_peg_kursus))){
				// $key['id_pegawai'] = $id;
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

		
		// $this->form_validation->set_rules('id_pegawai', 'NIP / NIT', 'trim|required');
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
				// 'id_pegawai'=>$id,
				// 'urut' => $this->input->post('urut'),
				'id_pegawai' => $id,
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
				$key['id_pegawai'] = $id;
        		$this->db->update("pegawai_dp3",$key);

				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
		}
	}
}