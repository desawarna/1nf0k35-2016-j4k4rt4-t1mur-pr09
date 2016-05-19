<?php
class Lap_bhp_pengeluaran extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('inventory/lap_bhp_pengeluaran_model');
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
			//$this->db->where('code','P'.$kodepuskesmas);
		$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
		$data['jenisbaranghabis'] = $this->lap_bhp_pengeluaran_model->get_data_jenis();
		$data['content'] = $this->parser->parse("inventory/lap_bhp_pengeluaran/detail",$data,true);

		$this->template->show($data,"home");
	}
	
	
	function permohonan_export(){
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		
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
		/*if($this->session->userdata('filter_jenisbarang')!=''){
			if($this->session->userdata('filter_jenisbarang')=="all"){

			}else{
				//$this->db->where("jenis_bhp",$this->session->userdata('filter_jenisbarang'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="all"){

			}else{
				//$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){

			}else{
			//	$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		
		
		//$rows_all = $this->lap_bhp_pengeluaran_model->get_data_permohonan();
*/
		$filtername ='';
		$order='';
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				$filtername ="and $field like ".'"%'.$value.'%"'."";
			}

			if(!empty($ord)) {
				//$this->db->order_by($ord, $this->input->post('sortorder'));
				$val =  $this->input->post('sortorder');
				$order="order by $ord $val";
			}
		}
		$filbulan=date("m");
		$filtahun=date("Y");
		$filterbhp ="";
		if($this->input->post('jenisbarang')!=''){
			if($this->input->post('jenisbarang')=="all"){
				$filterbhp ="";
			}else{
				/*if ($this->input->post('jenisbarang')=='obat') {
					$jenis='obat';
				}else{
					$jenis='umum';
				}*/
				$filterbhp = " and mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis = ".'"'.$this->input->post('jenisbarang').'"'."";
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_bulan')!=''){
			if($this->session->userdata('filter_bulan')=="All"){
			}else{
				//$this->db->where("MONTH(tgl_opname)",$this->session->userdata('filter_bulan'));
				$filbulan =$this->session->userdata('filter_bulan');
			}
		}else{
			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		if($this->session->userdata('filter_tahun')!=''){
			if($this->session->userdata('filter_tahun')=="all"){
			}else{
				//$this->db->where("YEAR(tgl_opname)",$this->session->userdata('filter_tahun'));
				$filtahun =$this->session->userdata('filter_tahun');
			}
		}else{

			//$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$kode);
		}
		$tanggals = explode("-", $this->input->post('filter_tanggal'));
		$rows = $this->lap_bhp_pengeluaran_model->get_data_permohonan($tanggals[1],$tanggals[0],$filterbhp,$filtername,$order);
		//die(print_r($rows));
	//	$get_jumlahawal = $this->lap_bhp_pengeluaran_model->get_jumlahawal();
		$data = array();
		$no=0;
		$data_tabel = array();
		$temp='';
		$jml=0;
		foreach ($rows as $key => $val) {
			$no++;
			foreach ($val as $act => $value) {
				
				if($key==$temp){
					$data_tabel["$key"]["keluar$act"]		= $value['pengeluaranperhari']*-1;	
					$data_tabel["$key"]["harga"]			= $value['harga'];	
					$data_tabel["$key"]["jumlah_op"]		= ($data_tabel["$key"]["jumlah_op"]+$value['pengeluaranperhari']);	
					$data_tabel["$key"]['nilai_aset_total']	= ($data_tabel["$key"]["jumlah_awal"] + $data_tabel["$key"]["jumlah_op"])*$value['harga'];
					$data_tabel["$key"]['total']			= $data_tabel["$key"]["jumlah_awal"] + $data_tabel["$key"]["jumlah_op"];
					$data_tabel["$key"]['nilai_aset_awal']  = $value['jumlah_awal']*$value['harga'];
															  
				}else{
				$temp = $key;
				$data_tabel[$key]= array(
					'no'				=> $no,								
					'uraian'			=> $key,
					'harga'				=> $value['harga'],
					'jumlah_op'			=> ($value['pengeluaranperhari']),
					'jumlah_awal'		=> $value['jumlah_awal'],
					'nilai_aset_awal'	=> $value['jumlah_awal']*$value['harga'],
					'total'				=> $value['jumlah_awal'] + $value['pengeluaranperhari'],
					'nilai_aset_total'	=> ($value['jumlah_awal'] + $value['pengeluaranperhari'])*$value['harga'],
					'keluar1'			=> $act == 1 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar2'			=> $act == 2 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar3'			=> $act == 3 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar4'			=> $act == 4 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar5'			=> $act == 5 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar6'			=> $act == 6 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar7'			=> $act == 7 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar8'			=> $act == 8 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar9'			=> $act == 9 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar10'			=> $act == 10 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar11'			=> $act == 11 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar12'			=> $act == 12 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar13'			=> $act == 13 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar14'			=> $act == 14 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar15'			=> $act == 15 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar16'			=> $act == 16 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar17'			=> $act == 17 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar18'			=> $act == 18 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar19'			=> $act == 19 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar20'			=> $act == 20 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar21'			=> $act == 21 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar22'			=> $act == 22 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar23'			=> $act == 23 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar24'			=> $act == 24 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar25'			=> $act == 25 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar26'			=> $act == 26 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar27'			=> $act == 27 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar28'			=> $act == 28 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar29'			=> $act == 29 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar30'			=> $act == 30 ? ($value['pengeluaranperhari'])*-1 : '',
					'keluar31'			=> $act == 31 ? ($value['pengeluaranperhari'])*-1 : '',
				);
			}
				
			}
		}
		///die(print_r($data_tabel));
		
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$nama_puskesmas = $this->input->post('nama_puskesmas');
		//$tgl = date("d-m-Y");
		$jenis_barang = $this->input->post('jenisbarang');
		$tgl = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$tgl1=date("m-m-Y");
		$tgl2=date("d-m-Y");
		$data_puskesmas[] = array('jenis_barang' => $jenis_barang,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'nama_puskesmas' => $nama_puskesmas,'bulan'=>$tgl,'tahun'=>$tahun,'tgl1'=>$tgl1,'tgl2'=>$tgl2,'tanggal_export'=>$tgl2);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/lap_bhp_pengeluaran.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_daftarstokopname_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
		
	}

}
