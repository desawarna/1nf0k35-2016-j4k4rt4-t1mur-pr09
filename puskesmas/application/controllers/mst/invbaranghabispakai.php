<?php
class Invbaranghabispakai extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/pengadaanbarang_model');
		$this->load->model('mst/invbaranghabispakai_model');
	}

	function filter(){
		if($_POST) {
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_code_cl_phc',$this->input->post('code_cl_phc'));
			}
		}
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

		
		$rows_all = $this->invbaranghabispakai_model->get_data();


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

		
		$rows = $this->invbaranghabispakai_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		//$unlock = 1;
		foreach($rows as $act) {
			$data[] = array(
				'no'					=> $no++,
				'uraian'				=> $act->uraian,
				'jumlah'				=> $act->jumlah,
				'id_mst_inv_barang_habispakai_jenis'	=> $act->id_mst_inv_barang_habispakai_jenis,
				'edit'					=> 1,
				'delete'				=> 1
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	public function edit_barang($kode=0)
	{
        $this->form_validation->set_rules('uraian', 'Uraian', 'trim|required');
		/*end validasi kode barang*/
		if($this->form_validation->run()== FALSE){
			$data = $this->invbaranghabispakai_model->get_data_detail_edit($kode); 
			$data['action']="edit";
			$data['kode']= $kode;
			$data['notice']			= validation_errors();
			$data['pilihan_satuan_barang'] = $this->pengadaanbarang_model->get_data_pilihan('satuan_bhp');
			die($this->parser->parse('mst/invbaranghabispakai/barang_form',$data));
		}else{
			$dataupdate = array(
					'code' 			=> $this->input->post('code'),
					'uraian'		=> $this->input->post('uraian'),
					'merek_tipe' 	=> $this->input->post('merek_tipe'),
					'negara_asal' 	=> $this->input->post('negara_asal'),
					'pilihan_satuan' => $this->input->post('pilihan_satuan'),
				);
			$key['id_mst_inv_barang_habispakai'] = $this->input->post('id_mst_inv_barang_habispakai_jenis');
			$simpan = $this->db->update("mst_inv_barang_habispakai",$dataupdate,$key);
			if($simpan==true){
				die("OK|Data Telah diUbah");
			}else{
				 die("Error|Proses data gagal");
			}
		}
		
	}
	function json_detail($kode = 0){
		$this->authentication->verify('mst','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);
				if($field=="jenisuraian"){
					$this->db->like("mst_inv_barang_habispakai_jenis.uraian",$value);
				}else if($field=="uraian"){
					$this->db->like("mst_inv_barang_habispakai.uraian",$value);
				}else{
					$this->db->like($field,$value);	
				}
				
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		
		$rows_all = $this->invbaranghabispakai_model->get_data_detail();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field=="jenisuraian"){
					$this->db->like("mst_inv_barang_habispakai_jenis.uraian",$value);
				}else if($field=="uraian"){
					$this->db->like("mst_inv_barang_habispakai.uraian",$value);
				}else{
					$this->db->like($field,$value);	
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){
				
			}else{
				$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		$rows = $this->invbaranghabispakai_model->get_data_detail($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		//$unlock = 1;
		foreach($rows as $act) {
			$data[] = array(
				'no'					=> $no++,
				'code'					=> $act->code,
				'uraian'				=> $act->uraian,
				'merek_tipe'			=> $act->merek_tipe,
				'negara_asal'			=> $act->negara_asal,
				'pilihan_satuan'		=> $act->pilihan_satuan,
				'value'					=> $act->value,
				'jenisuraian'			=> $act->jenisuraian,
				'id_mst_inv_barang_habispakai'			=> $act->id_mst_inv_barang_habispakai,
				'id_mst_inv_barang_habispakai_jenis'	=> $act->id_mst_inv_barang_habispakai_jenis,
				'edit'					=> 1,
				'delete'				=> 1
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
		$data['title_group'] 	= "Master";
		$data['title_form'] 	= "Barang Habis Pakai";

		$this->db->like('code','p'.substr($this->session->userdata('puskesmas'),0,7));
		if($this->session->userdata('filter_jenisbarang')!=''){
			$this->session->set_userdata('filter_jenisbarang','');
		}
		$data['content'] = $this->parser->parse("mst/invbaranghabispakai/show",$data,true);

		$this->template->show($data,"home");
	}
	
	

	function do_detail($kode=0){
		
		$this->authentication->verify('mst','edit');

		$data = $this->invbaranghabispakai_model->get_data_row($kode); 

		$data['title_group'] = "Master";
		$data['title_form']="Detail Jenis Barang Habis Pakai";
		$data['kode']= $kode;

		$data['jenisbarang'] = $this->invbaranghabispakai_model->get_data();
		$data['kondisi'] = $this->invbaranghabispakai_model->get_pilihan_kondisi()->result();
		$data['n_kondisi'] = $this->invbaranghabispakai_model->get_pilihan_kondisi()->num_rows();
	
		$data['barang'] = $this->parser->parse("mst/invbaranghabispakai/barang",$data,true);
		$data['content'] = $this->parser->parse("mst/invbaranghabispakai/detail",$data,true);
		$this->template->show($data,"home");
		
	}
	function filter_jenisbarang(){
		if($_POST) {
			if($this->input->post('jenisbarang') != '') {
				$this->session->set_userdata('filter_jenisbarang',$this->input->post('jenisbarang'));
			}
		}
	}
	function detail($id="")
	{
		$this->session->set_userdata('filter_id_jenis_habispakai',$id);
		
		$this->do_detail($id);
		
	}


	function add(){

		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('uraian', 'Uraian', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['code']		 		= $this->session->userdata('puskesmas');
			$data['title_group'] 		= "Master";
			$data['title_form']  		= "Tambah jenis Habis pakai";
			$data['action']      		= "add";
			$data['kode']				= "";

		
			$data['content'] = $this->parser->parse("mst/invbaranghabispakai/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->invbaranghabispakai_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."mst/invbaranghabispakai/");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."mst/invbaranghabispakai/add");
		}
	}

	function edit($kode=0)
	{
		$this->authentication->verify('mst','add');

        $this->form_validation->set_rules('uraian', 'Uraian', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->invbaranghabispakai_model->get_data_row($kode); 

			$data['title_group'] = "Master";
			$data['title_form']="Ubah Master Jenis Barang Habis Pakai";
			$data['action']="edit";
			$data['kode']= $kode;

		
			$data['content'] = $this->parser->parse("mst/invbaranghabispakai/edit",$data,true);
			$this->template->show($data,"home");
		}elseif($this->invbaranghabispakai_model->update_entry($kode)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."mst/invbaranghabispakai/edit/".$this->input->post('kode'));
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."mst/invbaranghabispakai/edit/".$kode);
		}
	}

	function dodel($id=""){
		$this->authentication->verify('mst','del');

		if($this->invbaranghabispakai_model->delete_entry($id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$id.')');
			redirect(base_url()."mst/invbaranghabispakai");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."mst/invbaranghabispakai");
		}
	}
	function dodelbarang($kode=0){
		$this->authentication->verify('inventory','del');

		if($this->invbaranghabispakai_model->delete_entryitem($kode)){
				
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}
	
	function export_detail(){
		$this->authentication->verify('mst','show');

		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

		$filter_group = $this->session->userdata('filter_group');

		$no=1;
		$data_tabel = array();
		if(!empty($filter_group) and $filter_group == '1'){
			$rows = $this->invbaranghabispakai_model->get_data_detail_group();
			
			$data = array();
			foreach($rows as $act) {
				$act['no']	= $no++;
				$act['harga']	= number_format($act['harga']);
				$act['kode_barang']	= substr(chunk_split($act['id_mst_inv_barang'], 2, '.'),0,14); 
				$act['keterangan']	= "";
				$data_tabel[] =  $act;
			}
		}else{
			$rows = $this->invbaranghabispakai_model->get_data_detail();
			
			$data = array();
			foreach($rows as $act) {
				$act['no']	= $no++;
				$act['kode_barang']	= substr(chunk_split($act['id_mst_inv_barang'], 2, '.'),0,14); 
				$act['harga']	= number_format($act['harga']);
				$data_tabel[] =  $act;
			}
		}
		
		$code_cl_phc 	= $this->input->post('filter_code_cl_phc');
		$ruang 			= $this->input->post('filter_id_ruang');
		$tanggal 			= $this->input->post('filter_tanggal');
		if(empty($code_cl_phc) or $code_cl_phc == 'Pilih Puskesmas'){
			$kode = "P".$this->session->userdata('puskesmas');
			$nama = "Data Seluruh Puskesmas";
		}else{
			$kode = $this->input->post('filter_code_cl_phc');
			$puskesmas  = $this->inv_barang_model->get_nama('value','cl_phc','code',$kode);
			$nama = $puskesmas;
		}

		if(empty($ruang) or $ruang == 'Pilih Ruangan'){
			$namaruang = 'Data Seluruh Ruangan';
		}else{
			$this->db->where('id_mst_inv_ruangan',$ruang);
			$this->db->where('code_cl_phc',$kode);
			$ruang = $this->db->get('mst_inv_ruangan')->row();
			$namaruang = !empty($ruang) ? $ruang->nama_ruangan : "-";
		}

		$propinsi = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode, 1,2));
		$kabkota  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode, 1,4));
		$kecamatan  = $this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 1,7));
		$tanggals = explode("-",$tanggal);
		$tanggal_export = $tanggals[2].'-'.$tanggals[1].'-'.$tanggals[0];
		$data_puskesmas['puskesmas'] 	= $nama;
		$data_puskesmas['tanggal'] 		= $tanggal_export;
		$data_puskesmas['ruangan'] 		= $namaruang;
		$data_puskesmas['kecamatan'] 	= $kecamatan;
		$data_puskesmas['kabkota'] 		= $kabkota;
		$data_puskesmas['propinsi'] 	= $propinsi;
		
		$TBS->ResetVarRef(false);
		$TBS->VarRef =  &$data_puskesmas;	
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/mst/kir.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		$TBS->MergeBlock('a', $data_tabel);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_kir_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	public function add_barang($kode=0)
	{	
		$data['action']			= "add";
		$data['kode']			= $kode;
        $this->form_validation->set_rules('uraian', 'Uraian', 'trim|required');
        $this->form_validation->set_rules('code', 'Kode', 'trim');
        $this->form_validation->set_rules('uraian', 'Uraian', 'trim');
        $this->form_validation->set_rules('merek_tipe', 'Merek Tipe', 'trim');
        $this->form_validation->set_rules('negara_asal', 'Negara Asal', 'trim');
        $this->form_validation->set_rules('pilihan_satuan', 'Satuan', 'trim');

		if($this->form_validation->run()== FALSE){
			$data['kode']			= $kode;
			$data['notice']			= validation_errors();
			$data['pilihan_satuan_barang'] = $this->pengadaanbarang_model->get_data_pilihan('satuan_bhp');
			die($this->parser->parse('mst/invbaranghabispakai/barang_form', $data));
		}else{
				$values = array(
					'id_mst_inv_barang_habispakai_jenis'=> $this->input->post('id_mst_inv_barang_habispakai_jenis'),
					'code' 			=> $this->input->post('code'),
					'uraian'		=> $this->input->post('uraian'),
					'merek_tipe' 	=> $this->input->post('merek_tipe'),
					'negara_asal' 	=> $this->input->post('negara_asal'),
					'pilihan_satuan' => $this->input->post('pilihan_satuan'),
					'harga' => $this->input->post('harga'),
				);
				$simpan=$this->db->insert('mst_inv_barang_habispakai', $values);
				if($simpan==true){
				die("OK|Data disimpan");
			}else{
				 die("Error|Proses data gagal");
			}
			
		}
	}
}
