<?php
class Lap_lplpo extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->add_package_path(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/demo/tbs_class.php');
		require_once(APPPATH.'third_party/tbs_plugin_opentbs_1.8.0/tbs_plugin_opentbs.php');		
		
		$this->load->model('admin_config_model');
		$this->load->model('mst/puskesmas_model');
		$this->load->model('inventory/inv_ruangan_model');
	}

	function index(){
		$this->authentication->verify('inventory','edit');

		$data = $this->admin_config_model->get_data(); 

		$data['title_group'] 	= "Laporan";
		$data['title_form'] 	= "L P L P O";;
		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(strlen($kodepuskesmas) == 4){
			$this->db->like('code','P'.substr($kodepuskesmas, 0,4));
		}else {
			$this->db->where('code','P'.$kodepuskesmas);
		}

		$data['bulan'] 	= array(
			'01'=>'Januari',
			'02'=>'Februari',
			'03'=>'Maret',
			'04'=>'April',
			'05'=>'Mei',
			'06'=>'Juni',
			'07'=>'Juli',
			'08'=>'Agustus',
			'09'=>'September',
			'10'=>'Oktober',
			'11'=>'November',
			'12'=>'Desember',
			);
		$tahun = array();
		for($i=date('Y');$i>(date('Y')-10);$i--){
			$tahun[$i] = $i;
		}
		$data['tahun'] 			= $tahun;
		$data['kodepuskesmas'] 	= $kodepuskesmas;
		$data['datapuskesmas'] 	= $this->inv_ruangan_model->get_data_puskesmas();
		$data['content'] = $this->parser->parse("inventory/lap_lplpo/detail",$data,true);

		$this->template->show($data,"home");
	}
}
