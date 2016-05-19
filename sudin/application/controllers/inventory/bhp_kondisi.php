<?php
class Bhp_kondisi extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('inventory/bhp_kondisi_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('mst/puskesmas_model');
	}
	function filter(){
		if($_POST) {
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_code_cl_phc',$this->input->post('code_cl_phc'));
			}
		}
	}

	function json(){
		$this->authentication->verify('inventory','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_update' ) {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
			}
		}
		$rows_all = $this->bhp_kondisi_model->getitem();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_update' ) {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
			}
		}
		$activity = $this->bhp_kondisi_model->getitem($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		
		foreach($activity as $act) {
			$data[] = array(
				'id_mst_inv_barang_habispakai'   		=> $act->id_mst_inv_barang_habispakai,
				'batch'   								=> $act->batch,
				'code_cl_phc'							=> $act->code_cl_phc,
				'jml_baik'								=> $act->jml_asli - ($act->jml_rusak+$act->jml_tdkdipakai),
				'tgl_update'							=> date("d-m-Y",strtotime($act->tgl_update)),
				'jml_rusak'								=> $act->jml_rusak,
				'uraian'								=> $act->uraian,
				'jml_tdkdipakai'						=> $act->jml_tdkdipakai,
				'id_inv_inventaris_habispakai_opname'	=> $act->id_inv_inventaris_habispakai_opname,
				'pilihan_satuan'						=> $act->pilihan_satuan,
				'id_mst_inv_barang_habispakai_jenis'	=> $act->id_mst_inv_barang_habispakai_jenis,
				'harga'									=> $act->harga,
				'edit'		=> 0,
				'delete'	=> 0
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	
	function pengadaan_export(){
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$this->authentication->verify('inventory','show');

		$this->authentication->verify('inventory','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_update' ) {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
			}
		}
		$rows_all = $this->bhp_kondisi_model->getitem();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_update' ) {
					$value = date("Y-m-d",strtotime($value));

					$this->db->where($field,$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
			}
		}
		$activity = $this->bhp_kondisi_model->getitem();
		$data_tabel = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		$no=1;
		foreach($activity as $act) {
			$data_tabel[] = array(
				'no'									=> $no++,
				'id_mst_inv_barang_habispakai'   		=> $act->id_mst_inv_barang_habispakai,
				'batch'   								=> $act->batch,
				'code_cl_phc'							=> $act->code_cl_phc,
				'jml_baik'								=> $act->jml_asli - ($act->jml_rusak+$act->jml_tdkdipakai),
				'tgl_update'							=> date("d-m-Y",strtotime($act->tgl_update)),
				'jml_rusak'								=> $act->jml_rusak,
				'uraian'								=> $act->uraian,
				'jml_tdkdipakai'						=> $act->jml_tdkdipakai,
				'id_inv_inventaris_habispakai_opname'	=> $act->id_inv_inventaris_habispakai_opname,
				'pilihan_satuan'						=> $act->pilihan_satuan,
				'id_mst_inv_barang_habispakai_jenis'	=> $act->id_mst_inv_barang_habispakai_jenis,
				'harga'									=> $act->harga
			);
		}
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$nama_puskesmas = $this->inv_barang_model->get_nama('value','cl_phc','code','P'.$kode_sess);
		
		$puskes = $this->input->post('puskes'); 
		$tahun = date("Y");
		$jenisbarang = strtoupper($this->input->post("jenisbarang"));
		$data_puskesmas[] = array('nama_puskesmas' => $nama_puskesmas,'tahun' => $tahun,'kd_kab' => $kd_kab,'kd_prov' => $kd_prov,'jenisbarang' => $jenisbarang);
		$dir = getcwd().'/';
		
		if (($jenisbarang=='ALL')||$jenisbarang=='OBAT') {
			$template = $dir.'public/files/template/inventory/bhp_kondisi.xlsx';		
		}else{
			$template = $dir.'public/files/template/inventory/bhp_kondisi_umum.xlsx';		
		}
		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();

		$output_file_name = 'public/files/hasil/hasil_export_bhpkondisi'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function filter_jenisbarang(){
		if($_POST) {
			if($this->input->post('jenisbarang') != '') {
				$this->session->set_userdata('filter_jenisbarang',$this->input->post('jenisbarang'));
			}
		}
	}
	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] = "Barang Habis Pakai";
		$data['title_form'] = "Kondisi Barang";
		$this->session->set_userdata('filter_jenisbarang','');
		$this->session->set_userdata('filter_code_cl_phc','');
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);

		$data['datapuskesmas'] 	= $this->bhp_kondisi_model->get_data_puskesmas();
		$data['jenisbaranghabis'] = $this->bhp_kondisi_model->get_data_jenis();
		$data['content'] = $this->parser->parse("inventory/bhp_kondisi/show",$data,true);
		$this->template->show($data,"home");
	}

	public function add_barang($kode=0)
	{	
		$data['action']			= "add";
		$data['kode']			= $kode;
        $this->form_validation->set_rules('jml', 'Jumlah Barang', 'trim|required');
        $this->form_validation->set_rules('harga', 'Harga', 'trim|required');

		if($this->form_validation->run()== FALSE){

			$data = $this->bhp_kondisi_model->get_data_detail_edit_barang($kode); 
			$data['action']			= "add";
			$data['kode']			= $kode;
			$data['notice']			= validation_errors();

			die($this->parser->parse('inventory/bhp_kondisi/barang_form', $data));
		}else{
			if($simpan=$this->bhp_kondisi_model->insertdata()){
				$id=$this->input->post('id_mst_inv_barang_habispakai');
				die("OK|$id|Tersimpan");
			}else{
				$id=$this->input->post('id_mst_inv_barang_habispakai');
				 die("Error|$id|Proses data gagal");
			}
			
		}
	}
	public function kondisi_barang($barang=0,$batch=0,$pus=0,$tgl=0,$opname=0)
	{	
		$data['action']			= "add";
		$data['kode']			= $barang;
        $this->form_validation->set_rules('harga', 'harga', 'trim');
        $this->form_validation->set_rules('jml_rusak', 'jml_rusak', 'trim');
        $this->form_validation->set_rules('jml_tdkdipakai', 'jml_tdkdipakai', 'trim');
        $this->form_validation->set_rules('jumlahopname', 'Jumlah Opname', 'trim|required');

		if($this->form_validation->run()== FALSE){

			$data = $this->bhp_kondisi_model->get_data_detail_edit_barang_edit($barang,$batch,$pus,$tgl,$opname); 
			$data['action']			= "add";
			$data['barang']			= $barang;
			$data['batch']			= $batch;
			$data['pus']			= $pus;
			$data['tgl']			= $tgl;
			$data['opname']			= $opname;
			$data['notice']			= validation_errors();

			die($this->parser->parse('inventory/bhp_kondisi/form', $data));
		}else{
			if($simpan=$this->bhp_kondisi_model->insertdata($barang,$batch,$pus,$tgl,$opname)){
				$id=$this->input->post('id_mst_inv_barang');
				die("OK|$id|Tersimpan");
			}else{
				$id=$this->input->post('id_mst_inv_barang');
				 die("Error|$id|Proses data gagal");
			}
			
		}
	}
	
	public function timeline_comment($id_barang = 0){
  		$data = array();
       	$data['data_barang'] 	= $this->bhp_kondisi_model->get_barang($id_barang);
  		echo $this->parser->parse("inventory/bhp_kondisi/barang",$data);


  		die();
  	}
  	public function timeline_kondisi_barang($id_barang = 0){
  		$data = array();
       	$data['data_kondisi'] 	= $this->bhp_kondisi_model->get_kondisi_barang($id_barang);
  		echo $this->parser->parse("inventory/bhp_kondisi/kondisi",$data);


  		die();
  	}
	function dodel_barang($kode=0,$id_barang="",$table=0){
		$this->authentication->verify('inventory','del');

		if($this->bhp_kondisi_model->delete_entryitem_table($kode,$id_barang,$table)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

	

}
