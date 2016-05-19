<?php
class Sts extends CI_Controller {

    public function __construct(){
		parent::__construct();
		$this->load->model('keuangan/sts_model');
	}
	function convert_tgl($tgl){
		//2015-11-12
		$dataTgl = explode('-',$tgl);
		$tgl = $dataTgl[2].'-'.$dataTgl[1].'-'.$dataTgl[0];
		return $tgl;		
	}
		
	function index(){
		header("location:sts/general");
	}

	function api_data_sts_general(){
		$this->authentication->verify('keuangan','add');		
		
		if(!empty($this->session->userdata('puskes')) and  $this->session->userdata('puskes') != '0'){
			$data['ambildata'] = $this->sts_model->get_data_keu_sts_general($this->session->userdata('puskes'));
			foreach($data['ambildata'] as $d){
				$txt = $this->convert_tgl($d["tgl"])." \t ".$d["nomor"]." \t ".$d["total"]." \t ".$d["status"]." \t <a href=\"".base_url()."keuangan/sts/detail/".$d['tgl']."\"><img border=0 src='".base_url()."media/images/16_edit.gif'></a>  \t".($d['status'] == "buka" ? "  <a onclick=\"doDeleteSts('".$d['tgl']."')\" href=\"#\" ><img border=0 src='".base_url()."media/images/16_del.gif'></a>" : "<img border=0 src='".base_url()."media/images/16_lock.gif'>").  "\n ";				
				echo $txt;
			}
		}		
	}
	
	function api_data_sts_detail($tgl){
		$this->authentication->verify('keuangan','add');		
		
		
		if(!empty($this->session->userdata('puskes')) and  $this->session->userdata('puskes') != '0'){
			$data['ambildata'] = $this->sts_model->get_data_puskesmas_isi_sts($this->session->userdata('puskes'), $tgl);
			
			foreach($data['ambildata'] as $d){
				$txt = $d["id_anggaran"]." \t ".$d["sub_id"]." \t ".$d["kode_rekening"]." \t ".$d["kode_anggaran"]." \t ".$d["uraian"]." \t ".$d["tarif"]." \t ".$d["vol"]." \t ".$d["jml"]."\n";				
				echo $txt;
			}
			
		}		
	}
	
	function generate_nomor($date){
		//120/1/IX/15
		//nomorpertahun/tgldesimal/bulanromawi/2digittahun
		$tahun = substr(date("Y",strtotime($date)),2,2);
		$dataBulan = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XII','XIII'];
		$bulan = $dataBulan[date("n", strtotime($date))-1];
		$tanggal = date("j", strtotime($date));
		
		$this->db->select('nomor');
		$this->db->where("year(tgl) = ('".date("Y",strtotime($date))."')");
		$this->db->where('code_cl_phc',$this->session->userdata('puskes'));
		$this->db->order_by('tgl','desc');
		$this->db->limit('1');
		$query=$this->db->get('keu_sts');
		$no = 1;
		if(!empty($query->result())){
			foreach($query->result() as $q ){
				$no = explode('/',$q->nomor)[0]+1;				
			}
		}
		$ready = $no."/".$tanggal."/".$bulan."/".$tahun;
		return $ready;
		
	}
	function set_puskes(){
		$this->authentication->verify('keuangan','add');
		$this->session->set_userdata('puskes',$this->input->post('puskes'));
	}
	
	
	
	function general(){
		$this->authentication->verify('keuangan','add');
		$data['data_puskesmas']	= $this->sts_model->get_data_puskesmas();
		$data['title_group'] = "Surat Tanda Setoran";
		$data['title_form'] = "Surat Tanda Setoran";
		$data['ambildata'] = $this->sts_model->get_data();
		$data['kode_rekening'] = $this->sts_model->get_data_kode_rekening_all();
		$data['nomor'] = $this->generate_nomor(date("Y-m-d H:i:s"));		
		$data['nama_puskes'] = "";
		if(!empty($this->session->userdata('puskes')) and $this->session->userdata('puskes')!= '0'){
			$data['nama_puskes'] = $this->sts_model->get_puskesmas_name($this->session->userdata('puskes'));
		}
			
		$data['content'] = $this->parser->parse("keuangan/main_sts",$data,true);						
		
		$this->template->show($data,"home");
	}

	function detail($tgl){
		$this->authentication->verify('keuangan','add');
		$data['data_puskesmas']	= $this->sts_model->get_data_puskesmas();
		$data['title_group'] = "Detail Surat Tanda Setoran";
		$data['title_form'] = "Detail Surat Tanda Setoran";
		$data['data_sts'] = $this->sts_model->get_data_sts($tgl, $this->session->userdata('puskes'));
		$data['data_sts_total'] = $this->sts_model->get_data_sts_total($tgl, $this->session->userdata('puskes'));
		//$data['ambildata'] = $this->sts_model->get_data();
		//$data['kode_rekening'] = $this->sts_model->get_data_kode_rekening();
		$data['nomor'] = $this->generate_nomor(date("Y-m-d H:i:s"));		
		$data['tgl'] = $tgl;
		$data['tgl2'] = $this->convert_tgl($tgl);
		$data['content'] = $this->parser->parse("keuangan/detail_sts",$data,true);		
				
		$this->template->show($data,"home");
	}		
	function cek_tgl_sts($tgl_input){
		$this->db->select('tgl');
		$this->db->order_by('tgl','desc');
		$this->db->limit('1');
		$this->db->where('code_cl_phc',$this->session->userdata('puskes'));
		$query = $this->db->get('keu_sts');
		
		$datetime = new DateTime('tomorrow');
		$tgl_besok = $datetime->format('Y-m-d');
		$besok = strtotime($tgl_besok);
		#$sekarang = strtotime(date('Y-m-d'));
		$exp = explode('/', $tgl_input);
		//11/26/2015
		$tgl_input = $exp['2'].'-'.$exp['0'].'-'.$exp['1'];
		$tgl_inp = strtotime($tgl_input);

		if(!empty($query->result())){
			foreach($query->result() as $q){				
				$sekarang = strtotime($q->tgl);
				#echo "sekarang".$sekarang." # ".$q->tgl."<br>";
				#echo "besok".$besok." # ".$tgl_besok." <br>";
				#echo "inpput".$tgl_inp." # ".$tgl_input." <br>";
				if($tgl_inp > $sekarang and $tgl_inp < $besok){
					return true;
				}else{
					return false;
				}
			}
		}else{
			return true;
		}
		
		
	}
	function add_sts(){		
		$this->authentication->verify('keuangan','add');
		//nomor:nomor, tgl:tanggal, code_cl_phc:code_cl_phc
		$this->form_validation->set_rules('nomor','Nomor','trim|required');
		$this->form_validation->set_rules('tgl','Tanggal','trim|required');
		$this->form_validation->set_rules('code_cl_phc','code_cl_phc','trim|required');
		
		if($this->form_validation->run()== TRUE){
			if($this->cek_tgl_sts($this->input->post('tgl'))){	
				$this->sts_model->add_sts();
				echo 0;
			}else{
				echo "Data Tanggal harus lebih dari tanggal terakhir input dan tidak lebih dari tanggal hari ini, terimakasih.";
			}
		}else{			
			echo validation_errors();
		}
		
		
		
		
	}
	
	function reopen(){
		$this->authentication->verify('keuangan','edit');
		$this->sts_model->reopen();
		echo "buka lagi";
	}
	
	function update_volume(){		
		$this->authentication->verify('keuangan','edit');		
		echo $this->sts_model->update_volume();

	}
	
	function tutup_sts(){		
		$this->authentication->verify('keuangan','edit');		
		$this->sts_model->tutup_sts();
		$this->sts_model->rekap_sts_rekening();
		#redirect(base_url().'keuangan/sts/general', 'refresh');

	}
	
	function update_ttd(){		
		$this->authentication->verify('keuangan','edit');		
		if(empty($this->input->post('delete'))){
			$this->sts_model->update_ttd();
		}
				
		$this->form_validation->set_rules('ttd_pimpinan_nama', 'Nama Pimpinan', 'trim|required');
		$this->form_validation->set_rules('ttd_penerima_nama', 'Nama Penerima', 'trim|required');
		$this->form_validation->set_rules('ttd_penyetor_nama', 'Nama Penyetor', 'trim|required');
		
		$this->form_validation->set_rules('ttd_pimpinan_nip', 'NIP Pimpinan', 'trim|required');
		$this->form_validation->set_rules('ttd_penerima_nip', 'NIP Penerima', 'trim|required');
		$this->form_validation->set_rules('ttd_penyetor_nip', 'NIP Penyetor', 'trim|required');

		if($this->form_validation->run()== FALSE){
			$this->session->set_flashdata('notif_content', validation_errors());
			$this->session->set_flashdata('notif_type', 'error');
			redirect(base_url().'keuangan/sts/detail/'.$this->input->post('tgl'));
		}else{
			
			if(!empty($this->input->post('delete'))){
				$this->sts_model->update_ttd();
				$this->tutup_sts();
				$this->session->set_flashdata('notif_type', 'closed');
				
			}else{
				$this->session->set_flashdata('notif_type', 'saved');
				
			}
			redirect(base_url().'keuangan/sts/detail/'.$this->input->post('tgl'));
		}
	
		
	}
	
	function delete_sts(){
		$this->authentication->verify('keuangan','edit');
		$tgl=$this->input->post('tgl');
		$this->sts_model->delete_sts($tgl);
		redirect(base_url().'keuangan/sts/general', 'refresh');
	}
	
	function koderekening(){
		$this->authentication->verify('keuangan','edit');
		$data['title_group'] = "Kode Anggaran";
		$data['title_form'] = "Master Data - Kode Anggaran";

		$data['content'] = $this->parser->parse("keuangan/kode_rekening",$data,true);						

		$this->template->show($data,"home");
	}
	
	function kode_rekening_json(){
		$this->authentication->verify('keuangan','edit');


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

		$rows_all = $this->sts_model->get_data_kode_rekening_all();


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

		$rows = $this->sts_model->get_data_kode_rekening_all($this->input->post('recordstartindex'), $this->input->post('pagesize'));
		$data = array();
		foreach($rows as $r) {
			$data[] = array(
				'code'			=> $r->code,
				'kode_rekening'	=> $r->kode_rekening,
				'uraian'		=> $r->uraian,
				'tipe'			=> $r->tipe,
				'edit'			=> "<a onclick=\"editform('".$r->code."','".$r->kode_rekening."','".$r->uraian."','".$r->tipe."')\" data-toggle=\"modal\" data-target=\"#myModal\" href=\"#\"><img border=0 src='".base_url()."media/images/16_edit.gif'></a>",
				'delete'		=> "<a onclick=\"delete_rekening('".$r->code."')\" href=\"#\"><img border=0 src='".base_url()."media/images/16_del.gif'></a>",
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
