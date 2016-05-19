<?php
class Inv_barang_model extends CI_Model {

    var $tabel    = 'inv_inventaris_barang';
    var $lang     = '';

    function __construct() {
        parent::__construct();
        $this->lang   = $this->config->item('language');
    }
    function get_nama($kolom_sl,$tabel,$kolom_wh,$kond){
       $this->db->where($kolom_wh,$kond);
        $this->db->select($kolom_sl);
        $query = $this->db->get($tabel)->result();
        foreach ($query as $key) {
            return $key->$kolom_sl;
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
        $this->db->where("mst_inv_pilihan.tipe",'status_pengadaan');
        $this->db->select('mst_inv_pilihan.*');     
        $this->db->order_by('mst_inv_pilihan.code','asc');
        $query = $this->db->get('mst_inv_pilihan'); 
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

    function get_pilihan($code,$tipe)
    {
        $this->db->where('code',$code);
        $this->db->where('tipe',$tipe);
        $this->db->select('value');
        $query = $this->db->get('mst_inv_pilihan'); 
        foreach ($query->result() as $data ) {
                return $data->value;
        }
    }
    function get_data($start=0,$limit=999999,$options=array())
    {
        $query = $this->db->get("get_all_inventaris2",$limit,$start);
        return $query->result();
    }
    
    
    function get_data_laporan($start=0,$limit=999999,$options=array())
    {
        $this->db->group_by('inv_inventaris_barang.barang_kembar_proc');
        $this->db->select("mst_inv_barang.uraian AS uraian,
        IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                2) = '01'),
            inv_inventaris_barang_a.status_sertifikat_nomor,
            IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                    2) = '02'),
                IFNULL(inv_inventaris_barang_b.nomor_bpkb,
                        inv_inventaris_barang_b.no_polisi),
                IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                        2) = '03'),
                    IFNULL(inv_inventaris_barang_c.dokumen_nomor,
                            inv_inventaris_barang_c.nomor_kode_tanah),
                    IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                            2) = '04'),
                        IFNULL(inv_inventaris_barang_d.dokumen_nomor,
                                inv_inventaris_barang_d.nomor_kode_tanah),
                        IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                                2) = '06'),
                            inv_inventaris_barang_f.dokumen_nomor,
                            '-'))))) AS nobukti,
        IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                2) = '02'),
            bahan_b.value,
            IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                    2) = '03'),
                bahan_c.value,
                IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                        2) = '05'),
                    bahan_e.value,
                    IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                            2) = '06'),
                        bahan_f.value,
                        '-')))) AS bahan,
        IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                2) = '01'),
            inv_inventaris_barang_a.luas,
            IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                    2) = '02'),
                inv_inventaris_barang_b.ukuran_barang,
                IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                        2) = '03'),
                    inv_inventaris_barang_c.luas_lantai,
                    IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                            2) = '04'),
                        inv_inventaris_barang_d.luas,
                        IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                                2) = '05'),
                            inv_inventaris_barang_e.flora_fauna_ukuran,
                            IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                                    2) = '06'),
                                inv_inventaris_barang_f.luas,
                                '-')))))) AS ukuran,
        IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                2) = '01'),
            luas_a.value,
            IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                    2) = '02'),
                luas_b.value,
                IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                        2) = '05'),
                    luas_e.value,
                    '-'))) AS satuan,
        IF((LEFT(inv_inventaris_barang.id_mst_inv_barang,
                2) = '02'),
            inv_inventaris_barang_b.merek_type,
            '-') AS merk,
        keadaan.value AS keadaan,
        IFNULL(asal.value, '-') AS asal,
        inv_inventaris_barang.id_inventaris_barang AS id_inventaris_barang,
        inv_inventaris_barang.register AS register,
        inv_inventaris_barang.id_mst_inv_barang AS id_mst_inv_barang,
        inv_inventaris_barang.id_pengadaan AS id_pengadaan,
        inv_inventaris_barang.pilihan_keadaan_barang AS pilihan_keadaan_barang,
        inv_inventaris_barang.nama_barang AS nama_barang,
        inv_inventaris_barang.pilihan_komponen AS pilihan_komponen,
        inv_inventaris_barang.harga AS harga,
        inv_inventaris_barang.keterangan_pengadaan AS keterangan_pengadaan,
        inv_inventaris_barang.pilihan_status_invetaris AS pilihan_status_invetaris,
        inv_inventaris_barang.tanggal_pembelian AS tanggal_pembelian,
        inv_inventaris_barang.foto_barang AS foto_barang,
        inv_inventaris_barang.barang_kembar_proc AS barang_kembar_proc,
        inv_inventaris_barang.keterangan_inventory AS keterangan_inventory,
        inv_inventaris_barang.tanggal_pengadaan AS tanggal_pengadaan,
        inv_inventaris_barang.tanggal_diterima AS tanggal_diterima,
        inv_inventaris_barang.tanggal_dihapus AS tanggal_dihapus,
        inv_inventaris_barang.alasan_penghapusan AS alasan_penghapusan,
        inv_inventaris_barang.pilihan_asal AS pilihan_asal,
        inv_inventaris_barang.waktu_dibuat AS waktu_dibuat,
        inv_inventaris_barang.terakhir_diubah AS terakhir_diubah,
        inv_inventaris_barang.code_cl_phc AS code_cl_phc,
        COUNT(inv_inventaris_barang.id_inventaris_barang) AS jumlah",false);
        $this->db->join('mst_inv_barang',"inv_inventaris_barang.id_mst_inv_barang = mst_inv_barang.code",'left');
        $this->db->join('inv_inventaris_barang_a',"inv_inventaris_barang_a.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang",'left');
        $this->db->join('inv_inventaris_barang_b',"inv_inventaris_barang_b.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang",'left');
        $this->db->join('inv_inventaris_barang_c',"inv_inventaris_barang_c.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang",'left');
        $this->db->join('inv_inventaris_barang_d',"inv_inventaris_barang_d.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang",'left');
        $this->db->join('inv_inventaris_barang_e',"inv_inventaris_barang_e.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang",'left');
        $this->db->join('inv_inventaris_barang_f',"inv_inventaris_barang_f.id_inventaris_barang = inv_inventaris_barang.id_inventaris_barang",'left');
        $this->db->join('mst_inv_pilihan bahan_b',"inv_inventaris_barang_b.pilihan_bahan= bahan_b.code AND (bahan_b.tipe = 'bahan')",'left');
        $this->db->join('mst_inv_pilihan bahan_c',"inv_inventaris_barang_c.pilihan_kons_beton = bahan_c.code AND (bahan_c.tipe = 'kons_beton')",'left');
        $this->db->join('mst_inv_pilihan bahan_e',"inv_inventaris_barang_e.pilihan_budaya_bahan = bahan_e.code AND (bahan_e.tipe = 'bahan')",'left');
        $this->db->join('mst_inv_pilihan bahan_f',"inv_inventaris_barang_f.pilihan_konstruksi_beton = bahan_f.code AND (bahan_f.tipe = 'kons_beton')",'left');
        $this->db->join('mst_inv_pilihan luas_a',"inv_inventaris_barang_a.pilihan_satuan_barang = luas_a.code AND (luas_a.tipe = 'satuan')",'left');
        $this->db->join('mst_inv_pilihan luas_b',"inv_inventaris_barang_b.pilihan_satuan = luas_b.code AND (luas_b.tipe = 'satuan')",'left');
        $this->db->join('mst_inv_pilihan luas_e',"inv_inventaris_barang_e.pilihan_satuan = luas_e.code AND (luas_e.tipe = 'satuan')",'left');
        $this->db->join('mst_inv_pilihan keadaan',"inv_inventaris_barang.pilihan_keadaan_barang = keadaan.code AND (keadaan.tipe = 'keadaan_barang')",'left');
        $this->db->join('mst_inv_pilihan asal',"inv_inventaris_barang.pilihan_asal = asal.code AND (asal.tipe = 'asal_usul')",'left');
        $query = $this->db->get('inv_inventaris_barang');
       // $query = $this->db->get("get_laporan_inv",$limit,$start);
        return $query->result();
    }
    
    
    
    function get_data_golongan($table,$start=0,$limit=999999,$options=array()){
        $this->db->select("$table.*");
        $query = $this->db->get($table,$limit,$start);
        return $query->result();
    }
    function get_data_golongan_A($start=0,$limit=999999,$options=array()){
        if($this->input->post('filter_tanggal') != '') {
            $date = $this->input->post('filter_tanggal');
        }else{
            $date = date("Y-m-d");
        }

        if($this->session->userdata('filterHAPUS')==''){
       /*     $qr = " * FROM (SELECT * FROM kiba where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kiba where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kiba2 ";
                $this->db->where("((isnull(tanggal_dihapus) OR tanggal_dihapus='0000-00-00') OR tanggal_dihapus >= '".$date."')");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }else{

            /* $qr = " * FROM (SELECT * FROM kiba where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kiba where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kiba2 ";
                $this->db->where("(tanggal_dihapus!='0000-00-00' AND tanggal_dihapus < '".$date."') ");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }

        return $query->result();
    }
    function get_data_golongan_B($start=0,$limit=999999,$options=array()){
        if($this->input->post('filter_tanggal') != '') {
            $date = $this->input->post('filter_tanggal');
        }else{
            $date = date("Y-m-d");
        }

        if($this->session->userdata('filterHAPUS')==''){
            /*$qr = " * FROM (SELECT * FROM kibb where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibb where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";
                $this->db->where("((isnull(tanggal_dihapus) OR tanggal_dihapus='0000-00-00') OR tanggal_dihapus >= '".$date."')");*/
            $qr = " * FROM kibb2 ";
                $this->db->where("((isnull(tanggal_dihapus) OR tanggal_dihapus='0000-00-00') OR tanggal_dihapus >= '".$date."')");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }else{
            /*$qr = " * FROM (SELECT * FROM kibb where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibb   where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kibb2 ";
                $this->db->where("(tanggal_dihapus!='0000-00-00' AND tanggal_dihapus < '".$date."') ");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }
        return $query->result();
    }
    function get_data_golongan_C($start=0,$limit=999999,$options=array()){
        if($this->input->post('filter_tanggal') != '') {
            $date = $this->input->post('filter_tanggal');
        }else{
            $date = date("Y-m-d");
        }

        if($this->session->userdata('filterHAPUS')==''){
           /* $qr = " * FROM (SELECT * FROM kibc where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibc where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kibc2 ";
                $this->db->where("((isnull(tanggal_dihapus) OR tanggal_dihapus='0000-00-00') OR tanggal_dihapus >= '".$date."')");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }else{
           /* $qr = " * FROM (SELECT * FROM kibc where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibc   where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kibc2 ";
                $this->db->where("(tanggal_dihapus!='0000-00-00' AND tanggal_dihapus < '".$date."') ");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }
        return $query->result();
    }
    function get_data_golongan_D($start=0,$limit=999999,$options=array()){
        if($this->input->post('filter_tanggal') != '') {
            $date = $this->input->post('filter_tanggal');
        }else{
            $date = date("Y-m-d");
        }

        if($this->session->userdata('filterHAPUS')==''){
           /* $qr = " * FROM (SELECT * FROM kibd where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibd where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kibd2 ";
                $this->db->where("((isnull(tanggal_dihapus) OR tanggal_dihapus='0000-00-00') OR tanggal_dihapus >= '".$date."')");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }else{
            /*$qr = " * FROM (SELECT * FROM kibd where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibd   where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kibd2 ";
                $this->db->where("(tanggal_dihapus!='0000-00-00' AND tanggal_dihapus < '".$date."') ");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }
        return $query->result();
    }
     function get_data_golongan_E($start=0,$limit=999999,$options=array()){
        if($this->input->post('filter_tanggal') != '') {
            $date = $this->input->post('filter_tanggal');
        }else{
            $date = date("Y-m-d");
        }

        if($this->session->userdata('filterHAPUS')==''){
           /* $qr = " * FROM (SELECT * FROM kibe where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibe where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kibe2 ";
                $this->db->where("((isnull(tanggal_dihapus) OR tanggal_dihapus='0000-00-00') OR tanggal_dihapus >= '".$date."')");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }else{
            /*$qr = " * FROM (SELECT * FROM kibe where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibe   where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kibe2 ";
                $this->db->where("(tanggal_dihapus!='0000-00-00' AND tanggal_dihapus < '".$date."') ");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }
        return $query->result();
    }
     function get_data_golongan_F($start=0,$limit=999999,$options=array()){
        if($this->input->post('filter_tanggal') != '') {
            $date = $this->input->post('filter_tanggal');
        }else{
            $date = date("Y-m-d");
        }

        if($this->session->userdata('filterHAPUS')==''){
       /*     $qr = " * FROM (SELECT * FROM kibf where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibf where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kibf2 ";
                $this->db->where("((isnull(tanggal_dihapus) OR tanggal_dihapus='0000-00-00') OR tanggal_dihapus >= '".$date."')");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }else{
            /*$qr = " * FROM (SELECT * FROM kibf where (id_inventaris_distribusi , barang_kembar_proc) IN     
                (SELECT MAX(id_inventaris_distribusi), barang_kembar_proc
                FROM kibf where tgl_distribusi <= '".$date."'
                GROUP BY id_inventaris_barang) ) as a ";*/
                $qr = " * FROM kibf2 ";
                $this->db->where("(tanggal_dihapus!='0000-00-00' AND tanggal_dihapus < '".$date."') ");
            $this->db->select($qr,false);
            $query = $this->db->get();
        }
        return $query->result();
    }
    function get_data_row($kode){
        $data = array();
        $this->db->where("inv_pengadaan.id_pengadaan",$kode);
        $this->db->select("$this->tabel.*,mst_inv_pilihan.value");
        $this->db->join('mst_inv_pilihan', "inv_pengadaan.pilihan_status_pengadaan = mst_inv_pilihan.code AND mst_inv_pilihan.tipe='status_pengadaan'",'left');
        $query = $this->db->get($this->tabel);
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

    function get_register($kd_inventaris){
        $this->db->where('id_inventaris_barang',$kd_inventaris);
        $this->db->order_by('id_inventaris_distribusi','desc');
        $res = $this->db->get('inv_inventaris_distribusi')->row();
        if(!empty($res->register)){
            return $res->register;
        }else{
            return "-";
        }
    }

    function get_data_barang_edit_table($kembarproc,$id_barang,$kd_inventaris,$pilih_table,$id_distribusi){
        $data = array();
        if($pilih_table=='inv_inventaris_barang_a'){

           /* $sql= "SELECT inv_inventaris_barang_a.*, inv_inventaris_barang.*,
                    (SELECT COUNT(DISTINCT(id_inventaris_barang)) AS jml FROM inv_inventaris_distribusi WHERE id_inventaris_barang IN 
                    (SELECT id_inventaris_barang FROM inv_inventaris_barang WHERE barang_kembar_proc = ".'"'.$kembarproc.'"'.")) 
                    AS jumlah
                    FROM inv_inventaris_barang 
                    LEFT JOIN inv_inventaris_barang_a ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_a.id_inventaris_barang 
                    AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_a.id_mst_inv_barang)
                    WHERE inv_inventaris_barang.id_inventaris_barang = ".'"'.$kd_inventaris.'"'."
                    ";           
            $query= $this->db->query($sql);*/
            $this->db->where('id_inventaris_distribusi',"$id_distribusi");
                    $this->db->where('id_inventaris_barang',"$kd_inventaris");
            $query= $this->db->get('kiba2');

        }else if($pilih_table=='inv_inventaris_barang_b'){

           /* $sql= "SELECT inv_inventaris_barang_b.*, inv_inventaris_barang.*,
                    (SELECT COUNT(DISTINCT(id_inventaris_barang)) AS jml FROM inv_inventaris_distribusi WHERE id_inventaris_barang IN 
                    (SELECT id_inventaris_barang FROM inv_inventaris_barang WHERE barang_kembar_proc = ".'"'.$kembarproc.'"'.")) 
                    AS jumlah
                    FROM inv_inventaris_barang 
                    LEFT JOIN inv_inventaris_barang_b ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_b.id_inventaris_barang 
                    AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_b.id_mst_inv_barang)
                    WHERE inv_inventaris_barang.id_inventaris_barang = ".'"'.$kd_inventaris.'"'."
                    ";           */

                    $this->db->where('id_inventaris_distribusi',"$id_distribusi");
                    $this->db->where('id_inventaris_barang',"$kd_inventaris");
            $query= $this->db->get('kibb2');

        }else if($pilih_table=='inv_inventaris_barang_c'){

          /*  $sql= "SELECT inv_inventaris_barang_c.*, inv_inventaris_barang.*,
                    (SELECT COUNT(DISTINCT(id_inventaris_barang)) AS jml FROM inv_inventaris_distribusi WHERE id_inventaris_barang IN 
                    (SELECT id_inventaris_barang FROM inv_inventaris_barang WHERE barang_kembar_proc = ".'"'.$kembarproc.'"'.")) 
                    AS jumlah
                    FROM inv_inventaris_barang 
                    LEFT JOIN inv_inventaris_barang_c ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_c.id_inventaris_barang 
                    AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_c.id_mst_inv_barang)
                    WHERE inv_inventaris_barang.id_inventaris_barang = ".'"'.$kd_inventaris.'"'."
                    ";           
            $query= $this->db->query($sql);            */
            $this->db->where('id_inventaris_distribusi',"$id_distribusi");
                    $this->db->where('id_inventaris_barang',"$kd_inventaris");
            $query= $this->db->get('kibc2');

        }else if($pilih_table=='inv_inventaris_barang_d'){

           /* $sql= "SELECT inv_inventaris_barang_d.*, inv_inventaris_barang.*,
                    (SELECT COUNT(DISTINCT(id_inventaris_barang)) AS jml FROM inv_inventaris_distribusi WHERE id_inventaris_barang IN 
                    (SELECT id_inventaris_barang FROM inv_inventaris_barang WHERE barang_kembar_proc = ".'"'.$kembarproc.'"'.")) 
                    AS jumlah
                    FROM inv_inventaris_barang 
                    LEFT JOIN inv_inventaris_barang_d ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_d.id_inventaris_barang 
                    AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_d.id_mst_inv_barang)
                    WHERE inv_inventaris_barang.id_inventaris_barang = ".'"'.$kd_inventaris.'"'."
                    ";           
            $query= $this->db->query($sql);*/
            $this->db->where('id_inventaris_distribusi',"$id_distribusi");
                    $this->db->where('id_inventaris_barang',"$kd_inventaris");
            $query= $this->db->get('kibd2');

        }else if($pilih_table=='inv_inventaris_barang_e'){

            /*$sql= "SELECT inv_inventaris_barang_e.*, inv_inventaris_barang.*,
                    (SELECT COUNT(DISTINCT(id_inventaris_barang)) AS jml FROM inv_inventaris_distribusi WHERE id_inventaris_barang IN 
                    (SELECT id_inventaris_barang FROM inv_inventaris_barang WHERE barang_kembar_proc = ".'"'.$kembarproc.'"'.")) 
                    AS jumlah
                    FROM inv_inventaris_barang 
                    LEFT JOIN inv_inventaris_barang_e ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_e.id_inventaris_barang 
                    AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_e.id_mst_inv_barang)
                    WHERE inv_inventaris_barang.id_inventaris_barang = ".'"'.$kd_inventaris.'"'."
                    ";           

            $query= $this->db->query($sql);*/
            $this->db->where('id_inventaris_distribusi',"$id_distribusi");
                    $this->db->where('id_inventaris_barang',"$kd_inventaris");
            $query= $this->db->get('kibe2');

        }else if($pilih_table=='inv_inventaris_barang_f'){   

            /*$sql= "SELECT inv_inventaris_barang_f.*, inv_inventaris_barang.*,
                    (SELECT COUNT(DISTINCT(id_inventaris_barang)) AS jml FROM inv_inventaris_distribusi WHERE id_inventaris_barang IN 
                    (SELECT id_inventaris_barang FROM inv_inventaris_barang WHERE barang_kembar_proc = ".'"'.$kembarproc.'"'.")) 
                    AS jumlah
                    FROM inv_inventaris_barang 
                    LEFT JOIN inv_inventaris_barang_f ON (inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_f.id_inventaris_barang 
                    AND inv_inventaris_barang.id_mst_inv_barang=inv_inventaris_barang_f.id_mst_inv_barang)
                    WHERE inv_inventaris_barang.id_inventaris_barang = ".'"'.$kd_inventaris.'"'."
                    ";           
            $query= $this->db->query($sql);*/
            $this->db->where('id_inventaris_distribusi',"$id_distribusi");
                    $this->db->where('id_inventaris_barang',"$kd_inventaris");
            $query= $this->db->get('kibf2');

        }
        
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
    }
    function get_data_barang_edit_table_all($kembarproc,$id_barang,$kd_inventaris,$pilih_table,$id_distribusi){
        $data = array();
            $this->db->where('id_inventaris_distribusi',"$id_distribusi");
            $this->db->where('inv_inventaris_barang.id_inventaris_barang',"$kd_inventaris");
            $this->db->group_by("inv_inventaris_barang.id_inventaris_barang");
            $this->db->select("inv_inventaris_distribusi.status,COUNT(inv_inventaris_barang.id_inventaris_barang) AS jumlah,SUM(inv_inventaris_barang.harga) AS totalharga,inv_inventaris_distribusi.id_ruangan AS id_ruangan,inv_inventaris_distribusi.id_cl_phc AS id_cl_phc,inv_inventaris_barang.register AS register,cl_phc.value AS puskesmas,mst_inv_ruangan.nama_ruangan AS nama_ruangan,mst_inv_pilihan.value AS value,inv_inventaris_barang.id_inventaris_barang AS id_inventaris_barang,inv_inventaris_barang.id_mst_inv_barang AS id_mst_inv_barang,inv_inventaris_barang.id_pengadaan AS id_pengadaan,inv_inventaris_barang.pilihan_keadaan_barang AS pilihan_keadaan_barang,inv_inventaris_barang.nama_barang AS nama_barang,inv_inventaris_barang.pilihan_komponen AS pilihan_komponen,inv_inventaris_barang.harga AS harga,inv_inventaris_barang.keterangan_pengadaan AS keterangan_pengadaan,inv_inventaris_barang.pilihan_status_invetaris AS pilihan_status_invetaris,inv_inventaris_barang.tanggal_pembelian AS tanggal_pembelian,inv_inventaris_barang.foto_barang AS foto_barang,inv_inventaris_barang.barang_kembar_proc AS barang_kembar_proc,inv_inventaris_barang.keterangan_inventory AS keterangan_inventory,inv_inventaris_barang.tanggal_pengadaan AS tanggal_pengadaan,inv_inventaris_barang.tanggal_diterima AS tanggal_diterima,inv_inventaris_barang.tanggal_dihapus AS tanggal_dihapus,inv_inventaris_barang.alasan_penghapusan AS alasan_penghapusan,inv_inventaris_barang.pilihan_asal AS pilihan_asal,inv_inventaris_barang.waktu_dibuat AS waktu_dibuat,inv_inventaris_barang.terakhir_diubah AS terakhir_diubah,inv_inventaris_distribusi.id_inventaris_distribusi AS id_inventaris_distribusi");
            if (substr($id_barang, 0,2)=='01') {
                $this->db->select("inv_inventaris_barang_a.*,satuan.value AS satuan,hak.value AS hak,penggunaan.value AS penggunaan");
                $this->db->join("inv_inventaris_barang_a","inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_a.id_inventaris_barang AND inv_inventaris_barang.id_mst_inv_barang = inv_inventaris_barang_a.id_mst_inv_barang",'left');    
                $this->db->join("mst_inv_pilihan satuan","inv_inventaris_barang_a.pilihan_satuan_barang = satuan.code AND satuan.tipe = 'satuan'",'left');
                $this->db->join("mst_inv_pilihan hak","inv_inventaris_barang_a.pilihan_status_hak = hak.code AND (hak.tipe = 'status_hak')",'left');
                $this->db->join("mst_inv_pilihan penggunaan"," inv_inventaris_barang_a.pilihan_penggunaan = penggunaan.code AND (penggunaan.tipe = 'penggunaan')",'left');
                $this->db->join("mst_inv_pilihan asal_usul"," inv_inventaris_barang.pilihan_asal = asal_usul.code AND (asal_usul.tipe = 'asal_usul')",'left');
                
            }else if(substr($id_barang, 0,2)=='02'){
                $this->db->select("inv_inventaris_barang_b.*,satuan.value AS satuan,bahan.value AS bahan,asal_usul.value AS asal_usul,keadaan_barang.value AS keadaan_barang");
                $this->db->join("inv_inventaris_barang_b","inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_b.id_inventaris_barang AND inv_inventaris_barang.id_mst_inv_barang = inv_inventaris_barang_b.id_mst_inv_barang",'left');
                $this->db->join("mst_inv_pilihan satuan","inv_inventaris_barang_b.pilihan_satuan = satuan.code AND satuan.tipe = 'satuan'",'left');
                $this->db->join("mst_inv_pilihan keadaan_barang","inv_inventaris_barang.pilihan_keadaan_barang = keadaan_barang.CODE AND keadaan_barang.tipe = 'keadaan_barang'",'left');
                $this->db->join("mst_inv_pilihan bahan"," inv_inventaris_barang_b.pilihan_bahan = bahan.code AND (bahan.tipe = 'penggunaan')",'left');
                $this->db->join("mst_inv_pilihan asal_usul","inv_inventaris_barang.pilihan_asal = asal_usul.CODE AND (asal_usul.tipe = 'asal_usul')",'left');
            }
            else if(substr($id_barang, 0,2)=='03'){
                $this->db->select("inv_inventaris_barang_c.*");
                $this->db->join("inv_inventaris_barang_c","inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_c.id_inventaris_barang AND inv_inventaris_barang.id_mst_inv_barang = inv_inventaris_barang_c.id_mst_inv_barang",'left');
                /*$this->db->join("mst_inv_pilihan hak","inv_inventaris_barang_c.pilihan_satuan = hak.code AND hak.tipe = 'status_hak'",'left');
                $this->db->join("mst_inv_pilihan tingkat","inv_inventaris_barang_c.pilihan_keadaan_barang = tingkat.CODE AND tingkat.tipe = 'kons_tingkat'",'left');
                $this->db->join("mst_inv_pilihan beton"," inv_inventaris_barang_c.pilihan_bahan = beton.code AND (beton.tipe = 'penggunaan')",'left');
                $this->db->join("mst_inv_pilihan asal_usul","inv_inventaris_barang.pilihan_asal = asal_usul.CODE AND (asal_usul.tipe = 'asal_usul')",'left');*/
            }else if(substr($id_barang, 0,2)=='04'){
                $this->db->select("inv_inventaris_barang_d.*");
                $this->db->join("inv_inventaris_barang_d","inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_d.id_inventaris_barang AND inv_inventaris_barang.id_mst_inv_barang = inv_inventaris_barang_d.id_mst_inv_barang",'left');
            }else if(substr($id_barang, 0,2)=='05'){
                $this->db->select("inv_inventaris_barang_e.*");
                $this->db->join("inv_inventaris_barang_e","inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_e.id_inventaris_barang AND inv_inventaris_barang.id_mst_inv_barang = inv_inventaris_barang_e.id_mst_inv_barang",'left');
            }else if(substr($id_barang, 0,2)=='06'){
                $this->db->select("inv_inventaris_barang_f.*");
                $this->db->join("inv_inventaris_barang_f","inv_inventaris_barang.id_inventaris_barang = inv_inventaris_barang_f.id_inventaris_barang AND inv_inventaris_barang.id_mst_inv_barang = inv_inventaris_barang_f.id_mst_inv_barang",'left');
            }
            $this->db->join("mst_inv_pilihan","inv_inventaris_barang.pilihan_status_invetaris  = mst_inv_pilihan.code AND mst_inv_pilihan.tipe = 'status_inventaris'",'left');
            $this->db->join("inv_inventaris_distribusi","inv_inventaris_barang.id_inventaris_barang = inv_inventaris_distribusi.id_inventaris_barang",'left');
            $this->db->join("cl_phc","cl_phc.code =  inv_inventaris_distribusi.id_cl_phc ",'left');
            $this->db->join("mst_inv_ruangan","mst_inv_ruangan.code_cl_phc =  inv_inventaris_distribusi.id_cl_phc 
            AND mst_inv_ruangan.id_mst_inv_ruangan = inv_inventaris_distribusi.id_ruangan",'left');
            $query= $this->db->get('inv_inventaris_barang');
        
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
    }
    public function getSelectedData($table,$data){
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

    function get_inventaris($id)
    {
        $this->db->where('id_inventaris_barang',$id);
        $result = $this->db->get('inv_inventaris_barang')->row_array();
        if (empty($result))        {
            return false;
        }else {
            return $result;
        }

    }

    function get_inventarisbarang_id($id,$barang,$table)
    {
        $query  = $this->db->query("SELECT max(id_inventaris_barang) as id from $table WHERE id_pengadaan=$id AND id_mst_inv_barang=$barang");
        $result = $query->result();
        if (empty($result))
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
        $data['tgl_pengadaan']              = date("Y-m-d",strtotime($this->input->post('tgl')));
        $data['pilihan_status_pengadaan']   = $this->input->post('status');
        $data['keterangan']                 = $this->input->post('keterangan');
        $data['nomor_kontrak']              = $this->input->post('nomor_kontrak');
        $data['waktu_dibuat']               = date('Y-m-d H:i:s');
        $data['terakhir_diubah']            = "0000-00-00 00:00:00";
        $data['jumlah_unit']                = 0;
        $data['nilai_pengadaan']            = 0;
        if($this->db->insert($this->tabel, $data)){
            return $this->db->insert_id();
        }else{
            return mysql_error();
        }
    }
    function get_data_puskesmas($start=0,$limit=999999,$options=array())
    {
        $this->db->order_by('value','asc');
        // $this->db->where(code)
        $query = $this->db->get('cl_phc',$limit,$start);
        return $query->result();
    }
    function get_data_tanah($start=0,$limit=999999,$options=array())
    {
        $this->db->order_by('code','asc');
        $this->db->where("code like '%00000000'");
        $query = $this->db->get('mst_inv_barang',$limit,$start);
        return $query->result();
    }
    function tanggal($pengadaan){
        $query = $this->db->query("select tgl_pengadaan from inv_pengadaan where id_pengadaan = $pengadaan")->result();
        foreach ($query as $key) {
            return $key->tgl_pengadaan;
        }
    }
    function insert_data_from($id_barang,$kode_proc,$tanggal_diterima,$id_pengadaan){
        //$tanggal = $this->tanggal($kode);
        $values = array(
            'id_mst_inv_barang'     => $id_barang,
            'id_pengadaan'          => $id_pengadaan,
            'pilihan_asal'          => $this->input->post('pilihan_asal_usul'),
            'nama_barang'           => $this->input->post('nama_barang'),
            'harga'                 => $this->input->post('harga'),
            'keterangan_pengadaan'  => $this->input->post('keterangan_pengadaan'),
            'tanggal_diterima'      => $tanggal_diterima,
            'barang_kembar_proc'    => $kode_proc,
            'pilihan_status_invetaris'  => $this->input->post('pilihan_status_invetaris'),
            /*'tanggal_pembelian'   => $tanggal,
            'tanggal_pengadaan'     => $tanggal,*/
        );
        if($this->db->insert('inv_inventaris_barang', $values)){
            return $this->db->insert_id();
        }else{
            return mysql_error();
        }
    }

    function update_data_from($kode_proc,$tanggal_diterima){
        //$tanggal = $this->tanggal($kode);
        $values = array(
            'pilihan_asal'          => $this->input->post('pilihan_asal_usul'),
            'nama_barang'           => $this->input->post('nama_barang'),
            'harga'                 => $this->input->post('harga'),
            'keterangan_pengadaan'  => $this->input->post('keterangan_pengadaan'),
            'tanggal_diterima'      => $tanggal_diterima,
            'pilihan_status_invetaris'  => $this->input->post('pilihan_status_invetaris'),
            /*'tanggal_pembelian'   => $tanggal,
            'tanggal_pengadaan'     => $tanggal,*/
        );
        if($this->db->update('inv_inventaris_barang', $values, array('barang_kembar_proc' => $kode_proc))){

            $this->db->where("barang_kembar_proc",$kode_proc);
            $query = $this->db->get("inv_inventaris_barang");

            $id = array();
            foreach($query->result() as $k){
                $id[] = $k->id_inventaris_barang;
            }
            return $id;
        }else{
            return mysql_error();
        }
    }

    function update_entry($kode)
    {
        $data['tgl_pengadaan']             = date("Y-m-d",strtotime($this->input->post('tgl')));
        $data['pilihan_status_pengadaan']   = $this->input->post('status');
        $data['keterangan']                 = $this->input->post('keterangan');
        $data['nomor_kontrak']              = $this->input->post('nomor_kontrak');
        $data['terakhir_diubah']            = date('Y-m-d');
        $this->db->where('id_pengadaan',$kode);

        if($this->db->update($this->tabel, $data)){
            return true;
        }else{
            return mysql_error();
        }
    }
    public function getSelectedkembar($table,$data)
    {
        $this->db->select('barang_kembar_proc');
        return $this->db->get_where($table, $data);
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
    function get_foto($id){
        $this->db->select("*");
        $this->db->where('id_inventaris_barang',$id);
        $query=$this->db->get('inv_inventaris_barang_foto');
        return $query->result();
    }
    function doupload($upload_data,$id){
        $data['namafile'] = $upload_data['file_name'];
        $data['id_inventaris_barang'] = $id;
        $this->db->insert('inv_inventaris_barang_foto',$data);
        return $id;
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

    function update_status()
    {   
        $pilihan_inv    = $this->input->post('pilihan_inv');
        $kode_proc      = $this->input->post('kode_proc');
        
        $this->db->where('barang_kembar_proc',$kode_proc);
        $id = $this->db->get("inv_inventaris_barang")->result(); 

        foreach ($id as $key) {
            $data['pilihan_status_invetaris']   = $this->tampilstatus_id($pilihan_inv,'status_inventaris');
            $this->db->update('inv_inventaris_barang', $data,array('id_inventaris_barang'=> $key->id_inventaris_barang));
        }
            

    }
    function sum_jumlah_item($kode,$tipe){
        $this->db->select_sum($tipe);
        $this->db->where('id_pengadaan',$kode);
        $query=$this->db->get('inv_inventaris_barang');
        if($query->num_rows()>0)
        {
            foreach($query->result() as $k)
            {
                $jumlah = $k->harga;
            }
        }
        else
        {
            $jumlah = 0;
        }
        return  $jumlah;
    }
    function barang_kembar_proc($kode){
        $q = $this->db->query("SELECT  MAX(RIGHT(barang_kembar_proc,3)) as kd_max FROM inv_inventaris_barang WHERE id_mst_inv_barang=$kode ORDER BY barang_kembar_proc DESC");
        $kd = "";
        if($q->num_rows()>0)
        {
           foreach($q->result() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%03s", $tmp);
            }
        }
        else
        {
            $kd = "001";
        }
        return $kode.$kd;
    }
    function sum_unit($kode)
    {
        $this->db->select("*");
        $this->db->where('id_pengadaan',$kode);  
        return $query = $this->db->get("inv_inventaris_barang"); 
    }
    function delete_entry($kode)
    {
        $this->db->where('id_pengadaan',$kode);

        return $this->db->delete($this->tabel);
    }
    function delete_entryitem($id_barang,$kd_proc,$kd_inventaris){
        $this->db->where('id_inventaris_barang',$kd_inventaris);
        $this->db->delete('inv_inventaris_distribusi');
        $this->db->where('id_inventaris_barang',$kd_inventaris);
        $this->db->delete('inv_keadaan_barang');

        $kodebarang_ = substr($id_barang, 0,2);
        if($kodebarang_=='01') {
            $this->db->where('id_inventaris_barang',$kd_inventaris);
            $this->db->delete('inv_inventaris_barang_a');
        }else if($kodebarang_=='02') {  
            $this->db->where('id_inventaris_barang',$kd_inventaris);
            $this->db->delete('inv_inventaris_barang_b');
        }else if($kodebarang_=='03') {  
            $this->db->where('id_inventaris_barang',$kd_inventaris);
            $this->db->delete('inv_inventaris_barang_c');
        }else if($kodebarang_=='04') {                 
            $this->db->where('id_inventaris_barang',$kd_inventaris);
            $this->db->delete('inv_inventaris_barang_d');
        }else if($kodebarang_=='05') {  
            $this->db->where('id_inventaris_barang',$kd_inventaris);
            $this->db->delete('inv_inventaris_barang_e');
        }else if($kodebarang_=='06') {  
            $this->db->where('id_inventaris_barang',$kd_inventaris);
            $this->db->delete('inv_inventaris_barang_f');
        }

        $this->db->where('id_inventaris_barang',$kd_inventaris);
        return $this->db->delete('inv_inventaris_barang');
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
}