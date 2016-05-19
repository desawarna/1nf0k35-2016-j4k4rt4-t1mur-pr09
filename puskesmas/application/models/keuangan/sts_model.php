<?php
class Sts_model extends CI_Model {

    var $tb    = 'keu_sts';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
    function get_data($start=0,$limit=999999,$options=array()){
		$query = $this->db->get($this->tb);		
		return $query->result();
    }

	function delete_sts($id){		
		$this->db->where('id_sts', $id);
		$this->db->delete('keu_sts');

		$this->db->where('id_sts', $id);
		$this->db->delete('keu_sts_hasil');
	}

	function add_sts(){
    	$kodepuskesmas = 'P'.$this->session->userdata('puskesmas');

		$datatgl = explode('-', $this->input->post('tgl'));
		$tgl = $datatgl[2].'-'.$datatgl[1].'-'.$datatgl[0];

		$data['id_sts'] 		 = $this->kode_sts($this->input->post('id_sts'));
		$data['code_pl_phc']	 = $this->session->userdata('puskes');
        $data['nomor']           = $this->input->post('nomor');
       	$data['tgl']             = $tgl;

		if($this->db->set('status',"draft")){
          ($this->db->insert('keu_sts', $data));
           $this->db->query(" INSERT INTO `keu_sts_hasil` (
							  SELECT `mst_keu_anggaran`.`id_mst_anggaran`,".'"'.$data['id_sts'] .'"'.",`mst_keu_anggaran`.`tarif`,0,0
							  FROM `mst_keu_versi_status`
							  LEFT JOIN `mst_keu_anggaran` ON `mst_keu_anggaran`.`id_mst_anggaran_versi` = `mst_keu_versi_status`.`id_mst_anggaran_versi` 
							  WHERE `cl_phc_code` =".'"'.$kodepuskesmas.'"'.")");
            return $data['id_sts'];
        }else{
            return mysql_error();
        }
    }

    function get_versi($id){
    	$kodepuskesmas = 'P'.$this->session->userdata('puskesmas');
        $this->db->select("mst_keu_anggaran.tarif,mst_keu_anggaran.id_mst_anggaran");
		$this->db->join('mst_keu_anggaran','mst_keu_anggaran.id_mst_anggaran_versi = mst_keu_versi_status.id_mst_anggaran_versi');
        $this->db->where("cl_phc_code",$kodepuskesmas);
        $query = $this->db->get("mst_keu_versi_status");
		return $query->result();
    }

	function kode_sts($kode){
        $kode_sts = $kode;
        $urut = $this->nourut($kode_sts);
        return $urut;
    }

    function nourut($kode_sts){
        $kodepuskesmas = 'P'.$this->session->userdata('puskesmas');
        $this->db->select("MAX(id_sts) as kd_max");
        $this->db->where("id_sts LIKE '".$kodepuskesmas."%'");
        $q = $this->db->get("keu_sts")->row();
        $nourut="";
        $kd="";
        if(!empty($q->kd_max))
        {
                $tmp = substr($q->kd_max,-5)+1;
                $kd  = sprintf("%05s", $tmp);
                $nourut = $kodepuskesmas.$kd;
        }
        else
        {
            $nourut = $kodepuskesmas."00001";
        }
        return $nourut;
    }

	function get_data_sts_total($id){
 		$this->db->select('sum(jumlah) as n');				
		$this->db->where('id_sts',$id);
		$query = $this->db->get('keu_sts_hasil');
		foreach($query->result() as $q){
			return $q->n;
		}		
    }
	
	function get_data_sts($id, $puskes){
 		$this->db->select('*');		
		$this->db->where('id_sts', $id);
		$this->db->where('code_pl_phc', $puskes);
		$this->db->join('cl_phc','cl_phc.code = keu_sts.code_pl_phc');
		$query = $this->db->get('keu_sts');		
		return $query->result_array();
    }	

	function get_data_kode_rekening(){
    	return array();
    }	
	
	function get_data_type_filter($type){		
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
			$this->db->join('keu_anggaran_tarif', "keu_anggaran_tarif.id_keu_anggaran=keu_anggaran.id_anggaran and keu_anggaran_tarif.code_pl_phc= '".$pus."'",'left');
			$this->db->where("keu_anggaran.type",'kec');
			//kecamatan
		}else{
			$this->db->join('keu_anggaran_tarif', "keu_anggaran_tarif.id_keu_anggaran=keu_anggaran.id_anggaran and keu_anggaran_tarif.code_pl_phc= '".$pus."'",'left');
			$this->db->where("keu_anggaran.type",'kel');
			//kelurahan
		}
		$this->db->join('mst_keu_rekening', "mst_keu_rekening.code=keu_anggaran.kode_rekening",'inner');
		$this->db->order_by('id_anggaran','asc');
		$query = $this->db->get('keu_anggaran');		
		return $query->result_array();
    }
	
	function get_data_puskesmas_isi_sts($id){	
 		$this->db->select('*');		
		$this->db->join('keu_sts_hasil',"keu_sts_hasil.id_sts = keu_sts.id_sts",'left');
		$this->db->join('mst_keu_anggaran', "mst_keu_anggaran.id_mst_anggaran=keu_sts_hasil.id_mst_anggaran",'left');	
        $this->db->where("keu_sts.id_sts",$id);
		$query = $this->db->get('keu_sts');		
		return $query->result_array();
    }

    function get_data_for_export($table,$id){	
		$this->db->select('*');		
		$this->db->join('keu_sts_hasil',"keu_sts_hasil.id_sts = keu_sts.id_sts",'left');
		$this->db->join('mst_keu_anggaran', "mst_keu_anggaran.id_mst_anggaran=keu_sts_hasil.id_mst_anggaran",'left');	
		return $this->db->get_where($table,$id);	
    }

    function get_data_row($id){
		$data = array();
        $this->db->where("keu_sts.id_sts",$id);
		$this->db->select('*');
		$this->db->join('keu_sts_hasil',"keu_sts_hasil.id_sts = keu_sts.id_sts",'left');
		$this->db->join('mst_keu_anggaran', "mst_keu_anggaran.id_mst_anggaran=keu_sts_hasil.id_mst_anggaran",'left');	

		$query = $this->db->get('keu_sts');
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
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

	function get_data_nama($id){
        $data = array();
        $this->db->select('*');
        $this->db->where('code',$id);
        $query=$this->db->get('cl_phc');
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
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



	
	function reopen(){
		
		$data = array(		   
		   'status' => 'draft'
		);
		$this->db->where('id_sts', $this->input->post('id_sts'));
		$this->db->where('code_pl_phc', $this->input->post('code_pl_phc'));
		
		return $this->db->update("keu_sts", $data);				
	}
	
	function cek_tarif($id_anggaran){
		$this->db->select('count(id_keu_anggaran) as n, id_keu_anggaran');
		$this->db->where('id_keu_anggaran',$id_anggaran);
		$this->db->where('code_pl_phc',$this->session->userdata('puskes'));
		$query = $this->db->get('keu_anggaran_tarif');
		
		foreach($query->result() as $q){
				
				if($q->n > 0){
					
					return $q->id_keu_anggaran;
				}else{
					return 0;
				}
		}
	}
	function cek_volume($id,$id_mst_anggaran){
		$this->db->select('count(id_sts) as n');
		$this->db->where('id_mst_anggaran', $id_mst_anggaran);
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
	function update_total_sts($id,$total){
		$data=array(
			'total'=> $total
		);
		$this->db->where('id_sts', $id);
		$this->db->update('keu_sts', $data);				
	}

	function update_volume(){
		$data = array(		   
		   'id_sts' => $this->input->post('id_sts'),
		   'id_mst_anggaran' => $this->input->post('id_mst_anggaran'),
		   'tarif' => $this->input->post('tarif'),
		   'vol' => $this->input->post('vol'),
		   'jumlah' => ($this->input->post('vol')*$this->input->post('tarif')),
		);
		if($this->cek_volume($data['id_sts'],$data['id_mst_anggaran'])){
			//update
			$this->db->where('id_mst_anggaran', $data['id_mst_anggaran']);
			$this->db->where('id_sts', $data['id_sts']);
			$this->db->update('keu_sts_hasil', $data);
		}else{
			//input
			$this->db->insert('keu_sts_hasil', $data);
			
		}
		$total = $this->get_data_sts_total($data['id_sts']);
		$this->update_total_sts($data['id_sts'], $total);
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
		$this->db->where('id_sts', $this->input->post('id_sts'));		
		$this->db->where('code_pl_phc', $this->input->post('puskes'));
		$this->db->update('keu_sts', $data);
		
	}	
	
	function tutup_sts(){
		$data = array(		   
			'id_sts' => $this->input->post('id_sts'),
			'code_pl_phc' => $this->input->post('puskes'),
			'status' => 'draft'
		);
				
		//update
		$this->db->where('id_sts', $this->input->post('id_sts'));		
		$this->db->where('code_pl_phc', $this->input->post('puskes'));
		$this->db->update('keu_sts', $data);
		
	}
	
	function rekap_sts_rekening() {

		$id = $this->input->post('id_sts');

		$this->db->select("sum(jumlah) as total, id_sts ");
		$this->db->join("mst_keu_anggaran","keu_sts_hasil.id_mst_anggaran = mst_keu_anggaran.id_mst_anggaran");
		$this->db->where("id_sts",$id);
		
		$q=$this->db->get("keu_sts_hasil");

   		if ( $q->num_rows() > 0 ) {

   			$pk   = array('id_sts'=>$id);
   			$data = array('status'=>'disetor');

      		$this->db->update('keu_sts',$data,$pk);
   		
   		} else {
   			$data     = array(
   				'id_sts'=>$id,
   				'status'=>'draft');
      		$this->db->insert('keu_sts',$data);
   		}

   		 return $q->result();
	}
	
	function add_tarif(){
		$data = array(		   
		   'id_keu_anggaran' => $this->input->post('id_anggaran'),
		   'tarif' => $this->input->post('tarif'),
		   'code_pl_phc' => $this->session->userdata('puskes')
		);
		if($this->cek_tarif($data['id_keu_anggaran']) > 0){
			$this->db->where('keu_anggaran_tarif.id_keu_anggaran', $data['id_keu_anggaran']);
			$this->db->where('keu_anggaran_tarif.code_pl_phc', $data['code_pl_phc']);
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
		$this->db->where('id_anggaran');
		return $this->db->update($this->tb, $data);				
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
	
	function delete_anggaran(){		
		$this->db->where('id_anggaran', $this->input->post('id_anggaran'));
		$this->db->delete($this->tb);
				
		$this->db->where('sub_id', $this->input->post('id_anggaran'));
		$this->db->delete($this->tb);
		
		$this->db->where('id_keu_anggaran', $this->input->post('id_anggaran'));		
		return $this->db->delete('keu_anggaran_tarif');
	}

	function get_data_kode_rekening_all($start=0,$limit=100,$options=array())
    {
		$this->db->select('*');
        $query = $this->db->get('mst_keu_akun',$limit,$start);
        return $query->result();
    }
}
