<?php
class Lap_kir extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/lap_kir_model');
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
			$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
		}
		$rows_all = $this->lap_kir_model->get_data();


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
			$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
		}
		
		$rows = $this->lap_kir_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$unlock = 1;
		foreach($rows as $act) {
			$ruangan = $this->distribusibarang_model->get_count($act->code_cl_phc,$act->id_mst_lap_kir);
			$data[] = array(
				'id_mst_lap_kir'	=> $act->id_mst_lap_kir,
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
			$rows = $this->lap_kir_model->get_data_detail_group($this->input->post('recordstartindex'), $this->input->post('pagesize'));
			
			$data = array();
			foreach($rows as $act) {
				$act['id_mst_inv_barang'] = substr(chunk_split($act['id_mst_inv_barang'], 2, '.'),0,14);
				$act['register'] = $act['jml'];
				$data[] =  $act;
			}

			$rows_all = $this->lap_kir_model->get_data_detail_group();
			
			$size = count($rows_all);
			$json = array(
				'TotalRows' => (int) $size,
				'Rows' => $data
			);
		}else{
			$rows = $this->lap_kir_model->get_data_detail($this->input->post('recordstartindex'), $this->input->post('pagesize'));
			
			$data = array();
			foreach($rows as $act) {
				$act['id_mst_inv_barang'] = substr(chunk_split($act['id_mst_inv_barang'], 2, '.'),0,14);
				$data[] =  $act;
			}

			$rows_all = $this->lap_kir_model->get_data_detail();

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
			$q = $this->lap_kir_model->get_data_deskripsi($this->input->post('filter_code_cl_phc'),$this->input->post('filter_id_ruang'));
			foreach($q as $r){
				echo $r->value."_data_".$r->nama_ruangan."_data_".$r->keterangan;
			}
		}				
	}
	
	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] 	= "Laporan";
		$data['title_form'] 	= "KIR - Kartu Inventaris Ruangan";;
		$this->db->like('code','p'.substr($this->session->userdata('puskesmas'),0,7));

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		$data['kondisi'] = $this->lap_kir_model->get_pilihan_kondisi()->result();
		$data['n_kondisi'] = $this->lap_kir_model->get_pilihan_kondisi()->num_rows();
		$data['content'] = $this->parser->parse("inventory/lap_kir/detail",$data,true);

		$this->template->show($data,"home");
	}
	
	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_lap_kir = $this->input->post('id_mst_lap_kir');

			$kode 	= $this->lap_kir_model->getSelectedData('mst_lap_kir',$code_cl_phc)->result();
			
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_cl_phc',$this->input->post('code_cl_phc'));
				$this->session->set_userdata('filterruangan','');
			}else{
				$this->session->set_userdata('filter_cl_phc','');
				$this->session->set_userdata('filterruangan','');
			}
			echo "<option value=\"999999\">Pilih Ruangan</option>";
			foreach($kode as $kode) :
				$ruangan = $this->distribusibarang_model->get_count($code_cl_phc,$kode->id_mst_lap_kir);
				echo $select = $kode->id_mst_lap_kir == $id_mst_lap_kir ? 'selected' : '';
				echo '<option value="'.$kode->id_mst_lap_kir.'" '.$select.'>' . $kode->nama_ruangan .' '. $ruangan. '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}

	function export_detail(){
		$this->authentication->verify('inventory','show');

		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

		$filter_group = $this->session->userdata('filter_group');

		$no=1;
		$data_tabel = array();
		if(!empty($filter_group) and $filter_group == '1'){
			$rows = $this->lap_kir_model->get_data_detail_group();
			
			$data = array();
			foreach($rows as $act) {
				$act['no']	= $no++;
				$act['harga']	= number_format($act['harga']);
				$act['kode_barang']	= substr(chunk_split($act['id_mst_inv_barang'], 2, '.'),0,14); 
				$act['keterangan']	= "";
				$data_tabel[] =  $act;
			}
		}else{
			$rows = $this->lap_kir_model->get_data_detail();
			
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
			$this->db->where('id_mst_lap_kir',$ruang);
			$this->db->where('code_cl_phc',$kode);
			$ruang = $this->db->get('mst_lap_kir')->row();
			$namaruang = !empty($ruang) ? $ruang->nama_ruangan : "-";
		}

		$propinsi = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode, 1,2));
		$kabkota  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode, 1,4));
		$kecamatan  = $this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode, 1,7));

		$data_puskesmas['puskesmas'] 	= $nama;
		$data_puskesmas['tanggal'] 		= $tanggal;
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
