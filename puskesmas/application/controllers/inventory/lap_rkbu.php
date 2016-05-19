<?php
class Lap_rkbu extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/lap_rkbu_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('inventory/permohonanbarang_model');
		$this->load->model('inventory/distribusibarang_model');
		$this->load->model('mst/puskesmas_model');
	}

	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] 	= "Laporan";
		$data['title_form'] 	= "RKBU - Rekab Kebutuhan Barang Unit";;
		$this->db->like('code','p'.substr($this->session->userdata('puskesmas'),0,7));

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		$data['content'] = $this->parser->parse("inventory/lap_rkbu/detail",$data,true);

		$this->template->show($data,"home");
	}
	
	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_lap_rkbu = $this->input->post('id_mst_lap_rkbu');

			$kode 	= $this->lap_rkbu_model->getSelectedData('mst_lap_rkbu',$code_cl_phc)->result();
			
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_cl_phc',$this->input->post('code_cl_phc'));
				$this->session->set_userdata('filterruangan','');
			}else{
				$this->session->set_userdata('filter_cl_phc','');
				$this->session->set_userdata('filterruangan','');
			}
			echo "<option value=\"999999\">Pilih Ruangan</option>";
			foreach($kode as $kode) :
				$ruangan = $this->distribusibarang_model->get_count($code_cl_phc,$kode->id_mst_lap_rkbu);
				echo $select = $kode->id_mst_lap_rkbu == $id_mst_lap_rkbu ? 'selected' : '';
				echo '<option value="'.$kode->id_mst_lap_rkbu.'" '.$select.'>' . $kode->nama_ruangan .' '. $ruangan. '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}
	function permohonan_export(){
		
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

				if($field == 'tanggal_permohonan') {
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
		if($this->input->post('puskes') != '') {
			$this->db->where("inv_permohonan_barang.code_cl_phc",$this->input->post('puskes'));
		}
		if($this->input->post('ruang') != '') {
			$this->db->where("inv_permohonan_barang.id_mst_inv_ruangan",$this->input->post('ruang'));
		}
		$rows_all = $this->lap_rkbu_model->get_data_permohonan();
		

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tanggal_permohonan') {
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
		if($this->input->post('puskes') != '') {
			$this->db->where("inv_permohonan_barang.code_cl_phc",$this->input->post('puskes'));
		}
		if($this->input->post('ruang') != '') {
			$this->db->where("inv_permohonan_barang.id_mst_inv_ruangan",$this->input->post('ruang'));
		}
		#$rows = $this->permohonanbarang_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$rows = $this->lap_rkbu_model->get_data_permohonan();
		$data = array();
		$no=1;
		
		$data_tabel = array();
		foreach($rows as $act) {
			
			$data_tabel[] = array(
				'no'				=> $no++,								
				'nama_barang'		=> $act->nama_barang,				
				'merk_tipe'			=> $act->merk_tipe,
				'jumlah'			=> $act->jumlah,
				'harga'				=> $act->harga,
				'jumlah_biaya'		=> $act->harga*$act->jumlah,
				'rekening'			=> $act->rekening,
				'keterangan'		=> $act->keterangan			
			);
		}

		
		
		/*
		$data_tabel[] = array('no'=> '1', 'tgl'=>'10/10/2010' , 'ruangan'=>'Hill'      , 'jumlah'=>'19', 'keterangan'=>'bagus', 'status'=>'bagus');
		$data_tabel[] = array('no'=> '2', 'tgl'=>'10/10/2010' , 'ruangan'=>'Hill'      , 'jumlah'=>'19', 'keterangan'=>'bagus', 'status'=>'bagus');
		$data_tabel[] = array('no'=> '3', 'tgl'=>'10/10/2010' , 'ruangan'=>'Hill'      , 'jumlah'=>'19', 'keterangan'=>'bagus', 'status'=>'bagus');
		$data_tabel[] = array('no'=> '4', 'tgl'=>'10/10/2010' , 'ruangan'=>'Hill'      , 'jumlah'=>'19', 'keterangan'=>'bagus', 'status'=>'bagus');
		*/
		$puskes = $this->input->post('puskes'); 
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){	
				$nama = 'Semua Data Puskesmas';
		}else{
				$nama = $this->inv_barang_model->get_nama('value','cl_phc','code',$this->input->post('puskes'));
		}
		$tanggal = $this->input->post('filter_tanggal'); 
		$tanggal1 = $this->input->post('filter_tanggal1'); 
		if(empty($tanggal) or $tanggal == '' or empty($tanggal1) or $tanggal1 == ''){
			$tanggal = date('d-m-Y');
			$tanggal1 = date('d-m-Y');
		}else{
			$tanggals = explode("-", $this->input->post('filter_tanggal'));
			$tanggal = $tanggals[2].'-'.$tanggals[1].'-'.$tanggals[0];
			$tanggals1 = explode("-", $this->input->post('filter_tanggal1'));
			$tanggal1 = $tanggals1[2].'-'.$tanggals1[1].'-'.$tanggals1[0];
		}
		$ruang = $this->input->post('ruang');
		if(empty($ruang) or $ruang == 'Pilih Ruangan'){
			$ruang = '-';
		}else{
			$ruang = $this->inv_barang_model->get_nama('nama_ruangan','mst_inv_ruangan','id_mst_inv_ruangan',$this->input->post('ruang'));
		}
		$tahun =date("Y");
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'tanggal' => $tanggal,'tanggal1' => $tanggal1,'ruang' => $ruang,'tahun' =>$tahun);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/rkbu.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/laporan_rkbu_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
		
	}

}
