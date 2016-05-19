<?php
class Bhp_pengadaan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('inventory/bhp_pengadaan_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('mst/invbarang_model');
	}
	function pengadaan_export(){
		$this->authentication->verify('inventory','show');
		
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permohonan') {
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
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}

		$rows_all = $this->bhp_pengadaan_model->get_data();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permohonan') {
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
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows = $this->bhp_pengadaan_model->get_data();
		//$rows = $this->bhp_pengadaan_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data_tabel = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		$no=1;
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no'							=>$no++,
				'id_inv_hasbispakai_pembelian' 	=> $act->id_inv_hasbispakai_pembelian,
				'code_cl_phc' 					=> $act->code_cl_phc,
				'pilihan_status_pembelian' 		=> $act->pilihan_status_pembelian,
				'tgl_permohonan' 				=> date("Y-m-d",strtotime($act->tgl_permohonan)),
				'tgl_pembelian' 				=> $act->tgl_pembelian,
				'tgl_kwitansi'					=> $act->tgl_kwitansi,
				'nomor_kwitansi'				=> $act->nomor_kwitansi,
				'nomor_kontrak'					=> $act->nomor_kontrak,
				'jumlah_unit'					=> $act->jumlah_unit,
				'nilai_pembelian'				=> number_format($act->nilai_pembelian,2),
				'value'							=> $act->value,
				'keterangan'					=> $act->keterangan
			);
		}



		$puskes = $this->input->post('puskes');
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$tahun_ = date("Y");
		$data_puskesmas[] = array('nama_puskesmas' => $puskes,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tahun' => $tahun_);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/pengadaan_permohonan_habispakai.xlsx';		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_export_bhppengadaan'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function pengadaan_detail_export(){
		$this->authentication->verify('inventory','show');

		$id 	= $this->input->post('kode');

		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		

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
		$this->db->where('id_inv_hasbispakai_pembelian',$id);
		$rows_all_activity = $this->bhp_pengadaan_model->getItem();


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
		
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('inv_inventaris_habispakai_pembelian_item.code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		
		$this->db->where('inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian',$id);
		$activity = $this->bhp_pengadaan_model->getItem(/*$this->input->post('recordstartindex'), $this->input->post('pagesize')*/);
		$no=1;
		$datadetail = array();
		foreach($activity as $act) {
			$datadetail[] = array(
				'no'									=> $no++,
				'id_inv_hasbispakai_pembelian'   		=> $act->id_inv_hasbispakai_pembelian,
				'id_mst_inv_barang_habispakai'   		=> $act->id_mst_inv_barang_habispakai,
				'uraian'								=> $act->uraian,
				'jml'									=> $act->jml,
				'batch'									=> $act->batch,
				'harga'									=> number_format($act->harga,2),
				'subtotal'								=> number_format($act->jml*$act->harga,2),
				'tgl_update'							=> date("d-m-Y",strtotime($act->tgl_update)),
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$data_puskesmas	= $this->bhp_pengadaan_model->get_data_row($id);
		$nama_puskesmas	= $this->bhp_pengadaan_model->get_data_nama($data_puskesmas['code_cl_phc']);
		$onshow['puskesmas']		= $nama_puskesmas['value'];
		$onshow['tgl_permohonan']	= date("d-m-Y",strtotime($data_puskesmas['tgl_permohonan']));
		$onshow['tgl_kwitansi']		= date("d-m-Y",strtotime($data_puskesmas['tgl_kwitansi']));
		$onshow['nomor_kwitansi']	= $data_puskesmas['nomor_kwitansi'];
		$onshow['nomor_kontrak']	= $data_puskesmas['nomor_kontrak'];
		$onshow['keterangan']		= $data_puskesmas['keterangan'];
		$onshow['jumlah_unit']		= $data_puskesmas['jumlah_unit'];
		$onshow['nilai_pembelian']	= number_format($data_puskesmas['nilai_pembelian'],2);
		$onshow['tahun']			= date("Y");
		$onshow['pilihan_status_pembelian']	= $this->bhp_pengadaan_model->getPilihan("status_pembelian",$data_puskesmas['pilihan_status_pembelian']);
		$kode_sess=$this->session->userdata('puskesmas');
		$onshow['kd_prov'] = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$onshow['kd_kab']  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$TBS->ResetVarRef(false);
		$TBS->VarRef =  &$onshow;	
		$dir = getcwd().'/';
		if ($data_puskesmas['id_mst_inv_barang_habispakai_jenis']=='8') {
			$template = $dir.'public/files/template/inventory/bhp_pengadaanpermohonan_obat.xlsx';	
		}else{
			$template = $dir.'public/files/template/inventory/bhp_pengadaanpermohonan.xlsx';	
		}
		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		
		$TBS->MergeBlock('a', $datadetail);
		
		$code = date('Y-m-d-H-i-s');
		$output_file_name = 'public/files/hasil/hasil_detail_export_'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;
	}

	function autocomplite_barang($obat=0){
		$kodepuskesmas = "P".$this->session->userdata("puskesmas");
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("term=","",$search[0]);
		$search = str_replace("+"," ",$search);

		$this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis",$obat);
		$this->db->like("uraian",$search);
		$this->db->order_by('id_mst_inv_barang_habispakai','asc');
		$this->db->limit(10,0);
		$this->db->select("mst_inv_barang_habispakai.*");
		$query= $this->db->get("mst_inv_barang_habispakai")->result();
		foreach ($query as $q) {
			$barang[] = array(
				'key' 		=> $q->id_mst_inv_barang_habispakai , 
				'value' 	=> $q->uraian,
				'satuan'	=>$q->pilihan_satuan, 
				
			);
		}
		echo json_encode($barang);
	}

	function autocomplite_bnf($obat=0){
		$kodepuskesmas = "P".$this->session->userdata("puskesmas");
		$search = explode("&",$this->input->server('QUERY_STRING'));
		$search = str_replace("term=","",$search[0]);
		$search = str_replace("+"," ",$search);

		//$this->db->where("id_mst_inv_barang_habispakai_jenis",$obat);
		$this->db->like("nama",$search);
		$this->db->order_by('code','asc');
		$this->db->limit(10,0);
		$this->db->select("code,nama");
		$query= $this->db->get("mst_inv_pbf")->result();
		foreach ($query as $q) {
			$barang[] = array(
				'key' 	=> $q->code, 
				'value'	=> $q->nama,
			);
		}
		echo json_encode($barang);
	}
	
	function total_pengadaan($id){
		$this->db->where('id_inv_hasbispakai_pembelian',$id);
		$query = $this->db->get('inv_inventaris_habispakai_pembelian')->result();
		foreach ($query as $q) {
			$totalpengadaan[] = array(
				'jumlah_unit' => $q->jumlah_unit, 
				'nilai_pengadaan' => number_format($q->nilai_pembelian,2), 
				'waktu_dibuat' => date("d-m-Y H:i:s",strtotime($q->waktu_dibuat)),
				'terakhir_diubah' => date("d-m-Y H:i:s",strtotime($q->terakhir_diubah)),
			);
			echo json_encode($totalpengadaan);
		}
    }
    
    function deskripsi($id){
    	$kodepuskesmas = "P".$this->session->userdata("puskesmas");
		$this->db->where("id_mst_inv_barang_habispakai","$id");
		$this->db->select("mst_inv_barang_habispakai.*,
			(SELECT harga AS hrg FROM inv_inventaris_habispakai_opname_item JOIN inv_inventaris_habispakai_opname ON (inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname)  WHERE id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai ORDER BY tgl_opname DESC LIMIT 1) AS harga_opname,
			(select harga as hargapembelian from inv_inventaris_habispakai_pembelian_item 
            where  id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai order by tgl_update desc limit 1 ) as harga_pembelian,
            (SELECT tgl_opname AS tglopname FROM inv_inventaris_habispakai_opname_item JOIN inv_inventaris_habispakai_opname ON (inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname) WHERE id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai ORDER BY tgl_opname DESC LIMIT 1) AS tgl_opname,
            (select tgl_update  as tglpembelian from inv_inventaris_habispakai_pembelian_item where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai  order by tgl_update desc limit 1) as tgl_pembelian");
		$query= $this->db->get("mst_inv_barang_habispakai")->result();
		foreach ($query as $q) {
			if (($q->tgl_pembelian!=null)||($q->tgl_opname!=null)) {
	          if($q->tgl_opname==null){
	            $tgl_opname = 0;
	          }else{
	            $tgl_opname = $q->tgl_opname;
	          }

	          if ($q->tgl_pembelian==null) {
	            $tgl_pembelian = 0;
	          }else{
	            $tgl_pembelian = $q->tgl_pembelian;
	          }
	          if( $tgl_pembelian>= $tgl_opname){
	            $hargabarang = $q->harga_pembelian;  
	          }else{
	            $hargabarang = $q->harga_opname;  
	          }
	        }else{
	          if ($q->harga==null) {
	            $hargaasli =0;
	          }else{
	            $hargaasli =$q->harga;
	          }

	          $hargabarang = $hargaasli;
            }
			$totalpengadaan[] = array(
				'hargabarang' 					=> $hargabarang, 
			);
			echo json_encode($totalpengadaan);
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

				if($field == 'tgl_permohonan') {
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
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}

		$rows_all = $this->bhp_pengadaan_model->get_data();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permohonan') {
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
		if ($this->session->userdata('filter_code_cl_phc')!='' or !empty($this->session->userdata('filter_code_cl_phc'))) {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));
			}
		}
		$rows = $this->bhp_pengadaan_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		
		foreach($rows as $act) {
			$data[] = array(
				'id_inv_hasbispakai_pembelian' 	=> $act->id_inv_hasbispakai_pembelian,
				'code_cl_phc' 					=> $act->code_cl_phc,
				'pilihan_status_pembelian' 		=> $act->pilihan_status_pembelian,
				'tgl_permohonan' 				=> $act->tgl_permohonan,
				'tgl_pembelian' 				=> $act->tgl_pembelian,
				'tgl_kwitansi'					=> $act->tgl_kwitansi,
				'nomor_kwitansi'				=> $act->nomor_kwitansi,
				'nomor_kontrak'					=> $act->nomor_kontrak,
				'jumlah_unit'					=> $act->jumlah_unit,
				'uraian'						=> $act->uraian,
				'jenis_transaksi'				=> ucfirst($act->jenis_transaksi),
				'nilai_pembelian'				=> $act->nilai_pembelian,
				'jumlah_unit'					=> $act->jumlah_unit,
				'nilai_pembelian'				=> number_format($act->nilai_pembelian),
				'value'							=> $act->value,
				'keterangan'					=> $act->keterangan,
				'detail'						=> 1,
				'edit'							=> ($act->pilihan_status_pembelian==2) ? 0 : 1,//1,//$unlock,
				'delete'						=> ($act->pilihan_status_pembelian==2) ? 0 : 1
			);
		}


		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	public function barang($id = 0){
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
		// if ($this->session->userdata('puskesmas')!='' or empty($this->session->userdata('puskesmas'))) {
		// 	$this->db->where('inv_inventaris_habispakai_pembelian_item.code_cl_phc','P'.$this->session->userdata('puskesmas'));
		// }
		$this->db->where('inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian',$id);
		$rows_all_activity = $this->bhp_pengadaan_model->getItem();


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
		// if ($this->session->userdata('puskesmas')!='' or empty($this->session->userdata('puskesmas'))) {
		// 	$this->db->where('inv_inventaris_habispakai_pembelian_item.code_cl_phc','P'.$this->session->userdata('puskesmas'));
		// }
		$this->db->where('inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian',$id);
		$activity = $this->bhp_pengadaan_model->getItem($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		
		foreach($activity as $act) {
			$data[] = array(
				'id_inv_hasbispakai_pembelian'   		=> $act->id_inv_hasbispakai_pembelian,
				'id_mst_inv_barang_habispakai'   		=> $act->id_mst_inv_barang_habispakai,
				'uraian'								=> $act->uraian,
				'jml'									=> $act->jml,
				'batch'									=> $act->batch,
				'jml_rusak'								=> $act->jml_rusak,
				'harga'									=> number_format($act->harga,2),
				'subtotal'								=> number_format($act->jml*$act->harga,2),
				'tgl_update'							=> $act->tgl_update,
				'jml_distribusi'						=> $act->tgl_distribusi !='' ? 1:0
			);
		}


		
		$size = sizeof($rows_all_activity);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	public function barang__($id = 0)
	{
		$data	  	= array();
		$filter 	= array();
		$filterLike = array();

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
		$activity = $this->bhp_pengadaan_model->getItem('inv_inventaris_habispakai_pembelian_item', array('id_inv_hasbispakai_pembelian'=>$id))->result();
		foreach($activity as $act) {
			$data[] = array(
				'id_inv_hasbispakai_pembelian'   		=> $act->id_inv_hasbispakai_pembelian,
				'id_mst_inv_barang_habispakai'   		=> $act->id_mst_inv_barang_habispakai,
				'uraian'								=> $act->uraian,
				'jml'								=> $act->jml,
				'harga'									=> number_format($act->harga,2),
				'subtotal'								=> number_format($act->jml*$act->harga,2),
				'tgl_update'							=> $act->tgl_update,
				'edit'		=> 1,
				'delete'	=> 1
			);
		}

		$json = array(
			'TotalRows' => sizeof($data),
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function filter(){
		if($_POST) {
			if($this->input->post('code_cl_phc') != '') {
				$this->session->set_userdata('filter_code_cl_phc',$this->input->post('code_cl_phc'));
			}
		}
	}
	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] = "Bahan Habis Pakai";
		$data['title_form'] = "Penerimaan / Pengadaan";
		$this->session->set_userdata('filter_code_cl_phc','');
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);

		$data['datapuskesmas'] 	= $this->inv_ruangan_model->get_data_puskesmas();
		$data['content'] = $this->parser->parse("inventory/bhp_pengadaan/show",$data,true);
		$this->template->show($data,"home");
	}

	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_inv_ruangan = $this->input->post('id_mst_inv_ruangan');

			$kode 	= $this->inv_ruangan_model->getSelectedData('mst_inv_ruangan',$code_cl_phc)->result();

			'<option value="">Pilih Ruangan</option>';
			foreach($kode as $kode) :
				echo $select = $kode->id_mst_inv_ruangan == $id_mst_inv_ruangan ? 'selected' : '';
				echo '<option value="'.$kode->id_mst_inv_ruangan.'" '.$select.'>' . $kode->nama_ruangan . '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}
	public function get_nama()
	{
		if($this->input->is_ajax_request()) {
			$code = $this->input->post('code');

			$this->db->where("code",$code);
			$kode 	= $this->invbarang_model->getSelectedData('mst_inv_barang',$code)->row();

			if(!empty($kode)) echo $kode->uraian;

			return TRUE;
		}

		show_404();
	}

	function add(){
		$this->authentication->verify('inventory','add');

		$this->form_validation->set_rules('kode_inventaris_', 'Kode Inventaris', 'trim|required');
        $this->form_validation->set_rules('tgl', 'Tanggal Perngadaan', 'trim|required');
        $this->form_validation->set_rules('status', 'Status Pengadaan', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Kontrak', 'trim');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] 	= "Bahan Habis Pakai";
			$data['title_form']		= "Tambah Penerimaan / Pengadaan";
			$data['action']			= "add";
			$data['kode']			= "";
			$data['bulan']			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');

			$kodepuskesmas = $this->session->userdata('puskesmas');
			//$this->db->where('code','P'.$kodepuskesmas);
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
			$this->db->where("mst_inv_pilihan.code",'1');
			$data['kodestatus'] = $this->bhp_pengadaan_model->get_data_status();
			$data['kodejenis'] = $this->bhp_pengadaan_model->get_data_jenis();
			$data['kodedana'] = $this->bhp_pengadaan_model->pilih_data_status('sumber_dana');
		
			$data['content'] = $this->parser->parse("inventory/bhp_pengadaan/form",$data,true);
		}elseif($id = $this->bhp_pengadaan_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'inventory/bhp_pengadaan/edit/'.$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."inventory/bhp_pengadaan/add");
		}

		$this->template->show($data,"home");
	}

	function edit($id_pengadaan=0){
		$this->authentication->verify('inventory','edit');

        //$this->form_validation->set_rules('tgl', 'Tanggal Perngadaan', 'trim|required');
        $this->form_validation->set_rules('jenis_transaksi', 'Jenis Transaksi', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
        $this->form_validation->set_rules('nomor_kontrak', 'Nomor Kontrak', 'trim|required');
        $this->form_validation->set_rules('thn_periode', 'Periode', 'trim|required');
        $this->form_validation->set_rules('bln_periode', 'Periode', 'trim|required');
        $this->form_validation->set_rules('pilihan_sumber_dana', 'Nomor Kontrak', 'trim|required');
        $this->form_validation->set_rules('thn_dana', 'Sumber Dana', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        $this->form_validation->set_rules('id_mst_inv_pbf_code', 'Instansi/PBF', 'trim|required');
        
		if($this->form_validation->run()== FALSE){
			$data 	= $this->bhp_pengadaan_model->get_data_row($id_pengadaan);
			$data['title_group'] 	= "Barang Habis Pakai";
			$data['title_form']		= "Ubah Permohonan/Pengadaan Barang";
			$data['action']			= "edit";
			$data['kode']			= $id_pengadaan;
			$data['bulan'] 			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');

			
			$kodepuskesmas = $this->session->userdata('puskesmas');
			//$this->db->where('code','P'.$kodepuskesmas);
			
			$data['kodepuskesmas'] 	= $this->puskesmas_model->get_data();
			$data['kodestatus'] 	= $this->bhp_pengadaan_model->get_data_status();
			$data['kodestatus_inv'] = $this->bhp_pengadaan_model->pilih_data_status('status_pembelian');
			$data['kodejenis'] 		= $this->bhp_pengadaan_model->get_data_jenis();
			$data['kodedana'] 		= $this->bhp_pengadaan_model->pilih_data_status('sumber_dana');

			$data['barang']	  	= $this->parser->parse('inventory/bhp_pengadaan/barang', $data, TRUE);
			$data['content'] 	= $this->parser->parse("inventory/bhp_pengadaan/edit",$data,true);
		}elseif($this->bhp_pengadaan_model->update_entry($id_pengadaan)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."inventory/bhp_pengadaan/edit/".$id_pengadaan);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."inventory/bhp_pengadaan/edit/".$id_pengadaan);
		}

		$this->template->show($data,"home");
	}
	function detail($id_permohonan=0){
		$this->authentication->verify('inventory','edit');
		if($this->form_validation->run()== FALSE){
			$data 	= $this->bhp_pengadaan_model->get_data_row($id_permohonan);
			$data['title_group'] 	= "Barang Habis Pakai";
			$data['title_form']		= "Permohonan / Pengadaan Barang";
			$data['action']			= "view";
			$data['kode']			= $id_permohonan;
			$data['viewreadonly']	= "readonly=''";

			
			$data['unlock'] = 0;
			$data['bulan'] 			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
			$data['kodejenis'] = $this->bhp_pengadaan_model->get_data_jenis();
			$data['kodedana'] = $this->bhp_pengadaan_model->pilih_data_status('sumber_dana');
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
			$data['kodestatus'] = $this->bhp_pengadaan_model->get_data_status();
			$data['kodestatus_inv'] = $this->bhp_pengadaan_model->pilih_data_status('status_pembelian');
			//$data['tgl_opnamecond']		= $this->bhp_pengadaan_model->gettgl_opname($id_permohonan);
			$data['barang']	  	= $this->parser->parse('inventory/bhp_pengadaan/barang', $data, TRUE);
			$data['content'] 	= $this->parser->parse("inventory/bhp_pengadaan/edit",$data,true);
			$this->template->show($data,"home");
		}
	}
	function dodel($kode=0){
		$this->authentication->verify('inventory','del');

		if($this->bhp_pengadaan_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."inventory/bhp_pengadaan");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."inventory/bhp_pengadaan");
		}
	}
	function updatestatus_barang(){
		$this->authentication->verify('inventory','edit');
		$this->bhp_pengadaan_model->update_status();				
	}
	function dodelpermohonan($kode=0,$barang=0,$batch=""){
		if($this->bhp_pengadaan_model->delete_entryitem($kode,$barang,$batch)){
				
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
				$dataupdate['terakhir_diubah']= date('Y-m-d H:i:s');
				$dataupdate['jumlah_unit']=  $this->bhp_pengadaan_model->sum_jumlah_item( $kode,'jml');
				$dataupdate['nilai_pembelian']= $this->bhp_pengadaan_model->sum_jumlah_item_jumlah( $kode,'harga');
				$key['id_inv_hasbispakai_pembelian'] = $kode;
        		$this->db->update("inv_inventaris_habispakai_pembelian",$dataupdate,$key);
	}
	function cekdelete($kode=0,$barang=0,$batch=""){
		$hasil = $this->bhp_pengadaan_model->cekdelete($barang,$batch);
		if($hasil=='1'){
			die('1');
		}else{
			die('0');
		}

	}

	public function kodeInvetaris($id=0){
		$this->db->where('code',$id);
		$query = $this->db->get('cl_phc')->result();
		foreach ($query as $q) {
			$kode[] = array(
				'kodeinv' => $q->cd_kompemilikbarang.'.'.$q->cd_propinsi.'.'.$q->cd_kabkota.'.'.$q->cd_bidang.'.'.$q->cd_unitbidang.'.'.$q->cd_satuankerja, 
			);
			echo json_encode($kode);
		}
	}
	/*
	public function tanggalopnamecondisi($id='')
	{
		$query = $this->bhp_pengadaan_model->gettgl_opname($id);
			$totalpengadaan[] = array(
				'tgl_opname' => date("Y-m-d",strtotime($query)), 
			);
			echo json_encode($totalpengadaan);
	}*/

	public function add_barang($kode=0,$obat=0,$code_cl_phc='')
	{	
		$data['action']			= "add";
		$data['kode']			= $kode;
        $this->form_validation->set_rules('id_permohonan_barang', 'Kode Barang', 'trim|required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
        $this->form_validation->set_rules('harga', 'Harga Satuan', 'trim|required');
        $this->form_validation->set_rules('jqxinput', 'Nama Barang', 'trim');
        $this->form_validation->set_rules('subtotal', 'subtotal', 'trim');
        $this->form_validation->set_rules('id_mst_inv_barang', 'barang', 'trim');
        if(!empty($this->input->post('obat'))&&($this->input->post('obat')=="8")){
        	$this->form_validation->set_rules('batch', 'Nomor Batch', 'trim|required');
        }

		if($this->form_validation->run()== FALSE){

			$data['kodebarang']		= $this->bhp_pengadaan_model->get_databarang();
			$data['notice']			= validation_errors();
			$data['kode']			= $kode;
			$data['obat']			= $obat;
			$data['code_cl_phc']			= $code_cl_phc;
			die($this->parser->parse('inventory/bhp_pengadaan/barang_form', $data));
		}else{
			if($this->bhp_pengadaan_model->insertdata($kode,$code_cl_phc)!=0){
				$dataupdate['terakhir_diubah']= date('Y-m-d H:i:s');
				$dataupdate['jumlah_unit']=  $this->bhp_pengadaan_model->sum_jumlah_item( $kode,'jml');
				$dataupdate['nilai_pembelian']= $this->bhp_pengadaan_model->sum_jumlah_item_jumlah( $kode,'harga');
				$key['id_inv_hasbispakai_pembelian'] = $kode;
        		$this->db->update("inv_inventaris_habispakai_pembelian",$dataupdate,$key);
				die("OK|Data Tersimpan");
			}else{
				 die("Error|Maaf, data tidak dapat diproses");
			}
			
		}
	}
	public function edit_barang($obat=0,$id_permohonan=0,$kd_barang=0)
	{
		$data['action']			= "edit";
		$data['kode']			= $id_permohonan;
		$this->form_validation->set_rules('id_permohonan_barang', 'Kode Barang', 'trim|required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
        $this->form_validation->set_rules('harga', 'Harga Satuan', 'trim|required');
        $this->form_validation->set_rules('jqxinput', 'Nama Barang', 'trim');
        $this->form_validation->set_rules('subtotal', 'subtotal', 'trim');
        $this->form_validation->set_rules('id_mst_inv_barang', 'barang', 'trim');
        $this->form_validation->set_rules('jml_rusak', 'rusak', 'trim');
        if(!empty($this->input->post('obat'))&&($this->input->post('obat')=="8")){
        	$this->form_validation->set_rules('batch', 'Nomor Batch', 'trim|required');
        }
		if($this->form_validation->run()== FALSE){
			$data = $this->bhp_pengadaan_model->get_data_barang_edit_table($id_permohonan,$kd_barang); 
			$data['action']			= "edit";
			$data['kode']			= $id_permohonan;
			$data['obat']			= $obat;
			$data['notice']			= validation_errors();
			
			die($this->parser->parse('inventory/bhp_pengadaan/barang_form_edit', $data));
		}else{
			if(!empty($this->input->post('obat'))&&($this->input->post('obat')=='8')){
				$tgl_kadaluarsa = explode("-", $this->input->post('tgl_kadaluarsa'));
				$batch = $this->input->post('batch');
			}else{
				$tgl_kadaluarsa = explode("-", "00-00-0000");
				$batch = "-";
			}
			//'code_cl_phc' => 'P'.$this->session->userdata('puskesmas'),
   			$values = array(
					'jml' => $this->input->post('jumlah'),
					'harga' => $this->input->post('harga'),
					'tgl_update' => $this->bhp_pengadaan_model->tanggal($id_permohonan),
					'batch' => $batch,
					'jml_rusak' => $this->input->post('jml_rusak'),
					'tgl_kadaluarsa' => $tgl_kadaluarsa[2]."-".$tgl_kadaluarsa[1]."-".$tgl_kadaluarsa[0],
				);
   			$keyupdate = array(
					'id_inv_hasbispakai_pembelian'=>$this->input->post('id_permohonan_barang'),
					'id_mst_inv_barang_habispakai'=> $this->input->post('id_mst_inv_barang'),
   				);
			$simpan=$this->db->update('inv_inventaris_habispakai_pembelian_item', $values,$keyupdate);
			if($simpan==true){
				$dataupdate['terakhir_diubah']= date('Y-m-d H:i:s');
				$dataupdate['jumlah_unit']=  $this->bhp_pengadaan_model->sum_jumlah_item( $id_permohonan,'jml');
				$dataupdate['nilai_pembelian']= $this->bhp_pengadaan_model->sum_jumlah_item_jumlah( $id_permohonan,'harga');
				$key['id_inv_hasbispakai_pembelian'] = $id_permohonan;
        		$this->db->update("inv_inventaris_habispakai_pembelian",$dataupdate,$key);
				die("OK|Data Telah di Ubah");
			}else{
				 die("Error|Proses data gagal");
			}
		}
		
	}

	function dodel_barang($kode=0,$id_barang="",$table=0){
		$this->authentication->verify('inventory','del');

		if($this->bhp_pengadaan_model->delete_entryitem_table($kode,$id_barang,$table)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
	}

	
	public function get_autonama() {
        $kode = $this->input->post('code_mst_inv_barang',TRUE); //variabel kunci yang di bawa dari input text id kode
        $query = $this->mkota->get_allkota(); //query model
 
        $kota       =  array();
        foreach ($query as $d) {
            $kota[]     = array(
                'label' => $d->nama_kota, //variabel array yg dibawa ke label ketikan kunci
                'nama' => $d->nama_kota , //variabel yg dibawa ke id nama
                'ibukota' => $d->ibu_kota, //variabel yang dibawa ke id ibukota
                'keterangan' => $d->keterangan //variabel yang dibawa ke id keterangan
            );
        }
        echo json_encode($kota);      //data array yang telah kota deklarasikan dibawa menggunakan json
    }
	public function add_barang_master($kode=0)
	{	
		$data['action']			= "add";
		$data['kode']			= $kode;
        $this->form_validation->set_rules('uraian_master', 'Uraian', 'trim|required');
        $this->form_validation->set_rules('code_master', 'Kode', 'trim');
        $this->form_validation->set_rules('merk_master', 'Merek Tipe', 'trim');
        $this->form_validation->set_rules('negara_master', 'Negara Asal', 'trim');
        $this->form_validation->set_rules('pilihan_satuan_barang_master', 'Satuan', 'trim');
        $this->form_validation->set_rules('harga_master', 'Harga', 'trim');

		if($this->form_validation->run()== FALSE){
			$data['kode']			= $kode;
			$data['noticemaster']		= validation_errors();
			$data['pilihan_jenis_barang'] = $this->bhp_pengadaan_model->getnamajenis();
			$data['pilihan_satuan_barang'] = $this->bhp_pengadaan_model->pilih_data_status('satuan_bhp');
			die($this->parser->parse('inventory/bhp_pengadaan/form_masterbarang', $data));
		}else{
				$values = array(
					'id_mst_inv_barang_habispakai_jenis'=> $this->input->post('id_mst_inv_barang_habispakai_jenis'),
					'code' 			=> $this->input->post('code_master'),
					'uraian'		=> $this->input->post('uraian_master'),
					'merek_tipe' 	=> $this->input->post('merk_master'),
					'negara_asal' 	=> $this->input->post('negara_master'),
					'pilihan_satuan' => $this->input->post('pilihan_satuan_barang_master'),
					'harga' => $this->input->post('harga_master'),
				);
				$simpan=$this->db->insert('mst_inv_barang_habispakai', $values);
				if($simpan==true){
				die("OK|Data disimpan");
			}else{
				 die("Error|Proses data gagal");
			}
			
		}
	}
}
