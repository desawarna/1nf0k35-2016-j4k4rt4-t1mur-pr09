<?php
class Kodelokasi_model extends CI_Model {

    var $tabel    = 'cl_phc';
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
    
}