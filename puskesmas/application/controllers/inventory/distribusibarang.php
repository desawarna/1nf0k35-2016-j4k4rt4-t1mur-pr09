<?php
class Distribusibarang extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/pengadaanbarang_model');
		$this->load->model('inventory/distribusibarang_model');
		$this->load->model('inventory/inv_barang_model');
		$this->load->model('inventory/inv_ruangan_model');
	}

	function json(){
		$this->authentication->verify('inventory','show');


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_pengadaan') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}
				elseif($field == 'kode') {
					$this->db->like('inv_inventaris_barang.id_mst_inv_barang',$value);
				}
				elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		$rows_all = $this->distribusibarang_model->get_data();


		if($_POST) {
			$fil = $this->input->post('filterscount');
			$ord = $this->input->post('sortdatafield');

			for($i=0;$i<$fil;$i++) {
				$field = $this->input->post('filterdatafield'.$i);
				$value = $this->input->post('filtervalue'.$i);

				if($field == 'tgl_pengadaan') {
					$value = date("Y-m-d",strtotime($value));
					$this->db->where($field,$value);
				}
				elseif($field == 'kode') {
					$this->db->like('inv_inventaris_barang.id_mst_inv_barang',$value);
				}
				elseif($field != 'year') {
					$this->db->like($field,$value);
				}
			}

			if(!empty($ord)) {
				$this->db->order_by($ord, $this->input->post('sortorder'));
			}
		}
		
		$rows = $this->distribusibarang_model->get_data($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $act) {
			$data[] = array(
				'kode' 				=> substr(chunk_split($act->id_mst_inv_barang, 2, '.'), 0,14),
				'kode_barang' 		=> $act->id_mst_inv_barang,
				'register' 			=> $act->register,
				'nama_barang' 		=> $act->nama_barang,
				'harga' 			=> $act->harga,
				'kondisi'			=> $act->pilihan_keadaan_barang." - ".$act->val,		
				'id_barang'			=> $act->id_inventaris_barang,				
				'nama' 				=> preg_replace('/[^A-Za-z0-9\-]/', '_', $act->nama_barang)
			);
		}


		
		$size = sizeof($rows_all);
		$json = array(
			'TotalRows' => (int) $size,
			'Rows' => $data
		);

		echo json_encode(array($json));
	}
	
	public function pop_add($data_barang)
	{	
		$data['action']			= "add";

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required');
        $this->form_validation->set_rules('code_cl_phc2', 'Data Puskesmas', 'trim|required|min_length[11]');
        $this->form_validation->set_rules('data_barang', 'Data Barang', 'trim|required');
		
		if($this->form_validation->run()== FALSE){
			/*$data['kodebarang']		= $this->permohonanbarang_model->get_databarang();
			$data['notice']			= validation_errors();
			*/
			
			$kodepuskesmas = $this->session->userdata('puskesmas');
			if(strlen($kodepuskesmas) == 4){
				$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
			}else {
				$this->db->where('code','P'.$kodepuskesmas);
			}
			$data['datapuskesmas'] 	= $this->inv_barang_model->get_data_puskesmas();
			$data['data_barang'] 	= $data_barang;
			$data['notice']			= validation_errors();
			die($this->parser->parse('inventory/distribusi_barang/pop_add', $data));
		}else{

			if($this->distribusibarang_model->add_distribusi()){			

				die("OK|");
			}else{
				die("Error|Proses data gagal");
			}
			
		}
	}
	
	function index(){
		$this->authentication->verify('inventory','edit');
		$data['title_group'] = "Inventory";
		$data['title_form'] = "Distribusi Barang";

		$this->session->set_userdata('code_cl_phc',$this->input->post('code_cl_phc'));//$this->session->userdata('puskesmas'));
		$this->session->unset_userdata('code_ruangan');

		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}
		$data['datapuskesmas'] 	= $this->inv_barang_model->get_data_puskesmas();
		$data['pilih_kondisi'] = $this->distribusibarang_model->get_pilihan_kondisi();
		$data['content'] = $this->parser->parse("inventory/distribusi_barang/show",$data,true);
		$this->template->show($data,"home");
	}

	public function get_ruangan()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');//'P'.$this->session->userdata('puskesmas');//;
			$id_mst_inv_ruangan = $this->input->post('id_mst_inv_ruangan');

			$kode 	= $this->inv_ruangan_model->getSelectedData('mst_inv_ruangan',$code_cl_phc)->result();

			$all = $this->distribusibarang_model->get_count($code_cl_phc);
			echo '<option value="all">-- Seluruh Ruangan '.$all.' --</option>';
			//if(substr($code_cl_phc, -2)=="01"){
				$none = $this->distribusibarang_model->get_count();
				echo '<option value="none">-- Belum Distribusi '.$none.' --</option>';
			//}
			foreach($kode as $kode) :
				$ruangan = $this->distribusibarang_model->get_count($code_cl_phc,$kode->id_mst_inv_ruangan);
				echo $select = $kode->id_mst_inv_ruangan == $id_mst_inv_ruangan ? 'selected' : '';
				echo '<option value="'.$kode->id_mst_inv_ruangan.'" '.$select.'>' . $kode->nama_ruangan . ' '.$ruangan.' </option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}
	
	function update_data(){
		$this->form_validation->set_rules('kondisi', 'Kondisi Barang', 'trim|required');
		$this->form_validation->set_rules('register', 'Register', 'trim|required');
		
		if($this->form_validation->run()== FALSE){
			echo validation_errors();
		}else{
			$this->distribusibarang_model->update_kondisi();
			$this->distribusibarang_model->update_register();
		}
		
	}
	
	
	public function get_ruangan_pop()
	{
		if($this->input->is_ajax_request()) {
			$code_cl_phc = $this->input->post('code_cl_phc');
			$id_mst_inv_ruangan = $this->input->post('id_mst_inv_ruangan');

			$kode 	= $this->inv_ruangan_model->getSelectedData('mst_inv_ruangan',$code_cl_phc)->result();
			echo '<option>-- Pilih Ruangan --</option>';
			foreach($kode as $kode) :
				echo $select = $kode->id_mst_inv_ruangan == $id_mst_inv_ruangan ? 'selected' : '';
				echo '<option value="'.$kode->id_mst_inv_ruangan.'" '.$select.'>' . $kode->nama_ruangan . '</option>';
			endforeach;

			return FALSE;
		}

		show_404();
	}
	
	function set_filter(){
		$code_cl_phc = $this->input->post('code_cl_phc');
		$code_ruangan = $this->input->post('code_ruangan');
		
		if(!empty($code_cl_phc)){
			$this->session->set_userdata('code_cl_phc',$this->input->post('code_cl_phc'));			
		}else{
			$this->session->unset_userdata('code_ruangan');
		}
		
		if(!empty($code_ruangan)){
			$this->session->set_userdata('code_ruangan',$this->input->post('code_ruangan'));
		}else{
			$this->session->set_userdata('code_ruangan','all');
		}
	}

}
