<?php
class Bhp_distribusi extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('inventory/bhp_distribusi_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('mst/invbarang_model');
	}

	public function export_distribusi($id = 0){
		if($this->input->post('kode')!='' || !empty($this->input->post('kode'))){
			$id= $this->input->post('kode');
		}else{
			$id = 0;
		}
		if($this->input->post('jenis_bhp')!='' || !empty($this->input->post('jenis_bhp'))){
			$jenis_bhp= $this->input->post('jenis_bhp');
			if ($this->input->post('jenis_bhp')=="8") {
				$nama_jenis = "Obat";
			}else{
				$nama_jenis = "Umum";
			}
		}else{
			$jenis_bhp = 0;
			$nama_jenis = "Umum";
		}
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
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

		$this->db->where('inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi',$id);
		
		$rows_all_activity = $this->bhp_distribusi_model->getitemdistribusi();


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

		$this->db->where('inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi',$id);

		$activity = $this->bhp_distribusi_model->getitemdistribusi();
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
				'no'										=> $no++,
				'id_inv_inventaris_habispakai_distribusi'   => $act->id_inv_inventaris_habispakai_distribusi,
				'id_mst_inv_barang_habispakai'   			=> $act->id_mst_inv_barang_habispakai,
				'batch'										=> $act->batch,
				'uraian'									=> $act->uraian,
				'pilihan_satuan'							=> $act->pilihan_satuan,
				'tgl_kadaluarsa'							=> date("d-m-Y",strtotime($act->tgl_kadaluarsa)),
				'jumlah'									=> $act->jml,
			);
		}


		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$namapus  = $this->inv_barang_model->get_nama('value','cl_phc','code','P'.$kode_sess);
		$tahun  = date("Y");
		$datautama = $this->bhp_distribusi_model->get_data_row($id);
		$nomor_dokumen = $datautama['nomor_dokumen'];
		$tgl_distribusi =$datautama['tgl_distribusi'];
		
		$jenis_bhps = $nama_jenis;
		//$jsontotal  = json_decode($this->total_distribusi($id));
		$jumlah = $this->bhp_distribusi_model->sum_jumlah_item_jumlah($id);//$jsontotal->jumlah_tot;
		$penerima = $datautama['penerima_nama'];

		$data_puskesmas[] = array('nama_puskesmas' => $namapus,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tahun' => $tahun,'nomor_dokumen' => $nomor_dokumen,'tgl_distribusi' => $tgl_distribusi,'jenis_bhp' => $jenis_bhps,'jumlah' => $jumlah,'penerima' => $penerima);
		$dir = getcwd().'/';
		if ($jenis_bhp!='8') {
			$template = $dir.'public/files/template/inventory/bhp_distribusi_umum.xlsx';		
		}else{
			$template = $dir.'public/files/template/inventory/bhp_distribusi_obat.xlsx';		
		}
		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		if ($jenis_bhp!='8') {
			$output_file_name = 'public/files/hasil/hasil_export_bhpdistribusiumum_'.$code.'.xlsx';
		}else{
			$output_file_name = 'public/files/hasil/hasil_export_bhpdistribusiobat_'.$code.'.xlsx';
		}
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	public function distribusi_export_umum(){
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

				if($field == 'tgl_permohonan') {
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

		$rows_all = $this->bhp_distribusi_model->get_data();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permohonan') {
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
		$rows = $this->bhp_distribusi_model->get_data();
		$data_tabel = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		$no=1;
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'							=> $no++,
				'id_inv_inventaris_habispakai_distribusi' 	=> $act->id_inv_inventaris_habispakai_distribusi,
				'code_cl_phc' 					=> $act->code_cl_phc,
				'jenis_bhp' 					=> ucfirst($act->jenis_bhp),
				'tgl_distribusi' 				=> $act->tgl_distribusi,
				'nomor_dokumen'					=> $act->nomor_dokumen,
				'penerima_nama'					=> $act->penerima_nama,
				'penerima_nip'					=> $act->penerima_nip,
				'keterangan'					=> $act->keterangan,
				'bln_periode'					=> $act->bln_periode,
				'jumlah'						=> ($act->jumlah==null ? 0:$act->jumlah),
				'detail'						=> 1,
				'edit'							=> 1,//$unlock,
				'delete'						=> 1//$unlock
			);
		}


		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$namapus  = $this->inv_barang_model->get_nama('value','cl_phc','code','P'.$kode_sess);
		$tahun  = date("Y");
		

		$data_puskesmas[] = array('nama_puskesmas' => $namapus,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tahun' => $tahun);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/distribusi.xlsx';		
		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasi_export_distribusi'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	
	function json(){
		$this->authentication->verify('inventory','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permohonan') {
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

		$rows_all = $this->bhp_distribusi_model->get_data();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permohonan') {
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
		$rows = $this->bhp_distribusi_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas)=="4"){
			$unlock = 0;
		}else{
			$unlock = 1;
		}
		
		foreach($rows as $act) {
			$data[] = array(
				'id_inv_inventaris_habispakai_distribusi' 	=> $act->id_inv_inventaris_habispakai_distribusi,
				'code_cl_phc' 					=> $act->code_cl_phc,
				'jenis_bhp' 					=> ucfirst($act->jenis_bhp),
				'tgl_distribusi' 				=> $act->tgl_distribusi,
				'nomor_dokumen'					=> $act->nomor_dokumen,
				'penerima_nama'					=> $act->penerima_nama,
				'penerima_nip'					=> $act->penerima_nip,
				'keterangan'					=> $act->keterangan,
				'bln_periode'					=> $act->bln_periode,
				'jumlah'						=> ($act->jumlah==null ? 0:$act->jumlah),
				'detail'						=> 1,
				'edit'							=> $unlock,
				'delete'						=> $unlock
			);
		}


		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	public function barang($id = 0){
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
		
		$rows_all_activity = $this->bhp_distribusi_model->getitem();


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
		$activity = $this->bhp_distribusi_model->getitem($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		
		foreach($activity as $act) {
			$data[] = array(
				'id_inv_hasbispakai_pembelian'   		=> $act->id_inv_hasbispakai_pembelian,
				'id_mst_inv_barang_habispakai'   		=> $act->id_mst_inv_barang_habispakai,
				'uraian'								=> $act->uraian,
				'jumlah'								=> $act->jumlah ,
				'batch'									=> $act->batch,
				'harga'									=> number_format($act->harga,2),
				'subtotal'								=> number_format($act->jumlah*$act->harga,2),
				'tgl_update'							=> $act->tgl_update,
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
	public function distribusibarang($id = 0){
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

		$this->db->where('inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi',$id);
		
		$rows_all_activity = $this->bhp_distribusi_model->getitemdistribusi();


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

		$this->db->where('inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi',$id);

		$activity = $this->bhp_distribusi_model->getitemdistribusi($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
				'id_mst_inv_barang_habispakai'   			=> $act->id_mst_inv_barang_habispakai,
				'batch'										=> $act->batch,
				'uraian'									=> $act->uraian,
				'pilihan_satuan'							=> $act->pilihan_satuan,
				'tgl_kadaluarsa'							=> date("d-m-Y",strtotime($act->tgl_kadaluarsa)),
				'jml'										=> $act->jml,
				'jml_opname'								=> $act->tgl_opname !='' ? 0:1,
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

	public function total_distribusi($id){
		$this->db->where('id_inv_inventaris_habispakai_distribusi',$id);
		$this->db->select('sum(jml) as jumlah_tot');
		$query = $this->db->get('inv_inventaris_habispakai_distribusi_item')->result();
		foreach ($query as $q) {
			$totaldistribusi[] = array(
				'jumlah_tot' => ($q->jumlah_tot==null ? 0:$q->jumlah_tot), 
			);
			echo json_encode($totaldistribusi);
		}
    }
    function filter(){
		if($_POST) {
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_code_cl_phc',$this->input->post('code_cl_phc'));
			}
		}
	}
	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] = "Bahan Habis Pakai";
		$data['title_form'] = "Distribusi";
		$data['unlock'] = "0";
		$this->session->set_userdata('filter_code_cl_phc','');
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);

		$data['datapuskesmas'] 	= $this->inv_ruangan_model->get_data_puskesmas();
		$data['content'] = $this->parser->parse("inventory/bhp_distribusi/show",$data,true);
		$this->template->show($data,"home");
	}

	function add(){
		$this->authentication->verify('inventory','add');

		$this->form_validation->set_rules('kode_distribusi_', 'Kode Distribusi', 'trim|required');
        $this->form_validation->set_rules('tgl_distribusi', 'Tanggal Distribusi', 'trim|required');
        $this->form_validation->set_rules('penerima_nama', 'Nama Penerima', 'trim|required');
        $this->form_validation->set_rules('penerima_nip', 'NIP Penerima', 'trim|required');
        $this->form_validation->set_rules('nomor_dokumen', 'Nomor Dokumen', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('jenis_bhp', 'jenis_bhp', 'trim');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] 	= "Bahan Habis Pakai";
			$data['title_form']		= "Tambah Dokumen Distribusi";
			$data['action']			= "add";
			$data['kode']			= "";
			$data['bulan']			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');

			$kodepuskesmas = $this->session->userdata('puskesmas');
			//$this->db->where('code','P'.$kodepuskesmas);
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();

			$data['kodestatus'] = $this->bhp_distribusi_model->get_data_status();
		
			$data['content'] = $this->parser->parse("inventory/bhp_distribusi/form",$data,true);
		}elseif($id = $this->bhp_distribusi_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			if ($this->input->post('jenis_bhp')=="obat") {
				$jenis='8';
			}else{
				$jenis='0';
			}
			redirect(base_url().'inventory/bhp_distribusi/edit/'.$id.'/'.$jenis);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."inventory/bhp_distribusi/add");
		}

		$this->template->show($data,"home");
	}

	function edit($id_distribusi=0,$jenis_bhp=0){
		$this->authentication->verify('inventory','edit');

        $this->form_validation->set_rules('kode_distribusi_', 'Kode Distribusi', 'trim|required');
        $this->form_validation->set_rules('tgl_distribusi', 'Tanggal Distribusi', 'trim|required');
        $this->form_validation->set_rules('penerima_nama', 'Nama Penerima', 'trim|required');
        $this->form_validation->set_rules('penerima_nip', 'NIP Penerima', 'trim|required');
        $this->form_validation->set_rules('nomor_dokumen', 'Nomor Dokumen', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');
        $this->form_validation->set_rules('jenis_bhp', 'jenis_bhp', 'trim');

		if($this->form_validation->run()== FALSE){
			$data 	= $this->bhp_distribusi_model->get_data_row($id_distribusi);
			$data['title_group'] 	= "Barang Habis Pakai";
			$data['title_form']		= "Ubah Distribusi Barang";
			$data['unlock']			= "1";
			$data['action']			= "edit";
			$data['kode']			= $id_distribusi;
			$data['jenis_bhp']		= $jenis_bhp;
			$data['bulan'] 			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');

			
			$kodepuskesmas = $this->session->userdata('puskesmas');
			//$this->db->where('code','P'.$kodepuskesmas);
			
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
			$data['kodestatus'] = $this->bhp_distribusi_model->get_data_status();
			$data['kodestatus_inv'] = $this->bhp_distribusi_model->pilih_data_status('status_pembelian');
			$data['jenis_bhp']		= $jenis_bhp;

			$data['barang']	  			= $this->parser->parse('inventory/bhp_distribusi/barang', $data, TRUE);
			$data['barang_distribusi'] 	= $this->parser->parse('inventory/bhp_distribusi/barang_distribusi', $data, TRUE);
			$data['content'] 			= $this->parser->parse("inventory/bhp_distribusi/edit",$data,true);
		}elseif($this->bhp_distribusi_model->update_entry($id_distribusi)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."inventory/bhp_distribusi/edit/".$id_distribusi);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."inventory/bhp_distribusi/edit/".$id_distribusi);
		}

		$this->template->show($data,"home");
	}

	function detail($id_distribusi=0,$jenis_bhp=0){
		$this->authentication->verify('inventory','edit');
		if($this->form_validation->run()== FALSE){
			$data 	= $this->bhp_distribusi_model->get_data_row($id_distribusi);
			$data['title_group'] 	= "Barang Habis Pakai";
			$data['title_form']		= "Permohonan / Pengadaan Barang";
			$data['action']			= "view";
			$data['kode']			= $id_distribusi;
			$data['viewreadonly']	= "readonly=''";
			//$data['code_cl_phc']	= 'P'.$this->session->userdata('puskesmas');

			$data['jenis_bhp']		= $jenis_bhp;
			$data['unlock'] = 0;
			$data['bulan'] 			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
			$data['kodejenis'] = $this->bhp_distribusi_model->get_data_jenis();
			$data['kodedana'] = $this->bhp_distribusi_model->pilih_data_status('sumber_dana');
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
			$data['kodestatus'] = $this->bhp_distribusi_model->get_data_status();
			$data['kodestatus_inv'] = $this->bhp_distribusi_model->pilih_data_status('status_pembelian');
			//$data['tgl_opnamecond']		= $this->bhp_distribusi_model->gettgl_opname($id_distribusi);
			$data['barang_distribusi'] 	= $this->parser->parse('inventory/bhp_distribusi/barang_detail', $data, TRUE);
			$data['content'] 	= $this->parser->parse("inventory/bhp_distribusi/edit",$data,true);
			$this->template->show($data,"home");
		}
	}
	function dodel($kode=0){
		$this->authentication->verify('inventory','del');

		if($this->bhp_distribusi_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."inventory/bhp_distribusi");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."inventory/bhp_distribusi");
		}
	}
	
	function dodelpermohonan($kode=0,$barang=0,$batch=0){
		if($this->bhp_distribusi_model->delete_entryitem($kode,$barang,$batch)){
				return true;
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
				
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
	public function tanggalopnamecondisi($id='')
	{
		$query = $this->bhp_distribusi_model->gettgl_opname($id);
			$totalpengadaan[] = array(
				'tgl_opname' => date("Y-m-d",strtotime($query)), 
			);
			echo json_encode($totalpengadaan);
	}
	public function add_distribusi($id_distribusi=0,$kode=0,$batch="")
	{	
		$data['action']			= "add";
		$data['kode']			= $kode;
        $this->form_validation->set_rules('jumlahdistribusi', 'Jumlah Distribusi', 'trim|required');
        $this->form_validation->set_rules('uraian', 'Nama Barang', 'trim');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim');

		if($this->form_validation->run()== FALSE){

			$data 					= $this->bhp_distribusi_model->get_data_distribusi($kode,$batch);
			$data['notice']			= validation_errors();
			$data['kode']			= $kode;
			$data['batch']			= $batch;
			$data['action']			= "add";
			$data['id_distribusi']  = $id_distribusi;
			die($this->parser->parse('inventory/bhp_distribusi/barang_form', $data));
		}else{
			$this->db->where("id_mst_inv_barang_habispakai",$kode);
			$this->db->where("batch",$batch);
			$this->db->where("tgl_distribusi",$this->input->post("tgl_distribusi"));
			$this->db->join("inv_inventaris_habispakai_distribusi","inv_inventaris_habispakai_distribusi.id_inv_inventaris_habispakai_distribusi = inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi");
			$query = $this->db->get('inv_inventaris_habispakai_distribusi_item');
			if ($query->num_rows() > 0) {
				die("Error|Maaf data sudah di distribusi pada hari yang sama");
			}else{

					$this->db->where("id_inv_inventaris_habispakai_distribusi",$id_distribusi);
					$this->db->where("id_mst_inv_barang_habispakai",$kode);
					$this->db->where("batch",$batch);
					$query = $this->db->get('inv_inventaris_habispakai_distribusi_item');
					if ($query->num_rows() > 0) {
						foreach ($query->result() as $key) {
								$jumlahdata = $key->jml;
						}	
						$values = array(
							'jml' => $this->input->post('jumlahdistribusi')+$jumlahdata,
						);
						$kodeup = array(
							'id_inv_inventaris_habispakai_distribusi'=>$id_distribusi,
							'id_mst_inv_barang_habispakai'=> $kode,
							'batch' => $batch,
						);
						$simpan=$this->db->update('inv_inventaris_habispakai_distribusi_item', $values,$kodeup);	
					}else{
						$values = array(
							'id_inv_inventaris_habispakai_distribusi'=>$id_distribusi,
							'id_mst_inv_barang_habispakai'=> $kode,
							'batch' => $batch,
							'jml' => $this->input->post('jumlahdistribusi'),
						);
						$simpan=$this->db->insert('inv_inventaris_habispakai_distribusi_item', $values);
					}
				if($simpan==true){
					die("OK|Data Tersimpan");
				}else{
					 die("Error|Proses data gagal");
				}
			}
			
		}
	}
	

	function dodel_barang($kode=0,$id_barang="",$table=0){
		$this->authentication->verify('inventory','del');

		if($this->bhp_distribusi_model->delete_entryitem_table($kode,$id_barang,$table)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

	
	public function get_autonama() {
        $kode = $this->input->post('code_mst_inv_barang',TRUE); //variabel kunci yang di bawa dari input text id kode
        $query = $this->mkota->get_allkota(); //query model
 
        $kota       =  array();
        foreach ($query as $d) {
            $kota[]     = array(
                'label' => $d->nama_kota, //variabel array yg dibawa ke label ketikan kunci
                'nama' => $d->nama_kota , //variabel yg dibawa ke id nama
                'ibukota' => $d->ibu_kota, //variabel yang dibawa ke id ibukota
                'keterangan' => $d->keterangan //variabel yang dibawa ke id keterangan
            );
        }
        echo json_encode($kota);      //data array yang telah kota deklarasikan dibawa menggunakan json
    }
	function autocomplite_nama($obat=0){
		$kodepuskesmas = "P".$this->session->userdata("puskesmas");
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("term=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->distinct();
		$this->db->like("penerima_nama",$search);
		$this->db->order_by('id_inv_inventaris_habispakai_distribusi','asc');
		$this->db->limit(10,0);
		$this->db->select("penerima_nama");
		$query= $this->db->get("inv_inventaris_habispakai_distribusi")->result();
		foreach ($query as $q) {
			$barang[] = array(
				'value'	=> $q->penerima_nama,
			);
		}
		echo json_encode($barang);
	}
	function autocomplite_nip($obat=0){
		$kodepuskesmas = "P".$this->session->userdata("puskesmas");
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("term=","",$search[0]);
		$search = str_replace("+"," ",$search);
		
		$this->db->distinct();
		$this->db->like("penerima_nip",$search);
		$this->db->order_by('id_inv_inventaris_habispakai_distribusi','asc');
		$this->db->limit(10,0);
		$this->db->select("penerima_nip");
		$query= $this->db->get("inv_inventaris_habispakai_distribusi")->result();
		foreach ($query as $q) {
			$barang[] = array(
				'value'	=> $q->penerima_nip,
			);
		}
		echo json_encode($barang);
	}
}
