<?php
class Lap_kibd extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/lap_kibd_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('inventory/distribusibarang_model');
		$this->load->model('mst/puskesmas_model');
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
				if ($this->input->post('puskes')!='') {
				$this->db->where('id_cl_phc',$this->input->post('puskes'));
				}else
				if(($this->session->userdata('filter_cl_phc') == 'all')||(($this->session->userdata('filter_cl_phc')) == '')){
				}else{
					$kodeplch = $this->session->userdata('filter_cl_phc');
					$this->db->where("id_cl_phc",$kodeplch);	
				}
				
			}
		}else {
			if ($this->input->post('puskes')!='') {
				$this->db->where('id_cl_phc',$this->input->post('puskes'));
			}else
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
	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] 	= "Laporan";
		$data['title_form'] 	= "KIB D - Kartu Inventaris Barang D";;
		$this->db->like('code','p'.substr($this->session->userdata('puskesmas'),0,7));

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		$data['content'] = $this->parser->parse("inventory/lap_kibd/detail",$data,true);

		$this->template->show($data,"home");
	}
	
	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_lap_kibd = $this->input->post('id_mst_lap_kibd');

			$kode 	= $this->lap_kibd_model->getSelectedData('mst_lap_kibd',$code_cl_phc)->result();
			
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_cl_phc',$this->input->post('code_cl_phc'));
				$this->session->set_userdata('filterruangan','');
			}else{
				$this->session->set_userdata('filter_cl_phc','');
				$this->session->set_userdata('filterruangan','');
			}
			echo "<option value=\"999999\">Pilih Ruangan</option>";
			foreach($kode as $kode) :
				$ruangan = $this->distribusibarang_model->get_count($code_cl_phc,$kode->id_mst_lap_kibd);
				echo $select = $kode->id_mst_lap_kibd == $id_mst_lap_kibd ? 'selected' : '';
				echo '<option value="'.$kode->id_mst_lap_kibd.'" '.$select.'>' . $kode->nama_ruangan .' '. $ruangan. '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}

}
