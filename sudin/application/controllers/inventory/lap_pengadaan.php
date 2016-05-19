<?php
class Lap_pengadaan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/lap_pengadaan_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('inventory/distribusibarang_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/pengadaanbarang_model');
	}

	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] 	= "Laporan";
		$data['title_form'] 	= "Pengadaan Barang";;
		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}

		$data['datapuskesmas'] 	= $this->inv_ruangan_model->get_data_puskesmas();
		$data['kodestatus'] = $this->pengadaanbarang_model->get_data_status();
		$data['content'] = $this->parser->parse("inventory/lap_pengadaan/detail",$data,true);

		$this->template->show($data,"home");
	}
	
	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_lap_pengadaan = $this->input->post('id_mst_lap_pengadaan');

			$kode 	= $this->lap_pengadaan_model->getSelectedData('mst_lap_pengadaan',$code_cl_phc)->result();
			
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_cl_phc',$this->input->post('code_cl_phc'));
				$this->session->set_userdata('filterruangan','');
			}else{
				$this->session->set_userdata('filter_cl_phc','');
				$this->session->set_userdata('filterruangan','');
			}
			echo "<option value=\"999999\">Pilih Ruangan</option>";
			foreach($kode as $kode) :
				$ruangan = $this->distribusibarang_model->get_count($code_cl_phc,$kode->id_mst_lap_pengadaan);
				echo $select = $kode->id_mst_lap_pengadaan == $id_mst_lap_pengadaan ? 'selected' : '';
				echo '<option value="'.$kode->id_mst_lap_pengadaan.'" '.$select.'>' . $kode->nama_ruangan .' '. $ruangan. '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}
	function bulan($bulan){
			Switch ($bulan){
			    case 1 : $bulan="Januari";
			        Break;
			    case 2 : $bulan="Februari";
			        Break;
			    case 3 : $bulan="Maret";
			        Break;
			    case 4 : $bulan="April";
			        Break;
			    case 5 : $bulan="Mei";
			        Break;
			    case 6 : $bulan="Juni";
			        Break;
			    case 7 : $bulan="Juli";
			        Break;
			    case 8 : $bulan="Agustus";
			        Break;
			    case 9 : $bulan="September";
			        Break;
			    case 10 : $bulan="Oktober";
			        Break;
			    case 11 : $bulan="November";
			        Break;
			    case 12 : $bulan="Desember";
			        Break;
			    }
			return $bulan;
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
		
		if($this->input->post('status') != '') {
			$this->db->where("inv_pengadaan.pilihan_status_pengadaan",$this->input->post('status'));
		}
		if ($this->input->post('puskesmas')!='' or empty($this->input->post('puskesmas'))) {
			$this->db->where('inv_inventaris_barang.code_cl_phc',$this->input->post('puskesmas'));
		}
		$rows_all = $this->lap_pengadaan_model->get_data();

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
		
		if($this->input->post('status') != '') {
			$this->db->where("inv_pengadaan.pilihan_status_pengadaan",$this->input->post('status'));
		}
		if ($this->input->post('puskesmas')!='' or empty($this->input->post('puskesmas'))) {
			$this->db->where('inv_inventaris_barang.code_cl_phc',$this->input->post('puskesmas'));
		}
		$rows = $this->lap_pengadaan_model->get_data();
		
		$data_tabel = array();
		$no=1;
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'						=> $no++,
				'nama_barang'   			=> $act->nama_barang,
				'tgl_pengadaan'				=> date("d-m-Y",strtotime($act->tgl_pengadaan)),
				'nomor_kontrak'				=> $act->nomor_kontrak,
				'tgl_kwitansi'		   		=> date("d-m-Y",strtotime($act->tgl_kwitansi)),
				'nomor_kwitansi'			=> $act->nomor_kwitansi,
				'jumlah'					=> $act->jumlah,
				'hargasatuan'				=> $act->hargasatuan,
				'totalharga'				=> $act->totalharga,
				'keterangan'				=> $act->keterangan
			);
		}
		$tanggal_export1 = $this->input->post('filter_tanggal1');
		$tanggal_export = $this->input->post('filter_tanggal');
		if(empty($tanggal_export) or $tanggal_export == '' or empty($tanggal_export1) or $tanggal_export1 == ''){
			$tanggal_export = date('d-m-Y');
			$tanggal_export1 = date('d-m-Y');
		}else{
			$tanggals = explode("-", $this->input->post('filter_tanggal'));
			$tanggal_export = $tanggals[2].'-'.$this->bulan($tanggals[1]).'-'.$tanggals[0];
			$tanggals1 = explode("-", $this->input->post('filter_tanggal1'));
			$tanggal_export1 = $tanggals1[2].'-'.$this->bulan($tanggals1[1]).'-'.$tanggals1[0];
		}
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$puskesmas  = $this->inv_barang_model->get_nama('value','cl_phc','code','P'.$kode_sess);
		
		$data_puskesmas[] = array('tanggal_export'=>$tanggal_export,'tanggal_export1'=>$tanggal_export1,'kd_kab' => $kd_kab,'kd_prov' => $kd_prov,'puskesmas' => $puskesmas);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/lap_pengadaan.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_lapPengadaan_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
		
	}

		
		
}
