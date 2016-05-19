<?php
class Lap_bhp_pengadaan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/lap_bhp_pengadaan_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('inventory/permohonanbarang_model');
		$this->load->model('inventory/distribusibarang_model');
		$this->load->model('mst/puskesmas_model');
	}

	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] 	= "Laporan";
		$data['title_form'] 	= "Riwayat Pengadaan BHP";;
		$this->db->like('code','p'.substr($this->session->userdata('puskesmas'),0,7));

		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);

		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		$data['jenisbaranghabis'] = $this->lap_bhp_pengadaan_model->get_data_jenis();
		$data['content'] = $this->parser->parse("inventory/lap_bhp_pengadaan/detail",$data,true);

		$this->template->show($data,"home");
	}
	
	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_lap_rkbu = $this->input->post('id_mst_lap_rkbu');

			$kode 	= $this->lap_bhp_pengadaan_model->getSelectedData('mst_lap_rkbu',$code_cl_phc)->result();
			
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
		
		$rows_all = $this->lap_bhp_pengadaan_model->get_data_permohonan();
		

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
		#$rows = $this->permohonanbarang_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$rows = $this->lap_bhp_pengadaan_model->get_data_permohonan();
		$data = array();
		//die(print_r($rows));
		$no=0;
		$temp='';
		$data_tabel = array();
		foreach ($rows as $key => $val) {
			$no++;
			foreach ($val as $act => $value) {
				
				if($key==$temp){	
					$data_tabel[$key]["harga_akhir"]		= $value['hargabeli'];	
					$data_tabel[$key]["totaljumlah"]		= ($data_tabel["$key"]["totaljumlah"]+$value['total']);	
					$data_tabel[$key]['nilaiaset']			= ($data_tabel["$key"]["totaljumlah"] * $data_tabel["$key"]["harga_akhir"]);
					$data_tabel[$key]["harga_beli$act"] 	= $value['hargabeli'];
					$data_tabel[$key]["jmlbeli$act"]  		= $value['total'];
					$data_tabel[$key]["total$act"]			= $value['total']*$value['hargabeli'];
															  
				}else{
					$temp = $key;
					$bulanex = $act;
					
					$data_tabel[$key] = array(
						'no'				=> $no,								
						'uraian'			=> $key,			
						'harga_akhir'		=> $value['hargabeli'],
						'value'				=> $value['pilihan_satuan'],
						'totaljumlah'		=> $value['total'],
						'nilaiaset'			=> $value['total']*$value['hargabeli'],
						'harga_beli1'		=> $bulanex == '01' ? $value['hargabeli'] : '',
						'jmlbeli1'			=> $bulanex == '01' ? $value['total'] : '',
						'total1'			=> $bulanex == '01' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli2'		=> $bulanex == '02' ? $value['hargabeli'] : '',
						'jmlbeli2'			=> $bulanex == '02' ? $value['total'] : '',
						'total2'			=> $bulanex == '02' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli3'		=> $bulanex == '03' ? $value['hargabeli'] : '',
						'jmlbeli3'			=> $bulanex == '03' ? $value['total'] : '',
						'total3'			=> $bulanex == '03' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli4'		=> $bulanex == '04' ? $value['hargabeli'] : '',
						'jmlbeli4'			=> $bulanex == '04' ? $value['total'] : '',
						'total4'			=> $bulanex == '04' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli5'		=> $bulanex == '05' ? $value['hargabeli'] : '',
						'jmlbeli5'			=> $bulanex == '05' ? $value['total'] : '',
						'total5'			=> $bulanex == '05' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli6'		=> $bulanex == '06' ? $value['hargabeli'] : '',
						'jmlbeli6'			=> $bulanex == '06' ? $value['total'] : '',
						'total6'			=> $bulanex == '06' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli7'		=> $bulanex == '07' ? $value['hargabeli'] : '',
						'jmlbeli7'			=> $bulanex == '07' ? $value['total'] : '',
						'total7'			=> $bulanex == '07' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli8'		=> $bulanex == '08' ? $value['hargabeli'] : '',
						'jmlbeli8'			=> $bulanex == '08' ? $value['total'] : '',
						'total8'			=> $bulanex == '08' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli9'		=> $bulanex == '09' ? $value['hargabeli'] : '',
						'jmlbeli9'			=> $bulanex == '09' ? $value['total'] : '',
						'total9'			=> $bulanex == '09' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli10'		=> $bulanex == '10' ? $value['hargabeli'] : '',
						'jmlbeli10'			=> $bulanex == '10' ? $value['total'] : '',
						'total10'			=> $bulanex == '10' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli11'		=> $bulanex == '11' ? $value['hargabeli'] : '',
						'jmlbeli11'			=> $bulanex == '11' ? $value['total'] : '',
						'total11'			=> $bulanex == '11' ? $value['total']*$value['hargabeli'] : '',
						'harga_beli12'		=> $bulanex == '12' ? $value['hargabeli'] : '',
						'jmlbeli12'			=> $bulanex == '12' ? $value['total'] : '',
						'total12'			=> $bulanex == '12' ? $value['total']*$value['hargabeli'] : '',
					);
				}	
			}
		}
		
		// die(print_r($data_tabel));
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
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$data_puskesmas[] = array('nama_puskesmas' => $nama,'tgl1' => $tanggal,'tgl2' => $tanggal1,'kd_prov'=>$kd_prov,'kd_kab'=>$kd_kab);
		
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/lap_bhp_pengadaan.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/laporan_bhp_pengadaan_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
		
	}

}
