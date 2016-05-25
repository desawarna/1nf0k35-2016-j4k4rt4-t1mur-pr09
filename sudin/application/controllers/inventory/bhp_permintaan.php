<?php
class Bhp_permintaan extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');

		$this->load->model('inventory/bhp_permintaan_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/inv_ruangan_model');
		$this->load->model('mst/invbarang_model');
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
		$data['title_form'] = "Permintaan / Permohonan";
		$this->session->set_userdata('filter_code_cl_phc','');
		$kodepuskesmas = $this->session->userdata('puskesmas');
		//$this->db->where('code','P'.$kodepuskesmas);

		$data['datapuskesmas'] 	= $this->inv_ruangan_model->get_data_puskesmas();
		$data['content'] = $this->parser->parse("inventory/bhp_permintaan/show",$data,true);
		$this->template->show($data,"home");
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
	
	function total_permintaan($id){
		$this->db->where('id_inv_hasbispakai_permintaan',$id);
		$query = $this->db->get('inv_inventaris_habispakai_permintaan')->result();
		foreach ($query as $q) {
			$totalpermintaan[] = array(
				'jumlah_unit' => $q->jumlah_unit, 
				'nilai_permintaan' => number_format($q->nilai_pembelian,2), 
				'waktu_dibuat' => date("d-m-Y H:i:s",strtotime($q->waktu_dibuat)),
				'terakhir_diubah' => date("d-m-Y H:i:s",strtotime($q->terakhir_diubah)),
			);
			echo json_encode($totalpermintaan);
		}
    }
    
    function deskripsi($id){
    	$kodepuskesmas = "P".$this->session->userdata("puskesmas");
		$this->db->where("id_mst_inv_barang_habispakai","$id");
		$this->db->select("IFNULL((SELECT b.harga FROM inv_inventaris_habispakai_permintaan_item b JOIN inv_inventaris_habispakai_permintaan a ON (a.id_inv_hasbispakai_permintaan = b.id_inv_hasbispakai_permintaan) WHERE b.id_mst_inv_barang_habispakai =mst_inv_barang_habispakai.id_mst_inv_barang_habispakai ORDER BY a.tgl_permintaan DESC LIMIT 1 ),mst_inv_barang_habispakai.harga) AS harga",false);
		$query= $this->db->get("mst_inv_barang_habispakai")->result();
		foreach ($query as $q) {
	          $hargabarang = $q->harga;
        }
		$totalpermintaan[] = array(
			'hargabarang' 					=> $hargabarang, 
		);
			echo json_encode($totalpermintaan);
    }
	
	function json(){
		$this->authentication->verify('inventory','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permintaan') {
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
		if ($this->session->userdata('filter_code_cl_phc')!='') {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));	
			}
			
		}
		$rows_all = $this->bhp_permintaan_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permintaan') {
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
		if ($this->session->userdata('filter_code_cl_phc')!='') {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));	
			}
			
		}
		$rows = $this->bhp_permintaan_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		
		foreach($rows as $act) {
			$data[] = array(
				'id_inv_hasbispakai_permintaan' => $act->id_inv_hasbispakai_permintaan,
				'code_cl_phc' 					=> $act->code_cl_phc,
				'tgl_permintaan' 				=> $act->tgl_permintaan,
				'jumlah_unit'					=> $act->jumlah_unit,
				'status_permintaan'				=> ucwords($act->status_permintaan),
				'uraian'						=> $act->uraian,
				'nilai_pembelian'				=> $act->nilai_pembelian,
				'jumlah_unit'					=> $act->jumlah_unit,
				'nilai_pembelian'				=> number_format($act->nilai_pembelian),
				'value'							=> $act->value,
				'keterangan'					=> $act->keterangan,
				'detail'						=> 1,
				'edit'							=> 0,
				'delete'						=> ($act->status_permintaan=='diterima') ? 0 : 0
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

				if($field == 'harga' ) {
					$this->db->where('inv_inventaris_habispakai_permintaan_item.harga',$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where('inv_inventaris_habispakai_permintaan_item.id_inv_hasbispakai_permintaan',$id);
		$rows_all_activity = $this->bhp_permintaan_model->getItem();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'harga' ) {
					$this->db->where('inv_inventaris_habispakai_permintaan_item.harga',$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$this->db->where('inv_inventaris_habispakai_permintaan_item.id_inv_hasbispakai_permintaan',$id);
		$activity = $this->bhp_permintaan_model->getItem($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		
		foreach($activity as $act) {
			$data[] = array(
				'id_inv_hasbispakai_permintaan'   		=> $act->id_inv_hasbispakai_permintaan,
				'id_mst_inv_barang_habispakai'   		=> $act->id_mst_inv_barang_habispakai,
				'uraian'								=> $act->uraian,
				'jml'									=> $act->jml,
				'harga'									=> number_format($act->harga,2),
				'subtotal'								=> number_format($act->jml*$act->harga,2),
				'tgl_permintaan'							=> $act->tgl_permintaan,
				'status_permintaan'							=> $act->status_permintaan,
				'pilihan_satuan'							=> $act->pilihan_satuan,
			);
		}


		
		$size = sizeof($rows_all_activity);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}

	public function get_nama(){
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
        $this->form_validation->set_rules('tgl', 'Tanggal Permintaan', 'trim|required');
        $this->form_validation->set_rules('id_mst_inv_barang_habispakai_jenis', 'Jenis Barang', 'trim|required');
        $this->form_validation->set_rules('status', 'Status Permintaan', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$data['title_group'] 	= "Bahan Habis Pakai";
			$data['title_form']		= "Tambah Permintaan / Permohonan";
			$data['action']			= "add";
			$data['kode']			= "";

			$kodepuskesmas = $this->session->userdata('puskesmas');
			//$this->db->where('code','P'.$kodepuskesmas);
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();

			$data['kodestatus'] = array('permintaan' =>'Permintaan','diterima' =>'Diterima');
			$data['kodejenis'] = $this->bhp_permintaan_model->get_data_jenis();
		
			$data['content'] = $this->parser->parse("inventory/bhp_permintaan/form",$data,true);
		}elseif($id = $this->bhp_permintaan_model->insert_entry()){
			$this->session->set_flashdata('alert', 'Save data successful...');
			redirect(base_url().'inventory/bhp_permintaan/edit/'.$id);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."inventory/bhp_permintaan/add");
		}

		$this->template->show($data,"home");
	}

	function edit($id_permintaan=0){
		$this->authentication->verify('inventory','edit');

        $this->form_validation->set_rules('tgl', 'Tanggal Permintaan', 'trim|required');
        $this->form_validation->set_rules('id_mst_inv_barang_habispakai_jenis', 'Jenis Barang', 'trim');
        $this->form_validation->set_rules('status', 'Status Permintaan', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
        
		if($this->form_validation->run()== FALSE){
			$data 	= $this->bhp_permintaan_model->get_data_row($id_permintaan);
			$data['title_group'] 	= "Barang Habis Pakai";
			$data['title_form']		= "Ubah Permohonan/Pengadaan Barang";
			$data['action']			= "edit";
			$data['kode']			= $id_permintaan;

			
			$kodepuskesmas = $this->session->userdata('puskesmas');
			//$this->db->where('code','P'.$kodepuskesmas);
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();

			$data['kodestatus'] = array('permintaan' =>'Permintaan','diterima' =>'Diterima');
			$data['kodejenis'] = $this->bhp_permintaan_model->get_data_jenis();

			$data['barang']	  	= $this->parser->parse('inventory/bhp_permintaan/barang', $data, TRUE);
			$data['content'] 	= $this->parser->parse("inventory/bhp_permintaan/edit",$data,true);
		}elseif($this->bhp_permintaan_model->update_entry($id_permintaan)){
			$this->session->set_flashdata('alert_form', 'Save data successful...');
			redirect(base_url()."inventory/bhp_permintaan/edit/".$id_permintaan);
		}else{
			$this->session->set_flashdata('alert_form', 'Save data failed...');
			redirect(base_url()."inventory/bhp_permintaan/edit/".$id_permintaan);
		}

		$this->template->show($data,"home");
	}
	function detail($id_permohonan=0){
		$this->authentication->verify('inventory','edit');
		if($this->form_validation->run()== FALSE){
			$data 	= $this->bhp_permintaan_model->get_data_row($id_permohonan);
			$data['title_group'] 	= "Barang Habis Pakai";
			$data['title_form']		= "Permohonan / Pengadaan Barang";
			$data['action']			= "view";
			$data['kode']			= $id_permohonan;
			$data['viewreadonly']	= "readonly=''";

			
			$data['unlock'] = 0;
			$data['bulan'] 			= array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
			$data['kodejenis'] = $this->bhp_permintaan_model->get_data_jenis();
			$data['kodedana'] = $this->bhp_permintaan_model->pilih_data_status('sumber_dana');
			$data['kodepuskesmas'] = $this->puskesmas_model->get_data();
			$data['kodestatus'] = $this->bhp_permintaan_model->get_data_status();
			$data['kodestatus'] = array('permintaan' =>'Permintaan','diterima' =>'Diterima');
			$data['barang']	  	= $this->parser->parse('inventory/bhp_permintaan/barang', $data, TRUE);
			$data['content'] 	= $this->parser->parse("inventory/bhp_permintaan/edit",$data,true);
			$this->template->show($data,"home");
		}
	}
	function dodel($kode=0){
		$this->authentication->verify('inventory','del');

		if($this->bhp_permintaan_model->delete_entry($kode)){
			$this->session->set_flashdata('alert', 'Delete data ('.$kode.')');
			redirect(base_url()."inventory/bhp_permintaan");
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
			redirect(base_url()."inventory/bhp_permintaan");
		}
	}
	function updatestatus_barang(){
		$this->authentication->verify('inventory','edit');
		$this->bhp_permintaan_model->update_status();				
	}
	function dodelpermohonan($kode=0,$barang=0){
		if($this->bhp_permintaan_model->delete_entryitem($kode,$barang)){
				
		}else{
			$this->session->set_flashdata('alert', 'Delete data error');
		}
				$dataupdate['terakhir_diubah']= date('Y-m-d H:i:s');
				$dataupdate['jumlah_unit']=  $this->bhp_permintaan_model->sum_jumlah_item( $kode,'jml');
				$dataupdate['nilai_pembelian']= $this->bhp_permintaan_model->sum_jumlah_item_jumlah( $kode,'harga');
				$key['id_inv_hasbispakai_permintaan'] = $kode;
        		$this->db->update("inv_inventaris_habispakai_permintaan",$dataupdate,$key);
	}
	function cekdelete($kode=0,$barang=0){
		$hasil = $this->bhp_permintaan_model->cekdelete($barang);
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


	public function add_barang($kode=0,$obat=0)
	{	
		$data['action']			= "add";
		$data['kode']			= $kode;
        $this->form_validation->set_rules('id_mst_inv_barang', 'Kode Barang', 'trim');
        $this->form_validation->set_rules('jqxinput', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
        $this->form_validation->set_rules('harga', 'Harga Satuan', 'trim|required');
        $this->form_validation->set_rules('subtotal', 'subtotal', 'trim');

		if($this->form_validation->run()== FALSE){

			$data['notice']			= validation_errors();
			$data['kode']			= $kode;
			$data['obat']			= $obat;
			die($this->parser->parse('inventory/bhp_permintaan/barang_form', $data));
		}else{
			if($this->bhp_permintaan_model->insertdata($kode)!=0){
				$dataupdate['terakhir_diubah']= date('Y-m-d H:i:s');
				$dataupdate['jumlah_unit']=  $this->bhp_permintaan_model->sum_jumlah_item( $kode,'jml');
				$dataupdate['nilai_pembelian']= $this->bhp_permintaan_model->sum_jumlah_item_jumlah( $kode,'harga');
				$key['id_inv_hasbispakai_permintaan'] = $kode;
        		$this->db->update("inv_inventaris_habispakai_permintaan",$dataupdate,$key);
				die("OK|Data Tersimpan");
			}else{
				 die("Error|Maaf, data tidak dapat diproses");
			}
			
		}
	}
	public function edit_barang($kode=0,$jenis=0,$kd_barang=0)
	{
		$data['action']			= "edit";
		$data['kode']			= $kode;
		$this->form_validation->set_rules('id_mst_inv_barang', 'Kode Barang', 'trim');
        $this->form_validation->set_rules('jqxinput', 'Nama Barang', 'trim|required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'trim|required');
        $this->form_validation->set_rules('harga', 'Harga Satuan', 'trim|required');
        $this->form_validation->set_rules('subtotal', 'subtotal', 'trim');
		if($this->form_validation->run()== FALSE){
			$data = $this->bhp_permintaan_model->get_data_barang_edit_table($kode,$kd_barang); 
			$data['action']			= "edit";
			$data['kode']			= $kode;
			$data['obat']			= $jenis;
			$data['disable']		= 'disable';
			$data['notice']			= validation_errors();
			
			die($this->parser->parse('inventory/bhp_permintaan/barang_form', $data));
		}else{
   			$values = array(
					'jml' => $this->input->post('jumlah'),
					'harga' => $this->input->post('harga'),
				);
   			$keyupdate = array(
					'id_inv_hasbispakai_permintaan'=>$kode,
					'id_mst_inv_barang_habispakai'=> $this->input->post('id_mst_inv_barang'),
   				);
			$simpan=$this->db->update('inv_inventaris_habispakai_permintaan_item', $values,$keyupdate);
			if($simpan==true){
				$dataupdate['terakhir_diubah']= date('Y-m-d H:i:s');
				$dataupdate['jumlah_unit']=  $this->bhp_permintaan_model->sum_jumlah_item( $kode,'jml');
				$dataupdate['nilai_pembelian']= $this->bhp_permintaan_model->sum_jumlah_item_jumlah( $kode,'harga');
				$key['id_inv_hasbispakai_permintaan'] = $kode;
        		$this->db->update("inv_inventaris_habispakai_permintaan",$dataupdate,$key);
				die("OK|Data Telah di Ubah");
			}else{
				 die("Error|Proses data gagal");
			}
		}
		
	}

	function dodel_barang($kode=0,$id_barang="",$table=0){
		$this->authentication->verify('inventory','del');

		if($this->bhp_permintaan_model->delete_entryitem_table($kode,$id_barang,$table)){
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
			$data['pilihan_jenis_barang'] = $this->bhp_permintaan_model->getnamajenis();
			$data['pilihan_satuan_barang'] = $this->bhp_permintaan_model->pilih_data_status('satuan_bhp');
			die($this->parser->parse('inventory/bhp_permintaan/form_masterbarang', $data));
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
	function permintaan_export()
	{
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$this->authentication->verify('inventory','show');

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permintaan') {
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
		if ($this->session->userdata('filter_code_cl_phc')!='') {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));	
			}
			
		}
		$rows_all = $this->bhp_permintaan_model->get_data();

		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_permintaan') {
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
		if ($this->session->userdata('filter_code_cl_phc')!='') {
			if ($this->session->userdata('filter_code_cl_phc')=='all') {
				# code...
			}else{
				$this->db->where('code_cl_phc',$this->session->userdata('filter_code_cl_phc'));	
			}
			
		}
		$rows = $this->bhp_permintaan_model->get_data();
		$data_tabel = array();
		$no=1;
		foreach($rows as $act) {
			$data_tabel[] = array(
				'no' 							=> $no++,
				'id_inv_hasbispakai_permintaan' => $act->id_inv_hasbispakai_permintaan,
				'code_cl_phc' 					=> $act->code_cl_phc,
				'tgl_permintaan' 				=> date("d-m-Y",strtotime($act->tgl_permintaan)),
				'jumlah_unit'					=> $act->jumlah_unit,
				'status_permintaan'				=> ucwords($act->status_permintaan),
				'uraian'						=> $act->uraian,
				'nilai_pembelian'				=> $act->nilai_pembelian,
				'jumlah_unit'					=> $act->jumlah_unit,
				'nilai_pembelian'				=> number_format($act->nilai_pembelian),
				'value'							=> $act->value,
				'keterangan'					=> $act->keterangan,
				'detail'						=> 1,
				'edit'							=> 1,
				'delete'						=> ($act->status_permintaan=='diterima') ? 0 : 1
			);
		}
		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$namapus  = $this->inv_barang_model->get_nama('value','cl_phc','code','P'.$kode_sess);
		$tahun  = date("Y");

		$data_puskesmas[] = array('nama_puskesmas' => $namapus,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tahun' => $tahun);
		$dir = getcwd().'/';
		$template = $dir.'public/files/template/inventory/bhp_permintaan.xlsx';		
		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		$output_file_name = 'public/files/hasil/hasil_export_bhp_permintaan'.$code.'.xlsx';
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;

	}
	function permintaan_detail_export($id = 0)
	{
		$TBS = new clsTinyButStrong;		
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'harga' ) {
					$this->db->where('inv_inventaris_habispakai_permintaan_item.harga',$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		$this->db->where('inv_inventaris_habispakai_permintaan_item.id_inv_hasbispakai_permintaan',$id);
		$rows_all_activity = $this->bhp_permintaan_model->getItem();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'harga' ) {
					$this->db->where('inv_inventaris_habispakai_permintaan_item.harga',$value);
				}elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}

		$this->db->where('inv_inventaris_habispakai_permintaan_item.id_inv_hasbispakai_permintaan',$id);
		$activity = $this->bhp_permintaan_model->getItem();
		$data_tabel = array();

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){
			$unlock = 1;
		}else{
			$unlock = 0;
		}
		$no=1;
		foreach($activity as $act) {
			$data_tabel[] = array(
				'no'									=> $no++,
				'id_inv_hasbispakai_permintaan'   		=> $act->id_inv_hasbispakai_permintaan,
				'id_mst_inv_barang_habispakai'   		=> $act->id_mst_inv_barang_habispakai,
				'uraian'								=> $act->uraian,
				'jml'									=> $act->jml,
				'harga'									=> number_format($act->harga,2),
				'subtotal'								=> number_format($act->jml*$act->harga,2),
				'tgl_permintaan'							=> $act->tgl_permintaan,
				'status_permintaan'							=> $act->status_permintaan,
				'pilihan_satuan'							=> $act->pilihan_satuan,
				'merek_tipe'							=> $act->merek_tipe,
			);
		}

		$kode_sess=$this->session->userdata('puskesmas');
		$kd_prov = $this->inv_barang_model->get_nama('value','cl_province','code',substr($kode_sess, 0,2));
		$kd_kab  = $this->inv_barang_model->get_nama('value','cl_district','code',substr($kode_sess, 0,4));
		$kd_kec  = 'KEC. '.$this->inv_barang_model->get_nama('nama','cl_kec','code',substr($kode_sess, 0,7));
		$namapus  = $this->inv_barang_model->get_nama('value','cl_phc','code','P'.$kode_sess);
		$tahun  = date("Y");
		$datautama = $this->bhp_permintaan_model->get_data_row($id);
		$tgl_permintaan = date("d-m-Y",strtotime($datautama['tgl_permintaan']));
		$kategori_barang = $this->inv_barang_model->get_nama('uraian','mst_inv_barang_habispakai_jenis','id_mst_inv_barang_habispakai_jenis',$datautama['id_mst_inv_barang_habispakai_jenis']);
		$status = $datautama['status_permintaan'];
		$jumlah_unit =$datautama['jumlah_unit'];
		$nilai_unit = number_format($datautama['nilai_pembelian'],2);
		$data_puskesmas[] = array('nama_puskesmas' => $namapus,'kd_prov' => $kd_prov,'kd_kab' => $kd_kab,'tahun' => $tahun,'kategori_barang' => $kategori_barang,'tgl_permintaan' => $tgl_permintaan,'status' => $status,'jumlah_unit' => $jumlah_unit,'nilai_unit' => $nilai_unit);
		$dir = getcwd().'/';
		if ($datautama['id_mst_inv_barang_habispakai_jenis']=='8') {
			$template = $dir.'public/files/template/inventory/bhp_permintaan_detail_obat.xlsx';		
		}else{
			$template = $dir.'public/files/template/inventory/bhp_permintaan_detail.xlsx';		
		}
		
		$TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

		// Merge data in the first sheet
		$TBS->MergeBlock('a', $data_tabel);
		$TBS->MergeBlock('b', $data_puskesmas);
		
		$code = uniqid();
		if ($datautama['id_mst_inv_barang_habispakai_jenis']=='8') {
			$output_file_name = 'public/files/hasil/hasil_export_bhppermintaandetailobat_'.$code.'.xlsx';
		}else{
			$output_file_name = 'public/files/hasil/hasil_export_bhppermintaandetail_'.$code.'.xlsx';
		}
		$output = $dir.$output_file_name;
		$TBS->Show(OPENTBS_FILE, $output); // Also merges all [onshow] automatic fields.
		
		echo base_url().$output_file_name ;

	}
}
