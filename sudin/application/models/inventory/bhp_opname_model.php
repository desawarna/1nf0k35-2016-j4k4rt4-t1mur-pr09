<?php
class Bhp_opname_model extends CI_Model {

    var $tabel    = 'mst_inv_barang_habispakai';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');

    }
    function get_data_puskesmas($start=0,$limit=999999,$options=array())
    {
        $this->db->order_by('value','asc');
        // $this->db->where(code)
        $query = $this->db->get('cl_phc',$limit,$start);
        return $query->result();
    }

    function get_data($start=0,$limit=999999,$options=array())
    {
        $this->db->where('inv_inventaris_habispakai_opname.tipe','opname');
        $this->db->select("(SELECT a.tgl_opname FROM inv_inventaris_habispakai_opname a WHERE a.jenis_bhp = inv_inventaris_habispakai_opname.jenis_bhp ORDER BY a.tgl_opname DESC LIMIT 1) AS last_tgl_opname,inv_inventaris_habispakai_opname.*");
        $query = $this->db->get('inv_inventaris_habispakai_opname',$limit,$start);
        return $query->result();
    }
    function get_data_export($start=0,$limit=999999,$options=array())
    {
        $kodepuskesmas = "P".$this->session->userdata("puskesmas");
        $data = array();
        $this->db->select("*");
        $this->db->join('mst_inv_pilihan',"mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code and mst_inv_pilihan.tipe='satuan_bhp'",'left');
        $this->db->join('mst_inv_barang_habispakai_jenis',"mst_inv_barang_habispakai_jenis.id_mst_inv_barang_habispakai_jenis=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis");
        $query = $this->db->get('mst_inv_barang_habispakai',$limit,$start);
        return $query->result();
    }
    function get_datapengeluaran($start=0,$limit=999999,$options=array())
    {
        $kodepuskesmas = "P".$this->session->userdata("puskesmas");
        $data = array();
        $this->db->order_by('inv_inventaris_habispakai_pengeluaran.tgl_update','desc');
        $this->db->where("inv_inventaris_habispakai_pengeluaran.code_cl_phc",$kodepuskesmas);
        $this->db->select("mst_inv_barang_habispakai.uraian,inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai,inv_inventaris_habispakai_pengeluaran.tgl_update,inv_inventaris_habispakai_pengeluaran.harga,inv_inventaris_habispakai_pengeluaran.jml,
            mst_inv_barang_habispakai.pilihan_satuan,mst_inv_pilihan.value as nama_pilihan");
        $this->db->join('mst_inv_barang_habispakai',"mst_inv_barang_habispakai.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai");
        $this->db->join('mst_inv_pilihan',"mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code and mst_inv_pilihan.tipe='satuan_bhp'",'left');
        $query = $this->db->get('inv_inventaris_habispakai_pengeluaran',$limit,$start);
        return $query->result();
    }
    function get_data_jenis()
    {
        $this->db->select('*');
        $query = $this->db->get('mst_inv_barang_habispakai_jenis');
        return $query->result();
    }
    function get_data_detail_edit($kode){
        $data = array();
        $this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai",$kode);
        $this->db->select("mst_inv_barang_habispakai.*,mst_inv_pilihan.value as nama_satuan");
        $this->db->join('mst_inv_pilihan',"mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code and mst_inv_pilihan.tipe='satuan_bhp'",'left');
        $query = $this->db->get('mst_inv_barang_habispakai');
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }
        $query->free_result();    
        return $data;
    }
    function get_data_detail_edit_barang($kodeopname,$idbarang,$batch,$tanggal_opnam='0000-00-00'){
        $kodepuskesmas = "P".$this->session->userdata("puskesmas");
        $data = array();
        $this->db->query("set @var =".'"'.$tanggal_opnam.'"'."");
        $this->db->where("id_mst_inv_barang_habispakai",$idbarang);
        $this->db->where("batch",$batch);
        $this->db->where("code_cl_phc",$kodepuskesmas);
        $this->db->select("*");
        $query = $this->db->get('bhp_distribusi_opname',1,0);

        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }
        $query->free_result();    
        return $data;
    }
    function get_data_detail_bhp($kodeopname,$idbarang,$batch){
        $kodepuskesmas = "P".$this->session->userdata("puskesmas");
        $data = array();
         //$this->db->group_by('id_mst_inv_barang_habispakai','batch');
        $this->db->where('inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai',$idbarang);
        $this->db->where('inv_inventaris_habispakai_opname_item.batch',$batch);
        $this->db->where('inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname',$kodeopname);
        $this->db->select("inv_inventaris_habispakai_opname_item.*,mst_inv_barang_habispakai.uraian,mst_inv_barang_habispakai.pilihan_satuan,mst_inv_barang_habispakai.merek_tipe,inv_inventaris_habispakai_opname.tgl_opname,inv_inventaris_habispakai_opname.petugas_nip,inv_inventaris_habispakai_opname.petugas_nama,inv_inventaris_habispakai_opname.nomor_opname,,inv_inventaris_habispakai_opname.catatan,

            ");
        $this->db->join("mst_inv_barang_habispakai","mst_inv_barang_habispakai.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai");
        $this->db->join("inv_inventaris_habispakai_opname","inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname");
        $query = $this->db->get("inv_inventaris_habispakai_opname_item",1,0);
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }
        $query->free_result();    
        return $data;
    }
    function get_barang($kode){
        $this->db->order_by('tgl_update','desc');
        $this->db->where("id_mst_inv_barang_habispakai",$kode);
        $this->db->select("*");
        return $query = $this->db->get('inv_inventaris_habispakai_opname',3,0)->result();
    }
    function get_kondisi_barang($kode){
        $this->db->order_by('inv_inventaris_habispakai_kondisi.tgl_update','desc');
        $this->db->where("inv_inventaris_habispakai_kondisi.id_mst_inv_barang_habispakai",$kode);
        $this->db->select("inv_inventaris_habispakai_kondisi.*,
            (select jml as jumlah from inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai = inv_inventaris_habispakai_kondisi.id_mst_inv_barang_habispakai and code_cl_phc=inv_inventaris_habispakai_kondisi.code_cl_phc order by tgl_update desc limit 1) as jml
            ");
        return $query = $this->db->get('inv_inventaris_habispakai_kondisi',3,0)->result();
    }
    function tanggalp($kode,$barang,$batch)
    {
        $datapus = "P".$this->session->userdata('puskesmas');
        $this->db->select('tgl_opname');
        $this->db->where('batch',$batch);
        $this->db->where('id_mst_inv_barang_habispakai',$barang);
        $this->db->where('inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname',$kode);
        $this->db->where('code_cl_phc',$datapus);
        $this->db->join('inv_inventaris_habispakai_opname_item','inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname=inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname','left');
        $query = $this->db->get('inv_inventaris_habispakai_opname');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                return $key->tgl_opname;
            }
        }else{
            return '0000-00-00';
        }
    }
    function delete_entryitem($kode,$barang,$batch)
    {
        $tgl_update = $this->tanggalp($kode,$barang,$batch);
        $this->db->where('batch',$batch);
        $this->db->where('id_mst_inv_barang_habispakai',$barang);
        $this->db->where('id_inv_inventaris_habispakai_opname',$kode);
        $this->db->where('tgl_update',$tgl_update);
        $this->db->delete('inv_inventaris_habispakai_kondisi')->result;

        $this->db->where('batch',$batch);
        $this->db->where('id_mst_inv_barang_habispakai',$barang);
        $this->db->where('id_inv_inventaris_habispakai_opname',$kode);
        $query = $this->db->delete('inv_inventaris_habispakai_opname_item');
        return $query->result();
    }
    function update_entry()
    {   $tanggal =explode("-", $this->input->post('tgl_opname'));
        $dataupdate = array(
            'jenis_bhp'     => $this->input->post('jenis_bhp'),
            'petugas_nip'   => $this->input->post('penerima_nip'),
            'petugas_nama'  => $this->input->post('penerima_nama'),
            'catatan'       => $this->input->post('catatan'),
            'nomor_opname'  => $this->input->post('nomor_opname'),
        );
        $datakey = array(
            'id_inv_inventaris_habispakai_opname'           =>$this->input->post('id_inv_inventaris_habispakai_opname'),
            'tgl_opname'                                    =>$tanggal[2].'-'.$tanggal[1].'-'.$tanggal[0],
            'code_cl_phc'                                   =>$this->input->post('puskesmas'),
         );
        return $this->db->update("inv_inventaris_habispakai_opname",$dataupdate,$datakey);
    }
    function insertdata(){
        if ($this->input->post('batch')=='undefined') {
            $nobac='-';
        }else{
            $nobac=$this->input->post('batch');
        }
        $this->db->where('id_inv_inventaris_habispakai_opname',$this->input->post('id_inv_inventaris_habispakai_opname'));
        $this->db->where('id_mst_inv_barang_habispakai',$this->input->post('id_mst_inv_barang_habispakai'));
        $this->db->where('batch',$nobac);
        $this->db->select("*");
        $query = $this->db->get("inv_inventaris_habispakai_opname_item");
           if ($query->num_rows() > 0){
                $keyvaluesdata  = array(
                    'id_mst_inv_barang_habispakai'  => $this->input->post('id_mst_inv_barang_habispakai'),
                    'id_inv_inventaris_habispakai_opname' => $this->input->post('id_inv_inventaris_habispakai_opname'),
                    'batch'                         => $nobac,
                    'code_cl_phc'                   => 'P'.$this->session->userdata('puskesmas'),
                    'tgl_update'                    => $this->input->post('tgl_update_opname')
                );
                $updatevaluesdata  = array(
                    'jml_rusak'                     => $this->input->post('jml_rusak'),
                    'jml_tdkdipakai'                => $this->input->post('jml_tdkdipakai')
                );
                $this->db->update('inv_inventaris_habispakai_kondisi',$updatevaluesdata,$keyvaluesdata);

                $dataupdate = array(
                    'jml_awal'      => $this->input->post('jumlah'),
                    'jml_akhir'     => $this->input->post('jumlahopname'),
                    'harga'         => $this->input->post('harga'),
                    );
                $datakey = array(
                    'id_mst_inv_barang_habispakai'          =>$this->input->post('id_mst_inv_barang_habispakai'),
                    'id_inv_inventaris_habispakai_opname'   =>$this->input->post('id_inv_inventaris_habispakai_opname') ,
                    'batch'                                 =>$nobac,
                     );
                if($simpan=$this->db->update("inv_inventaris_habispakai_opname_item",$dataupdate,$datakey)){
                    return true;
                }else{
                    return mysql_error();
                }
            }else{
                $valuesdata  = array(
                    'jml_rusak'                     => $this->input->post('jml_rusak'),
                    'jml_tdkdipakai'                => $this->input->post('jml_tdkdipakai'),
                    'id_mst_inv_barang_habispakai'  => $this->input->post('id_mst_inv_barang_habispakai'),
                    'id_inv_inventaris_habispakai_opname' => $this->input->post('id_inv_inventaris_habispakai_opname'),
                    'batch'                         => $nobac,
                    'code_cl_phc'                   => 'P'.$this->session->userdata('puskesmas'),
                    'tgl_update'                    => $this->input->post('tgl_update_opname')
                );
                $this->db->insert('inv_inventaris_habispakai_kondisi',$valuesdata);
                $values = array(
                    'jml_awal'                      => $this->input->post('jumlah'),
                    'jml_akhir'                     => $this->input->post('jumlahopname') ,
                    'harga'                         => $this->input->post('harga'),
                    'id_mst_inv_barang_habispakai'  => $this->input->post('id_mst_inv_barang_habispakai'),
                    'id_inv_inventaris_habispakai_opname' => $this->input->post('id_inv_inventaris_habispakai_opname'),
                    'batch'                         => $nobac,
                );
                if($simpan=$this->db->insert('inv_inventaris_habispakai_opname_item', $values)){
                    return true;
                }else{
                    return mysql_error();
                }
            }
            
    }
    function insertdatamaster(){
        if ($this->input->post('batch_master')=='undefined') {
            $nobac='-';
        }else{
            $nobac=$this->input->post('batch_master');
        }
        $this->db->where('id_inv_inventaris_habispakai_opname',$this->input->post('id_inv_inventaris_habispakai_opname_master'));
        $this->db->where('id_mst_inv_barang_habispakai',$this->input->post('id_mst_inv_barang_habispakai_master'));
        $this->db->where('batch',$nobac);
        $this->db->select("*");
        $query = $this->db->get("inv_inventaris_habispakai_opname_item");
           if ($query->num_rows() > 0){
                $dataupdate = array(
                    'jml_awal' => $this->input->post('jumlah_awal_opname'),
                    'jml_akhir' => $this->input->post('jumlah_masteropname'),
                    'harga' => $this->input->post('harga_master'),
                    );
                $datakey = array(
                    'id_mst_inv_barang_habispakai'          =>$this->input->post('id_mst_inv_barang_habispakai_master'),
                    'id_inv_inventaris_habispakai_opname'   =>$this->input->post('id_inv_inventaris_habispakai_opname_master') ,
                    'batch'                                 => $nobac,
                     );
                if($simpan=$this->db->update("inv_inventaris_habispakai_opname_item",$dataupdate,$datakey)){
                    return true;
                }else{
                    return mysql_error();
                }
            }else{
                $values = array(
                    'jml_awal'                      => $this->input->post('jumlah_awal_opname'),
                    'jml_akhir'                     => $this->input->post('jumlah_masteropname') ,
                    'harga'                         => $this->input->post('harga_master'),
                    'id_mst_inv_barang_habispakai'  => $this->input->post('id_mst_inv_barang_habispakai_master'),
                    'id_inv_inventaris_habispakai_opname' => $this->input->post('id_inv_inventaris_habispakai_opname_master'),
                    'batch'                         => $nobac,
                );
                if($simpan=$this->db->insert('inv_inventaris_habispakai_opname_item', $values)){
                    return true;
                }else{
                    return mysql_error();
                }
            }
            
    }
    function insertdatakondisi(){
        $this->db->where('tgl_update',date('Y-m-d'));
        $this->db->where('code_cl_phc','P'.$this->session->userdata('puskesmas'));
        $this->db->where('id_mst_inv_barang_habispakai',$this->input->post('id_mst_inv_barang'));
        $this->db->select("*");
        $query = $this->db->get("inv_inventaris_habispakai_kondisi");
           if ($query->num_rows() > 0){
                $dataupdate = array(
                    'jml' => $this->input->post('dikeluarkan__'),
                    'harga' => $this->input->post('jml_tdkdipakai'),
                    );
                $datakey = array(
                    'id_mst_inv_barang_habispakai'  =>$this->input->post('id_mst_inv_barang_habispakai'),
                    'code_cl_phc'                   =>'P'.$this->session->userdata('puskesmas') ,
                    'tgl_update'=> date('Y-m-d'),
                     );
                if($simpan=$this->db->update("inv_inventaris_habispakai_pengeluaran",$dataupdate,$datakey)){
                    return true;
                }else{
                    return mysql_error();
                }
            }else{
                $values = array(
                    'id_mst_inv_barang_habispakai'  =>$this->input->post('id_mst_inv_barang_habispakai'),
                    'code_cl_phc'                   =>'P'.$this->session->userdata('puskesmas') ,
                    'tgl_update'=> date('Y-m-d'),
                    'jml_rusak' => $this->input->post('jml_rusak'),
                    'jml_tdkdipakai' => $this->input->post('jml_tdkdipakai'),
                );
                if($simpan=$this->db->insert('inv_inventaris_habispakai_kondisi', $values)){
                    return true;
                }else{
                    return mysql_error();
                }
            }
            
    }
    public function getnamajenis()
    {
        $this->db->select("*");
        $query = $this->db->get("mst_inv_barang_habispakai_jenis");
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
    function insert_entry()
    {
        $data['id_inv_inventaris_habispakai_opname'] = $this->kode_distribusi($this->input->post('kode_distribusi_'));
        $data['code_cl_phc']                = $this->input->post('puskesmas');
        $data['jenis_bhp']                  = $this->input->post('jenis_bhp');
        $data['tgl_opname']                 = date("Y-m-d",strtotime($this->input->post('tgl_opname')));
        $data['nomor_opname']               = $this->input->post('nomor_opname');
        $data['petugas_nama']              = $this->input->post('penerima_nama');
        $data['petugas_nip']               = $this->input->post('penerima_nip');
        $data['catatan']                    = $this->input->post('catatan');
        $data['tipe']                       = 'opname';
        if($this->db->insert('inv_inventaris_habispakai_opname', $data)){
            return $data['id_inv_inventaris_habispakai_opname'];
        }else{
            return mysql_error();
        }
    }
    public function getitem($start=0,$limit=999999,$options=array()){
        $this->db->having('jmlawal != 0'); 
        $query = $this->db->get("bhp_distribusi_opname",$limit,$start);
        return $query->result();
    }
    function get_data_opname($start=0,$limit=999999,$options=array())
    {
        $this->db->group_by('id_mst_inv_barang_habispakai','batch');
        $this->db->order_by('id_inv_inventaris_habispakai_opname','desc');
        $this->db->select("inv_inventaris_habispakai_opname_item.*,mst_inv_barang_habispakai.uraian,mst_inv_barang_habispakai.pilihan_satuan,mst_inv_barang_habispakai.merek_tipe,inv_inventaris_habispakai_opname.tgl_opname");
        $this->db->join("mst_inv_barang_habispakai","mst_inv_barang_habispakai.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai");
        $this->db->join("inv_inventaris_habispakai_opname","inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname");
        $query = $this->db->get("inv_inventaris_habispakai_opname_item",$limit,$start);
        return $query->result();
        
    }
    public function getitemopname($start=0,$limit=999999,$options=array()){

        $query = $this->db->get("satuan_opname",$limit,$start);
        return $query->result();
    }

    function get_data_lap_opname($bulan,$tahun,$jenisbhp,$filtername,$ord,$puskesmas)
    {
        $a_date = "$tahun-$bulan-01";
        $last= date("Y-m-t", strtotime($a_date));
          $data = array();
        for($i=1; $i<=31;$i++){
            $tanggal = date("Y-m-d",mktime(0, 0, 0, $bulan, $i, $tahun));
            $pusksmas = $puskesmas;//"P".$this->session->userdata('puskesmas');
            $query =  $this->db->query("
                    SELECT ((Ifnull( 
                   ( 
                            SELECT   a.jml_akhir
                            FROM     inv_inventaris_habispakai_opname_item a 
                            JOIN     inv_inventaris_habispakai_opname b 
                            ON       a.id_inv_inventaris_habispakai_opname = b.id_inv_inventaris_habispakai_opname
                            WHERE    Month(b.tgl_opname) < ".'"'.$bulan.'"'."
                            AND      Year(b.tgl_opname) <= ".'"'.$tahun.'"'."
                            AND      a.id_mst_inv_barang_habispakai= inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai
                            AND      a.batch = inv_inventaris_habispakai_opname_item.batch 
                            AND      a.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname
                            ORDER BY b.tgl_opname DESC limit 1),0))+ (Ifnull( 
                   ( 
                          SELECT Sum(jml) 
                          FROM   inv_inventaris_habispakai_distribusi_item 
                          JOIN   inv_inventaris_habispakai_distribusi 
                          ON     inv_inventaris_habispakai_distribusi_item.id_inv_inventaris_habispakai_distribusi = inv_inventaris_habispakai_distribusi.id_inv_inventaris_habispakai_distribusi
                          WHERE  inv_inventaris_habispakai_distribusi_item.batch = inv_inventaris_habispakai_opname_item.batch
                          AND    inv_inventaris_habispakai_distribusi_item.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai
                          AND    ( 
                                        inv_inventaris_habispakai_distribusi.tgl_distribusi) > Ifnull( 
                                 ( 
                                          SELECT   f.tgl_opname 
                                          FROM     inv_inventaris_habispakai_opname f 
                                          JOIN     inv_inventaris_habispakai_opname_item g 
                                          ON       f.id_inv_inventaris_habispakai_opname = g.id_inv_inventaris_habispakai_opname
                                          WHERE    g.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai
                                          AND      g.batch = inv_inventaris_habispakai_opname_item.batch 
                                          AND      Month(f.tgl_opname) < ".'"'.$bulan.'"'." 
                                          AND      Year(f.tgl_opname) <= ".'"'.$tahun.'"'." 
                                          ORDER BY f.tgl_opname DESC limit 1 ),'0000-00-00') 
                          AND    ( inv_inventaris_habispakai_distribusi.tgl_distribusi) <= ".'"'.$last.'"'."),0))) AS jumlah_awal,
                   inv_inventaris_habispakai_opname_item.harga, 
                   inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai, 
                   mst_inv_barang_habispakai.uraian, 
                   inv_inventaris_habispakai_opname.tgl_opname, 
                   inv_inventaris_habispakai_opname.petugas_nama, 
                   (inv_inventaris_habispakai_opname_item.jml_akhir - inv_inventaris_habispakai_opname_item.jml_awal) AS pengeluaranperhari
            FROM   (inv_inventaris_habispakai_opname) 
            JOIN   inv_inventaris_habispakai_opname_item 
            ON     inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname
            JOIN   mst_inv_barang_habispakai 
            ON     mst_inv_barang_habispakai.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai
            WHERE  inv_inventaris_habispakai_opname.code_cl_phc = ".'"'.$pusksmas.'"'."
            AND    inv_inventaris_habispakai_opname.tgl_opname = ".'"'.$tanggal.'"'."
            and    (inv_inventaris_habispakai_opname.tipe!='terimarusak'
            and    inv_inventaris_habispakai_opname.tipe!='retur')
            $jenisbhp $filtername $ord
     ");
            $datas = $query->result_array();  
           // print_r($datas);
            foreach ($datas as $brg) {
                $data[$brg['uraian']][$i] = $brg;
            }
        }
        //die(print_r($data));
        return $data;
    }
    function get_data_row($kode){
        $data = array();
        $this->db->where("id_inv_inventaris_habispakai_opname",$kode);
        $this->db->select("inv_inventaris_habispakai_opname.*");
        $query = $this->db->get('inv_inventaris_habispakai_opname');
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
    }
    function kode_distribusi($kode){
        $inv=explode(".", $kode);
        $kode_pengadaan = $inv[0].$inv[1].$inv[2].$inv[3].$inv[4].$inv[5].$inv[6];
        $tahun          = $inv[6];
        $urut = $this->nourut($kode_pengadaan);
        return  $kode_pengadaan.$urut;
    }
    function nourut($kode_pengadaan){
        $q = $this->db->query("select MAX(RIGHT(id_inv_inventaris_habispakai_opname,6)) as kd_max from inv_inventaris_habispakai_opname where (LEFT(id_inv_inventaris_habispakai_opname,15))=".'"'.$kode_pengadaan.'"'."");
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
    function deleteopname($value=0)
    {

    }
    function delete_opname($kode=0)
    {   
        $this->deleteopname($kode);
        $this->db->where('id_inv_inventaris_habispakai_opname',$kode);
        $this->db->delete('inv_inventaris_habispakai_opname_item');

        $this->deleteopname($kode);
        $this->db->where('id_inv_inventaris_habispakai_opname',$kode);
        $this->db->delete('inv_inventaris_habispakai_kondisi');


        $this->deleteopname($kode);
        $this->db->where('id_inv_inventaris_habispakai_opname',$kode);
        return $this->db->delete('inv_inventaris_habispakai_opname');
    }
}