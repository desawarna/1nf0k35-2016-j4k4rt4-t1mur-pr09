<?php
class Lap_mutasibarang extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/lap_mutasibarang_model');
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
		$data['content'] = $this->parser->parse("inventory/lap_mutasibarang/detail",$data,true);

		$this->template->show($data,"home");
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
        if(($this->input->post('kibinv') != '') and !empty($this->input->post('kibinv'))) {
            if ($this->input->post('kibinv')=='all') {
        		# code...
        	}else{
            	$this->db->like('inv_inventaris_barang.id_mst_inv_barang', $this->input->post('kibinv'),'after');
            }
        }
		$rows_all = $this->lap_mutasibarang_model->get_laporan_inv();


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
        if(($this->input->post('kibinv') != '') and !empty($this->input->post('kibinv'))) {
        	if ($this->input->post('kibinv')=='all') {
        		# code...
        	}else{
            	$this->db->like('inv_inventaris_barang.id_mst_inv_barang', $this->input->post('kibinv'),'after');
            }
        }
		$rows = $this->lap_mutasibarang_model->get_laporan_inv();
		$no=1;

		$data_tabel = array();
		foreach($rows as $act) {
			if($act->tgl_distribusi == null){
				$date="00-00-0000";
			}else{
				$date = date("Y",strtotime($act->tgl_distribusi));
			}
			if ($act->jml_berkurang=='0') {
				$jmlkurang="";
				$hargakurang="";
				$jumlahhargakurang="";
				$satuan = "";
			}else{
				$jmlkurang=$act->jml_berkurang;
				$hargakurang=number_format($act->harga,2);
				$jumlahhargakurang=number_format($act->jumlahharga,2);
				if ($act->satuan!="") {
					$satuan = $act->satuan;
				}else{
					$satuan = "-";
				}

			}
			if ($act->satuan!="") {
				$satuantmb = $act->satuan;
			}else{
				$satuantmb ="-";
			}
			$s = array();
			$s[0] = substr($act->id_mst_inv_barang, 0,2);
			$s[1] = substr($act->id_mst_inv_barang, 2,2);
			$s[2] = substr($act->id_mst_inv_barang, 4,2);
			$s[3] = substr($act->id_mst_inv_barang, 6,2);
			$s[4] = substr($act->id_mst_inv_barang, 8,2);
			$data_tabel[] = array(
				'no'   							=> $no++,
				'uraian'						=> $act->uraian,
				'spkd'		   					=> $act->Dikes,
				'id_mst_inv_barang'		   		=> implode(".", $s),
				'tahunperolehan'				=> $date,
				'satuantmb'						=> $satuantmb,
				'hargatmb'						=> $act->harga,
				'merek'		   					=> $act->merek,
				'jml_bertambah'		   			=> $act->jml_bertambah,
				'harga'		   					=> number_format($act->harga,2),
				'jml_harga'						=> number_format($act->jml_harga,2),
				'jml_berkurang'				   	=> $jmlkurang,
				'harga'							=> $hargakurang,
				'jumlahharga'					=> $jumlahhargakurang,
				'satuan'						=> $satuan,
				'total'		   					=> number_format($act->jml_harga - $act->jumlahharga,2),
				'keterangan'					=> $act->keterangan,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}
		//die(print_r($data_tabel));
		$puskes = $this->input->post('namepuskes'); 
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
		$tahun = explode("-", $this->input->post('filter_tanggal'));
		$tahun1 = explode("-", $this->input->post('filter_tanggal1'));
		$kib = $this->input->post('kib');
		$data_puskesmas[] = array('nama_puskesmas' => $kd_upb,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'bidang' => $bidang,'unit' => $unit,'subunit' => $subunit,'upb' => $upb,'tahun1' =>$tahun1[2].'-'.$tahun1[1].'-'.$tahun1[0],'tahun' =>$tahun[2].'-'.$tahun[1].'-'.$tahun[0],'kib' =>$kib);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/lap_mutasibarang.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_lapmutasibarang_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

}
