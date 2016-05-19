<?php
class Bhp_distribusi_model extends CI_Model {

    var $tabel    = 'inv_inventaris_habispakai_distribusi';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');

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
        $this->db->order_by('id_inv_inventaris_habispakai_distribusi','desc');
        $this->db->select("inv_inventaris_habispakai_distribusi.*,(SELECT sum(jml) FROM inv_inventaris_habispakai_distribusi_item WHERE inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi = inv_inventaris_habispakai_distribusi.id_inv_inventaris_habispakai_distribusi) AS jumlah,");
        $query = $this->db->get("inv_inventaris_habispakai_distribusi",$limit,$start);
        return $query->result();
    }
    public function getitem($start=0,$limit=999999,$options=array()){
        
        $query = $this->db->get("bhp_distribusi",$limit,$start);
        return $query->result();
    }
    
    public function getitemdistribusi($start=0,$limit=999999,$options=array()){
        $this->db->group_by('id_mst_inv_barang_habispakai','batch');
        $this->db->order_by('id_inv_inventaris_habispakai_distribusi','desc');
        $this->db->select("inv_inventaris_habispakai_distribusi_item.*,mst_inv_barang_habispakai.uraian,mst_inv_barang_habispakai.pilihan_satuan,inv_inventaris_habispakai_pembelian_item.tgl_kadaluarsa,(SELECT inv_inventaris_habispakai_opname.tgl_opname FROM inv_inventaris_habispakai_opname JOIN inv_inventaris_habispakai_opname_item ON inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_distribusi_item.batch = inv_inventaris_habispakai_opname_item.batch AND inv_inventaris_habispakai_distribusi_item.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai AND tgl_opname >= inv_inventaris_habispakai_distribusi.tgl_distribusi       ORDER BY tgl_opname DESC LIMIT 1 )AS tgl_opname");
        $this->db->join("mst_inv_barang_habispakai","mst_inv_barang_habispakai.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_distribusi_item.id_mst_inv_barang_habispakai");
        $this->db->join("inv_inventaris_habispakai_distribusi","inv_inventaris_habispakai_distribusi.id_inv_inventaris_habispakai_distribusi = inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi",'left');
        $this->db->join("inv_inventaris_habispakai_pembelian_item","inv_inventaris_habispakai_distribusi_item.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai and inv_inventaris_habispakai_distribusi_item.batch = inv_inventaris_habispakai_pembelian_item.batch",'left');
        $query = $this->db->get("inv_inventaris_habispakai_distribusi_item",$limit,$start);
        return $query->result();
    }

 	function get_data_distribusi($kode,$batch){
		$data = array();

        $this->db->where("id_mst_inv_barang_habispakai",$kode);
        $this->db->where("batch",$batch);
        $query = $this->db->get("bhp_distribusi");
		if ($query->num_rows() > 0){
			$data = $query->row_array();
		}

		$query->free_result();    
		return $data;
	}
    function get_data_row($kode){
        $data = array();
        $this->db->where("id_inv_inventaris_habispakai_distribusi",$kode);
        $this->db->select("$this->tabel.*");
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
        $data['id_inv_inventaris_habispakai_distribusi'] = $this->kode_distribusi($this->input->post('kode_distribusi_'));
        $data['code_cl_phc']                = $this->input->post('codepus');
        $data['jenis_bhp']                  = $this->input->post('jenis_bhp');
    	$data['tgl_distribusi']	            = date("Y-m-d",strtotime($this->input->post('tgl_distribusi')));
        $data['nomor_dokumen']              = $this->input->post('nomor_dokumen');
        $data['bln_periode']                = $this->input->post('thn_periode')."-".$this->input->post('bln_periode');
        $data['penerima_nama']              = $this->input->post('penerima_nama');
        $data['penerima_nip']               = $this->input->post('penerima_nip');
		$data['keterangan']		            = $this->input->post('keterangan');
		if($this->db->insert($this->tabel, $data)){
			return $data['id_inv_inventaris_habispakai_distribusi'];
		}else{
			return mysql_error();
		}
    }
    function kode_distribusi($kode){
        $inv=explode(".", $kode);
        $kode_pengadaan = $inv[0].$inv[1].$inv[2].$inv[3].$inv[4].$inv[5].$inv[6];
        $tahun          = $inv[6];
        $urut = $this->nourut($kode_pengadaan);
        return  $kode_pengadaan.$urut;
    }
    function nourut($kode_pengadaan){
        $q = $this->db->query("select MAX(RIGHT(id_inv_inventaris_habispakai_distribusi,6)) as kd_max from inv_inventaris_habispakai_distribusi where (LEFT(id_inv_inventaris_habispakai_distribusi,15))=$kode_pengadaan");
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
        $query = $this->db->query("select tgl_pembelian from inv_inventaris_habispakai_pembelian where id_inv_hasbispakai_pembelian = $pengadaan")->result();
        foreach ($query as $key) {
            return $key->tgl_pembelian;
        }
    }
    function update_entry($kode)
    {
        $data['jenis_transaksi']            = $this->input->post('jenis_transaksi');
        $data['keterangan']                 = $this->input->post('keterangan');
        $data['nomor_kontrak']              = $this->input->post('nomor_kontrak');
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
    function sum_jumlah_item_jumlah($id){
        $this->db->where('id_inv_inventaris_habispakai_distribusi',$id);
        $this->db->select('sum(jml) as jumlah_tot');
        $query = $this->db->get('inv_inventaris_habispakai_distribusi_item');
        
        if($query->num_rows()>0)
        {
            foreach ($query->result() as $q) {
                $jumlah = ($q->jumlah_tot==null ? 0:$q->jumlah_tot);
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
		$this->db->where('id_inv_inventaris_habispakai_distribusi',$kode);

		return $this->db->delete('inv_inventaris_habispakai_distribusi');
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
	{   
        $this->db->where('id_inv_inventaris_habispakai_distribusi',$kode);
        $this->db->where('id_mst_inv_barang_habispakai',$barang);
        $this->db->where('batch',$batch);
        $this->db->delete('inv_inventaris_habispakai_distribusi_item');
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
    public function gettgl_opname($id=0)
    {
        $nmpuskes = "P".$this->session->userdata("puskesmas");
        $sql = "SELECT inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian, inv_inventaris_habispakai_opname.tgl_update AS tgl_opname
                FROM inv_inventaris_habispakai_pembelian_item
                JOIN inv_inventaris_habispakai_opname 
                    ON inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai
                    AND inv_inventaris_habispakai_opname.code_cl_phc = inv_inventaris_habispakai_pembelian_item.code_cl_phc
                WHERE inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian = ".'"'.$id.'"'."
                ORDER BY inv_inventaris_habispakai_opname.tgl_update DESC
                LIMIT 1";
                /*  JOIN inv_inventaris_habispakai_pembelian 
                    ON inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian=inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian
                    AND inv_inventaris_habispakai_pembelian.pilihan_status_pembelian=2
                    AND inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = "12100307041601000004"*/
                /*
                inv_inventaris_habispakai_opname.tgl_update desc,
                inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian
                */
        if ($this->db->query($sql)->num_rows()>0) {
            foreach ($this->db->query($sql)->result() as $key) {
                return $key->tgl_opname;
            }
        }else{
             return '1970-01-01';   
        }
        
    }
}