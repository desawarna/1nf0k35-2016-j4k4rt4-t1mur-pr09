<?php
class Json extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('mst/invbarang_model');
	}

	

	function json_barang(){
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


		if($this->session->userdata('filterHAPUS') != '') {
			$this->db->where("pilihan_status_invetaris","3");
		}

		if (($this->session->userdata('filterHAPUS') == '') ||($this->session->userdata('filterGIB') != '')) {
				$this->db->where("pilihan_status_invetaris !=","3");
			}
		$rows_all = $this->inv_barang_model->get_data();


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

		if($this->session->userdata('filterHAPUS') != '') {
			$this->db->where("pilihan_status_invetaris","3");
		}
		if (($this->session->userdata('filterHAPUS') == '') ||($this->session->userdata('filterGIB') != '')) {
				$this->db->where("pilihan_status_invetaris !=","3");
			}
		$rows = $this->inv_barang_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));

		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_inventaris_barang'   		=> $act->id_inventaris_barang,
				'id_mst_inv_barang'   			=> substr(chunk_split($act->id_mst_inv_barang, 2, '.'),0,14),
				'id_pengadaan'		   			=> $act->id_pengadaan,
				'register'		   				=> $act->register,
				'barang_kembar_proc'		   	=> $act->barang_kembar_proc,
				'nama_barang'					=> $act->nama_barang,
				'jumlah'						=> $act->jumlah,
				'harga'							=> number_format($act->harga,2),
				'totalharga'					=> number_format($act->totalharga,2),
				'keterangan_pengadaan'			=> $act->keterangan_pengadaan,
				'pilihan_status_invetaris'		=> $act->pilihan_status_invetaris,
				'id_inventaris_distribusi'		=> $act->id_inventaris_distribusi,
				'barang_kembar_proc'			=> $act->barang_kembar_proc,
				'tanggal_diterima'				=> $act->tanggal_diterima,
				'tanggal_dihapus'				=> $act->tanggal_dihapus,
				'waktu_dibuat'					=> $act->waktu_dibuat,
				'terakhir_diubah'				=> $act->terakhir_diubah,
				'value'							=> $act->value,
				'puskesmas'						=> $act->puskesmas,
				'nama_ruangan'					=> $act->nama_ruangan,
				'edit'		=> ($act->id_pengadaan==0) ? 1 : 0,
				'delete'	=> 1
			);
		}


		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}


	function golongan_a(){
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

		$rows_all = $this->inv_barang_model->get_data_golongan_A();

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

		$rows = $this->inv_barang_model->get_data_golongan_A($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_inventaris_barang'   	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'   			=> substr(chunk_split($act->id_mst_inv_barang, 2, '.'),0,14),
				'uraian'					=> $act->uraian,
				'id_pengadaan'		   		=> $act->id_pengadaan,
				'barang_kembar_proc'		=> $act->barang_kembar_proc,
				'satuan'					=> $act->satuan,
				'id_ruangan'				=> $act->id_ruangan,
				'hak'						=> $act->hak,
				'id_cl_phc'					=> $act->id_cl_phc,
				'register'					=> $act->register,
				'asal_usul'					=> $act->asal_usul,
				'keterangan_pengadaan'		=> $act->keterangan_pengadaan,
				'harga'						=> number_format($act->harga,2),
				'id_inventaris_distribusi'	=> $act->id_inventaris_distribusi,
				'tanggal_diterima'			=> $act->tanggal_diterima,
				'jumlah'					=> $act->jumlah,
				'jumlah_satuan'				=> $act->jumlah.' '.$act->satuan,
				'penggunaan'				=> $act->penggunaan,
				'luas' 						=> $act->luas,
				'alamat' 					=> $act->alamat,
				'pilihan_satuan_barang' 	=> $act->pilihan_satuan_barang,
				'pilihan_status_hak' 		=> $act->pilihan_status_hak,
				'status_sertifikat_tanggal' => date("d-m-Y",strtotime($act->status_sertifikat_tanggal)),
				'status_sertifikat_nomor'	=> $act->status_sertifikat_nomor,
				'pilihan_penggunaan' 		=> $act->pilihan_penggunaan,
				'edit'		=> ($act->id_pengadaan==0) ? 1 : 0,
				'delete'	=> 1
			);
		}


		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function golongan_b(){
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if(($field == 'tanggal_bpkb')||($field == 'tanggal_perolehan')) {
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

		$rows_all = $this->inv_barang_model->get_data_golongan_B();
		
		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if(($field == 'tanggal_bpkb')||($field == 'tanggal_perolehan')) {
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

		$rows = $this->inv_barang_model->get_data_golongan_B($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'   	=> substr(chunk_split($act->id_mst_inv_barang, 2, '.'),0,14),
				'uraian' 				=> $act->uraian,
				'id_pengadaan'		   	=> $act->id_pengadaan,
				'barang_kembar_proc'	=> $act->barang_kembar_proc,
				'merek_type' 			=> $act->merek_type,
				'keterangan_pengadaan'		=> $act->keterangan_pengadaan,
				'id_inventaris_distribusi'	=> $act->id_inventaris_distribusi,
				'bahan'		 			=> $act->bahan,
				'asal_usul'				=> $act->asal_usul,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'keadaan_barang'				=> $act->keadaan_barang,
				'jumlah'				=> $act->jumlah,
				'harga'					=> number_format($act->harga,2),
				'satuan'	 			=> $act->satuan,
				'ukuran_satuan' 		=> $act->ukuran_barang.'  '.$act->satuan,
				'identitas_barang' 		=> $act->identitas_barang,
				'pilihan_bahan' 		=> $act->pilihan_bahan,
				'ukuran_barang' 		=> $act->ukuran_barang,
				'pilihan_satuan' 		=> $act->pilihan_satuan,
				'tanggal_bpkb'			=> date("d-m-Y",strtotime($act->tanggal_bpkb)),
				'nomor_bpkb'		 	=> $act->nomor_bpkb,
				'no_polisi'		 		=> $act->no_polisi,
				'tanggal_perolehan'	 	=> date("d-m-Y",strtotime($act->tanggal_perolehan)),
				'edit'		=> ($act->id_pengadaan==0) ? 1 : 0,
				'delete'	=> 1
			);
		}



		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function golongan_c(){
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

		$rows_all = $this->inv_barang_model->get_data_golongan_C();


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

		$rows = $this->inv_barang_model->get_data_golongan_C($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'   	=> substr(chunk_split($act->id_mst_inv_barang, 2, '.'),0,14),
				'uraian' 				=> $act->uraian,
				'hak' 					=> $act->hak,
				'id_pengadaan'		   	=> $act->id_pengadaan,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'harga'					=> number_format($act->harga,2),
				'barang_kembar_proc'	=> $act->barang_kembar_proc,
				'keterangan_pengadaan'		=> $act->keterangan_pengadaan,
				'asal_usul'				=> $act->asal_usul,
				'tingkat' 				=> $act->tingkat,
				'id_inventaris_distribusi'	=> $act->id_inventaris_distribusi,
				'tanggal_diterima'			=> $act->tanggal_diterima,
				'beton' 				=> $act->beton,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'harga'					=> $act->harga,
				'luas_lantai' 			=> $act->luas_lantai,
				'letak_lokasi_alamat' 	=> $act->letak_lokasi_alamat,
				'pillihan_status_hak' 	=> $act->pillihan_status_hak,
				'nomor_kode_tanah' 		=> $act->nomor_kode_tanah,
				'pilihan_kons_tingkat' 	=> $act->pilihan_kons_tingkat,
				'pilihan_kons_beton'	=> $act->pilihan_kons_beton,
				'dokumen_tanggal'		=> $act->dokumen_tanggal,
				'dokumen_nomor'		 	=> $act->dokumen_nomor,
				'edit'		=> ($act->id_pengadaan==0) ? 1 : 0,
				'delete'	=> 1
			);
		}



		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function golongan_d(){
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

		$rows = $this->inv_barang_model->get_data_golongan_D($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'   	=> substr(chunk_split($act->id_mst_inv_barang, 2, '.'),0,14),
				'uraian' 				=> $act->uraian,
				'konstruksi' 			=> $act->konstruksi,
				'tanah' 				=> $act->tanah,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'harga'					=> number_format($act->harga,2),
				'keterangan_pengadaan'		=> $act->keterangan_pengadaan,
				'id_inventaris_distribusi'	=> $act->id_inventaris_distribusi,
				'tanggal_diterima'			=> $act->tanggal_diterima,
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
				'edit'		=> ($act->id_pengadaan==0) ? 1 : 0,
				'delete'	=> 1
			);
		}



		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function golongan_e(){
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tahun_cetak_beli') {
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
			if(($this->session->userdata('filter_cl_phc') == 'all')||($this->session->userdata('filter_cl_phc') =='')||empty($this->session->userdata('filter_cl_phc'))){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows_all = $this->inv_barang_model->get_data_golongan_E();
		


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tahun_cetak_beli') {
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
			if(($this->session->userdata('filter_cl_phc') == 'all')||($this->session->userdata('filter_cl_phc') =='')||empty($this->session->userdata('filter_cl_phc'))){
			}else{
				$this->db->where('id_cl_phc',$this->session->userdata('filter_cl_phc'));
			}
		}

		$rows = $this->inv_barang_model->get_data_golongan_E($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'   	=> substr(chunk_split($act->id_mst_inv_barang, 2, '.'),0,14),
				'uraian' 				=> $act->uraian,
				'bahan' 				=> $act->bahan,
				'satuan' 				=> $act->satuan,
				'id_pengadaan'		   	=> $act->id_pengadaan,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'jumlah'				=> $act->jumlah,
				'harga'					=> number_format($act->harga,2),
				'keterangan_pengadaan'	=> $act->keterangan_pengadaan,
				'asal_usul'				=> $act->asal_usul,
				'barang_kembar_proc'	=> $act->barang_kembar_proc,
				'buku_judul_pencipta' 	=> $act->buku_judul_pencipta,

				'id_inventaris_distribusi'	=> $act->id_inventaris_distribusi,
				'tanggal_diterima'			=> $act->tanggal_diterima,
				'buku_spesifikasi' 		=> $act->buku_spesifikasi,
				'budaya_asal_daerah' 	=> $act->budaya_asal_daerah,
				'budaya_pencipta' 		=> $act->budaya_pencipta,
				'pilihan_budaya_bahan' 	=> $act->pilihan_budaya_bahan,
				'flora_fauna_jenis'		=> $act->flora_fauna_jenis,
				'flora_fauna_ukuran'	=> $act->flora_fauna_ukuran,
				'flora_ukuran_satuan'	=> $act->flora_fauna_ukuran.'  '.$act->satuan,
				'pilihan_satuan'		=> $act->pilihan_satuan,
				'tahun_cetak_beli'		=> $act->tahun_cetak_beli,
				'edit'		=> ($act->id_pengadaan==0) ? 1 : 0,
				'delete'	=> 1
			);
		}



		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	function golongan_f(){
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tanggal_mulai') {
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

		$rows_all = $this->inv_barang_model->get_data_golongan_F();
		


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tanggal_mulai') {
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

		$rows = $this->inv_barang_model->get_data_golongan_F($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'id_inventaris_barang' 	=> $act->id_inventaris_barang,
				'id_mst_inv_barang'   	=> substr(chunk_split($act->id_mst_inv_barang, 2, '.'),0,14),
				'uraian' 				=> $act->uraian,
				'tanah' 				=> $act->tanah,
				'beton' 				=> $act->beton,
				'tingkat' 				=> $act->tingkat,
				'id_pengadaan'		   	=> $act->id_pengadaan,
				'id_cl_phc'				=> $act->id_cl_phc,
				'register'				=> $act->register,
				'harga'					=> number_format($act->harga,2),
				'id_inventaris_distribusi'	=> $act->id_inventaris_distribusi,
				'tanggal_diterima'			=> $act->tanggal_diterima,
				'keterangan_pengadaan'	=> $act->keterangan_pengadaan,
				'asal_usul'				=> $act->asal_usul,
				'jumlah'				=> $act->jumlah,
				'barang_kembar_proc'	=> $act->barang_kembar_proc,
				'bangunan' 				=> $act->bangunan,
				'pilihan_konstruksi_bertingkat' 	=> $act->pilihan_konstruksi_bertingkat,
				'pilihan_konstruksi_beton' 			=> $act->pilihan_konstruksi_beton,
				'luas' 					=> $act->luas,
				'lokasi' 				=> $act->lokasi,
				'dokumen_tanggal'		=> $act->dokumen_tanggal,
				'dokumen_nomor'			=> $act->dokumen_nomor,
				'tanggal_mulai'		 	=> $act->tanggal_mulai,
				'pilihan_status_tanah'	=> $act->pilihan_status_tanah,
				'edit'		=> ($act->id_pengadaan==0) ? 1 : 0,
				'delete'	=> 1
			);
		}



		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	
	

}
