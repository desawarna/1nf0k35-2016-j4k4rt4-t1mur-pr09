<?php
class Bhp_pengadaan_model extends CI_Model {

    var $tabel    = 'inv_inventaris_habispakai_pembelian';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');

    }
    function cekdelete($habispakai,$batch)
    {
        $this->db->where('inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai',$habispakai);
        $this->db->where('inv_inventaris_habispakai_pembelian_item.batch',$batch);
        $this->db->where('inv_inventaris_habispakai_distribusi.tgl_distribusi <= inv_inventaris_habispakai_pembelian_item.tgl_update');
        $this->db->select('inv_inventaris_habispakai_distribusi_item.*');
        $this->db->join('inv_inventaris_habispakai_distribusi_item','inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_distribusi_item.id_mst_inv_barang_habispakai AND  inv_inventaris_habispakai_pembelian_item.batch = inv_inventaris_habispakai_distribusi_item.batch','left');
        $this->db->join('inv_inventaris_habispakai_distribusi','inv_inventaris_habispakai_distribusi.id_inv_inventaris_habispakai_distribusi = inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi');
        $query = $this->db->get('inv_inventaris_habispakai_pembelian_item');
        if($query->num_rows() > 0 ){
            return 1;
        }else{
            return 0;
        }
    }
    function insertdata($kode=0,$code_cl_phc='')
    {
        
        $this->db->where("id_inv_hasbispakai_pembelian",$this->input->post('id_permohonan_barang'));
        $this->db->where("id_mst_inv_barang_habispakai",$this->input->post('id_mst_inv_barang'));
        $cek=$this->db->get("inv_inventaris_habispakai_pembelian_item");
        if ($cek->num_rows() > 0) {
            return false;
        }else{
            if(!empty($this->input->post('obat'))&&($this->input->post('obat')=="8")){
                $tgl_kadaluarsa = explode("-", $this->input->post('tgl_kadaluarsa'));
                $batch = $this->input->post('batch');
            }else{
                $tgl_kadaluarsa = explode("-", "00-00-0000");
                $batch = "-";
            }
            $values = array(
                        'id_inv_hasbispakai_pembelian'=>$this->input->post('id_permohonan_barang'),
                        'id_mst_inv_barang_habispakai'=> $this->input->post('id_mst_inv_barang'),
                        'batch' => $batch,
                        'jml' => $this->input->post('jumlah'),
                        'jml_rusak' => $this->input->post('jml_rusak'),
                        'tgl_kadaluarsa' => $tgl_kadaluarsa[2]."-".$tgl_kadaluarsa[1]."-".$tgl_kadaluarsa[0],
                        'harga' => $this->input->post('harga'),
                        'tgl_update' => $this->bhp_pengadaan_model->tanggal($kode),
                        'code_cl_phc' => $code_cl_phc,//'P'.$this->session->userdata('puskesmas'),
            );
            /*if ($this->cekdata($values['id_mst_inv_barang_habispakai'],$values['batch'],$values['tgl_update'],$values['code_cl_phc'])==1) {
                return false;
            }else{*/
                if($this->db->insert('inv_inventaris_habispakai_pembelian_item', $values)){
                    return true;
                }else{
                    return false;
                }
          //  }
        }

    }
    function cekdata($idpakai=0,$batch=0,$tgl_update=0,$code_cl_phc=0)
    {
        $this->db->where('id_mst_inv_barang_habispakai',$idpakai);
        $this->db->where('batch',$batch);
        $this->db->where('tgl_distribusi >=',$tgl_update);
        $this->db->where('code_cl_phc',$code_cl_phc);
        $query = $this->db->get('inv_inventaris_habispakai_pembelian_item');
        if ($query->num_rows() > 0) {
            return 1;
        }else{
            return 0;
        }

    }
    function kode_invetaris($kode){
        $inv=explode(".", $kode);
        $kode_invetaris = $inv[0].$inv[1].$inv[2].$inv[3].$inv[4].$inv[5].$inv[6];
        $tahun          = $inv[6];
        $id_barang      = $inv[7].$inv[8].$inv[9].$inv[10].$inv[11];
        $register = $this->register($kode_invetaris,$id_barang);
        return  $kode_invetaris.$id_barang.$register;
    }
    function barang_kembar_proc_($kode){
        $inv=explode(".", $kode);
        $kode_invetaris = $inv[0].$inv[1].$inv[2].$inv[3].$inv[4].$inv[5].$inv[6];
        $tahun          = $inv[6];
        $id_barang      = $inv[7].$inv[8].$inv[9].$inv[10].$inv[11];
        $nomorproc = $this->proc($kode_invetaris,$id_barang);
        return  $kode_invetaris.$id_barang.$nomorproc;
    }
    function proc($inv,$barang){
        $q = $this->db->query("SELECT  MAX(RIGHT(barang_kembar_proc,4)) as kd_max FROM inv_inventaris_barang WHERE id_mst_inv_barang=".'"'.$barang.'"'." and id_inventaris_barang like ".'"%'.$inv.'%"'." ORDER BY barang_kembar_proc DESC");
        $kd="";
        if($q->num_rows()>0)
        {
            foreach($q->result() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }
        else
        {
            $kd = "0001";
        }
        return $kd;
    }
    function register($inv,$barang){
        $this->db->select("max(register) as register");
        $this->db->like('id_inventaris_barang',"$inv");
        $this->db->where('id_mst_inv_barang',$barang);
        $q=$this->db->get('inv_inventaris_barang');
        if($q->num_rows()>0)
        {
            foreach($q->result() as $k)
            {
                $tmp = ((int)$k->register)+1;
                $register = sprintf("%04s", $tmp);
            }
        }
        else
        {
            $register = "0001";
        }
        return $register;
    }
    function get_data_status()
    
    {   
        $this->db->where("mst_inv_pilihan.tipe",'status_pembelian');
        $this->db->select('mst_inv_pilihan.*');     
        $this->db->order_by('mst_inv_pilihan.code','asc');
        $query = $this->db->get('mst_inv_pilihan'); 
        return $query->result();    
    }
    function get_data_jenis()
    {   
        $query = $this->db->get('mst_inv_barang_habispakai_jenis'); 
        return $query->result();    
    }
    function pilih_data_status($status)
    {   
        $this->db->where("mst_inv_pilihan.tipe",$status);
        $this->db->select('mst_inv_pilihan.*');     
        $this->db->order_by('mst_inv_pilihan.code','asc');
        $query = $this->db->get('mst_inv_pilihan'); 
        return $query->result();    
    }
    function get_data_pilihan($pilih)
    {   
        $this->db->where("mst_inv_pilihan.tipe",$pilih);
        $this->db->select('mst_inv_pilihan.*');     
        $this->db->order_by('mst_inv_pilihan.code','asc');
        $query = $this->db->get('mst_inv_pilihan'); 
        return $query->result();    
    }
    function get_data($start=0,$limit=999999,$options=array())
    {
        $this->db->order_by('tgl_permohonan','desc');
        $this->db->select("mst_inv_barang_habispakai_jenis.uraian,$this->tabel.*,mst_inv_pilihan.value");
        $this->db->join('mst_inv_pilihan', "inv_inventaris_habispakai_pembelian.pilihan_status_pembelian = mst_inv_pilihan.code AND mst_inv_pilihan.tipe='status_pembelian'",'left');
        $this->db->join('mst_inv_barang_habispakai_jenis', "mst_inv_barang_habispakai_jenis.id_mst_inv_barang_habispakai_jenis = inv_inventaris_habispakai_pembelian.id_mst_inv_barang_habispakai_jenis",'left');
        $query = $this->db->get($this->tabel,$limit,$start);
        return $query->result();
    }
    public function getItem($start=0,$limit=999999,$options=array()){
        $this->db->order_by('mst_inv_barang_habispakai.uraian','asc');
        $this->db->select("inv_inventaris_habispakai_pembelian_item.*, 
           mst_inv_barang_habispakai.uraian,
           (SELECT tgl_distribusi FROM inv_inventaris_habispakai_distribusi LEFT JOIN inv_inventaris_habispakai_distribusi_item 
            ON inv_inventaris_habispakai_distribusi.id_inv_inventaris_habispakai_distribusi =
            inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi 
            WHERE  
            inv_inventaris_habispakai_distribusi_item.batch = inv_inventaris_habispakai_pembelian_item.batch  AND
            inv_inventaris_habispakai_distribusi_item.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai
            AND tgl_distribusi >= inv_inventaris_habispakai_pembelian_item.tgl_update ORDER BY tgl_distribusi DESC LIMIT 1
           ) AS tgl_distribusi",false);
        $this->db->join("mst_inv_barang_habispakai","mst_inv_barang_habispakai.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai","inner");
        $query = $this->db->get("inv_inventaris_habispakai_pembelian_item",$limit,$start);
        return $query->result();
    }

 	function get_data_row($kode){
		$data = array();
		$this->db->where("inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian",$kode);
		$this->db->select("mst_inv_pbf.nama as nama_pbf,$this->tabel.*,mst_inv_pilihan.value");
        $this->db->join('mst_inv_pilihan', "inv_inventaris_habispakai_pembelian.pilihan_status_pembelian = mst_inv_pilihan.code AND mst_inv_pilihan.tipe='status_pengadaan'",'left');
        $this->db->join('mst_inv_pbf', "mst_inv_pbf.code = inv_inventaris_habispakai_pembelian.mst_inv_pbf_code",'left');
		$query = $this->db->get($this->tabel);
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
    function get_data_nama($kode){
        $data = array();
        $this->db->select('*');
        $this->db->where('code',$kode);
        $query=$this->db->get('cl_phc');
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
    }
	function get_data_barang_edit($id_barang,$kd_proc,$kd_inventaris){
		$data = array();
		
		/*$this->db->select("inv_inventaris_barang.id_inventaris_barang,inv_inventaris_barang.id_mst_inv_barang,inv_inventaris_barang.nama_barang,inv_inventaris_barang.harga,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang) AS jumlah,
                        COUNT(inv_inventaris_barang.id_mst_inv_barang)*inv_inventaris_barang.harga AS totalharga,
                        inv_inventaris_barang.keterangan_pengadaan,inv_inventaris_barang.tanggal_diterima,
                        inv_inventaris_barang.waktu_dibuat,inv_inventaris_barang.terakhir_diubah,inv_inventaris_barang.pilihan_status_invetaris");
		$this->db->where("id_inventaris_barang",$kd_inventaris);
		$this->db->where("id_mst_inv_barang",$id_barang);
        $this->db->where("barang_kembar_proc",$kd_proc);*/
        $sql="SELECT inv_inventaris_barang.*,COUNT(inv_inventaris_barang.barang_kembar_proc) AS jumlah FROM inv_inventaris_barang WHERE inv_inventaris_barang.barang_kembar_proc = (SELECT barang_kembar_proc FROM inv_inventaris_barang WHERE id_inventaris_barang= ? )";
		$query = $this->db->query($sql, array($kd_inventaris));
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
    function get_data_barang_edit_table($kd_permohonan,$id_barang){
        $data = array();
        $this->db->where('inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian',$kd_permohonan);
        $this->db->where('inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai',$id_barang);
        $this->db->join("mst_inv_barang_habispakai","mst_inv_barang_habispakai.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai");
        $this->db->select("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis,inv_inventaris_habispakai_pembelian_item.*,mst_inv_barang_habispakai.uraian");
        $query= $this->db->get("inv_inventaris_habispakai_pembelian_item");
        
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
    }
	public function getSelectedData($table,$data)
    {
        return $this->db->get_where($table, $data);
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
	function get_inventarisbarang_id($id,$barang,$table)
    {
    	$query  = $this->db->query("SELECT MAX(id_inventaris_barang) as id from $table WHERE id_pengadaan=$id AND id_mst_inv_barang=$barang");
        $result = $query->result();
    	if(empty($result)){
    		return 1;
    	}else {
    		foreach ($query->result() as $jum ) {
    			return $jum->id+1;
    		}
    	}

	}

   function insert_entry()
    {
        $data['id_inv_hasbispakai_pembelian'] = $this->kode_pengadaan($this->input->post('kode_inventaris_'));
        $data['code_cl_phc']                = $this->input->post('codepus');
        $data['id_mst_inv_barang_habispakai_jenis'] = $this->input->post('id_mst_inv_barang_habispakai_jenis');
    	$data['tgl_permohonan']	            = date("Y-m-d",strtotime($this->input->post('tgl')));
        $data['tgl_pembelian']              = date("Y-m-d",strtotime($this->input->post('tgl2')));
        $data['jenis_transaksi']            = $this->input->post('jenistransaksi');
		$data['pilihan_status_pembelian']	= $this->input->post('status');
        $data['bln_periode']                = $this->input->post('thn_periode')."-".$this->input->post('bln_periode');
        $data['thn_dana']                   = $this->input->post('thn_dana');
        $data['pilihan_sumber_dana']        = $this->input->post('pilihan_sumber_dana');
        $data['mst_inv_pbf_code']           = $this->input->post('id_mst_inv_pbf_code');
		$data['keterangan']		            = $this->input->post('keterangan');
		$data['waktu_dibuat']		        = date('Y-m-d H:i:s');
        $data['terakhir_diubah']            = "0000-00-00 00:00:00";
		$data['jumlah_unit']      	        = 0;
        $data['nilai_pembelian']            = 0;
		if($this->db->insert($this->tabel, $data)){
			return $data['id_inv_hasbispakai_pembelian'];
		}else{
			return mysql_error();
		}
    }
    function kode_pengadaan($kode){
        $inv=explode(".", $kode);
        $kode_pengadaan = $inv[0].$inv[1].$inv[2].$inv[3].$inv[4].$inv[5].$inv[6];
        $tahun          = $inv[6];
        $urut = $this->nourut($kode_pengadaan);
        return  $kode_pengadaan.$urut;
    }
    function nourut($kode_pengadaan){
        $q = $this->db->query("select MAX(RIGHT(id_inv_hasbispakai_pembelian,6)) as kd_max from inv_inventaris_habispakai_pembelian where (LEFT(id_inv_hasbispakai_pembelian,15))=$kode_pengadaan");
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
    function tanggal($pengadaan){
        $query = $this->db->query("select tgl_pembelian from inv_inventaris_habispakai_pembelian where id_inv_hasbispakai_pembelian =".'"'.$pengadaan.'"'."")->result();
        foreach ($query as $key) {
            return $key->tgl_pembelian;
        }
    }
    function update_entry($kode)
    {
        $data['jenis_transaksi']            = $this->input->post('jenis_transaksi');
        $data['keterangan']                 = $this->input->post('keterangan');
        $data['nomor_kontrak']              = $this->input->post('nomor_kontrak');
        $data['tgl_permohonan']             = date("Y-m-d",strtotime($this->input->post('tgl')));
        $data['tgl_pembelian']              = date("Y-m-d",strtotime($this->input->post('tgl2')));
        $data['tgl_kwitansi']               = date("Y-m-d",strtotime($this->input->post('tgl1')));
        $data['nomor_kwitansi']             = $this->input->post('nomor_kwitansi');
        $data['mst_inv_pbf_code']           = $this->input->post('id_mst_inv_pbf_code');
        $data['bln_periode']                = $this->input->post('thn_periode')."-".$this->input->post('bln_periode');
        $data['pilihan_sumber_dana']        = $this->input->post('pilihan_sumber_dana');
        $data['thn_dana']                   = $this->input->post('thn_dana');
        $data['pilihan_status_pembelian']   = $this->input->post('status');
        $data['terakhir_diubah']            = date('Y-m-d H:i:s');
		$this->db->where('id_inv_hasbispakai_pembelian',$kode);

		if($this->db->update($this->tabel, $data)){
            $this->db->select("*");
            $this->db->where('id_inv_hasbispakai_pembelian',$kode);
            $query = $this->db->get("inv_inventaris_habispakai_pembelian_item");
            if($query->num_rows()>0){
                $dataupdateitem['tgl_update']              = date("Y-m-d",strtotime($this->input->post('tgl2')));
                $this->db->where('id_inv_hasbispakai_pembelian',$kode);
                $this->db->update("inv_inventaris_habispakai_pembelian_item",$dataupdateitem);
            }
			return true;
		}else{
			return mysql_error();
		}
    }

    function tampilstatus_id($status,$tipe){
        $this->db->select('code');
        $this->db->where('value',$status);
        $this->db->where('tipe',$tipe);
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

    function getPilihan($tipe,$code){
        $this->db->select('value');
        $this->db->where('code',$code);
        $this->db->where('tipe',$tipe);
        $query=$this->db->get('mst_inv_pilihan')->row();
        if(!empty($query)){
            return  $query->value;
        }else{
            return $tipe;
        }
    }

    
    function sum_jumlah_item($kode,$tipe){
        $namapus = "P".$this->session->userdata('puskesmas');
    	$this->db->select_sum($tipe);
    	$this->db->where('id_inv_hasbispakai_pembelian',$kode);
        //$this->db->where('code_cl_phc',$namapus);
		$query=$this->db->get('inv_inventaris_habispakai_pembelian_item');
		if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $jumlah = $k->$tipe;
            }
        }
        else
        {
            $jumlah = 0;
        }
        return  $jumlah;
    }
    function sum_jumlah_item_jumlah($kode,$tipe){
        $namapus = "P".$this->session->userdata('puskesmas');
        //and code_cl_phc= ".'"'.$namapus.'"'."
        $query=$this->db->query("SELECT SUM(jml*harga) as totalharga FROM inv_inventaris_habispakai_pembelian_item WHERE id_inv_hasbispakai_pembelian = ".'"'.$kode.'"'." ");
        if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $jumlah = $k->totalharga;
            }
        }
        else
        {
            $jumlah = 0;
        }
        return  $jumlah;
    }
   
    function sum_unit($kode)
    {
        $this->db->select("*");
        $this->db->where('id_inv_hasbispakai_pembelian',$kode);  
        return $query = $this->db->get("inv_inventaris_habispakai_pembelian_item"); 
    }
	function delete_entry($kode)
	{
		$this->db->where('id_inv_hasbispakai_pembelian',$kode);

		return $this->db->delete('inv_inventaris_habispakai_pembelian');
	}
    function jumlahtable($table,$id_inventaris_barang){

        $this->db->where('id_inventaris_barang',$id_inventaris_barang);
        $q = $this->db->get($table);
        $kd = 0;
        if($q->num_rows()>0)
        {
           $kd = $q->num_rows();
        }
        else
        {
            $kd = 0;
        }
        return $kd;
    }
	function delete_entryitem($kode,$barang,$batch)
	{  /* if($this->cekdelete!="0"){
            return false;
        }else{*/
            $this->db->where('id_inv_hasbispakai_pembelian',$kode);
            $this->db->where('id_mst_inv_barang_habispakai',$barang);
            $this->db->delete('inv_inventaris_habispakai_pembelian_item');
        //}
	}
    function delete_entryitem_table($kode,$id_barang,$table)
    {    
        $this->db->where('id_pengadaan',$kode);
        $this->db->where('id_mst_inv_barang',$id_barang);
        return $this->db->delete($table);
    }
	function get_databarang($start=0,$limit=999999)
    {
		$this->db->order_by('uraian','asc');
        $query = $this->db->get('mst_inv_barang',$limit,$start);
        return $query->result();
    }
    function get_databarangwhere($obat=0)
    {
        if($obat=="8"){

        }
        $this->db->order_by('uraian','asc');
        $query = $this->db->get('mst_inv_barang');
        return $query->result();
    }
    public function getnamajenis()
    {
        $this->db->select("*");
        $query = $this->db->get("mst_inv_barang_habispakai_jenis");
        return $query->result();
    }
    
    /*
    public function gettgl_opname($id=0)
    {
        $nmpuskes = "P".$this->session->userdata("puskesmas");
        $sql = "SELECT inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian, inv_inventaris_habispakai_opname.tgl_update AS tgl_opname
                FROM inv_inventaris_habispakai_pembelian_item
                JOIN inv_inventaris_habispakai_opname 
                    ON inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai
                    AND inv_inventaris_habispakai_opname.code_cl_phc = inv_inventaris_habispakai_pembelian_item.code_cl_phc
                WHERE inv_inventaris_habispakai_pembelian_item.code_cl_phc=".'"'.$nmpuskes.'"'."
                AND inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian = ".'"'.$id.'"'."
                ORDER BY inv_inventaris_habispakai_opname.tgl_update DESC
                LIMIT 1";
                /*  JOIN inv_inventaris_habispakai_pembelian 
                    ON inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian=inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian
                    AND inv_inventaris_habispakai_pembelian.pilihan_status_pembelian=2
                    AND inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = "12100307041601000004"*/
                /*
                inv_inventaris_habispakai_opname.tgl_update desc,
                inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian
        if ($this->db->query($sql)->num_rows()>0) {
            foreach ($this->db->query($sql)->result() as $key) {
                return $key->tgl_opname;
            }
        }else{
             return '1970-01-01';   
        }
        
    }
    */
}