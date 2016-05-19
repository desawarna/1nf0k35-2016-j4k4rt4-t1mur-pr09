<?php
class Bhp_retur extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('inventory/inv_barang_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/bhp_retur_model');
		$this->load->model('mst/invbarang_model');
	}
	function filter(){
		if($_POST) {
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_code_cl_phc',$this->input->post('code_cl_phc'));
			}
		}
	}
	function laporan_opname_barang_retur($id=0){
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

		if ($id!=0) {
			$this->db->where('inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname',$id);
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				if ($this->session->userdata('filter_jenisbarang')=='8') {
					$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
				}else{
					$this->db->where("id_mst_inv_barang_habispakai_jenis !=",'8');
				}
				
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
				$this->db->where("MONTH(tgl_opname)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(tgl_opname)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all'){

			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows_all_activity = $this->bhp_retur_model->getitemopname_retur();


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
				if ($this->session->userdata('filter_jenisbarang')=='8') {
					$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
				}else{
					$this->db->where("id_mst_inv_barang_habispakai_jenis !=",'8');
				}
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
			$this->db->where("MONTH(tgl_opname)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(tgl_opname)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all'){

			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$activity = $this->bhp_retur_model->getitemopname_retur();
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
				'id_mst_inv_barang_habispakai'   			=> $act->id_mst_inv_barang_habispakai,
				'batch'										=> $act->batch,
				'uraian'									=> $act->uraian,
				'id_inv_inventaris_habispakai_opname'		=> $act->id_inv_inventaris_habispakai_opname,
				'jml_awal'									=> $act->jml_awal,
				'jml_akhir'									=> $act->jml_akhir,
				'harga'										=> $act->harga,
				'nama'										=> $act->nama,
				'merek_tipe'								=> $act->merek_tipe,
				'total_penerimaan'							=> $act->total_penerimaan,
				'tgl_opname'								=> date("d-m-Y",strtotime($act->tgl_opname)),
				'jml_selisih'								=> ($act->jml_awal - $act->jml_akhir),
				'edit'		=> 1,
				'delete'	=> 1
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
		$template = $dir.'public/files/template/inventory/bhp_retur_barang.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_bhpreturbarang_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
	function laporan_opname_retur($id=0){
		
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

		if ($id!=0) {
			$this->db->where('inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname',$id);
		}
		if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				if ($this->session->userdata('filter_jenisbarang')=='8') {
					$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
				}else{
					$this->db->where("id_mst_inv_barang_habispakai_jenis !=",'8');
				}
				
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(tgl_pembelian_terakhir)",$this->session->userdata('filter_bulan'));
			}
		}else{
				$this->db->where("MONTH(tgl_pembelian_terakhir)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_pembelian_terakhir)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(tgl_pembelian_terakhir)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all'){

			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows_all_activity = $this->bhp_retur_model->getitemopname();


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
				if ($this->session->userdata('filter_jenisbarang')=='8') {
					$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
				}else{
					$this->db->where("id_mst_inv_barang_habispakai_jenis !=",'8');
				}
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(tgl_pembelian_terakhir)",$this->session->userdata('filter_bulan'));
			}
		}else{
			$this->db->where("MONTH(tgl_pembelian_terakhir)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_pembelian_terakhir)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(tgl_pembelian_terakhir)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all'){

			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$activity = $this->bhp_retur_model->getitemopname();
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
				'id_mst_inv_barang_habispakai'   			=> $act->id_mst_inv_barang_habispakai,
				'batch'										=> $act->batch,
				'uraian'									=> $act->uraian,
				'total_penerimaan'							=> $act->total_penerimaan,
				'jml_rusakakhir'							=> $act->jml_rusakakhir,
				'id_mst_inv_barang_habispakai_jenis'		=> $act->id_mst_inv_barang_habispakai_jenis,
				'merek_tipe'								=> $act->merek_tipe,
				'nama'										=> $act->nama,
				'harga'										=> $act->harga,
				'tgl_pembelian_terakhir'					=> date("d-m-Y",strtotime($act->tgl_pembelian_terakhir)),
				'edit'		=> 1,
				'delete'	=> 1
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
		$template = $dir.'public/files/template/inventory/bhp_retur.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_bhpretur_'.$code.'.xlsx';
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
	function json($id=0){
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
				if ($this->session->userdata('filter_jenisbarang')=='8') {
					$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
				}else{
					$this->db->where("id_mst_inv_barang_habispakai_jenis !=",'8');
				}
				
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(tgl_pembelian_terakhir)",$this->session->userdata('filter_bulan'));
			}
		}else{
				$this->db->where("MONTH(tgl_pembelian_terakhir)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_pembelian_terakhir)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(tgl_pembelian_terakhir)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all'){

			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows_all_activity = $this->bhp_retur_model->getitemopname();


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
				if ($this->session->userdata('filter_jenisbarang')=='8') {
					$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
				}else{
					$this->db->where("id_mst_inv_barang_habispakai_jenis !=",'8');
				}
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				$this->db->where("MONTH(tgl_pembelian_terakhir)",$this->session->userdata('filter_bulan'));
			}
		}else{
			$this->db->where("MONTH(tgl_pembelian_terakhir)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_pembelian_terakhir)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(tgl_pembelian_terakhir)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all'){

			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$activity = $this->bhp_retur_model->getitemopname($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
				'total_penerimaan'							=> $act->total_penerimaan,
				'jml_rusakakhir'							=> $act->jml_rusakakhir,
				'id_mst_inv_barang_habispakai_jenis'		=> $act->id_mst_inv_barang_habispakai_jenis,
				'merek_tipe'								=> $act->merek_tipe,
				'nama'										=> $act->nama,
				'harga'										=> $act->harga,
				'tgl_pembelian_terakhir'					=> date("d-m-Y",strtotime($act->tgl_pembelian_terakhir)),
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
	function json_retur($id=0){
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
				if ($this->session->userdata('filter_jenisbarang')=='8') {
					$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
				}else{
					$this->db->where("id_mst_inv_barang_habispakai_jenis !=",'8');
				}
				
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
				$this->db->where("MONTH(tgl_opname)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(tgl_opname)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all'){

			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows_all_activity = $this->bhp_retur_model->getitemopname_retur();


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
				if ($this->session->userdata('filter_jenisbarang')=='8') {
					$this->db->where("id_mst_inv_barang_habispakai_jenis",$this->session->userdata('filter_jenisbarang'));
				}else{
					$this->db->where("id_mst_inv_barang_habispakai_jenis !=",'8');
				}
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
			$this->db->where("MONTH(tgl_opname)",date("m"));
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
				$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			$this->db->where("YEAR(tgl_opname)",date("Y"));
		}
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all'){

			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$activity = $this->bhp_retur_model->getitemopname_retur($this->input->post('recordstartindex'), $this->input->post('pagesize'));
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
				'harga'										=> $act->harga,
				'nama'										=> $act->nama,
				'merek_tipe'								=> $act->merek_tipe,
				'total_penerimaan'							=> $act->total_penerimaan,
				'tgl_opname'								=> date("d-m-Y",strtotime($act->tgl_opname)),
				'jml_selisih'								=> ($act->jml_awal - $act->jml_akhir),
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
	function add_retur($jenis=8,$barang=0,$batch='')
	{
		$this->authentication->verify('inventory','add');
			
		$this->form_validation->set_rules('kode_distribusi_', 'Kode Lokasi', 'trim|required');
        $this->form_validation->set_rules('tgl_opname', 'Tanggal Opname', 'trim|required');
        $this->form_validation->set_rules('penerima_nama', 'Nama Penerima', 'trim|required');
        $this->form_validation->set_rules('penerima_nip', 'NIP Penerima', 'trim|required');
        $this->form_validation->set_rules('nomor_opname', 'Nomor Opname', 'trim|required');
        $this->form_validation->set_rules('catatan', 'Catatan', 'trim|required');
        $this->form_validation->set_rules('puskesmas', 'puskesmas', 'trim');
        $this->form_validation->set_rules('instansi', 'Nama Instansi Pbf', 'trim|required');
        $this->form_validation->set_rules('uraian', 'Uraian Barang', 'trim|required');
        $this->form_validation->set_rules('batch', 'Batch', 'trim|required');
        $this->form_validation->set_rules('total_penerimaan', 'Total Terima', 'trim|required');
        $this->form_validation->set_rules('jml_rusakakhir', 'Jumlah Rusak', 'trim|required');
        $this->form_validation->set_rules('id_instansi', 'Id instansi', 'trim|required');
        $this->form_validation->set_rules('id_uraian', 'Id Uraian', 'trim|required');
        $this->form_validation->set_rules('jml_rusakakhir_simpan', 'jml_rusakakhir_simpan', 'trim');
        $this->form_validation->set_rules('jml_awalopname', 'jml_awalopname', 'trim');
        $this->form_validation->set_rules('jml_rusaktotal', 'jml_rusaktotal', 'trim');
        $this->form_validation->set_rules('hargaterakhir', 'hargaterakhir', 'trim');
        $this->form_validation->set_rules('id_mst_inv_barang_habispakai_jenis', 'id_mst_inv_barang_habispakai_jenis', 'trim');
        $data 	= $this->bhp_retur_model->get_data_row_rusak($jenis,$barang,$batch);
		$data['title_group'] 	= "Bahan Habis Pakai";
		$data['title_form']		= "Tambah Opname";
		$data['action']			= "add";
		$data['barang']			= $barang;
		$data['jenis']			= $jenis;
		$data['batch']			= $batch;
		$data['kode']			= "";
		$data['alert_form']="";
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		if($this->form_validation->run()== FALSE){
			die($this->parser->parse("inventory/bhp_retur/form",$data,true));
		}elseif($id = $this->bhp_retur_model->insert_entry($jenis,$barang,$batch)){
			$data['alert_form']='Save data successful...';
			if ($this->input->post('jenis_bhp')=="obat") {
				$jenis='8';
			}else{
				$jenis='0';
			}
			//$this->edit_opname($id,$jenis);
			$this->daftar_retur();
		}else{
			$data['alert_form']='Save data failed...';
		}
	}
	function detail($id_opname=0,$jenis_bhp=0){

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
        $this->form_validation->set_rules('puskesmas', 'jenis_bhp', 'trim');

    	$data 	= $this->bhp_retur_model->get_data_row($id_opname);
		$data['title_group'] 	= "Barang Habis Pakai";
		$data['title_form']		= "Ubah Stok Opname";
		$data['action']			= "edit";
		$data['kode']			= $id_opname;
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		$data['kodestatus_inv'] = $this->bhp_retur_model->pilih_data_status('status_pembelian');
		$data['alert_form'] ='';
		if($this->form_validation->run()== FALSE){

			die($this->parser->parse("inventory/bhp_retur/edit",$data,true));
		}elseif($this->bhp_retur_model->update_entry($id_opname)){
			$data['alert_form'] 	= 'Save data successful...';
		}else{
			$data['alert_form'] 	= 'Save data failed...';
		}
		die($this->parser->parse("inventory/bhp_retur/edit",$data,true));
	}
	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] = "Barang Habis Pakai";
		$this->session->set_userdata('filter_code_cl_phc','');
		$data['title_form'] = "Retur";
		$this->session->set_userdata('filter_jenisbarang','');
		$this->session->set_userdata('filter_bulan','');
		$data['content'] = $this->parser->parse("inventory/bhp_retur/show",$data,true);
		$this->template->show($data,"home");
	}

	function tab($index){
		if($index==1) $this->daftar_retur();
		else $this->daftar_barangretur();
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

	function dodelpermohonan($kode=0){
		
		if($this->bhp_retur_model->delete_entryitem($kode)==true){
				$this->daftar_barangretur();
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
				
	}
	function daftar_barangretur(){
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);
		$this->session->set_userdata('filter_jenisbarang','');
		$data['datapuskesmas'] 	= $this->bhp_retur_model->get_data_puskesmas();
		$data['jenisbaranghabis'] = array('8'=>'Obat','umum'=>'Umum');
		$data['msg_opname'] = "";
		$this->session->set_userdata('filter_jenisbarang','');
		$this->session->set_userdata('filter_bulan','');
		$data['bulan']			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		die($this->parser->parse("inventory/bhp_retur/show_barangretur",$data,true));
	}

	function daftar_retur(){
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);
		$data['datapuskesmas'] 	= $this->bhp_retur_model->get_data_puskesmas();
		$data['jenisbaranghabis'] = array('8'=>'Obat','umum'=>'Umum');
		$data['msg_opname'] = "";
		$this->session->set_userdata('filter_jenisbarang','');
		$this->session->set_userdata('filter_bulan','');
		$this->session->set_userdata('filter_tahun','');
		$data['bulan']			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
		die($this->parser->parse("inventory/bhp_retur/show_retur",$data,true));
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

		if($this->bhp_retur_model->delete_opname($kode,$id_barang,$table)){
			$data['msg_opname'] = "Delete data $kode";
			die($this->parser->parse("inventory/bhp_retur/show_opname",$data,true));
		}else{
			$data['msg_opname']= 'Delete data error';
			die($this->parser->parse("inventory/bhp_retur/show_opname",$data,true));
		}
	}
	function lastopname($bhp='obat')
	{
		$kodepus = $this->session->userdata('puskesmas');
		$this->db->order_by('tgl_opname','desc');
		$this->db->where('code_cl_phc','P'.$kodepus);
		$this->db->where('jenis_bhp',$bhp);
		$this->db->where("tipe ='terimarusak' or tipe='retur'");
		$this->db->select("max(tgl_opname) as last_opname");
        $query = $this->db->get('inv_inventaris_habispakai_opname',1);
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
