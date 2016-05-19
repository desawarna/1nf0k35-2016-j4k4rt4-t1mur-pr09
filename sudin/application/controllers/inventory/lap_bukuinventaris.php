<?php
class Lap_bukuinventaris extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/lap_bukuinventaris_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('inventory/permohonanbarang_model');
		$this->load->model('inventory/distribusibarang_model');
		$this->load->model('mst/puskesmas_model');
	}

	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] 	= "Laporan";
		$data['title_form'] 	= "Invetaris";;
		$this->db->like('code','p'.substr($this->session->userdata('puskesmas'),0,7));

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		$data['content'] = $this->parser->parse("inventory/lap_bukuinventaris/detail",$data,true);

		$this->template->show($data,"home");
	}
	
	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_lap_bukuinventaris = $this->input->post('id_mst_lap_bukuinventaris');

			$kode 	= $this->lap_bukuinventaris_model->getSelectedData('mst_lap_rkbu',$code_cl_phc)->result();
			
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

		$this->db->where('code_cl_phc',$this->input->post('puskes'));
		
		if(($this->input->post('filter_tanggal') != '') and ($this->input->post('filter_tanggal1') != '')) {
            $this->db->where('tanggal_diterima >=', $this->input->post('filter_tanggal'));
			$this->db->where('tanggal_diterima <=', $this->input->post('filter_tanggal1'));
        }
		$rows_all = $this->lap_bukuinventaris_model->get_laporan_inv();


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

		
		$this->db->where('code_cl_phc',$this->input->post('puskes'));
		

		if(($this->input->post('filter_tanggal') != '') and ($this->input->post('filter_tanggal1') != '')) {
            $this->db->where('tanggal_diterima >=', $this->input->post('filter_tanggal'));
			$this->db->where('tanggal_diterima <=', $this->input->post('filter_tanggal1'));
        }
		$rows = $this->lap_bukuinventaris_model->get_laporan_inv();
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
		//die(print_r($data_tabel));
		$puskes = $this->input->post('puskes'); 
		$ruang = $this->input->post('ruang'); 
		if(empty($puskes) or $puskes == 'Pilih Puskesmas'){
			$namapus = 'Semua Data Puskesmas';
		}else{
			$namapus = $this->input->post('puskes');
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

}
