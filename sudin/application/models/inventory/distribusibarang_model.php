<?php
class Distribusibarang_model extends CI_Model {

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    
	function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->distinct();
        $this->db->select('inv_inventaris_barang.id_inventaris_barang, id_mst_inv_barang, register, nama_barang, harga, pilihan_keadaan_barang, value as val');
		$this->db->join('mst_inv_pilihan','mst_inv_pilihan.code=inv_inventaris_barang.pilihan_keadaan_barang');


		$kodepuskesmas = $this->session->userdata('code_cl_phc');
		$code_cl_phc = $this->session->userdata('code_cl_phc');////$this->session->userdata('code_cl_phc');
		$code_ruangan = $this->session->userdata('code_ruangan');

		if($this->session->userdata('code_ruangan') == 'none'){ //belum distribusi
			//if(substr($kodepuskesmas, -2)=="01"){
				$this->db->where('inv_inventaris_barang.id_inventaris_barang NOT IN (SELECT DISTINCT id_inventaris_barang FROM inv_inventaris_distribusi) ');
				if ($this->session->userdata('code_cl_phc')=='all') {
					
				}else{
					$this->db->where('inv_inventaris_barang.code_cl_phc',$this->session->userdata('code_cl_phc'));
				}
				
	        	$this->db->join('inv_inventaris_distribusi','inv_inventaris_distribusi.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang','left');
	        	$this->db->join('inv_pengadaan',"inv_inventaris_barang.id_pengadaan = inv_pengadaan.id_pengadaan AND inv_pengadaan.pilihan_status_pengadaan ='4'");
			/*}else {
				$this->db->where('inv_inventaris_distribusi.id_cl_phc',$this->session->userdata('code_cl_phc'));
	        	$this->db->join('inv_inventaris_distribusi','inv_inventaris_distribusi.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang','inner');
			}*/
		}
		elseif(!empty($code_cl_phc)){		//seluruh dan per ruangan
			if ($this->session->userdata('code_cl_phc')=='all') {
				
			}else{
				$this->db->where('inv_inventaris_distribusi.id_cl_phc',$this->session->userdata('code_cl_phc'));	
			}

			
			$this->db->where('inv_inventaris_distribusi.status','1');
			
			if(!empty($code_ruangan) and $code_ruangan == 'all'){ //semua ruangan
			}else{ //per ruangan
				if ($code_ruangan!='0') {
					$this->db->where('inv_inventaris_distribusi.id_ruangan',$this->session->userdata('code_ruangan'));
				}
				
			}			
        	$this->db->join('inv_inventaris_distribusi','inv_inventaris_distribusi.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang','inner');
			
		}else{
			$this->db->where('inv_inventaris_distribusi.status','1');
			$this->db->where('inv_inventaris_distribusi.id_ruangan > 0');
        	$this->db->join('inv_inventaris_distribusi','inv_inventaris_distribusi.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang','inner');
		}
		
        
        $query = $this->db->get('inv_inventaris_barang',$limit,$start);
        return $query->result();
    }
	
	function get_count($id_cl_phc="",$id_ruangan=""){
		if($id_cl_phc=="none"){
			return " ";
		}else{
			if ($id_ruangan=="belum") {
				$this->db->where("inv_inventaris_barang.id_inventaris_barang NOT IN (SELECT DISTINCT id_inventaris_barang FROM inv_inventaris_distribusi) ");
				if ($id_cl_phc=='all') {
					# code...
				}else{
					$this->db->where('inv_inventaris_barang.code_cl_phc',$id_cl_phc);	
				}
				
				//$this->db->join('inv_pengadaan',"'inv_inventaris_barang.id_pengadaan = inv_pengadaan.id_pengadaan AND inv_inventaris_barang.code_cl_phc ='".$puskes."'");
				$this->db->join('inv_pengadaan',"inv_inventaris_barang.id_pengadaan = inv_pengadaan.id_pengadaan AND inv_pengadaan.pilihan_status_pengadaan ='4'");
        		$count = $query = $this->db->get('inv_inventaris_barang')->num_rows();
				return "(".$count.")";
			}
			else if(($id_ruangan!="" && $id_cl_phc!="")){ //per ruangan
				$this->db->where('id_cl_phc', $id_cl_phc);
				$this->db->where('id_ruangan', $id_ruangan);
				$this->db->where('status', 1);
        		$this->db->join('inv_inventaris_barang','inv_inventaris_distribusi.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang','inner');
		        $count = $this->db->get('inv_inventaris_distribusi')->num_rows();
				return "(".$count.")";
			}elseif($id_cl_phc!=""){ //seluruhh ruangan 
				if ($id_cl_phc=='all') {
					# code...
				}else{
					$this->db->where('inv_inventaris_distribusi.id_cl_phc', $id_cl_phc);	
				}
				
				$this->db->where('status', 1);
        		$this->db->join('inv_inventaris_barang','inv_inventaris_distribusi.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang','inner');
		        $count = $this->db->get('inv_inventaris_distribusi')->num_rows();
				return "(".$count.")";
			}
			else{ //belum distribusi
				$puskes =$this->session->userdata('code_cl_phc');//
				$this->db->where("inv_inventaris_barang.id_inventaris_barang NOT IN (SELECT DISTINCT id_inventaris_barang FROM inv_inventaris_distribusi) ");
				$this->db->where('inv_inventaris_barang.code_cl_phc',$this->session->userdata('code_cl_phc'));
				//$this->db->join('inv_pengadaan',"'inv_inventaris_barang.id_pengadaan = inv_pengadaan.id_pengadaan AND inv_inventaris_barang.code_cl_phc ='".$puskes."'");
				$this->db->join('inv_pengadaan',"inv_inventaris_barang.id_pengadaan = inv_pengadaan.id_pengadaan AND inv_pengadaan.pilihan_status_pengadaan ='4'");
        		$count = $query = $this->db->get('inv_inventaris_barang')->num_rows();
				return "(".$count.")";
			}
		}
	}

	function get_kembar_id($id){
		$this->db->select('barang_kembar_proc');
		$this->db->where('id_inventaris_barang', $id);
		$this->db->limit(1);
		$q=$this->db->get('inv_inventaris_barang');
		$result = "";
		foreach($q->result() as $r){
			$result = $r->barang_kembar_proc;
		}
		return $result;
		
	}

	function get_register($id_barang, $id_ruangan, $code_cl_phc){
		$id_kembar = $this->get_kembar_id($id_barang);
		$this->db->select('register');
		if($id_ruangan!='') $this->db->where('id_ruangan', $id_ruangan);
		else $this->db->where('id_ruangan IS NULL');
		$this->db->where('id_cl_phc', $code_cl_phc);
		$this->db->where('barang_kembar_inv', $id_kembar);
		$this->db->where('status', '1');
		$q = $this->db->get('inv_inventaris_distribusi');
		$result = "";
		foreach($q->result() as $r){
			$result = $r->register;
		}
		
		$reg = intval(ltrim($result, '0'));
		$reg =  $reg+1;
		$result_reg = str_pad($reg, 4, '0', STR_PAD_LEFT);
		return $result_reg;
	}
	
	function get_id_distribusi($id_barang){

		$q = $this->db->query('SELECT
				if(isnull(max(id_inventaris_distribusi)+1),1,max(id_inventaris_distribusi)+1) as new_id
			FROM
				inv_inventaris_distribusi
			WHERE
				id_inventaris_barang = ?
			',array($id_barang));
		$result = "1";
		foreach($q->result() as $r){
			$result = $r->new_id;
		}
		return $result;
	}
	
	function add_distribusi(){
		$db = explode('_tr_',$this->input->post('data_barang'));
		for($i=0; $i<(count($db))-1; $i++){
			$barang = explode('_td_', $db[$i]);
			$val_update = array(
				'status' => '0',				
			);
			$this->db->where('id_inventaris_barang', $barang[0]);
			$this->db->update('inv_inventaris_distribusi', $val_update);
			
			//$reg = $this->get_register($barang[0], $this->input->post('code_ruangan2'), $this->input->post('code_cl_phc2'));
			$id_kembar = $this->get_kembar_id($barang[0]);
			#105_td_Kopi_td_B	
			$t = explode('-',$this->input->post('tanggal'));
			$tgl = $t[2].'-'.$t[1].'-'.$t[0];

			$values = array(
				'id_inventaris_distribusi'=>$this->get_id_distribusi($barang[0]),
				'id_inventaris_barang' => $barang[0],
				//'register' => $reg,
				'id_cl_phc' => $this->input->post('code_cl_phc2'),
				'id_ruangan' => $this->input->post('code_ruangan2'),
				'barang_kembar_inv' => $id_kembar,
				'tgl_distribusi' => $tgl, 
				'status' => 1
			);
			$this->db->insert('inv_inventaris_distribusi', $values);
			
			
		}
		return true;
		
		
	}
	
	function get_pilihan_kondisi(){
		$this->db->select('code as id,value as val');
		$this->db->where('tipe','keadaan_barang');
		$q = $this->db->get('mst_inv_pilihan');
		return $q->result();
		
	}
	
	function update_register(){
		$data=array(
			'register' => $result_reg = str_pad($this->input->post('register'), 4, '0', STR_PAD_LEFT)
		);
		$this->db->where('id_ruangan', $this->input->post('id_ruang'));
		$this->db->where('id_cl_phc', $this->input->post('code_cl_phc'));
		$this->db->where('id_inventaris_barang', $this->input->post('id_barang'));
		$this->db->where('status', '1');
		return $this->db->update('inv_inventaris_distribusi',$data);
		
	}

	function get_id_keadaan($id_barang){
		$q=$this->db->query('SELECT
							if(isnull(max(id_keadaan_barang)+1),1,max(id_keadaan_barang)+1) as new_id
							FROM inv_keadaan_barang');
		$res = $q->result();
		
		$id = 1;
		if(!empty($res)){
			foreach($q->result() as $r){
				$id = $r->new_id;
			}
		}		
		
		return $id;
			
	}

	function update_kondisi(){
		//update barang
		$kondisi = explode(' - ', $this->input->post('kondisi'));
		#$kondisi = $this->input->post('kondisi')
		$data_update=array(
			'pilihan_keadaan_barang' => $kondisi[0]
		);
		$this->db->where('id_inventaris_barang', $this->input->post('id_barang'));
		$this->db->update('inv_inventaris_barang',$data_update);
		
		//input keadaan_barang
		$data_input=array(
			'id_keadaan_barang'=>$this->get_id_keadaan('id_barang'),
			'id_inventaris_barang'=>$this->input->post('id_barang'),
			'pilihan_keadaan_barang'=>$kondisi[0],
			'tanggal'=>date('Y-m-d')
		);
		
		return $this->db->insert('inv_keadaan_barang', $data_input);		
		
	}
}