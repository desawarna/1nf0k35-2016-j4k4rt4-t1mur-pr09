<?php
class Export extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('mst/invbarang_model');
	}
	function permohonan_export_inventori(){
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		//[data_tabel.no;block=tbs:row]	[data_tabel.tgl]	[data_tabel.ruangan]	[data_tabel.jumlah]	[data_tabel.keterangan]	[data_tabel.status]
		
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tanggal_diterima') {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("code_cl_phc",$kodeplch);	
				}
				
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		if($this->session->userdata('filterHAPUS') != '') {
			$this->db->where("pilihan_status_invetaris","3");
		}

		if (($this->session->userdata('filterHAPUS') == '') ||($this->session->userdata('filterGIB') != '')) {
				$this->db->where("pilihan_status_invetaris !=","3");
			}
		$rows_all = $this->inv_barang_model->get_data_laporan();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tanggal_diterima') {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("code_cl_phc",$kodeplch);	
				}
				
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		if($this->session->userdata('filterHAPUS') != '') {
			$this->db->where("pilihan_status_invetaris","3");
		}
		if (($this->session->userdata('filterHAPUS') == '') ||($this->session->userdata('filterGIB') != '')) {
				$this->db->where("pilihan_status_invetaris !=","3");
			}
		$rows = $this->inv_barang_model->get_data_laporan();
		$no=1;

		$data_tabel = array();
		foreach($rows as $act) {
			if($act->tanggal_pembelian == null){
				$date="00-00-0000";
			}else{
				$date = date("d-m-Y",strtotime($act->tanggal_pembelian));
			}
			$asal = $this->inv_barang_model->get_pilihan($act->pilihan_asal,"asal_usul");
			$keadaan = $this->inv_barang_model->get_pilihan($act->pilihan_keadaan_barang,"keadaan_barang");
			$data_tabel[] = array(
				'no'   							=> $no++,
				'id_inventaris_barang'   		=> $act->id_inventaris_barang,
				'id_mst_inv_barang'   			=> $act->id_mst_inv_barang,
				'id_pengadaan'		   			=> $act->id_pengadaan,
				'barang_kembar_proc'		   	=> $act->barang_kembar_proc,
				'nama_barang'					=> $act->nama_barang,
				'register'		   				=> $act->register,
				'pilihan_keadaan_barang'		=> $act->keadaan,
				'tanggal_pembelian'		   		=> $date,
				'merk'		   					=> $act->merk,
				'nobukti'				   		=> $act->nobukti,
				'bahan'		   					=> $act->bahan,
				'ukuran'		   				=> $act->ukuran,
				'satuan'		   				=> $act->satuan,
				'jumlah'						=> $act->jumlah,
				'pilihan_asal'				   	=> $act->asal,
				'harga'							=> number_format($act->harga,2),
				'totalharga'					=> number_format($act->harga*$act->jumlah,2),
				'keterangan_pengadaan'			=> $act->keterangan_pengadaan,
				'pilihan_status_invetaris'		=> $act->pilihan_status_invetaris,
				'barang_kembar_proc'			=> $act->barang_kembar_proc,
				'tanggal_diterima'				=> $act->tanggal_diterima,
				'waktu_dibuat'					=> $act->waktu_dibuat,
				'terakhir_diubah'				=> $act->terakhir_diubah,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$puskes = $this->input->post('puskes'); 
		$ruang = $this->input->post('ruang'); 
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){
			$namapus = 'Semua Data Puskesmas';
		}else{
			$namapus = $this->input->post('puskes');
		}
		if(empty($ruang) or $this->ruang == 'Pilih Ruangan'){
			$namaruang = 'Semua Data Ruangan';
		}else{
			$namaruang = $this->input->post('ruang');
		}
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$kd_upb  = $this->input->post('namepuskes');
		$bidang = 'Bidang Kesehatan';
		$unit = 'Bidang Kesehatan';
		$subunit = 'Bidang Kesehatan';
		$upb = 'Bidang Kesehatan';

		$data_puskesmas[] = array('nama_puskesmas' => $namapus,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'bidang' => $bidang,'unit' => $unit,'subunit' => $subunit,'upb' => $upb);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/kibinventaris.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_KibInventaris_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	function permohonan_export_kiba(){
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		//[data_tabel.no;block=tbs:row]	[data_tabel.tgl]	[data_tabel.ruangan]	[data_tabel.jumlah]	[data_tabel.keterangan]	[data_tabel.status]
		
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'status_sertifikat_tanggal') {
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
		
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);
				}
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows_all = $this->inv_barang_model->get_data_golongan_A();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'status_sertifikat_tanggal') {
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
		
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);
				}
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows = $this->inv_barang_model->get_data_golongan_A();
		
		$data_tabel = array();
		$no=1;
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'						=> $no++,
				'id_inventaris_barang'   	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'			=> $act->id_mst_inv_barang,
				'uraian'					=> $act->uraian,
				'id_pengadaan'		   		=> $act->id_pengadaan,
				'barang_kembar_proc'		=> $act->barang_kembar_proc,
				'satuan'					=> $act->satuan,
				'id_ruangan'				=> $act->id_ruangan,
				'hak'						=> $act->hak,
				'id_cl_phc'					=> $act->id_cl_phc,
				'register'					=> $act->register,
				'asal_usul'					=> $act->asal_usul,
				'keterangan_pengadaan'		=> $act->keterangan_pengadaan,
				'harga'						=> number_format($act->harga,2),
				'jumlah'					=> $act->jumlah,
				'jumlah_satuan'				=> $act->jumlah.' '.$act->satuan,
				'penggunaan'				=> $act->penggunaan,
				'luas' 						=> $act->luas,
				'alamat' 					=> $act->alamat,
				'pilihan_satuan_barang' 	=> $act->pilihan_satuan_barang,
				'pilihan_status_hak' 		=> $act->pilihan_status_hak,
				'status_sertifikat_tanggal' => date("d-m-Y",strtotime($act->status_sertifikat_tanggal)),
				'status_sertifikat_nomor'	=> $act->status_sertifikat_nomor,
				'pilihan_penggunaan' 		=> $act->pilihan_penggunaan,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$puskes = $this->input->post('puskes'); 
		$ruang = $this->input->post('ruang'); 
		$tanggal_export = $this->input->post('filter_tanggal'); 
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){	
				$kode='P '.$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
		}else{
				$kode_sess=$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
				$kd_upb  = $this->input->post('namepuskes');
				$kode = $this->input->post('puskes');
		}
		if(empty($ruang) or $ruang == 'Pilih Ruangan'){
			$namaruang = 'Semua Data Ruangan';
		}else{
			$namaruang = $this->input->post('ruang');
		}
		if(empty($tanggal_export) or $tanggal_export == ''){
			$tanggal_export = date('d-m-Y');
		}else{
			$tanggals = explode("-", $this->input->post('filter_tanggal'));
			$tanggal_export = $tanggals[2].'-'.$tanggals[1].'-'.$tanggals[0];
		}
		$data_puskesmas[] = array('kode' => $kode,'namaruang' => $namaruang,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_upb' => $kd_upb,'tanggal_export'=>$tanggal_export);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/kiba.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_KibA_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
		
	}
	function permohonan_export_kibb(){
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		//[data_tabel.no;block=tbs:row]	[data_tabel.tgl]	[data_tabel.ruangan]	[data_tabel.jumlah]	[data_tabel.keterangan]	[data_tabel.status]
		
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if(($field == 'tanggal_bpkb')||($field == 'tanggal_perolehan')) {
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
		
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);	
				}
				
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows_all = $this->inv_barang_model->get_data_golongan_B();
		
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if(($field == 'tanggal_bpkb')||($field == 'tanggal_perolehan')) {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);	
				}
				
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows = $this->inv_barang_model->get_data_golongan_B();
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no' 					=> $no++,
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'		=> $act->id_mst_inv_barang,
				'uraian' 				=> $act->uraian,
				'id_pengadaan'		   	=> $act->id_pengadaan,
				'barang_kembar_proc'	=> $act->barang_kembar_proc,
				'merek_type' 			=> $act->merek_type,
				'keterangan_pengadaan'	=> $act->keterangan_pengadaan,
				'bahan'		 			=> $act->bahan,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'asal_usul'				=> $act->asal_usul,
				'keadaan_barang'		=> $act->keadaan_barang,
				'jumlah'				=> $act->jumlah,
				'harga'					=> number_format($act->harga,2),
				'satuan'	 			=> $act->satuan,
				'ukuran_satuan' 		=> $act->ukuran_barang.'  '.$act->satuan,
				'identitas_barang' 		=> $act->identitas_barang,
				'pilihan_bahan' 		=> $act->pilihan_bahan,
				'ukuran_barang' 		=> $act->ukuran_barang,
				'pilihan_satuan' 		=> $act->pilihan_satuan,
				'tanggal_bpkb'			=> date("d-m-Y",strtotime($act->tanggal_bpkb)),
				'nomor_bpkb'		 	=> $act->nomor_bpkb,
				'no_polisi'		 		=> $act->no_polisi,
				'tanggal_perolehan'	 	=> date("d-m-Y",strtotime($act->tanggal_perolehan)),
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$puskes = $this->input->post('puskes'); 
		$ruang = $this->input->post('ruang'); 
		$tanggal_export = $this->input->post('filter_tanggal'); 
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){	
				$kode='P '.$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
		}else{
				$kode_sess=$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
				$kd_upb  = $this->input->post('namepuskes');
				$kode = $this->input->post('puskes');
		}
		if(empty($ruang) or $ruang == 'Pilih Ruangan'){
			$namaruang = 'Semua Data Ruangan';
		}else{
			$namaruang = $this->input->post('ruang');
		}
		if(empty($tanggal_export) or $tanggal_export == ''){
			$tanggal_export = date('d-m-Y');
		}else{
			$tanggals = explode("-", $this->input->post('filter_tanggal'));
			$tanggal_export = $tanggals[2].'-'.$tanggals[1].'-'.$tanggals[0];
		}
		$data_puskesmas[] = array('kode' => $kode,'namaruang' => $namaruang,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_upb' => $kd_upb,'tanggal_export'=>$tanggal_export);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/kibb.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_KibB_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function permohonan_export_kibc(){
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		//[data_tabel.no;block=tbs:row]	[data_tabel.tgl]	[data_tabel.ruangan]	[data_tabel.jumlah]	[data_tabel.keterangan]	[data_tabel.status]
		
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'dokumen_tanggal') {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);	
				}
				
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows_all = $this->inv_barang_model->get_data_golongan_C();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'dokumen_tanggal') {
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

		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);	
				}
				
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows = $this->inv_barang_model->get_data_golongan_C();
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'		=> $act->id_mst_inv_barang,
				'uraian' 				=> $act->uraian,
				'hak' 					=> $act->hak,
				'id_pengadaan'		   	=> $act->id_pengadaan,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'harga'					=> number_format($act->harga,2),
				'barang_kembar_proc'	=> $act->barang_kembar_proc,
				'keterangan_pengadaan'		=> $act->keterangan_pengadaan,
				'asal_usul'				=> $act->asal_usul,
				'tingkat' 				=> $act->tingkat,
				'beton' 				=> $act->beton,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'harga'					=> $act->harga,
				'luas_lantai' 			=> $act->luas_lantai,
				'letak_lokasi_alamat' 	=> $act->letak_lokasi_alamat,
				'pillihan_status_hak' 	=> $act->pillihan_status_hak,
				'nomor_kode_tanah' 		=> $act->nomor_kode_tanah,
				'pilihan_kons_tingkat' 	=> $act->pilihan_kons_tingkat,
				'pilihan_kons_beton'	=> $act->pilihan_kons_beton,
				'dokumen_tanggal'		=> $act->dokumen_tanggal,
				'dokumen_nomor'		 	=> $act->dokumen_nomor,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		
		$puskes = $this->input->post('puskes'); 
		$ruang = $this->input->post('ruang'); 
		$tanggal_export = $this->input->post('filter_tanggal'); 
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){	
				$kode='P '.$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
		}else{
				$kode_sess=$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
				$kd_upb  = $this->input->post('namepuskes');
				$kode = $this->input->post('puskes');
		}
		if(empty($ruang) or $ruang == 'Pilih Ruangan'){
			$namaruang = 'Semua Data Ruangan';
		}else{
			$namaruang = $this->input->post('ruang');
		}
		if(empty($tanggal_export) or $tanggal_export == ''){
			$tanggal_export = date('d-m-Y');
		}else{
			$tanggals = explode("-", $this->input->post('filter_tanggal'));
			$tanggal_export = $tanggals[2].'-'.$tanggals[1].'-'.$tanggals[0];
		}
		$data_puskesmas[] = array('kode' => $kode,'namaruang' => $namaruang,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_upb' => $kd_upb,'tanggal_export'=>$tanggal_export);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/kibc.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_KibC_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function permohonan_export_kibd(){
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		//[data_tabel.no;block=tbs:row]	[data_tabel.tgl]	[data_tabel.ruangan]	[data_tabel.jumlah]	[data_tabel.keterangan]	[data_tabel.status]
		
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'dokumen_tanggal') {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);	
				}
				
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows_all = $this->inv_barang_model->get_data_golongan_D();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'dokumen_tanggal') {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);	
				}
				
			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows = $this->inv_barang_model->get_data_golongan_D();
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'		=> $act->id_mst_inv_barang,
				'uraian' 				=> $act->uraian,
				'konstruksi' 			=> $act->konstruksi,
				'tanah' 				=> $act->tanah,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'harga'					=> number_format($act->harga,2),
				'keterangan_pengadaan'		=> $act->keterangan_pengadaan,
				'asal_usul'				=> $act->asal_usul,
				'id_pengadaan'		   	=> $act->id_pengadaan,
				'barang_kembar_proc'	=> $act->barang_kembar_proc,
				'panjang' 				=> $act->panjang,
				'lebar' 				=> $act->lebar,
				'letak_lokasi_alamat' 	=> $act->letak_lokasi_alamat,
				'luas' 					=> $act->luas,
				'dokumen_tanggal'		=> $act->dokumen_tanggal,
				'dokumen_nomor'			=> $act->dokumen_nomor,
				'pilihan_status_tanah'	=> $act->pilihan_status_tanah,
				'nomor_kode_tanah'		=> $act->nomor_kode_tanah,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}


		$puskes = $this->input->post('puskes'); 
		$ruang = $this->input->post('ruang'); 
		$tanggal_export = $this->input->post('filter_tanggal'); 
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){	
				$kode='P '.$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
		}else{
				$kode_sess=$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
				$kd_upb  = $this->input->post('namepuskes');
				$kode = $this->input->post('puskes');
		}
		if(empty($ruang) or $ruang == 'Pilih Ruangan'){
			$namaruang = 'Semua Data Ruangan';
		}else{
			$namaruang = $this->input->post('ruang');
		}
		if(empty($tanggal_export) or $tanggal_export == ''){
			$tanggal_export = date('d-m-Y');
		}else{
			$tanggals = explode("-", $this->input->post('filter_tanggal'));
			$tanggal_export = $tanggals[2].'-'.$tanggals[1].'-'.$tanggals[0];
		}
		$data_puskesmas[] = array('kode' => $kode,'namaruang' => $namaruang,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_upb' => $kd_upb,'tanggal_export'=>$tanggal_export);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/kibd.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_KibD_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function permohonan_export_kibe(){
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		//[data_tabel.no;block=tbs:row]	[data_tabel.tgl]	[data_tabel.ruangan]	[data_tabel.jumlah]	[data_tabel.keterangan]	[data_tabel.status]
		
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tahun_cetak_beli') {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		if($this->input->post('puskes') != '') {
			$this->db->where("id_cl_phc",$this->input->post('puskes'));
		}else{
			$this->db->where("id_cl_phc",$this->session->userdata('filter_cl_phc'));
		}
		$rows_all = $this->inv_barang_model->get_data_golongan_E();
		


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tahun_cetak_beli') {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		if($this->input->post('puskes') != '') {
			$this->db->where("id_cl_phc",$this->input->post('puskes'));
		}else{
			$this->db->where("id_cl_phc",$this->session->userdata('filter_cl_phc'));
		}

		$rows = $this->inv_barang_model->get_data_golongan_E();
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'		=> $act->id_mst_inv_barang,
				'uraian' 				=> $act->uraian,
				'bahan' 				=> $act->bahan,
				'satuan' 				=> $act->satuan,
				'id_pengadaan'		   	=> $act->id_pengadaan,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'jumlah'				=> $act->jumlah,
				'harga'					=> number_format($act->harga,2),
				'keterangan_pengadaan'	=> $act->keterangan_pengadaan,
				'asal_usul'				=> $act->asal_usul,
				'barang_kembar_proc'	=> $act->barang_kembar_proc,
				'buku_judul_pencipta' 	=> $act->buku_judul_pencipta,
				'buku_spesifikasi' 		=> $act->buku_spesifikasi,
				'budaya_asal_daerah' 	=> $act->budaya_asal_daerah,
				'budaya_pencipta' 		=> $act->budaya_pencipta,
				'pilihan_budaya_bahan' 	=> $act->pilihan_budaya_bahan,
				'flora_fauna_jenis'		=> $act->flora_fauna_jenis,
				'flora_fauna_ukuran'	=> $act->flora_fauna_ukuran,
				'flora_ukuran_satuan'	=> $act->flora_fauna_ukuran.'  '.$act->satuan,
				'pilihan_satuan'		=> $act->pilihan_satuan,
				'tahun_cetak_beli'		=> $act->tahun_cetak_beli,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		
		$puskes = $this->input->post('puskes'); 
		$ruang = $this->input->post('ruang');
		$tanggal_export = $this->input->post('filter_tanggal');  
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){	
				$kode='P '.$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
		}else{
				$kode_sess=$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
				$kd_upb  = $this->input->post('namepuskes');
				$kode = $this->input->post('puskes');
		}
		if(empty($ruang) or $ruang == 'Pilih Ruangan'){
			$namaruang = 'Semua Data Ruangan';
		}else{
			$namaruang = $this->input->post('ruang');
		}
		if(empty($tanggal_export) or $tanggal_export == ''){
			$tanggal_export = date('d-m-Y');
		}else{
			$tanggals = explode("-", $this->input->post('filter_tanggal'));
			$tanggal_export = $tanggals[2].'-'.$tanggals[1].'-'.$tanggals[0];
		}
		$data_puskesmas[] = array('kode' => $kode,'namaruang' => $namaruang,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_upb' => $kd_upb,'tanggal_export'=>$tanggal_export);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/kibe.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_KIBE_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	
	function permohonan_export_kibf(){
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		//[data_tabel.no;block=tbs:row]	[data_tabel.tgl]	[data_tabel.ruangan]	[data_tabel.jumlah]	[data_tabel.keterangan]	[data_tabel.status]
		
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tanggal_mulai') {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);
				}

			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows_all = $this->inv_barang_model->get_data_golongan_F();
		


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tanggal_mulai') {
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
		if($this->session->userdata('filterruangan') != ''){
			$filter = $this->session->userdata('filterruangan');
			$this->db->where("id_ruangan",$filter);
		}

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			if($this->session->userdata('filter_cl_phc') != ''){
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);
				}

			}
		}else {
			if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows = $this->inv_barang_model->get_data_golongan_F();
		$no=1;
		$data_tabel = array();
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'					=> $no++,
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'		=> $act->id_mst_inv_barang,
				'uraian' 				=> $act->uraian,
				'tanah' 				=> $act->tanah,
				'beton' 				=> $act->beton,
				'tingkat' 				=> $act->tingkat,
				'id_pengadaan'		   	=> $act->id_pengadaan,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'harga'					=> number_format($act->harga,2),
				'keterangan_pengadaan'	=> $act->keterangan_pengadaan,
				'asal_usul'				=> $act->asal_usul,
				'jumlah'				=> $act->jumlah,
				'barang_kembar_proc'	=> $act->barang_kembar_proc,
				'bangunan' 				=> $act->bangunan,
				'pilihan_konstruksi_bertingkat' 	=> $act->pilihan_konstruksi_bertingkat,
				'pilihan_konstruksi_beton' 			=> $act->pilihan_konstruksi_beton,
				'luas' 					=> $act->luas,
				'lokasi' 				=> $act->lokasi,
				'dokumen_tanggal'		=> $act->dokumen_tanggal,
				'dokumen_nomor'			=> $act->dokumen_nomor,
				'tanggal_mulai'		 	=> $act->tanggal_mulai,
				'pilihan_status_tanah'	=> $act->pilihan_status_tanah,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		
		$puskes = $this->input->post('puskes'); 
		$ruang = $this->input->post('ruang'); 
		$tanggal_export = $this->input->post('filter_tanggal'); 
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){	
				$kode='P '.$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode, 2,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode, 2,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
				$kd_upb  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 2,7));
		}else{
				$kode_sess=$this->session->userdata('puskesmas');
				$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
				$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
				$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
				$kd_upb  = $this->input->post('namepuskes');
				$kode = $this->input->post('puskes');
		}
		if(empty($ruang) or $ruang == 'Pilih Ruangan'){
			$namaruang = 'Semua Data Ruangan';
		}else{
			$namaruang = $this->input->post('ruang');
		}
		if(empty($tanggal_export) or $tanggal_export == ''){
			$tanggal_export = date('d-m-Y');
		}else{
			$tanggals = explode("-", $this->input->post('filter_tanggal'));
			$tanggal_export = $tanggals[2].'-'.$tanggals[1].'-'.$tanggals[0];
		}
		$data_puskesmas[] = array('kode' => $kode,'namaruang' => $namaruang,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'kd_kec' => $kd_kec,'kd_upb' => $kd_upb,'tanggal_export'=>$tanggal_export);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/kibf.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_KIBF_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
}
