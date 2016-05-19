<?php
class Keuakun_model extends CI_Model {

    var $tabel    = '';
	var $lang	  = '';
    var $tb       = 'mst_keu_akun';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');
    }

    function insert_entry(){
        $akun_urutan        = $this->input->post('akun_urutan');
        $akun_urutan_induk  = $this->input->post('akun_urutan_induk');
        $this->db->where('id_mst_akun',$akun_urutan_induk);
        $dt = $this->db->get($this->tb)->row();
        if($akun_urutan == "sebelum"){
            $data['urutan']  = $dt->urutan;
        }else{
            $data['urutan']  = $dt->urutan+1;
        }

        $data['uraian']         = $this->input->post('uraian');
        $data['saldo_normal']   = $this->input->post('saldo_normal');

        $data['aktif']              = 1;
        $data['saldo_awal']         = 0;
        $data['bisa_transaksi']     = 0;
        $data['bisa_diedit']        = 0;
        $data['buku_besar_umum']    = 1;
        $data['mendukung_target']   = 0;
        $data['mendukung_anggaran'] = 0;
        $data['mendukung_transaksi']= 0;
        $data['tanggal_dibuat']     = date('Y-m-d H:i:s');
	
		if($this->db->insert($this->tb, $data)){
            $id = mysql_insert_id();
            $this->db->query("UPDATE mst_keu_akun SET urutan=urutan+1 WHERE isnull(`id_mst_akun_parent`) AND urutan >= ".$data['urutan']." AND `id_mst_akun`<>".$id);

            return 1;
		}else{
			return mysql_error();
		}
    }

    function mendukung_transaksi_update($id){
        $data['mendukung_transaksi']      = $this->input->post('mendukung_transaksi');
       
        $this->db->where('id_mst_akun',$id);

        if($this->db->update($this->tb, $data)){
            $this->db->where('id_mst_akun',$id);
            $this->db->select('mendukung_transaksi');
            $variable = $this->db->get('mst_keu_akun');
            foreach ($variable->result() as $key) {
                if ($key->mendukung_transaksi=='1') {
                    return '1';
                }else{
                    return '2';    
                }
            }
        }else{
            return mysql_error();
        }
    }

    function mendukung_anggaran_update($id){
        $data['mendukung_anggaran']       = $this->input->post('mendukung_anggaran');
       
        $this->db->where('id_mst_akun',$id);

        if($this->db->update($this->tb, $data)){
            $this->db->where('id_mst_akun',$id);
            $this->db->select('mendukung_anggaran');
            $variable = $this->db->get('mst_keu_akun');
            foreach ($variable->result() as $key) {
                if($key->mendukung_anggaran=='1'){
                    return '1';
                }else{
                    return '2';
                }
            }
        }else{
            return mysql_error();
        }
    }

    function mendukung_target_update($id){
        $data['mendukung_target']         = $this->input->post('mendukung_target');
       
        $this->db->where('id_mst_akun',$id);

        if($this->db->update($this->tb, $data)){
            $this->db->where('id_mst_akun',$id);
            $this->db->select('mendukung_target');
            $variable = $this->db->get('mst_keu_akun');
            foreach ($variable->result() as $key ) {
               if ($key->mendukung_target=='1') {
                   return '1';
               }else{
                   return '2';
               }
            }
        }else{
            return mysql_error();
        }
    }

    function akun_delete(){  
        $this->db->where('id_mst_akun', $this->input->post('id_mst_akun'));
        $this->db->delete($this->tb);
                
        $this->db->where('id_mst_akun_parent', $this->input->post('id_mst_akun'));
        $this->db->delete($this->tb);
    }

    function akun_add(){
        $data = array(
           'id_mst_akun_parent'     => $this->input->post('id_mst_akun_parent'),
           'kode'                   => $this->input->post('kode') ,
           'saldo_normal'           => $this->input->post('saldo_normal'),
           'saldo_awal'             => $this->input->post('saldo_awal'),
           'uraian'                 => $this->input->post('uraian'),
           'aktif'                  => 1,
           'bisa_diedit'            => 1,
           'buku_besar_umum'        => 1,
           'mendukung_transaksi'    => $this->input->post('mendukung_transaksi')
        );

         if($this->db->set('tanggal_dibuat', 'NOW()', FALSE)){
           ($this->db->insert($this->tb, $data));
            return 1;
        }else{
            return mysql_error();
        }
    }

    function akun_update(){
        $data = array(
           'id_mst_akun_parent'  => $this->input->post('id_mst_akun_parent'),
           'kode'                => $this->input->post('kode') ,
           'saldo_normal'        => $this->input->post('saldo_normal') ,
           'saldo_awal'          => $this->input->post('saldo_awal'),
           'uraian'              => $this->input->post('uraian'),
           'mendukung_transaksi' => $this->input->post('mendukung_transaksi')
        );
        $this->db->where('id_mst_akun', $this->input->post('id_mst_akun'));
        return $this->db->update($this->tb, $data);             
    }

 	function get_data($start=0,$limit=999999,$options=array())
    {
		$this->db->order_by('id_mst_akun','asc');
        $query = $this->db->get('mst_keu_akun',$limit,$start);
        return $query->result();
    }

    function get_data_akun(){     
        $this->db->where('aktif',1);
        $this->db->order_by('urutan','asc');
        $query = $this->db->get('mst_keu_akun');     
        return $query->result_array();  
    }

    function get_data_akun_non_aktif(){     
        $this->db->where('aktif','0');
        $query = $this->db->get('mst_keu_akun');     
        return $query->result_array();  
    }

    function get_parent_akun(){     
        $this->db->where('id_mst_akun_parent IS NULL');
        $this->db->order_by('uraian','asc');
        $query = $this->db->get('mst_keu_akun');     
        return $query->result();  
    }

   function get_data_akun_detail($id){
        $data = array();
        $this->db->where("id_mst_akun",$id);
        $query = $this->db->get('mst_keu_akun');
        if($query->num_rows()>0){
            $data = $query->row_array();
        }

        $query->free_result();
        return $data;
    }

    function get_data_akun_non_aktif_detail($id){
        $data = array();
        $this->db->where("id_mst_akun",$id);
        $this->db->where('aktif',0);
        $query = $this->db->get('mst_keu_akun');
        if($query->num_rows()>0){
            $data = $query->row_array();
        }

        $query->free_result();
        return $data;
    }

}

