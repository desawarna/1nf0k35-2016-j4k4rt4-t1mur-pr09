<?php
class Master_sts extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('keuangan/sts_model');
	}

	function index(){
		header("location:master_sts/anggaran");
	}

	function api_data(){
		$this->authentication->verify('keuangan','edit');		
		
		if(empty($this->session->userdata('tipe'))){
			$this->session->userdata('tipe','kec');
		}
		
		$data['ambildata'] = $this->sts_model->get_data_type_filter($this->session->userdata('tipe'));
		foreach($data['ambildata'] as $d){
			$txt = $d["id_anggaran"]." \t ".$d["sub_id"]." \t ".$d["kode_rekening"]."-".$d["rekening"]." \t ".$d["kode_anggaran"]." \t ".$d["uraian"]." \t ".$d["type"]." \n";				
			echo $txt;
		}
		
	}
	
	function api_data_tarif(){
		$this->authentication->verify('keuangan','edit');		
		
		if(!empty($this->session->userdata('puskes')) and  $this->session->userdata('puskes') != '0'){
			$data['ambildata'] = $this->sts_model->get_data_puskesmas_filter($this->session->userdata('puskes'));
			$i=0;
			foreach($data['ambildata'] as $d){
				$txt = $d["id_anggaran"]." \t ".$d["sub_id"]." \t ".$d["rekening"]." \t ".$d["kode_anggaran"]." \t ".$d["uraian"]." \t ".$d["type"]." \t".$i++." \t".$d["id_keu_anggaran"]." \t".$d["tarif"]." \t".$d["code_cl_phc"]." \n";				
				echo $txt;
			}
		}
		
	}
	
	function set_type(){
		$this->authentication->verify('keuangan','edit');
		$this->session->set_userdata('tipe',$this->input->post('tipe'));
	}
	
	function set_puskes(){
		$this->authentication->verify('keuangan','edit');
		$this->session->set_userdata('puskes',$this->input->post('puskes'));		
		
	}
	function anggaran(){
		$this->authentication->verify('keuangan','edit');
		$data['title_group'] = "Anggaran";
		$data['title_form'] = "Master Data - Anggaran";
		$data['ambildata'] = $this->sts_model->get_data();
		$data['kode_rekening'] = $this->sts_model->get_data_kode_rekening();
		$data['content'] = $this->parser->parse("keuangan/anggaran",$data,true);		
		
		$this->template->show($data,"home");
	}
	function anggaran_tarif(){
		$this->authentication->verify('keuangan','edit');
		$data['title_group'] = "Tarif Anggaran";
		$data['title_form'] = "Master Data - Tarif Anggaran ";
		$data['ambildata'] = $this->sts_model->get_data();
		$data['data_puskesmas']	= $this->sts_model->get_data_puskesmas();
		$data['content'] = $this->parser->parse("keuangan/anggaran_tarif",$data,true);					
		$this->template->show($data,"home");
	}
	
	function add_tarif(){
		$this->authentication->verify('keuangan','add');
		
		$this->form_validation->set_rules('id_anggaran','ID Anggaran','trim|required');
		$this->form_validation->set_rules('tarif','Tarif','trim|required');
				
		if($this->form_validation->run()== TRUE){
			$this->sts_model->add_tarif();	
			echo "0";
		}else{			
			echo validation_errors();
		}	
		
					
	}
	
	function anggaran_add(){
		$this->authentication->verify('keuangan','add');
		$this->form_validation->set_rules('sub_id','sub_id','trim|required');
		$this->form_validation->set_rules('kode_anggaran','Kode Anggaran','trim|required');
		$this->form_validation->set_rules('kode_rekening','Kode Rekening','trim|required');
		if($this->form_validation->run()== TRUE){
			$this->sts_model->add_anggaran();	
			echo "0";
		}else{			
			echo validation_errors();
		}	
				
		
	}
	function anggaran_update(){
		$this->authentication->verify('keuangan','edit');		
		
		$this->form_validation->set_rules('kode_rekening','Kode Rekening','trim|required');
		$this->form_validation->set_rules('id_anggaran','ID Anggaran','trim|required');
		$this->form_validation->set_rules('sub_id','Sub Id','trim|required');
		$this->form_validation->set_rules('kode_anggaran','Kode Anggaran','trim|required');
		$this->form_validation->set_rules('uraian','Uraian','trim|required');		
		
		if($this->form_validation->run()== TRUE){
			$this->sts_model->update_anggaran();
			echo "0";			
		}else{						
			echo validation_errors();
		}
		
	}
	function anggaran_delete(){
		$this->authentication->verify('keuangan','del');
		$this->sts_model->delete_anggaran();				
	}
	
	function kode_rekening_add(){
		#var_dump($_POST);
		$this->authentication->verify('keuangan','add');
		
		$this->form_validation->set_rules('kode_rekening','Kode Rekening','trim|required');
		$this->form_validation->set_rules('uraian','Uraian Anggaran','trim|required');
		$this->form_validation->set_rules('tipe','Tipe Rekening','trim|required');
		
		if($this->form_validation->run()== TRUE){
			$this->sts_model->add_kode_rekening();
			echo "0";
		}else{			
			echo validation_errors();
		}	
	}
	
	function kode_rekening_update(){
		#var_dump($_POST);
		$this->authentication->verify('keuangan','edit');
		$this->form_validation->set_rules('kode_rekening','Kode Rekening','trim|required');
		$this->form_validation->set_rules('uraian','Uraian Anggaran','trim|required');
		$this->form_validation->set_rules('tipe','Tipe Rekening','trim|required');
		$this->form_validation->set_rules('code','Tipe Rekening','trim|required');
				
		if($this->form_validation->run()== TRUE){
			$this->sts_model->update_kode_rekening();
			echo "0";
		}else{			
			echo validation_errors();
		}		
	}
	
	function kode_rekening_delete(){
		#var_dump($_POST);
		$this->authentication->verify('keuangan','edit');		
		$this->form_validation->set_rules('code','Tipe Rekening','trim|required');
		
		if($this->form_validation->run()== TRUE){
			$this->sts_model->delete_kode_rekening();
		}else{
			echo "ups";
		}	
	}




}
