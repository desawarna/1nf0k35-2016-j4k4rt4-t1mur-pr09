<?php
class Bku_penerimaan_model extends CI_Model {

    var $tb    = 'keu_anggaran';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
    function get_data()
    {
 		$this->db->select('*');		
		$query = $this->db->get($this->tb);		
		return $query->result_array();
    } 
	
	function get_rekening_by_type($tipe)
    {
 		$this->db->select('*');		
		$this->db->where('tipe',$tipe);
		$query = $this->db->get('mst_keu_rekening');		
		return $query->result_array();
    } 
	
	function get_rekening_all()
    {
 		$this->db->select('*');			
		$query = $this->db->get('mst_keu_rekening');		
		return $query->result_array();
    } 
	
	public function getItemBku($start=0, $limit = 999999, $options=array())
    {
		if(empty($this->session->userdata('bku_penerimaan_bulan')) ){			
			$this->session->set_userdata('bku_penerimaan_bulan', date('m'));
		}
		
		if(empty($this->session->userdata('bku_penerimaan_tahun')) ){			
			$this->session->set_userdata('bku_penerimaan_tahun', date('Y'));
		} 
		$this->db->where('year(tgl)',$this->session->userdata('bku_penerimaan_tahun'));
		$this->db->where('month(tgl)',$this->session->userdata('bku_penerimaan_bulan'));
        return $this->db->get('keu_bku_penerimaan', $limit, $start)->result();
    }
	
	function get_sts_no_setor()
    {
 		$this->db->select('*');		
		$this->db->join('cl_phc','cl_phc.code = keu_sts.code_cl_phc','inner');
		$this->db->where('status', 'tutup');
		$query = $this->db->get('keu_sts');		
		
		return $query->result_array();
    }
	
	function get_data_sts_total($tgl, $puskes)
    {
 		$this->db->select('sum(jml) as n');				
		$this->db->where('tgl',$tgl);
		$this->db->where('code_cl_phc',$puskes);
		$query = $this->db->get('keu_sts_hasil');
		foreach($query->result() as $q){
			return $q->n;
		}		
    }
	
	function get_data_sts($tgl, $puskes)
    {
 		$this->db->select('*');		
		$this->db->where('tgl', $tgl);
		$this->db->where('code_cl_phc', $puskes);
		$this->db->join('cl_phc','cl_phc.code = keu_sts.code_cl_phc');
		$query = $this->db->get('keu_sts');		
		return $query->result_array();
    }	
	function get_data_kode_rekening($tipe)
    {
 		$this->db->select('*');		
		$this->db->where('tipe',$tipe);
		$query = $this->db->get("mst_keu_rekening");		
		
		return $query->result_array();
    }	
	
	function get_data_type_filter($type)
    {		
 		$this->db->select('id_anggaran, sub_id, mst_keu_rekening.code as code, mst_keu_rekening.kode_rekening as kode_rekening, mst_keu_rekening.uraian as rekening, kode_anggaran, keu_anggaran.uraian, type');		
		$this->db->join('mst_keu_rekening','mst_keu_rekening.code = keu_anggaran.kode_rekening');
		$this->db->where('type', $type);		
		$this->db->order_by('id_anggaran','asc');
		$query = $this->db->get($this->tb);		
		return $query->result_array();
    }
	
	function get_data_keu_sts_general($puskes)
    {		
 		$this->db->select('*');			
		$this->db->where('code_cl_phc', $puskes);		
		$this->db->order_by('tgl','desc');
		$query = $this->db->get('keu_sts');		
		return $query->result_array();
    }
	
	function get_data_puskesmas_filter($pus)
    {				
 		$this->db->select('keu_anggaran.*,keu_anggaran_tarif.*,mst_keu_rekening.kode_rekening AS rekening');		
		
		#$kodepuskesmas = $this->session->userdata('puskesmas');
		$kodepuskesmas = $pus;
		if(substr($kodepuskesmas, -2)=="01"){			
			$this->db->join('keu_anggaran_tarif', "keu_anggaran_tarif.id_keu_anggaran=keu_anggaran.id_anggaran and keu_anggaran_tarif.code_cl_phc= '".$pus."'",'left');
			$this->db->where("keu_anggaran.type",'kec');
			//kecamatan
		}else{
			$this->db->join('keu_anggaran_tarif', "keu_anggaran_tarif.id_keu_anggaran=keu_anggaran.id_anggaran and keu_anggaran_tarif.code_cl_phc= '".$pus."'",'left');
			$this->db->where("keu_anggaran.type",'kel');
			//kelurahan
		}
		$this->db->join('mst_keu_rekening', "mst_keu_rekening.code=keu_anggaran.kode_rekening",'inner');
		$this->db->order_by('id_anggaran','asc');
		$query = $this->db->get('keu_anggaran');		
		return $query->result_array();
    }
	
	function get_data_puskesmas_isi_sts($id_puskesmas, $tgl)
    {				
 		$this->db->select('*');		
		
		$kodepuskesmas = $this->session->userdata('puskesmas');
		$this->db->join('keu_sts_hasil',"keu_sts_hasil.id_keu_anggaran = keu_anggaran.id_anggaran and keu_sts_hasil.code_cl_phc = '".$id_puskesmas."' and tgl='".$tgl."'",'left');
		if(substr($kodepuskesmas, -2)=="01"){			
			$this->db->join('keu_anggaran_tarif', "keu_anggaran_tarif.id_keu_anggaran=keu_anggaran.id_anggaran and keu_anggaran_tarif.code_cl_phc= '".$id_puskesmas."' where keu_anggaran.type='kec'",'left');			
			//kecamatan
		}else{
			$this->db->join('keu_anggaran_tarif', "keu_anggaran_tarif.id_keu_anggaran=keu_anggaran.id_anggaran and keu_anggaran_tarif.code_cl_phc= '".$id_puskesmas."' where keu_anggaran.type='kel'",'left');
			//kelurahan
		}
		
		$this->db->order_by('id_anggaran','asc');
		$query = $this->db->get('keu_anggaran');		
		return $query->result_array();
    }
	
	function get_data_puskesmas(){
		
		
		$kodepuskesmas = $this->session->userdata('puskesmas');
		if(substr($kodepuskesmas, -2)=="01"){			
			//kecamatan
			
				
				$this->db->like('code','P'.substr($kodepuskesmas,0,7));
			
		}else{
			$this->db->like('code','P'.$kodepuskesmas);
			//kelurahan
		}
		
		$query = $this->db->get('cl_phc');
		return $query->result_array();
	}
	
	function cek_duplicate(){
		$this->db->select('count(id_anggaran) as n');
		$query = $this->db->get($this->tb);
		
		foreach($query->result() as $q){
				if($q->n > 0){
					return true;
				}else{
					return false;
				}
		}
	}
	
	function get_new_id_keu_anggaran(){
		$this->db->select('max(id_anggaran) as n');
		$query = $this->db->get($this->tb);
		
		foreach($query->result() as $q){
				return $q->n+1;
		}
	}
	
	function get_new_id_keu_anggaran_tarif(){
		$this->db->select('max(id) as n');
		$query = $this->db->get("keu_anggaran_tarif");
		
		foreach($query->result() as $q){
				return $q->n+1;
		}
	}
	
	function add_anggaran(){
		$dataExplode = explode("-",$this->input->post('kode_rekening'));
		
		$data = array(
		   'id_anggaran' => $this->get_new_id_keu_anggaran(),
		   'sub_id' => $this->input->post('sub_id') ,
		   'kode_rekening' => $dataExplode[0],
		   'kode_anggaran' => $this->input->post('kode_anggaran'),
		   'uraian' => $this->input->post('uraian'),
		   'type' => $this->session->userdata('tipe')
		);
		
		return $this->db->insert($this->tb, $data);				
	}
	
	function add_kode_rekening(){				
		$data = array(
		   'kode_rekening' => $this->input->post('kode_rekening') ,		   
		   'uraian' => $this->input->post('uraian') ,		   
		   'tipe' => $this->input->post('tipe'),		   
		);
		
		return $this->db->insert('mst_keu_rekening', $data);				
	}
	
	function update_kode_rekening(){				
		$data = array(
		   'code' => $this->input->post('code') ,		   
		   'kode_rekening' => $this->input->post('kode_rekening') ,		   
		   'uraian' => $this->input->post('uraian') ,		   
		   'tipe' => $this->input->post('tipe'),		   
		);
		$this->db->where("code",$data['code']);
		return $this->db->update('mst_keu_rekening', $data);				
	}
	
	function delete_kode_rekening(){		
		$this->db->where('code', $this->input->post('code'));
		return $this->db->delete('mst_keu_rekening');
		
	}
	
	function get_puskesmas_name($id){
		$this->db->select('value');
		$this->db->where('code', $id);
		$query = $this->db->get("cl_phc");
		if(!empty($query->result())){
			foreach($query->result() as $q){
				return $q->value;
			}
		}else{
			return "";
		}	
	}
	function add_sts(){
		$datatgl = explode('/', $this->input->post('tgl'));
		$tgl = $datatgl[2].'-'.$datatgl[0].'-'.$datatgl[1];
		$data = array(		   
		   'nomor' => $this->input->post('nomor') ,
		   'tgl' => $tgl,		   		   
		   'code_cl_phc' => $this->input->post('code_cl_phc') ,		  
		);
		
		return $this->db->insert("keu_sts", $data);				
	}
	
	function reopen(){
		
		$data = array(		   
		   'status' => 'buka'
		);
		$this->db->where('tgl', $this->input->post('tgl'));
		$this->db->where('code_cl_phc', $this->input->post('code_cl_phc'));
		
		return $this->db->update("keu_sts", $data);				
	}
	
	function cek_tarif($id_anggaran){
		$this->db->select('count(id_keu_anggaran) as n, id_keu_anggaran');
		$this->db->where('id_keu_anggaran',$id_anggaran);
		$this->db->where('code_cl_phc',$this->session->userdata('puskes'));
		$query = $this->db->get('keu_anggaran_tarif');
		
		foreach($query->result() as $q){
				
				if($q->n > 0){
					
					return $q->id_keu_anggaran;
				}else{
					return 0;
				}
		}
	}
	function cek_volume($tgl, $puskes, $id_keu_anggaran){
		$this->db->select('count(tgl) as n');
		$this->db->where('id_keu_anggaran', $id_keu_anggaran);
		$this->db->where('tgl', $tgl);
		$this->db->where('code_cl_phc', $puskes);
		$query = $this->db->get('keu_sts_hasil');
		
		foreach($query->result() as $q){
				if($q->n > 0){
					return true;
					//ready update
				}else{
					return false;
					// need input
				}
		}
		
	}
	function update_total_sts($tgl, $code_cl_phc, $total){
		$data=array(
			'total'=> $total
		);
		$this->db->where('tgl', $tgl);
		$this->db->where('code_cl_phc', $code_cl_phc);
		$this->db->update('keu_sts', $data);				
	}
	function update_volume(){
		$data = array(		   
		   'tgl' => $this->input->post('tgl'),
		   'id_keu_anggaran' => $this->input->post('id_keu_anggaran'),
		   'tarif' => $this->input->post('tarif'),
		   'vol' => $this->input->post('vol'),
		   'jml' => ($this->input->post('vol')*$this->input->post('tarif')),
		   'code_cl_phc' => $this->session->userdata('puskes')
		);
		if($this->cek_volume($data['tgl'], $data['code_cl_phc'], $data['id_keu_anggaran'])){
			//update
			$this->db->where('id_keu_anggaran', $data['id_keu_anggaran']);
			$this->db->where('tgl', $data['tgl']);
			$this->db->where('code_cl_phc', $data['code_cl_phc']);
			$this->db->update('keu_sts_hasil', $data);
		}else{
			//input
			$this->db->insert('keu_sts_hasil', $data);
			
		}
		$total = $this->get_data_sts_total($data['tgl'], $data['code_cl_phc']);
		$this->update_total_sts($data['tgl'], $data['code_cl_phc'], $total);
		return $total;
		
		
	}
	function update_ttd(){
		$data = array(		   
		   'ttd_pimpinan_nip' => $this->input->post('ttd_pimpinan_nip'),
		   'ttd_pimpinan_nama' => $this->input->post('ttd_pimpinan_nama'),
		   'ttd_penerima_nip' => $this->input->post('ttd_penerima_nip'),
		   'ttd_penerima_nama' => $this->input->post('ttd_penerima_nama'),
		   'ttd_penyetor_nip' => $this->input->post('ttd_penyetor_nip'),
		   'ttd_penyetor_nama' => $this->input->post('ttd_penyetor_nama')
		);
				
		//update
		$this->db->where('tgl', $this->input->post('tgl'));		
		$this->db->where('code_cl_phc', $this->input->post('puskes'));
		$this->db->update('keu_sts', $data);
		
	}	
	
	function tutup_sts(){
		$data = array(		   
			'tgl' => $this->input->post('tgl'),
			'code_cl_phc' => $this->input->post('puskes'),
			'status' => 'tutup'
		);
				
		//update
		$this->db->where('tgl', $this->input->post('tgl'));		
		$this->db->where('code_cl_phc', $this->input->post('puskes'));
		$this->db->update('keu_sts', $data);
		
	}
	
	
	function cek_rekap_sts_rekening($tgl, $kode_rekening, $code_cl_phc){
		$this->db->select('count(tgl) as n');
		$this->db->where('tgl',$tgl);
		$this->db->where('code_mst_keu_rekening',$kode_rekening);
		$this->db->where('code_cl_phc',$code_cl_phc);
		$q = $this->db->get('keu_sts_hasil_rekap');
		foreach($q->result() as $r){
			if($r->n > 0){
				echo "update";
				return 'update';
			}else{
				echo "input";
				return 'input';
			}
		}
	}
	function rekap_sts_rekening(){
		$tgl = $this->input->post('tgl');
		$code_cl_phc = $this->input->post('puskes');
		$this->db->select("sum(jml) as total, kode_rekening, tgl, code_cl_phc ");
		$this->db->join("keu_anggaran","keu_sts_hasil.id_keu_anggaran = keu_anggaran.id_anggaran");
		$this->db->where("tgl",$tgl);
		$this->db->where("code_cl_phc",$code_cl_phc);
		$this->db->group_by("kode_rekening");
		$query=$this->db->get("keu_sts_hasil");
		
		if(!empty($query->result())){
			foreach($query->result() as $q){
				#echo $q->kode_rekening." # ".$q->total." # ".$q->code_cl_phc." # ".$q->tgl."<br>";
				
								
				$data = array(		   
					'tgl' => $q->tgl,
					'code_cl_phc' => $q->code_cl_phc,
					'jml' => $q->total,
					'code_mst_keu_rekening' => $q->kode_rekening
				);
				if($this->cek_rekap_sts_rekening($q->tgl, $q->kode_rekening, $q->code_cl_phc) == 'input'){
					$this->db->insert('keu_sts_hasil_rekap', $data);
				}else{
					$this->db->where('tgl',$q->tgl);
					$this->db->where('code_mst_keu_rekening',$q->kode_rekening);
					$this->db->where('code_cl_phc',$q->code_cl_phc);
					$this->db->update('keu_sts_hasil_rekap', $data);
				}
				
				
				
			}
		}
	
	}
	
	function add_tarif(){
		$data = array(		   
		   'id_keu_anggaran' => $this->input->post('id_anggaran'),
		   'tarif' => $this->input->post('tarif'),
		   'code_cl_phc' => $this->session->userdata('puskes')
		);
		if($this->cek_tarif($data['id_keu_anggaran']) > 0){
			$this->db->where('keu_anggaran_tarif.id_keu_anggaran', $data['id_keu_anggaran']);
			$this->db->where('keu_anggaran_tarif.code_cl_phc', $data['code_cl_phc']);
			return $this->db->update('keu_anggaran_tarif', $data);
		}else{
			return $this->db->insert('keu_anggaran_tarif', $data);
		}
	}
	
	function update_anggaran(){
		$dataExplode = explode("-",$this->input->post('kode_rekening'));
		$data = array(
		   'id_anggaran' => $this->input->post('id_anggaran') ,
		   'sub_id' => $this->input->post('sub_id') ,
		   'kode_rekening' => $dataExplode[0],
		   'kode_anggaran' => $this->input->post('kode_anggaran'),
		   'uraian' => $this->input->post('uraian'),
		   'type' => $this->session->userdata('tipe')
		);
		$this->db->where('id_anggaran', $this->input->post('id_anggaran_awal'));
		return $this->db->update($this->tb, $data);				
	}
			
	function delete_anggaran(){		
		$this->db->where('id_anggaran', $this->input->post('id_anggaran'));
		$this->db->delete($this->tb);
				
		$this->db->where('sub_id', $this->input->post('id_anggaran'));
		return $this->db->delete($this->tb);
	}
	
	function delete_sts($tgl){		
		$this->db->where('tgl', $tgl);
		$this->db->where('code_cl_phc', $this->session->userdata('puskes'));
		$this->db->delete('keu_sts');
		
		$this->db->where('tgl', $tgl);
		$this->db->where('code_cl_phc', $this->session->userdata('puskes'));
		$this->db->delete('keu_sts_hasil');
		
	}
	
	function get_data_kode_rekening_all($start=0,$limit=100,$options=array())
    {
		$this->db->select('*');
        $query = $this->db->get('mst_keu_rekening',$limit,$start);
        return $query->result();
    }
	
	function rekap_bku_sts($tgl_rekap, $tgl_sts, $code_cl_phc){
		$this->db->select('*');
		$this->db->where('tgl', $tgl_sts);
		$this->db->where('code_cl_phc', $code_cl_phc);
		$query=$this->db->get('keu_sts_hasil_rekap');
		foreach($query->result() as $q){
			$this->rekap_bku_rekening($q->code_mst_keu_rekening, $q->jml, $tgl_rekap);
		}
		
	}
	function rekap_bku($masuk, $keluar, $tgl){
		$p = explode('-',$tgl);
		#2015-11-12
		$y = $p[0];
		$m = $y."-".$p[1];
		$t = $m."-".$p[2];
		
		$periode = array($y, $m, $t);
		for($i=0; $i<count($periode); $i++){
			$this->db->select('*');
			$this->db->where('periode',$periode[$i]);
			$dataCek = $this->db->get('keu_bku_penerimaan_rekap');
			if(empty($dataCek->result())){
				$values = array(
					'periode' => $periode[$i],
					'total_pemasukan' => $masuk,
					'total_pengeluaran' => $keluar,
					'saldo' => $masuk - $keluar
				);
				
				$this->db->insert('keu_bku_penerimaan_rekap', $values);
			}else{
				foreach($dataCek->result() as $dc){
					$values = array(					
						'total_pemasukan' => $dc->total_pemasukan + $masuk,
						'total_pengeluaran' => $dc->total_pengeluaran + $keluar,
						'saldo' => $dc->saldo + $masuk - $keluar					
					);
					$this->db->where('periode',$periode[$i]);
					$this->db->update('keu_bku_penerimaan_rekap', $values);
				}
				
			}
			
		}
		
	}
	
	function rekap_bku_rekening($kode_rekening, $jml, $tgl){
		$p = explode('-',$tgl);
		#2015-11-12
		$y = $p[0];
		$m = $y."-".$p[1];
		$t = $m."-".$p[2];
		
		$periode = array($y, $m, $t);
		for($i=0; $i<count($periode); $i++){
			$this->db->select('*');
			$this->db->where('periode',$periode[$i]);
			$this->db->where('code_mst_keu_rekening',$kode_rekening);
			$dataCek = $this->db->get('keu_bku_penerimaan_rekap_rekening');
			if(empty($dataCek->result())){
				$values = array(
					'periode' => $periode[$i],
					'code_mst_keu_rekening' => $kode_rekening,
					'jumlah' => $jml,					
				);
				
				$this->db->insert('keu_bku_penerimaan_rekap_rekening', $values);
			}else{
				foreach($dataCek->result() as $dc){
					$values = array(					
						'jumlah' => $dc->jumlah+$jml,					
					);
					$this->db->where('periode',$periode[$i]);
					$this->db->update('keu_bku_penerimaan_rekap_rekening', $values);
				}
				
			}
			
		}
		
	}
	
	function bku_delete(){
		$data_tgl = $this->input->post('tgl');
		$data_bku = $this->input->post('id_bku');
		
		$this->db->select('*');
		$this->db->where('id_bku', $data_bku);
		$this->db->where('tgl', $data_tgl);
		$q = $this->db->get('keu_bku_penerimaan');
		
		foreach($q->result() as $r){
			$this->rekap_bku($r->penerimaan * -1, $r->pengeluaran * -1, $r->tgl);
			if(!empty($r->kode_rekening) and $r->kode_rekening > 0){
				
				$this->rekap_bku_rekening($r->kode_rekening, ($r->penerimaan - $r->pengeluaran)*-1, $r->tgl);
			}
			
			if($r->pengeluaran > 0){
				$data_update=array(
					'is_setor' => 0
				);
				
				$this->db->where('is_setor',$r->id_bku.'#'.$r->tgl);
				$this->db->update('keu_bku_penerimaan', $data_update);
			}
			
		}
		
		
		$this->db->where('id_bku',$data_bku);
		$this->db->where('tgl',$data_tgl);
		$this->db->delete('keu_bku_penerimaan');
	}
	
	function bku_setor(){
		$total = 0;
		$data_sumber = "";
		for($i=0; $i<count($this->input->post('data_all')); $i++){
			$d = explode('z', $this->input->post('data_all')[$i]);
			$this->db->select('penerimaan');
			$this->db->where('tgl',$d[1]);
			$this->db->where('id_bku', $d[0]);
			$q = $this->db->get('keu_bku_penerimaan');
			foreach($q->result() as $r){
				$total = $total + $r->penerimaan;
			}
			$data_sumber = $data_sumber.$this->input->post('data_all')[$i].'~';
		}
		//disetor melalui bank
		echo $total."x".$data_sumber;
	}
	
	function bku_add(){
			$tipe_bku = 'x';
			#echo $this->input->post('data_detail');
			
			if($this->input->post('tipe')=='penerimaan'){
				$penerimaan = $this->input->post('jumlah');
				$pengeluaran = 0;
			}else{
				$penerimaan = 0;
				$pengeluaran = $this->input->post('jumlah');
			}
								
			$t=explode('/', $this->input->post('tgl'));
			
			$tgl = $t[2].'-'.$t[1].'-'.$t[0];
			$this->db->select('max(id_bku) as n');
			$this->db->where('tgl', $tgl);
			$q = $this->db->get('keu_bku_penerimaan')->result();
			$id = 1;
			foreach($q as $r){
				$id = $r->n + 1;
			}
			
			#2015-11-12#P3172100103#KEL. UTAN KAYU UTARA#2400.00
			if(!empty($this->input->post('sts'))){
				//ada sts
				$dataSts = explode('#', $this->input->post('sts'));
				$this->rekap_bku_sts($tgl, $dataSts[0], $dataSts[1]);
				$tglSts = $dataSts[0];
				$codeSts = $dataSts[1];
				$data=array(
					'status' => 'setor'
				);
				$this->db->where('tgl', $dataSts[0]);
				$this->db->where('code_cl_phc', $dataSts[1]);
				$this->db->update('keu_sts',$data);
			}else{
				$tglSts = null;
				$codeSts = null;
			}
			
			
			if($this->input->post('data_detail') != '0'){
				#3|2015-11-17|0~6|2015-11-17|0~1|2015-11-18|0~
				#echo $this->input->post('data_detail');
				$d = explode('~',$this->input->post('data_detail'));
				for($i=0; $i< count($d)-1; $i++){
										
					#id|tgl|is_bku
					$d2 = explode('z', $d[$i]);
					
					$data_update = array(
						'is_setor' => $id.'#'.$tgl
					);
					
					$this->db->where('id_bku', $d2[0]);
					$this->db->where('tgl', $d2[1]);
					$this->db->update('keu_bku_penerimaan',$data_update);
					
					$tipe_bku = $d2[2];
				}
			}
			//input rekap disini pake $tgl 2015-11-12
			if(!empty($this->input->post('kode_rekening')) and $this->input->post('kode_rekening') > 0){
				$this->rekap_bku_rekening($this->input->post('kode_rekening'), $penerimaan-$pengeluaran, $tgl);
			}
			
			$this->rekap_bku($penerimaan, $pengeluaran, $tgl);
	
			if($tglSts == null and $tipe_bku == '0'){
				$is_bku = 1;
			}else{
				$is_bku = 0;
			}
			$values = array(
				'id_bku' => $id,
				'tgl' => $tgl,
				'uraian'=> $this->input->post('uraian'),
				'kode_rekening' => $this->input->post('kode_rekening'),
				'catatan' => $this->input->post('catatan'),
				'penerimaan' => $penerimaan,
				'pengeluaran' => $pengeluaran,
				'tgl_keu_sts' => $tglSts,
				'code_cl_phc_keu_sts' => $codeSts,
				'is_bku' => $is_bku
			);
			
			return $this->db->insert('keu_bku_penerimaan', $values);
	}
}
