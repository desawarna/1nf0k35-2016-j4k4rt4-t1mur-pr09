<?php
class Bhp_kondisi_model extends CI_Model {

    var $tabel    = 'mst_inv_barang_habispakai';
    var $t_puskesmas = 'cl_phc';
	var $lang	  = '';

    function __construct() {
        parent::__construct();
		$this->lang	  = $this->config->item('language');

    }
    
    function get_data_puskesmas($start=0,$limit=999999,$options=array())
    {
        $this->db->order_by('value','asc');
        $query = $this->db->get($this->t_puskesmas,$limit,$start);
        return $query->result();
    }

    public function getitem($start=0,$limit=999999,$options=array()){
        $this->db->having('jml_asli > 0');
        $query = $this->db->get("bhp_kondisi",$limit,$start);
        return $query->result();
    }
    function get_data_detail_edit_barang_edit($barang,$batch,$pus,$tgl,$opname)
    {
        $tglop = explode("-", $tgl);
        $this->db->where('id_mst_inv_barang_habispakai',$barang);
        $this->db->where('id_inv_inventaris_habispakai_opname',$opname);
        $this->db->where('batch',$batch);
        $this->db->where('code_cl_phc',$pus);
        $query = $this->db->get("bhp_kondisi");
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }
        $query->free_result();    
        return $data;
    }
    function get_data($start=0,$limit=999999,$options=array())
    {
        $kodepuskesmas = "P".$this->session->userdata("puskesmas");
        $data = array();
        $this->db->select("mst_inv_barang_habispakai.*,mst_inv_pilihan.value as value, 
            (select jml as jml from  inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jmlbaik,
            (select jml_rusak as jmlrusak from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai  and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jml_rusak,
            (select jml_tdkdipakai as jmltdkdipakai from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jml_tdkdipakai,
            (SELECT SUM(jml) AS jmltotal   FROM inv_inventaris_habispakai_pembelian_item JOIN inv_inventaris_habispakai_pembelian ON (inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian AND inv_inventaris_habispakai_pembelian.code_cl_phc = inv_inventaris_habispakai_pembelian_item.code_cl_phc AND inv_inventaris_habispakai_pembelian.pilihan_status_pembelian=2)   WHERE inv_inventaris_habispakai_pembelian_item.code_cl_phc=".'"'.$kodepuskesmas.'"'." AND id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.tgl_update > IF((SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc) IS NOT NULL,(SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc),  (SELECT MIN(tgl_update- INTERVAL 1 DAY) FROM inv_inventaris_habispakai_pembelian_item))AND inv_inventaris_habispakai_pembelian_item.tgl_update <= CURDATE()) AS totaljumlah,
            (SELECT SUM(jml) AS jmlpeng  FROM inv_inventaris_habispakai_pengeluaran WHERE id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai AND code_cl_phc=".'"'.$kodepuskesmas.'"'." AND inv_inventaris_habispakai_pengeluaran.tgl_update > IF((SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pengeluaran.code_cl_phc = inv_inventaris_habispakai_opname.code_cl_phc) IS NOT NULL,(SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pengeluaran.code_cl_phc = inv_inventaris_habispakai_opname.code_cl_phc), (SELECT MIN(tgl_update - INTERVAL 1 DAY) FROM inv_inventaris_habispakai_pengeluaran)) AND inv_inventaris_habispakai_pengeluaran.tgl_update <= CURDATE()   ORDER BY tgl_update DESC LIMIT 1) AS jmlpengeluaran,
            (select tgl_update as tglupdate from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_update,
            (select harga as hargaopname from  inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as harga_opname,
            (select harga as hargapembelian from inv_inventaris_habispakai_pembelian_item 
            where code_cl_phc=".'"'.$kodepuskesmas.'"'." and id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai 
            ORDER BY tgl_update DESC LIMIT 1) as harga_pembelian,
            (select tgl_update  as tglopname from inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_opname,
            (select tgl_update  as tglpembelian from inv_inventaris_habispakai_pembelian_item where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_pembelian
             ");
        $this->db->join('mst_inv_pilihan',"mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code and mst_inv_pilihan.tipe='satuan_bhp'",'left');
        $query = $this->db->get('mst_inv_barang_habispakai',$limit,$start);
        return $query->result();
    }
    function get_data_export($start=0,$limit=999999,$options=array())
    {
        $kodepuskesmas = "P".$this->session->userdata("puskesmas");
        $data = array();
        $this->db->distinct();
        $this->db->select("mst_inv_barang_habispakai.*,mst_inv_pilihan.value as value, mst_inv_barang_habispakai_jenis.uraian as nama_jenis,
            (select jml as jml from  inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jmlbaik,
            (select jml_rusak as jmlrusak from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai  and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jml_rusak,
            (select jml_tdkdipakai as jmltdkdipakai from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jml_tdkdipakai,
            (SELECT SUM(jml) AS jmltotal   FROM inv_inventaris_habispakai_pembelian_item JOIN inv_inventaris_habispakai_pembelian ON (inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian AND inv_inventaris_habispakai_pembelian.code_cl_phc = inv_inventaris_habispakai_pembelian_item.code_cl_phc AND inv_inventaris_habispakai_pembelian.pilihan_status_pembelian=2)   WHERE inv_inventaris_habispakai_pembelian_item.code_cl_phc=".'"'.$kodepuskesmas.'"'." AND id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.tgl_update > IF((SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc) IS NOT NULL,(SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc),  (SELECT MIN(tgl_update- INTERVAL 1 DAY) FROM inv_inventaris_habispakai_pembelian_item))AND inv_inventaris_habispakai_pembelian_item.tgl_update <= CURDATE()) AS totaljumlah,
            (SELECT SUM(jml) AS jmlpeng  FROM inv_inventaris_habispakai_pengeluaran WHERE id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai AND code_cl_phc=".'"'.$kodepuskesmas.'"'." AND inv_inventaris_habispakai_pengeluaran.tgl_update > IF((SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pengeluaran.code_cl_phc = inv_inventaris_habispakai_opname.code_cl_phc) IS NOT NULL,(SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pengeluaran.code_cl_phc = inv_inventaris_habispakai_opname.code_cl_phc), (SELECT MIN(tgl_update - INTERVAL 1 DAY) FROM inv_inventaris_habispakai_pengeluaran)) AND inv_inventaris_habispakai_pengeluaran.tgl_update <= CURDATE()   ORDER BY tgl_update DESC LIMIT 1) AS jmlpengeluaran,
            (select tgl_update as tglupdate from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_update,
            (select harga as hargaopname from  inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as harga_opname,
            (select harga as hargapembelian from inv_inventaris_habispakai_pembelian_item 
            where code_cl_phc=".'"'.$kodepuskesmas.'"'." and id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai 
            ORDER BY tgl_update DESC LIMIT 1) as harga_pembelian,
            (select tgl_update  as tglopname from inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_opname,
            (select tgl_update  as tglpembelian from inv_inventaris_habispakai_pembelian_item where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_pembelian
             ");
        $this->db->join('mst_inv_pilihan',"mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code and mst_inv_pilihan.tipe='satuan_bhp'",'left');
        $this->db->join('mst_inv_barang_habispakai_jenis',"mst_inv_barang_habispakai_jenis.id_mst_inv_barang_habispakai_jenis=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai_jenis");
        $this->db->join('inv_inventaris_habispakai_pembelian_item',"inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and inv_inventaris_habispakai_pembelian_item.code_cl_phc=".'"'.$kodepuskesmas.'"'."");
        $query = $this->db->get('mst_inv_barang_habispakai',$limit,$start);
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
    function get_data_detail_edit_barang($kode){
        $kodepuskesmas = "P".$this->session->userdata("puskesmas");
        $data = array();
        //$this->db->order_by('inv_inventaris_habispakai_opname.tgl_update','desc');- ((select jml_rusak as jmlrusak from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai order by tgl_update desc limit 1)+(select jml_tdkdipakai as jmltdkdipakai from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai order by tgl_update desc limit 1))
        $this->db->where("mst_inv_barang_habispakai.id_mst_inv_barang_habispakai",$kode);
        $this->db->select("mst_inv_barang_habispakai.*,mst_inv_pilihan.value as nama_satuan,
            (select jml  as jumlah from inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jml,
            (select jml_rusak as jmlrusak from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jml_rusak,
            (select jml_tdkdipakai as jmltdkdipakai from  inv_inventaris_habispakai_kondisi where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as jml_tdkdipakai,
            (SELECT SUM(jml) AS jmltotal   FROM inv_inventaris_habispakai_pembelian_item JOIN inv_inventaris_habispakai_pembelian ON (inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian AND inv_inventaris_habispakai_pembelian.code_cl_phc = inv_inventaris_habispakai_pembelian_item.code_cl_phc AND inv_inventaris_habispakai_pembelian.pilihan_status_pembelian=2)   WHERE inv_inventaris_habispakai_pembelian_item.code_cl_phc=".'"'.$kodepuskesmas.'"'." AND id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.tgl_update > IF((SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc) IS NOT NULL,(SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc),  (SELECT MIN(tgl_update- INTERVAL 1 DAY) FROM inv_inventaris_habispakai_pembelian_item))AND inv_inventaris_habispakai_pembelian_item.tgl_update <= CURDATE()) AS totaljumlah,
            (SELECT SUM(jml) AS jmlpeng  FROM inv_inventaris_habispakai_pengeluaran WHERE id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai AND code_cl_phc=".'"'.$kodepuskesmas.'"'." AND inv_inventaris_habispakai_pengeluaran.tgl_update > IF((SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pengeluaran.code_cl_phc = inv_inventaris_habispakai_opname.code_cl_phc) IS NOT NULL,(SELECT MAX(tgl_update) FROM inv_inventaris_habispakai_opname WHERE inv_inventaris_habispakai_pengeluaran.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pengeluaran.code_cl_phc = inv_inventaris_habispakai_opname.code_cl_phc), (SELECT MIN(tgl_update - INTERVAL 1 DAY) FROM inv_inventaris_habispakai_pengeluaran)) AND inv_inventaris_habispakai_pengeluaran.tgl_update <= CURDATE()   ORDER BY tgl_update DESC LIMIT 1) AS jmlpengeluaran,
            (select tgl_update  as tglupdate from inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_update,
            (select harga as hargaopname from  inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as harga_opname,
            (select harga as hargapembelian from inv_inventaris_habispakai_pembelian_item 
            where code_cl_phc=".'"'.$kodepuskesmas.'"'." and id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai 
            ORDER BY tgl_update DESC LIMIT 1) as harga_pembelian,
            (select tgl_update  as tglopname from inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_opname,
            (select tgl_update  as tglpembelian from inv_inventaris_habispakai_pembelian_item where id_mst_inv_barang_habispakai = mst_inv_barang_habispakai.id_mst_inv_barang_habispakai and code_cl_phc=".'"'.$kodepuskesmas.'"'." order by tgl_update desc limit 1) as tgl_pembelian
            ");
        $this->db->join('mst_inv_pilihan',"mst_inv_barang_habispakai.pilihan_satuan=mst_inv_pilihan.code and mst_inv_pilihan.tipe='satuan_bhp'",'left');
        /*$this->db->join('inv_inventaris_habispakai_opname',"inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai=mst_inv_barang_habispakai.id_mst_inv_barang_habispakai",'left');*/
        $query = $this->db->get('mst_inv_barang_habispakai',1,0);
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }
        $query->free_result();    
        return $data;
    }
    function get_barang($kode){
        $puskesmas = 'P'.$this->session->userdata('puskesmas');
        $this->db->order_by('tgl_update','desc');
        $this->db->where("inv_inventaris_habispakai_opname.code_cl_phc",$puskesmas);
        $this->db->where("id_mst_inv_barang_habispakai",$kode);
        $this->db->select("inv_inventaris_habispakai_opname.*,
            (select sum(jml) as jmltotal from inv_inventaris_habispakai_pembelian_item 
            JOIN inv_inventaris_habispakai_pembelian ON(inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian AND inv_inventaris_habispakai_pembelian.code_cl_phc = inv_inventaris_habispakai_pembelian_item.code_cl_phc AND inv_inventaris_habispakai_pembelian.pilihan_status_pembelian=2)  
            where inv_inventaris_habispakai_pembelian_item.code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc and inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai ) as totaljumlah,
            (select jml as jmlpeng from  inv_inventaris_habispakai_pengeluaran where id_mst_inv_barang_habispakai=inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai and code_cl_phc=inv_inventaris_habispakai_opname.code_cl_phc order by tgl_update desc limit 1) as jmlpengeluaran,
            ");
        return $query = $this->db->get('inv_inventaris_habispakai_opname',3,0)->result();
    }
    function get_kondisi_barang($kode){
        $puskesmas = 'P'.$this->session->userdata('puskesmas');
        $this->db->order_by('inv_inventaris_habispakai_kondisi.tgl_update','desc');
        $this->db->where("inv_inventaris_habispakai_kondisi.code_cl_phc",$puskesmas);
        $this->db->where("inv_inventaris_habispakai_kondisi.id_mst_inv_barang_habispakai",$kode);
        $this->db->select("inv_inventaris_habispakai_kondisi.*,
            (select jml as jumlah from inv_inventaris_habispakai_opname where id_mst_inv_barang_habispakai = inv_inventaris_habispakai_kondisi.id_mst_inv_barang_habispakai and code_cl_phc=inv_inventaris_habispakai_kondisi.code_cl_phc order by tgl_update desc limit 1) as jml,
            (select sum(jml) as jmltotal from inv_inventaris_habispakai_pembelian_item 
            JOIN inv_inventaris_habispakai_pembelian ON(inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian AND inv_inventaris_habispakai_pembelian.code_cl_phc = inv_inventaris_habispakai_pembelian_item.code_cl_phc AND inv_inventaris_habispakai_pembelian.pilihan_status_pembelian=2)  
            where inv_inventaris_habispakai_pembelian_item.code_cl_phc=inv_inventaris_habispakai_kondisi.code_cl_phc and inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_kondisi.id_mst_inv_barang_habispakai ) as totaljumlah,
            (select jml as jmlpeng from  inv_inventaris_habispakai_pengeluaran where id_mst_inv_barang_habispakai=inv_inventaris_habispakai_kondisi.id_mst_inv_barang_habispakai and code_cl_phc=inv_inventaris_habispakai_kondisi.code_cl_phc order by tgl_update desc limit 1) as jmlpengeluaran
            ");
       /* $this->db->join('inv_inventaris_habispakai_opname',"inv_inventaris_habispakai_opname.id_mst_inv_barang_habispakai=inv_inventaris_habispakai_kondisi.id_mst_inv_barang_habispakai and inv_inventaris_habispakai_opname.code_cl_phc=inv_inventaris_habispakai_kondisi.code_cl_phc AND inv_inventaris_habispakai_opname.tgl_update = inv_inventaris_habispakai_kondisi.tgl_update");*/
        return $query = $this->db->get('inv_inventaris_habispakai_kondisi',3,0)->result();
    }
    function insertdata($barang,$batch,$pus,$tgl,$opname){
        $this->db->where('tgl_update',(date('Y-m-d')));
        $this->db->where('code_cl_phc',$pus);
        $this->db->where('id_mst_inv_barang_habispakai',$barang);
        $this->db->where('batch',$batch);
        $this->db->where('id_inv_inventaris_habispakai_opname',$opname);
        $this->db->select("*");
        $query = $this->db->get("inv_inventaris_habispakai_kondisi");
           if ($query->num_rows() > 0){
                $dataupdate = array(
                    'jml_rusak' => $this->input->post('jml_rusak'),
                    'jml_tdkdipakai' => $this->input->post('jml_tdkdipakai'),
                    );
                $datakey = array(
                    'id_mst_inv_barang_habispakai'  =>$barang,
                    'code_cl_phc'                   =>$pus,
                    'batch'                         =>$batch,
                    'tgl_update'                    => date('Y-m-d'),
                    'id_inv_inventaris_habispakai_opname'                        => $opname,
                     );
                if($simpan=$this->db->update("inv_inventaris_habispakai_kondisi",$dataupdate,$datakey)){
                    return true;
                }else{
                    return mysql_error();
                }
            }else{
                $values = array(
                    'id_mst_inv_barang_habispakai'  =>$barang,
                    'code_cl_phc'                   =>$pus,
                    'batch'                         =>$batch,
                    'id_inv_inventaris_habispakai_opname'                        => $opname,
                    'tgl_update'                    => date('Y-m-d'),
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
    function insertdatakondisi(){
        $this->db->where('tgl_update',date('Y-m-d'));
        $this->db->where('code_cl_phc','P'.$this->session->userdata('puskesmas'));
        $this->db->where('id_mst_inv_barang_habispakai',$this->input->post('id_mst_inv_barang_habispakai'));
        $this->db->select("*");
        $query = $this->db->get("inv_inventaris_habispakai_kondisi");
           if ($query->num_rows() > 0){
                $dataupdate = array(
                    'jml_rusak' => $this->input->post('jml_rusak'),
                    'jml_tdkdipakai' => $this->input->post('jml_tdkdipakai'),
                    );
                $datakey = array(
                    'id_mst_inv_barang_habispakai'  =>$this->input->post('id_mst_inv_barang_habispakai'),
                    'code_cl_phc'                   =>'P'.$this->session->userdata('puskesmas') ,
                    'tgl_update'=> date('Y-m-d'),
                     );
                if($simpan=$this->db->update("inv_inventaris_habispakai_kondisi",$dataupdate,$datakey)){
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
}