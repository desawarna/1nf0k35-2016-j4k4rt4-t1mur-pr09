<?php
class Permohonanbarang_model extends CI_Model {

    var $tabel    = 'inv_permohonan_barang';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }
    function kode_permohonan($kode){
        $inv=explode(".", $kode);
        $kode_permohonan = $inv[0].$inv[1].$inv[2].$inv[3].$inv[4].$inv[5].$inv[6];
        $tahun          = $inv[6];
       // $id_barang      = $inv[7].$inv[8].$inv[9].$inv[10].$inv[11];
        $urut = $this->urut($kode_permohonan);
        return  $kode_permohonan.$urut;
    }
    function urut($kode){
        $jmldata = strlen($kode);
        $q = $this->db->query("select MAX(RIGHT(id_inv_permohonan_barang,6)) as kd_max from inv_permohonan_barang where (LEFT(id_inv_permohonan_barang,$jmldata))=$kode");
        $nourut="";
        if($q->num_rows()>0)
        {
            foreach($q->result() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $nourut = sprintf("%06s", $tmp);
            }
        }
        else
        {
            $nourut = "000001";
        }
        return $nourut;
    }
    function get_detail_ruang($id, $code_cl_phc){
		$this->db->where('id_inv_permohonan_barang',$id);
		$this->db->where('inv_permohonan_barang.code_cl_phc',$code_cl_phc);
		#$this->db->join('cl_phc','inv_permohonan_barang.code_cl_phc = cl_phc.code');
		$this->db->join('mst_inv_ruangan','inv_permohonan_barang.id_mst_inv_ruangan = mst_inv_ruangan.id_mst_inv_ruangan');
		$q = $this->db->get('inv_permohonan_barang',1);		
		return $q->result();
	}
    function get_data_status()
    {	
    	$this->db->where("mst_inv_pilihan.tipe",'status_pengadaan');
 		$this->db->select('mst_inv_pilihan.*');		
 		$this->db->order_by('mst_inv_pilihan.code','asc');
		$query = $this->db->get('mst_inv_pilihan');	
		return $query->result_array();	
    }
    function get_data($start=0,$limit=999999,$options=array())
    {	$puskesmas_ = 'P'.$this->session->userdata('puskesmas');
    	$this->db->select("$this->tabel.*,mst_inv_ruangan.nama_ruangan,mst_inv_pilihan.value");
    	$this->db->select("(select SUM(harga*jumlah) AS hrg FROM inv_permohonan_barang_item WHERE id_inv_permohonan_barang=inv_permohonan_barang.id_inv_permohonan_barang ) AS totalharga");
		$this->db->join('mst_inv_ruangan', "inv_permohonan_barang.id_mst_inv_ruangan = mst_inv_ruangan.id_mst_inv_ruangan and inv_permohonan_barang.code_cl_phc = mst_inv_ruangan.code_cl_phc ",'left');
		$this->db->join('mst_inv_pilihan', "inv_permohonan_barang.pilihan_status_pengadaan = mst_inv_pilihan.code AND mst_inv_pilihan.tipe='status_pengadaan'",'left');
		$this->db->order_by('inv_permohonan_barang.id_inv_permohonan_barang','desc');
		$query =$this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }
    function totalharga($kode){
    	$data = array();
    	//$this->db->where('code_cl_phc','P'.$this->session->userdata('puskesmas'));
    	$this->db->where('id_inv_permohonan_barang',$kode);
		$this->db->select("sum(harga*jumlah) as totalharga,sum(jumlah) as totaljumlah");
		$query = $this->db->get("inv_permohonan_barang_item");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
    }
    public function getItem($table,$data)
    {
        return $this->db->get_where($table, $data);
    }

 	function get_data_row($code_cl_phc,$kode){
		$data = array();
		$this->db->where("inv_permohonan_barang.code_cl_phc",$code_cl_phc);
		$this->db->where("inv_permohonan_barang.id_inv_permohonan_barang",$kode);
		$this->db->select("inv_permohonan_barang.*,cl_phc.value,mst_inv_ruangan.nama_ruangan,mst_inv_pilihan.value");
		$this->db->join('cl_phc', "inv_permohonan_barang.code_cl_phc = cl_phc.code");
		$this->db->join('mst_inv_ruangan', "inv_permohonan_barang.id_mst_inv_ruangan = mst_inv_ruangan.id_mst_inv_ruangan",'left');
        $this->db->join('mst_inv_pilihan', "inv_permohonan_barang.pilihan_status_pengadaan = mst_inv_pilihan.code AND mst_inv_pilihan.tipe='status_pengadaan'",'left');
		$query = $this->db->get("inv_permohonan_barang");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	function get_data_barang_edit($code_cl_phc, $permohonanbarang, $permohonanitem){
		$data = array();
		
		$this->db->select("*");
		$this->db->where("id_inv_permohonan_barang_item",$permohonanitem);
		$this->db->where("code_cl_phc",$code_cl_phc);
		$this->db->where("id_inv_permohonan_barang",$permohonanbarang);
		$query = $this->db->get("inv_permohonan_barang_item");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, array('id_inv_permohonan_barang'=>$data));
    }

    function get_permohonan_id($puskesmas="")
    {
    	$this->db->select('MAX(id_inv_permohonan_barang)+1 as id');
    	$this->db->where('code_cl_phc',$puskesmas);
    	$permohonan = $this->db->get('inv_permohonan_barang')->row();
    	if (empty($permohonan->id)) {
    		return 1;
    	}else {
    		return $permohonan->id;
    	}
	}
	function get_permohonanbarangitem_id($kode)
    {
    	$query  = $this->db->query("SELECT max(id_inv_permohonan_barang_item) as id from inv_permohonan_barang_item where id_inv_permohonan_barang = $kode");
    	$res = $query->result();
    	if (empty($res))
    	{
    		return 1;
    	}else {
    		foreach ($query->result() as $jum ) {
    			return $jum->id+1;
    		}
    	}

	}
   function insert_entry()
    {
    	$data['id_inv_permohonan_barang']	= $this->kode_permohonan($this->input->post('id_inv_permohonan_barang'));
    	$data['tanggal_permohonan']	= date("Y-m-d",strtotime($this->input->post('tgl')));
		$data['keterangan']			= $this->input->post('keterangan');
		$data['code_cl_phc']		= $this->input->post('codepus');
		$data['id_mst_inv_ruangan']	= $this->input->post('ruangan');

		$data['waktu_dibuat']		= date('Y-m-d');
		$data['jumlah_unit']      	= 0;
		$data['app_users_list_username'] 	= $this->session->userdata('username'); 
		//$data['id_inv_permohonan_barang']	= $this->get_permohonan_id($this->input->post('codepus'));
		if($this->db->insert($this->tabel, $data)){
			return $data['id_inv_permohonan_barang'];
		}else{
			return mysql_error();
		}
    }

    function update_entry($kode,$code_cl_phc)
    {
    	$data['tanggal_permohonan']	= date("Y-m-d",strtotime($this->input->post('tgl')));
		$data['keterangan']			= $this->input->post('keterangan');
		$data['code_cl_phc']		= $this->input->post('codepus');
		$data['id_mst_inv_ruangan']	= $this->input->post('ruangan');
        $data['pilihan_status_pengadaan'] = $this->input->post('statuspengadaan');

		$this->db->where('id_inv_permohonan_barang',$kode);
		$this->db->where('code_cl_phc',$code_cl_phc);

		if($this->db->update($this->tabel, $data)){
			return true;
		}else{
			return mysql_error();
		}
    }
    function tampil_id($status){
    	$this->db->select('code');
    	$this->db->where('value',$status);
		$this->db->where('tipe','status_pengadaan');
		$query=$this->db->get('mst_inv_pilihan');
		if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $id = $k->code;
            }
        }
        else
        {
            $id = 1;
        }
        	return  $id;
    }
    function update_status()
    {	
    	$status= $this->input->post('pilihan_status_pengadaan');
    	$data['pilihan_status_pengadaan']	= $this->tampil_id($status);
    	$id = $this->input->post('inv_permohonan_barang');
		if($this->db->update($this->tabel, $data,array('id_inv_permohonan_barang'=> $id,'code_cl_phc'=>'P'.$this->session->userdata('puskesmas')))){
			return true;
		}else{
			return mysql_error();
		}
    }
    function sum_jumlah_item($kode,$code_cl_phc){
    	$this->db->select_sum('jumlah');
    	$this->db->where('id_inv_permohonan_barang',$kode);
		$this->db->where('code_cl_phc',$code_cl_phc);
		$query=$this->db->get('inv_permohonan_barang_item');
		if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $jumlah = $k->jumlah;
            }
        }
        else
        {
            $jumlah = 0;
        }
        return  $jumlah;
    }

	function delete_entry($kode,$code_cl_phc)
	{
		$this->db->where('id_inv_permohonan_barang',$kode);
		$this->db->delete('inv_permohonan_barang_item');

		$this->db->where('id_inv_permohonan_barang',$kode);
		$this->db->where('code_cl_phc',$code_cl_phc);

		return $this->db->delete($this->tabel);

	}
	function delete_entryitem($kode,$code_cl_phc,$kode_item)
	{
		$this->db->where('id_inv_permohonan_barang',$kode);
		$this->db->where('id_inv_permohonan_barang_item',$kode_item);
		$this->db->where('code_cl_phc',$code_cl_phc);
		return $this->db->delete('inv_permohonan_barang_item');
	}
	function get_databarang($start=0,$limit=999999)
    {
		$this->db->order_by('uraian','asc');
        $query = $this->db->get('mst_inv_barang',$limit,$start);
        return $query->result();
    }
}