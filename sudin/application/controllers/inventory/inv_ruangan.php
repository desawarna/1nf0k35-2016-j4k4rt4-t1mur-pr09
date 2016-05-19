<?php
class Inv_ruangan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('inventory/distribusibarang_model');
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

				$this->db->like($field,$value);
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		if($this->session->userdata('filter_code_cl_phc') != '') {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('mst_inv_ruangan.code_cl_phc',$this->session->userdata('filter_code_cl_phc'));	
			}
		}
		// if ($this->session->userdata('puskesmas')!='' or empty($this->session->userdata('puskesmas'))) {
		// 	$this->db->where('mst_inv_ruangan.code_cl_phc','P'.$this->session->userdata('puskesmas'));
		// }
		$rows_all = $this->inv_ruangan_model->get_data();


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

		if($this->session->userdata('filter_code_cl_phc') != '') {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('mst_inv_ruangan.code_cl_phc',$this->session->userdata('filter_code_cl_phc'));	
			}
			
		}
		// if ($this->session->userdata('puskesmas')!='' or empty($this->session->userdata('puskesmas'))) {
		// 	$this->db->where('mst_inv_ruangan.code_cl_phc','P'.$this->session->userdata('puskesmas'));
		// }
		$rows = $this->inv_ruangan_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$unlock = 1;
		foreach($rows as $act) {
			$ruangan = $this->distribusibarang_model->get_count($act->code_cl_phc,$act->id_mst_inv_ruangan);
			$data[] = array(
				'id_mst_inv_ruangan'	=> $act->id_mst_inv_ruangan,
				'nama_ruangan'			=> $act->nama_ruangan,
				'keterangan'			=> $act->keterangan,
				'code_cl_phc'			=> $act->code_cl_phc,
				'puskesmas'				=> $act->puskesmas,
				'jml'					=> preg_replace("/[( )]/", " ", $ruangan),
				'nilai'					=> number_format($act->nilai,2),
				'edit'					=> $unlock,
				'delete'				=> $unlock
			);
		}

		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	
	function json_detail($code_cl_phc = 0, $id_ruang=0){
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

		$filter_group = $this->session->userdata('filter_group');
		if(!empty($filter_group) and $filter_group == '1'){
			$rows = $this->inv_ruangan_model->get_data_detail_group($this->input->post('recordstartindex'), $this->input->post('pagesize'));
			
			$data = array();
			foreach($rows as $act) {
				$act['id_mst_inv_barang'] = substr(chunk_split($act['id_mst_inv_barang'], 2, '.'),0,14);
				$act['register'] = $act['jml'];
				$data[] =  $act;
			}

			$rows_all = $this->inv_ruangan_model->get_data_detail_group();
			
			$size = count($rows_all);
			$json = array(
				'TotalRows' => (int) $size,
				'Rows' => $data
			);
		}else{
			$rows = $this->inv_ruangan_model->get_data_detail($this->input->post('recordstartindex'), $this->input->post('pagesize'));
			
			$data = array();
			foreach($rows as $act) {
				$act['id_mst_inv_barang'] = substr(chunk_split($act['id_mst_inv_barang'], 2, '.'),0,14);
				$data[] =  $act;
			}

			$rows_all = $this->inv_ruangan_model->get_data_detail();

			$size = sizeof($rows_all);
			$json = array(
				'TotalRows' => (int) $size,
				'Rows' => $data
			);
		}
		echo json_encode(array($json));
	}
	
	function set_detail_filter(){
		$filter_code_cl_phc = $this->input->post('filter_code_cl_phc');
		$filter_id_ruang 	= $this->input->post('filter_id_ruang');
		$filter_tanggal 	= $this->input->post('filter_tanggal');

		if(!empty($filter_code_cl_phc) and !empty($filter_id_ruang) and !empty($filter_tanggal) ){
			$this->session->set_userdata('filter_code_cl_phc',$this->input->post('filter_code_cl_phc'));
			$this->session->set_userdata('filter_id_ruang', $this->input->post('filter_id_ruang'));
			$this->session->set_userdata('filter_tanggal', $this->input->post('filter_tanggal'));
			$this->session->set_userdata('filter_group', $this->input->post('filter_group'));
			$q = $this->inv_ruangan_model->get_data_deskripsi($this->input->post('filter_code_cl_phc'),$this->input->post('filter_id_ruang'));
			foreach($q as $r){
				echo $r->value."_data_".$r->nama_ruangan."_data_".$r->keterangan;
			}
		}				
	}
	
	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] 	= "Inventory";
		$data['title_form'] 	= "Inventaris Ruangan";
		$this->session->set_userdata('filter_code_cl_phc','');
		$this->db->like('code','p'.substr($this->session->userdata('puskesmas'),0,7));

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}

		$data['datapuskesmas'] 	= $this->inv_ruangan_model->get_data_puskesmas();
		$data['content'] = $this->parser->parse("inventory/inv_ruangan/show",$data,true);

		$this->template->show($data,"home");
	}
	
	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_inv_ruangan = $this->input->post('id_mst_inv_ruangan');

			$kode 	= $this->inv_ruangan_model->getSelectedData('mst_inv_ruangan',$code_cl_phc)->result();
			
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_cl_phc',$this->input->post('code_cl_phc'));
				$this->session->set_userdata('filterruangan','');
			}else{
				$this->session->set_userdata('filter_cl_phc','');
				$this->session->set_userdata('filterruangan','');
			}
			echo "<option value=\"999999\">Pilih Ruangan</option>";
			foreach($kode as $kode) :
				$ruangan = $this->distribusibarang_model->get_count($code_cl_phc,$kode->id_mst_inv_ruangan);
				echo $select = $kode->id_mst_inv_ruangan == $id_mst_inv_ruangan ? 'selected' : '';
				echo '<option value="'.$kode->id_mst_inv_ruangan.'" '.$select.'>' . $kode->nama_ruangan .' '. $ruangan. '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}

	function do_detail($kode=0,$id=""){
		
		$this->authentication->verify('inventory','edit');

		$data = $this->inv_ruangan_model->get_data_row($kode,$id); 

		$data['title_group'] = "Inventory";
		$data['title_form']="Detail Inventaris Ruangan";
		$data['kode']= $kode;
		$data['id'] = $id;

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		$data['kondisi'] = $this->inv_ruangan_model->get_pilihan_kondisi()->result();
		$data['n_kondisi'] = $this->inv_ruangan_model->get_pilihan_kondisi()->num_rows();
	
		$data['barang'] = $this->parser->parse("inventory/inv_ruangan/barang",$data,true);
		$data['content'] = $this->parser->parse("inventory/inv_ruangan/detail",$data,true);
		$this->template->show($data,"home");
	}

	function detail($kode=0,$id="")
	{
		$this->session->set_userdata('filter_code_cl_phc',$kode);
		$this->session->set_userdata('filter_id_ruang',$id);
		$this->session->set_userdata('filter_tgl','0');
		
		$this->do_detail($kode, $id);
		
	}


	function add(){
		$this->load->model('inventory/inv_ruangan_model');

		$this->authentication->verify('inventory','add');

        $this->form_validation->set_rules('nama_ruangan', 'Nama Ruangan', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
        $this->form_validation->set_rules('codepus', 'Puskesmas', 'trim|required');

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();

		if($this->form_validation->run()== FALSE){
			$data['code']		 		= $this->session->userdata('puskesmas');
			$data['title_group'] 		= "Inventory";
			$data['title_form']  		= "Tambah Inventaris Ruangan";
			$data['action']      		= "add";
			$data['kode']				= "";

		
			$data['content'] = $this->parser->parse("inventory/inv_ruangan/form",$data,true);
			$this->template->show($data,"home");
		}elseif($this->inv_ruangan_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url()."inventory/inv_ruangan/");
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."inventory/inv_ruangan/add");
		}
	}

	function edit($kode=0,$id="")
	{
		$this->authentication->verify('inventory','add');

        // $this->form_validation->set_rules('id_mst_inv_ruangan', 'Id', 'trim|required');
        $this->form_validation->set_rules('nama_ruangan', 'Nama ruangan', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
        // $this->form_validation->set_rules('codepus', 'Kode', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data = $this->inv_ruangan_model->get_data_row($kode,$id); 

			$data['title_group'] = "Inventory";
			$data['title_form']="Ubah Inventory Ruangan";
			$data['action']="edit";
			$data['kode']= $kode;
			$data['id'] = $id;
			// $data['codepus']= $kode;
			// var_dump($data);
			// exit();
			$kodepuskesmas = $this->session->userdata('puskesmas');
			if(strlen($kodepuskesmas) == 4){
				$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
			}else {
				$this->db->where('code','P'.$kodepuskesmas);
			}
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();

		
			$data['content'] = $this->parser->parse("inventory/inv_ruangan/edit",$data,true);
			$this->template->show($data,"home");
		}elseif($this->inv_ruangan_model->update_entry($kode,$id)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."inventory/inv_ruangan/".$this->input->post('kode'));
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."inventory/inv_ruangan/edit/".$kode);
		}
	}

	function dodel($kode=0,$id=""){
		$this->authentication->verify('inventory','del');

		if($this->inv_ruangan_model->delete_entry($kode,$id)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."inventory/inv_ruangan");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."inventory/inv_ruangan");
		}
	}
	
	function export_detail(){
		$this->authentication->verify('inventory','show');

		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

		$filter_group = $this->session->userdata('filter_group');

		$no=1;
		$data_tabel = array();
		if(!empty($filter_group) and $filter_group == '1'){
			$rows = $this->inv_ruangan_model->get_data_detail_group();
			
			$data = array();
			foreach($rows as $act) {
				$act['no']	= $no++;
				$act['harga']	= number_format($act['harga']);
				$act['kode_barang']	= substr(chunk_split($act['id_mst_inv_barang'], 2, '.'),0,14); 
				$act['keterangan']	= "";
				$data_tabel[] =  $act;
			}
		}else{
			$rows = $this->inv_ruangan_model->get_data_detail();
			
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
		$template = $dir.'public/files/template/inventory/kir.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		$TBS->MergeBlock('a', $data_tabel);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_kir_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}
}
