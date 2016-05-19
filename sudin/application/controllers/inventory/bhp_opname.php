<?php
class Bhp_opname extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('inventory/bhp_opname_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('inventory/bhp_opname_model');
		$this->load->model('mst/invbarang_model');
	}
	function filter(){
		if($_POST) {
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_code_cl_phc',$this->input->post('code_cl_phc'));
			}
		}
	}
	function pengeluaran_export(){
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		
		$this->authentication->verify('inventory','show');

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
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				$this->db->where("jenis_bhp",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		
		$rows_all = $this->bhp_opname_model->get_data();


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

		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){
				
			}else{
				$this->db->where("jenis_bhp",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows = $this->bhp_opname_model->get_data();
		$data_tabel = array();
		$no=1;
		//$unlock = 1;
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
				'id_inv_inventaris_habispakai_opname'	=> $act->id_inv_inventaris_habispakai_opname,
				'code_cl_phc'			=> $act->code_cl_phc,
				'tgl_opname'			=> date("d-m-Y",strtotime($act->tgl_opname)),
				'jenis_bhp'				=> ucfirst($act->jenis_bhp),
				'petugas_nip'			=> $act->petugas_nip,
				'petugas_nama'			=> $act->petugas_nama,
				'catatan'				=> $act->catatan,
				'nomor_opname'			=> $act->nomor_opname,
				'edit'					=> 1,
				'delete'				=> 1,
			);
		}

		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$nama_puskesmas = $this->input->post('nama_puskesmas');
		//$tgl = date("d-m-Y");
		$jenis_barang = $this->input->post('jenisbarang');
		$tgl = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		
		$data_puskesmas[] = array('jenis_barang' => $jenis_barang,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'nama_puskesmas' => $nama_puskesmas,'bulan'=>$tgl,'tahun'=>$tahun);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/stok_opname.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_daftarstokopname_'.$code.'.xlsx';
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
	function filter_bulan(){
		if($_POST) {
			if($this->input->post('bulan') != '') {
				$this->session->set_userdata('filter_bulan',$this->input->post('bulan'));
			}
		}
	}
	function filter_tahun(){
		if($_POST) {
			if($this->input->post('tahun') != '') {
				$this->session->set_userdata('filter_tahun',$this->input->post('tahun'));
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

				$this->db->like($field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				$this->db->where("jenis_bhp",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows_all = $this->bhp_opname_model->get_data();


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

		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){
				
			}else{
				$this->db->where("jenis_bhp",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows = $this->bhp_opname_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		//$unlock = 1;
		foreach($rows as $act) {
			$data[] = array(
				'no'					=> $no++,
				'id_inv_inventaris_habispakai_opname'	=> $act->id_inv_inventaris_habispakai_opname,
				'code_cl_phc'			=> $act->code_cl_phc,
				'tgl_opname'			=> $act->tgl_opname,
				'jenis_bhp'				=> ucfirst($act->jenis_bhp),
				'petugas_nip'			=> $act->petugas_nip,
				'petugas_nama'			=> $act->petugas_nama,
				'catatan'				=> $act->catatan,
				'nomor_opname'			=> $act->nomor_opname,
				'last_opname' 			=> ($act->tgl_opname >= $act->last_tgl_opname) ? 1 :0,
				'edit'					=> 0,
				'delete'				=> 0,
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function add_opname($value='')
	{
		$this->authentication->verify('inventory','add');

		$this->form_validation->set_rules('kode_distribusi_', 'Kode Opname', 'trim|required');
        $this->form_validation->set_rules('tgl_opname', 'Tanggal Opname', 'trim|required');
        $this->form_validation->set_rules('penerima_nama', 'Nama Penerima', 'trim|required');
        $this->form_validation->set_rules('penerima_nip', 'NIP Penerima', 'trim|required');
        $this->form_validation->set_rules('nomor_opname', 'Nomor Opname', 'trim|required');
        $this->form_validation->set_rules('catatan', 'Catatan', 'trim|required');
        $this->form_validation->set_rules('jenis_bhp', 'jenis_bhp', 'trim');
        $this->form_validation->set_rules('puskesmastambah', 'puskesmastambah', 'trim');
		$data['title_group'] 	= "Bahan Habis Pakai";
		$data['title_form']		= "Tambah Opname";
		$data['action']			= "add";
		$data['kode']			= "";
		$data['alert_form']="";
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('xcode','P'.$kodepuskesmas);
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("inventory/bhp_opname/form",$data,true));
		}elseif($id = $this->bhp_opname_model->insert_entry()){
			$data['alert_form']='Save data successful...';
			if ($this->input->post('jenis_bhp')=="obat") {
				$jenis='8';
			}else{
				$jenis='0';
			}
			$this->edit_opname($id,$jenis);
		}else{
			$data['alert_form']='Save data failed...';
		}
		//die($this->parser->parse("inventory/bhp_opname/form",$data,true));
	}
	function edit_opname($id_opname=0,$jenis_bhp=0){

		if ($this->input->post('idjenis')!='' || !empty($this->input->post('idjenis'))) {
			$jenis_bhp=$this->input->post('idjenis');
		}
		if ($this->input->post('id')!='' || !empty($this->input->post('id'))) {
			$id_opname=$this->input->post('id');
		}
		

		$this->authentication->verify('inventory','edit');

        $this->form_validation->set_rules('kode_distribusi_', 'Kode Opname', 'trim|required');
        $this->form_validation->set_rules('tgl_opname', 'Tanggal Opname', 'trim|required');
        $this->form_validation->set_rules('penerima_nama', 'Nama Penerima', 'trim|required');
        $this->form_validation->set_rules('penerima_nip', 'NIP Penerima', 'trim|required');
        $this->form_validation->set_rules('nomor_opname', 'Nomor Opname', 'trim|required');
        $this->form_validation->set_rules('catatan', 'Catatan', 'trim|required');
        $this->form_validation->set_rules('jenis_bhp', 'jenis_bhp', 'trim');
        $this->form_validation->set_rules('id_inv_inventaris_habispakai_opname', 'id_inv_inventaris_habispakai_opname', 'trim');
        $this->form_validation->set_rules('puskesmastambah', 'puskesmastambah', 'trim');

    	$data 	= $this->bhp_opname_model->get_data_row($id_opname);
		$data['title_group'] 	= "Barang Habis Pakai";
		$data['title_form']		= "Ubah Stok Opname";
		$data['action']			= "edit";
		$data['kode']			= $id_opname;
		$data['jenisbarangbhp']		= $jenis_bhp;
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		$data['kodestatus_inv'] = $this->bhp_opname_model->pilih_data_status('status_pembelian');
		$data['barang']	  			= $this->parser->parse('inventory/bhp_opname/barang', $data, TRUE);
		$data['barang_opname'] 	= $this->parser->parse('inventory/bhp_opname/barang_opname', $data, TRUE);
		$data['alert_form'] ='';
		
		if($this->form_validation->run()== FALSE){

			die($this->parser->parse("inventory/bhp_opname/edit",$data,true));
		}elseif($this->bhp_opname_model->update_entry($id_opname)){
			$data['alert_form'] 	= 'Save data successful...';
		}else{
			$data['alert_form'] 	= 'Save data failed...';
		}
		die($this->parser->parse("inventory/bhp_opname/edit",$data,true));
	}
	function jsonpengeluaran($id=0){
		$this->authentication->verify('inventory','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_update') {
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
		
		if ($id!=0) {
			$this->db->where("inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai",$id);
		}
		
		$rows_all = $this->bhp_opname_model->get_datapengeluaran();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_update') {
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

		if ($id!=0) {
			$this->db->where("inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai",$id);
		}
		$rows = $this->bhp_opname_model->get_datapengeluaran($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		$no=1;
		//$unlock = 1;
		foreach($rows as $act) {
			$data[] = array(
				'uraian'				=> $act->uraian,
				'tgl_update'			=> $act->tgl_update,
				'nama_pilihan'			=> $act->nama_pilihan,
				/*'jmlawal'				=> ($act->totaljumlah+$act->jmlbaik)-($act->jml_rusak+$act->jml_tdkdipakai),
				'jml_akhir'				=> ($act->totaljumlah+$act->jmlbaik)-($act->jml_rusak+$act->jml_tdkdipakai+$act->jmlpengeluaran),*/
				'harga'					=> $act->harga,
				'jml'			=> $act->jml,
				'id_mst_inv_barang_habispakai'			=> $act->id_mst_inv_barang_habispakai,
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function json_barang($id=0,$tgl_opname='0000-00-00'){
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
		if ($id=='8') {
			$this->db->where('id_mst_inv_barang_habispakai_jenis',$id);
		}else{
			$this->db->where('id_mst_inv_barang_habispakai_jenis !=','8');
		}
		if ($tgl_opname!='0000-00-00' or empty($tgl_opname)) {
			$this->db->query("set @var =".'"'.$tgl_opname.'"'."");
		}
		$rows_all_activity = $this->bhp_opname_model->getitem();


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
		if ($id=='8') {
			$this->db->where('id_mst_inv_barang_habispakai_jenis',$id);
		}else{
			$this->db->where('id_mst_inv_barang_habispakai_jenis !=','8');
		}
		if ($tgl_opname!='0000-00-00' or empty($tgl_opname)) {
			$this->db->query("set @var =".'"'.$tgl_opname.'"'."");
		}
		$activity = $this->bhp_opname_model->getitem($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		
		foreach($activity as $act) {
			$data[] = array(
				'id_inv_inventaris_habispakai_distribusi'   => $act->id_inv_inventaris_habispakai_distribusi,
				'code_cl_phc'   						=> $act->code_cl_phc,
				'jenis_bhp'								=> $act->jenis_bhp,
				'tgl_distribusi'						=> $act->tgl_distribusi,
				'id_mst_inv_barang_habispakai'			=> $act->id_mst_inv_barang_habispakai,
				'uraian'								=> $act->uraian,
				'id_mst_inv_barang_habispakai_jenis'	=> $act->id_mst_inv_barang_habispakai_jenis,
				'jml_distribusi'						=> $act->jml_distribusi,
				'batch'									=> $act->batch,
				'jml_opname'							=> $act->jml_opname,
				'tgl_opname'							=> $act->tgl_opname,
				'jmlawal'								=> $act->jmlawal,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}


		
		$size = sizeof($rows_all_activity);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function json_opname($id=0){
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

		if ($id!=0) {
			$this->db->where('inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname',$id);
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				$this->db->where("jenis_bhp",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$bl =$this->session->userdata('filter_bulan');
				$this->db->query("set @bulan = ".'"'.$bl.'"'."");
				//$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
			}
		}else{
				$bl = date("m");
				$this->db->query("set @bulan = ".'"'.$bl.'"'."");
				//$this->db->where("MONTH(tgl_opname)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$th =$this->session->userdata('filter_tahun');
				$this->db->query("set @tahun = ".'"'.$th.'"'."");
				//$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$th =date("Y");
			$this->db->query("set @tahun = ".'"'.$th.'"'."");
			//$this->db->where("YEAR(tgl_opname)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows_all_activity = $this->bhp_opname_model->getitemopname();


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

		if ($id!=0) {
			$this->db->where('inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname',$id);
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				$this->db->where("jenis_bhp",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$bl =$this->session->userdata('filter_bulan');
				$this->db->query("set @bulan = ".'"'.$bl.'"'."");
				//$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
			}
		}else{
			$bl = date("m");
			$this->db->query("set @bulan = ".'"'.$bl.'"'."");
			//$this->db->where("MONTH(tgl_opname)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$th =$this->session->userdata('filter_tahun');
				$this->db->query("set @tahun = ".'"'.$th.'"'."");
				//$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$th =date("Y");
			$this->db->query("set @tahun = ".'"'.$th.'"'."");
			//$this->db->where("YEAR(tgl_opname)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$activity = $this->bhp_opname_model->getitemopname($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		
		foreach($activity as $act) {
			$data[] = array(
				'id_mst_inv_barang_habispakai'   			=> $act->id_mst_inv_barang_habispakai,
				'batch'										=> $act->batch,
				'uraian'									=> $act->uraian,
				'id_inv_inventaris_habispakai_opname'		=> $act->id_inv_inventaris_habispakai_opname,
				'jml_awal'									=> $act->jml_awal,
				'jml_akhir'									=> $act->jml_akhir,
				'jmlawal_opname'							=> $act->jmlawal_opname,
				'sumselisih'								=> ($act->sumselisih)*-1,
				'jmlakhir_opname'							=> $act->jmlawal_opname + $act->sumselisih,
				'harga'										=> $act->harga,
				'merek_tipe'								=> $act->merek_tipe,
				'tgl_opname'								=> date("d-m-Y",strtotime($act->tgl_opname)),
				'jml_selisih'								=> $act->jml_akhir-$act->jml_awal,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}


		
		$size = sizeof($rows_all_activity);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function json_opname_dalam($id=0){
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

		if ($id!=0) {
			$this->db->where('inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname',$id);
		}
		
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows_all_activity = $this->bhp_opname_model->get_data_opname();


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

		if ($id!=0) {
			$this->db->where('inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname',$id);
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$activity = $this->bhp_opname_model->get_data_opname($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		
		foreach($activity as $act) {
			$data[] = array(
				'id_mst_inv_barang_habispakai'   			=> $act->id_mst_inv_barang_habispakai,
				'batch'										=> $act->batch,
				'uraian'									=> $act->uraian,
				'id_inv_inventaris_habispakai_opname'		=> $act->id_inv_inventaris_habispakai_opname,
				'jml_awal'									=> $act->jml_awal,
				'jml_akhir'									=> $act->jml_akhir,
				'selisih'									=> $act->jml_akhir - $act->jml_awal,
				'harga'										=> $act->harga,
				'merek_tipe'								=> $act->merek_tipe,
				'tgl_opname'								=> date("d-m-Y",strtotime($act->tgl_opname)),
				'jml_selisih'								=> $act->jml_akhir-$act->jml_awal,
				'edit'		=> 0,
				'delete'	=> 0
			);
		}


		
		$size = sizeof($rows_all_activity);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] = "Barang Habis Pakai";
		$this->session->set_userdata('filter_code_cl_phc','');
		$data['title_form'] = "Stock Opname";
		$this->session->set_userdata('filter_jenisbarang','');
		$this->session->set_userdata('filter_bulan','');
		$data['content'] = $this->parser->parse("inventory/bhp_opname/show",$data,true);
		$this->template->show($data,"home");
	}

	function tab($index){
		if($index==1) $this->daftar_bhp();
		else $this->daftar_opname();
	}
	public function kodedistribusi($id=0){
		$this->db->where('code',$id);
		$query = $this->db->get('cl_phc')->result();
		foreach ($query as $q) {
			$kode[] = array(
				'kodeinv' => $q->cd_kompemilikbarang.'.'.$q->cd_propinsi.'.'.$q->cd_kabkota.'.'.$q->cd_bidang.'.'.$q->cd_unitbidang.'.'.$q->cd_satuankerja, 
			);
			echo json_encode($kode);
		}
	}

	function dodelpermohonan($kode=0,$barang=0,$batch=0){
		
		if($this->bhp_opname_model->delete_entryitem($kode,$barang,$batch)){
				return true;
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
				
	}
	function daftar_bhp(){
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);
		$this->session->set_userdata('filter_jenisbarang','');
		$data['datapuskesmas'] 	= $this->inv_ruangan_model->get_data_puskesmas();
		$data['jenisbaranghabis'] = array('obat'=>'Obat','umum'=>'Umum');
		$data['msg_opname'] = "";
		$this->session->set_userdata('filter_jenisbarang','');
		$this->session->set_userdata('filter_bulan','');
		$data['bulan']			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		die($this->parser->parse("inventory/bhp_opname/show_bhp",$data,true));
	}

	function daftar_opname(){
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);
		$data['datapuskesmas'] 	= $this->inv_ruangan_model->get_data_puskesmas();
		$data['jenisbaranghabis'] = array('obat'=>'Obat','umum'=>'Umum');
		$data['msg_opname'] = "";
		$this->session->set_userdata('filter_jenisbarang','');
		$this->session->set_userdata('filter_bulan','');
		$this->session->set_userdata('filter_tahun','');
		$data['bulan']			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		die($this->parser->parse("inventory/bhp_opname/show_opname",$data,true));
	}

	function autocomplite_barang(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("query=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$kodepuskesmas = "P".$this->session->userdata("puskesmas");
		$this->db->like("uraian",$search);
		$this->db->order_by('id_mst_inv_barang_habispakai','asc');
		$this->db->limit(10,0);
		$this->db->select("mst_inv_barang_habispakai.*,
			(select jml as jml from  inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jmlbaik,
            (select sum(jml) as jmltotal from inv_inventaris_habispakai_pembelian_item 
            JOIN inv_inventaris_habispakai_pembelian ON(inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian AND inv_inventaris_habispakai_pembelian.code_cl_phc = inv_inventaris_habispakai_pembelian_item.code_cl_phc AND inv_inventaris_habispakai_pembelian.pilihan_status_pembelian=2)  
            where inv_inventaris_habispakai_pembelian_item.code_cl_phc=".'"'.$kodepuskesmas.'"'." and id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai ) as totaljumlah,
            (select jml_rusak as jmlrusak from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai order by tgl_update desc limit 1) as jml_rusak,
            (select jml_tdkdipakai as jmltdkdipakai from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai order by tgl_update desc limit 1) as jml_tdkdipakai
			");
		$query= $this->db->get("mst_inv_barang_habispakai")->result();
		foreach ($query as $q) {
			$barang[] = array(
				'id_mst_inv_barang_habispakai' 	=> $q->id_mst_inv_barang_habispakai , 
				'uraian' 						=> $q->uraian, 
				'jmlbaik' 						=> $q->jmlbaik-($q->jml_rusak+$q->jml_tdkdipakai), 
				'totaljumlah' 					=> $q->totaljumlah, 
				'harga' 						=> $q->harga, 

			);
		}
		echo json_encode($barang);
	}
	public function add_barang($tanggal_opnam='0000-00-00',$kodeopname=0,$idbarang=0,$batch='')
	{	
		$data['action']			= "add";
		$data['kode']			= $kodeopname;

        $this->form_validation->set_rules('id_mst_inv_barang_habispakai_jenis', 'ID Barang', 'trim');
        $this->form_validation->set_rules('id_inv_inventaris_habispakai_opname', 'Nama Barang', 'trim');
        $this->form_validation->set_rules('batch', 'Batch', 'trim|required');
        $this->form_validation->set_rules('uraian', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah Awal', 'trim');
        $this->form_validation->set_rules('harga', 'harga', 'trim');
        $this->form_validation->set_rules('jml_rusak', 'jml_rusak', 'trim');
        $this->form_validation->set_rules('jml_tdkdipakai', 'jml_tdkdipakai', 'trim');
        $this->form_validation->set_rules('tgl_update_opname', 'tgl_update_opname', 'trim');
        $this->form_validation->set_rules('jumlahopname', 'Jumlah Opname', 'trim|required');

		if($this->form_validation->run()== FALSE){

			$data = $this->bhp_opname_model->get_data_detail_edit_barang($kodeopname,$idbarang,$batch,$tanggal_opnam); 
			$data['action']			= "add";
			$data['kode']			= $kodeopname;
			$data['notice']			= validation_errors();

			die($this->parser->parse('inventory/bhp_opname/barang_form', $data));
		}else{
			if($simpan=$this->bhp_opname_model->insertdata()){
				$id=$this->input->post('id_mst_inv_barang');
				die("OK|$id|Tersimpan");
			}else{
				$id=$this->input->post('id_mst_inv_barang');
				 die("Error|$id|Proses data gagal");
			}
			
		}
	}
	function detail_master($id){
    	$kodepuskesmas = "P".$this->session->userdata("puskesmas");
		$this->db->where("id_mst_inv_barang_habispakai","$id");
		$this->db->select("mst_inv_barang_habispakai.*,
			(SELECT harga AS hrg FROM inv_inventaris_habispakai_opname_item JOIN inv_inventaris_habispakai_opname ON (inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname)  WHERE code_cl_phc=".'"'.$kodepuskesmas.'"'." AND id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai ORDER BY tgl_opname DESC LIMIT 1) AS harga_opname,
			(select harga as hargapembelian from inv_inventaris_habispakai_pembelian_item 
            where code_cl_phc=".'"'.$kodepuskesmas.'"'." and id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai order by tgl_update desc limit 1 ) as harga_pembelian,
            (SELECT tgl_opname AS tglopname FROM inv_inventaris_habispakai_opname_item JOIN inv_inventaris_habispakai_opname ON (inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname) WHERE id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai AND code_cl_phc=".'"'.$kodepuskesmas.'"'."ORDER BY tgl_opname DESC LIMIT 1) AS tgl_opname,
            (select tgl_update  as tglpembelian from inv_inventaris_habispakai_pembelian_item where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_pembelian");
		$query= $this->db->get("mst_inv_barang_habispakai")->result();
		foreach ($query as $q) {
			if (($q->tgl_pembelian!=null)||($q->tgl_opname!=null)) {
	          if($q->tgl_opname==null){
	            $tgl_opname = 0;
	          }else{
	            $tgl_opname = $q->tgl_opname;
	          }

	          if ($q->tgl_pembelian==null) {
	            $tgl_pembelian = 0;
	          }else{
	            $tgl_pembelian = $q->tgl_pembelian;
	          }
	          if( $tgl_pembelian>= $tgl_opname){
	            $hargabarang = $q->harga_pembelian;  
	          }else{
	            $hargabarang = $q->harga_opname;  
	          }
	        }else{
	          if ($q->harga==null) {
	            $hargaasli =0;
	          }else{
	            $hargaasli =$q->harga;
	          }

	          $hargabarang = $hargaasli;
            }
			$totalpengadaan[] = array(
				'hargabarang' 					=> $hargabarang, 
			);
			echo json_encode($totalpengadaan);
		}
    }
	function autocomplite_barang_master($obat=8){
		$kodepuskesmas = "P".$this->session->userdata("puskesmas");
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("term=","",$search[0]);
		$search = str_replace("+"," ",$search);
		if ($obat=='0') {
			$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis !=",'8');
		}else{
			$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$obat);
		}
		$this->db->like("uraian",$search);
		$this->db->order_by('id_mst_inv_barang_habispakai','asc');
		$this->db->limit(7,0);
		$this->db->select("mst_inv_barang_habispakai.*");
		$query= $this->db->get("mst_inv_barang_habispakai")->result();
		foreach ($query as $q) {
			$barangmaster[] = array(
				'key' 		=> $q->id_mst_inv_barang_habispakai , 
				'value' 	=> $q->uraian,
				'satuan'	=>$q->pilihan_satuan, 
				
			);
		}
		echo json_encode($barangmaster);
	}
	public function add_barang_opnamemaster($kodeopname=0,$jenis_master=8)
	{	
		$data['action']			= "add";
		$data['kode']			= $kodeopname;

        $this->form_validation->set_rules('id_mst_inv_barang_habispakai_jenis_master', 'ID Barang', 'trim');
        $this->form_validation->set_rules('id_mst_inv_barang_habispakai_master', 'Nama Barang', 'trim');
        $this->form_validation->set_rules('id_inv_inventaris_habispakai_opname_master', 'Kode Opname', 'trim');
        $this->form_validation->set_rules('batch_master', 'Batch', 'trim|required');
        $this->form_validation->set_rules('uraian_master', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah_awal_opname', 'Jumlah Awal', 'trim');
        $this->form_validation->set_rules('harga_master', 'harga', 'trim');
        $this->form_validation->set_rules('jumlah_masteropname', 'Jumlah Opname', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['action']			= "add";
			$data['kode']			= $kodeopname;
			$data['notice_master']			= validation_errors();
			$data['jenis_master']			= $jenis_master;

			die($this->parser->parse('inventory/bhp_opname/barang_form_master', $data));
		}else{
			if($simpan=$this->bhp_opname_model->insertdatamaster()){
				$id=$this->input->post('id_mst_inv_barang');
				die("OK|$id|Tersimpan");
			}else{
				$id=$this->input->post('id_mst_inv_barang');
				 die("Error|$id|Proses data gagal");
			}
			
		}
	}
	public function detailbhp($kodeopname=0,$idbarang=0,$batch='')
	{	
		$data['action']			= "add";
		$data['kode']			= $kodeopname;

        $this->form_validation->set_rules('id_mst_inv_barang_habispakai_jenis', 'ID Barang', 'trim');
        $this->form_validation->set_rules('id_inv_inventaris_habispakai_opname', 'Nama Barang', 'trim');
        $this->form_validation->set_rules('batch', 'Batch', 'trim|required');
        $this->form_validation->set_rules('uraian', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah Awal', 'trim');
        $this->form_validation->set_rules('harga', 'harga', 'trim');
        $this->form_validation->set_rules('jumlahopname', 'Jumlah Opname', 'trim|required');

		if($this->form_validation->run()== FALSE){

			$data = $this->bhp_opname_model->get_data_detail_bhp($kodeopname,$idbarang,$batch); 
			$data['action']			= "add";
			$data['kode']			= $kodeopname;
			$data['notice']			= validation_errors();
			$kodepuskesmas = $this->session->userdata('puskesmas');
			$this->db->where('code','P'.$kodepuskesmas);
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
			die($this->parser->parse('inventory/bhp_opname/detail_form', $data));
		}else{
			if($simpan=$this->bhp_opname_model->insertdata()){
				$id=$this->input->post('id_mst_inv_barang');
				die("OK|$id|Tersimpan");
			}else{
				$id=$this->input->post('id_mst_inv_barang');
				 die("Error|$id|Proses data gagal");
			}
			
		}
	}
	
  	public function timeline_pengeluaran_barang($id_barang = 0){
  		$data = array();
  		$data['kode'] = $id_barang;
       	$data['data_kondisi'] 	= $this->bhp_opname_model->get_kondisi_barang($id_barang);
  		echo $this->parser->parse("inventory/bhp_opname/kondisi",$data);


  		die();
  	}
	
	function autocomplite_nama(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("term=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->distinct();
		$this->db->like("petugas_nama",$search);
		$this->db->order_by('id_inv_inventaris_habispakai_opname','asc');
		$this->db->limit(10,0);
		$this->db->select("petugas_nama");
		$query= $this->db->get("inv_inventaris_habispakai_opname")->result();
		foreach ($query as $q) {
			$barang[] = array(
				'value'	=> $q->petugas_nama,
			);
		}
		echo json_encode($barang);
	}
	function autocomplite_nip(){
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("term=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->distinct();
		$this->db->like("petugas_nip",$search);
		$this->db->order_by('id_inv_inventaris_habispakai_opname','asc');
		$this->db->limit(10,0);
		$this->db->select("petugas_nip");
		$query= $this->db->get("inv_inventaris_habispakai_opname")->result();
		foreach ($query as $q) {
			$barang[] = array(
				'value'	=> $q->petugas_nip,
			);
		}
		echo json_encode($barang);
	}
	function dodel_opname($kode=0,$id_barang="",$table=0){
		$this->authentication->verify('inventory','del');

		if($this->bhp_opname_model->delete_opname($kode,$id_barang,$table)){
			$data['msg_opname'] = "Delete data $kode";
			die($this->parser->parse("inventory/bhp_opname/show_opname",$data,true));
		}else{
			$data['msg_opname']= 'Delete data error';
			die($this->parser->parse("inventory/bhp_opname/show_opname",$data,true));
		}
	}
	function laporan_opname($id=0){

		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		
		$this->authentication->verify('inventory','show');

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
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				//$this->db->where("jenis_bhp",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				//$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
			//	$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		
		
		$rows_all = $this->bhp_opname_model->get_data();

		$filtername ='';
		$order='';
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$filtername ="and $field like ".'"%'.$value.'%"'."";
			}

			if(!empty($ord)) {
				//$this->db->order_by($ord, $this->input->post('sortorder'));
				$val =  $this->input->post('sortorder');
				$order="order by $ord $val";
			}
		}
		$filbulan=date("m");
		$filtahun=date("Y");
		$filterbhp ="";
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){
				$filterbhp ="";
			}else{
				if ($this->session->userdata('filter_jenisbarang')=='obat') {
					$jenis='obat';
				}else{
					$jenis='umum';
				}
				$filterbhp = " and inv_inventaris_habispakai_opname.jenis_bhp = ".'"'.$jenis.'"'."";
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){
			}else{
				//$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
				$filbulan =$this->session->userdata('filter_bulan');
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){
			}else{
				//$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
				$filtahun =$this->session->userdata('filter_tahun');
			}
		}else{

			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		$puskesmas = $this->input->post('puskesmas');
		$rows = $this->bhp_opname_model->get_data_lap_opname($filbulan,$filtahun,$filterbhp,$filtername,$order,$puskesmas);
		//die(print_r($rows));
	//	$get_jumlahawal = $this->bhp_opname_model->get_jumlahawal();
		$data = array();
		$no=0;
		$data_tabel = array();
		$temp='';
		$jml=0;
		foreach ($rows as $key => $val) {
			$no++;
			foreach ($val as $act => $value) {
				
				if($key==$temp){
					$data_tabel["$key"]["keluar$act"]		= $value['pengeluaranperhari']*-1;	
					$data_tabel["$key"]["harga"]			= $value['harga'];	
					$data_tabel["$key"]["jumlah_op"]		= ($data_tabel["$key"]["jumlah_op"]+$value['pengeluaranperhari']);	
					$data_tabel["$key"]['nilai_aset_total']	= ($data_tabel["$key"]["jumlah_awal"] + $data_tabel["$key"]["jumlah_op"])*$value['harga'];
					$data_tabel["$key"]['total']			= $data_tabel["$key"]["jumlah_awal"] + $data_tabel["$key"]["jumlah_op"];
					$data_tabel["$key"]['nilai_aset_awal']  = $value['jumlah_awal']*$value['harga'];
															  
				}else{
				$temp = $key;
				$data_tabel[$key]= array(
					'no'				=> $no,								
					'uraian'			=> $key,
					'harga'				=> $value['harga'],
					'jumlah_op'			=> ($value['pengeluaranperhari']),
					'jumlah_awal'		=> $value['jumlah_awal'],
					'nilai_aset_awal'	=> $value['jumlah_awal']*$value['harga'],
					'total'				=> $value['jumlah_awal'] + $value['pengeluaranperhari'],
					'nilai_aset_total'	=> ($value['jumlah_awal'] + $value['pengeluaranperhari'])*$value['harga'],
					'keluar1'			=> $act == 1 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar2'			=> $act == 2 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar3'			=> $act == 3 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar4'			=> $act == 4 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar5'			=> $act == 5 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar6'			=> $act == 6 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar7'			=> $act == 7 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar8'			=> $act == 8 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar9'			=> $act == 9 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar10'			=> $act == 10 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar11'			=> $act == 11 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar12'			=> $act == 12 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar13'			=> $act == 13 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar14'			=> $act == 14 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar15'			=> $act == 15 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar16'			=> $act == 16 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar17'			=> $act == 17 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar18'			=> $act == 18 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar19'			=> $act == 19 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar20'			=> $act == 20 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar21'			=> $act == 21 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar22'			=> $act == 22 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar23'			=> $act == 23 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar24'			=> $act == 24 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar25'			=> $act == 25 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar26'			=> $act == 26 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar27'			=> $act == 27 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar28'			=> $act == 28 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar29'			=> $act == 29 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar30'			=> $act == 30 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar31'			=> $act == 31 ? ($value['pengeluaranperhari'])*-1 : '',
				);
			}
				
			}
		}
		///die(print_r($data_tabel));
		
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$nama_puskesmas = $this->input->post('nama_puskesmas');
		//$tgl = date("d-m-Y");
		$jenis_barang = $this->input->post('jenisbarang');
		$tgl = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$tgl1=date("m-m-Y");
		$tgl2=date("d-m-Y");
		$data_puskesmas[] = array('jenis_barang' => $jenis_barang,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'nama_puskesmas' => $nama_puskesmas,'bulan'=>$tgl,'tahun'=>$tahun,'tgl1'=>$tgl1,'tgl2'=>$tgl2,'tanggal_export'=>$tgl2);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/lap_bhp_pengeluaran.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_daftarstokopname_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	function lastopname($bhp='obat')
	{
		$kodepus = $this->session->userdata('puskesmas');
		$this->db->where('code_cl_phc','P'.$kodepus);
		$this->db->where('jenis_bhp',$bhp);
		$this->db->select("max(tgl_opname) as last_opname");
        $query = $this->db->get('inv_inventaris_habispakai_opname');
        if ($query->num_rows()>0) {
        	foreach ($query->result() as $key) {
        		if ($key->last_opname !=null) {
        			die($key->last_opname);
        		}else{
        			die('0000-00-00');	
        		}
        	}
        }else{
        	die('0000-00-00');	
        }
        

	}
}
