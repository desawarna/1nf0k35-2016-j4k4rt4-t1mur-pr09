<?php
class Bhp_retur_model extends CI_Model {

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
    
    function get_barang($kode){
        $this->db->order_by('tgl_update','desc');
        $this->db->where("id_mst_inv_barang_habispakai",$kode);
        $this->db->select("*");
        return $query = $this->db->get('inv_inventaris_habispakai_opname',3,0)->result();
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
    function delete_entryitem($kode)
    {
        $this->db->where('id_inv_inventaris_habispakai_opname',$kode);
        $this->db->delete('inv_inventaris_habispakai_opname_item');

        $this->db->where('id_inv_inventaris_habispakai_opname',$kode);
        $this->db->delete('inv_inventaris_habispakai_opname');
        return true;
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
    
    
    function pilih_data_status($status)
    {   
        $this->db->where("mst_inv_pilihan.tipe",$status);
        $this->db->select('mst_inv_pilihan.*');     
        $this->db->order_by('mst_inv_pilihan.code','asc');
        $query = $this->db->get('mst_inv_pilihan'); 
        return $query->result();    
    }
    function insert_entry($jenis,$barang,$batch)
    {
        if ($this->input->post('id_mst_inv_barang_habispakai_jenis')=='8') {
            $jenisbhp = 'obat';
        }else{
            $jenisbhp = 'umum';
        }
        $data['id_inv_inventaris_habispakai_opname'] = $this->kode_distribusi($this->input->post('kode_distribusi_'));
        $data['code_cl_phc']                = $this->input->post('puskesmas');
        $data['jenis_bhp']                  = $jenisbhp;
        $data['tgl_opname']                 = date("Y-m-d",strtotime($this->input->post('tgl_opname')));
        $data['nomor_opname']               = $this->input->post('nomor_opname');
        $data['petugas_nama']              = $this->input->post('penerima_nama');
        $data['petugas_nip']               = $this->input->post('penerima_nip');
        $data['catatan']                    = $this->input->post('catatan');
        $data['tipe']                       = 'retur';
        if($this->db->insert('inv_inventaris_habispakai_opname', $data)){
            $datachild['id_inv_inventaris_habispakai_opname']       = $data['id_inv_inventaris_habispakai_opname'];
            $datachild['id_mst_inv_barang_habispakai']      = $barang;
            $datachild['batch']                             = $batch;
            $datachild['jml_awal']                          = $this->input->post('jml_awalopname')+$this->input->post('jml_rusakakhir');
            $datachild['jml_akhir']                         = $this->input->post('jml_awalopname');
            $datachild['harga']                             = $this->input->post('hargaterakhir');
            $this->db->insert('inv_inventaris_habispakai_opname_item', $datachild);
            return $data['id_inv_inventaris_habispakai_opname'];
        }else{
            return mysql_error();
        }
    }
    
    public function getitemopname($start=0,$limit=999999,$options=array()){
        $this->db->having('jml_rusakakhir > 0');
        $query = $this->db->get("bhp_retur",$limit,$start);
        return $query->result();
    }
    function getitemopname_retur($start=0,$limit=999999,$options=array())
    {
        $this->db->where("inv_inventaris_habispakai_opname.tipe = 'retur'");
        $this->db->select('(SELECT SUM(jml) FROM  inv_inventaris_habispakai_pembelian_item JOIN inv_inventaris_habispakai_pembelian ON inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian = inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian WHERE inv_inventaris_habispakai_pembelian_item.batch = inv_inventaris_habispakai_opname_item.batch AND inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai) AS total_penerimaan,(SELECT mst_inv_pbf.nama FROM mst_inv_pbf JOIN  inv_inventaris_habispakai_pembelian  ON (mst_inv_pbf.code = inv_inventaris_habispakai_pembelian.mst_inv_pbf_code) LEFT JOIN inv_inventaris_habispakai_pembelian_item ON(inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian= inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian) WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai =inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.batch = inv_inventaris_habispakai_opname_item.batch  LIMIT 1) AS nama,mst_inv_barang_habispakai.uraian,mst_inv_barang_habispakai.merek_tipe,inv_inventaris_habispakai_opname.* ,inv_inventaris_habispakai_opname_item.*');
        $this->db->join('inv_inventaris_habispakai_opname_item','inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname','left');
        $this->db->join('mst_inv_barang_habispakai','mst_inv_barang_habispakai.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai');
        $query =$this->db->get('inv_inventaris_habispakai_opname',$limit,$start);
        return $query->result();
    }

    
    function get_data_row($kode){
        $data = array();
        $this->db->where("inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname",$kode);
        $this->db->select("(SELECT e.tgl_opname FROM inv_inventaris_habispakai_opname e LEFT JOIN inv_inventaris_habispakai_opname_item f ON e.id_inv_inventaris_habispakai_opname = f.id_inv_inventaris_habispakai_opname WHERE f.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai AND f.batch = inv_inventaris_habispakai_opname_item.batch AND e.tipe='retur' ORDER BY e.tgl_opname DESC LIMIT 1) AS tgl_opnameterakhir,(SELECT mst_inv_pbf.nama FROM mst_inv_pbf JOIN  inv_inventaris_habispakai_pembelian  ON (mst_inv_pbf.code = inv_inventaris_habispakai_pembelian.mst_inv_pbf_code) LEFT JOIN inv_inventaris_habispakai_pembelian_item ON(inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian= inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian) WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai =inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.batch = inv_inventaris_habispakai_opname_item.batch  LIMIT 1) AS nama,mst_inv_barang_habispakai.uraian,mst_inv_barang_habispakai.merek_tipe,inv_inventaris_habispakai_opname.* ,inv_inventaris_habispakai_opname_item.*,(SELECT nomor_kwitansi FROM inv_inventaris_habispakai_pembelian LEFT JOIN inv_inventaris_habispakai_pembelian_item ON(inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian= inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian) WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai =inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.batch = inv_inventaris_habispakai_opname_item.batch ORDER BY inv_inventaris_habispakai_pembelian.tgl_pembelian DESC LIMIT 1) AS nomor_kwitansi,(SELECT tgl_kwitansi FROM inv_inventaris_habispakai_pembelian LEFT JOIN inv_inventaris_habispakai_pembelian_item ON(inv_inventaris_habispakai_pembelian_item.id_inv_hasbispakai_pembelian= inv_inventaris_habispakai_pembelian.id_inv_hasbispakai_pembelian)  WHERE inv_inventaris_habispakai_pembelian_item.id_mst_inv_barang_habispakai =inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai AND inv_inventaris_habispakai_pembelian_item.batch = inv_inventaris_habispakai_opname_item.batch ORDER BY inv_inventaris_habispakai_pembelian.tgl_pembelian DESC LIMIT 1) AS tgl_kwitansi");
        $this->db->join('inv_inventaris_habispakai_opname_item','inv_inventaris_habispakai_opname.id_inv_inventaris_habispakai_opname = inv_inventaris_habispakai_opname_item.id_inv_inventaris_habispakai_opname','left');
        $this->db->join('mst_inv_barang_habispakai','mst_inv_barang_habispakai.id_mst_inv_barang_habispakai = inv_inventaris_habispakai_opname_item.id_mst_inv_barang_habispakai');
        $query =$this->db->get('inv_inventaris_habispakai_opname');
        if ($query->num_rows() > 0){
            $data = $query->row_array();
        }

        $query->free_result();    
        return $data;
    }
    function get_data_row_rusak($jenis,$barang,$batch){
        $data = array();
        $this->db->where("id_mst_inv_barang_habispakai_jenis",$jenis);
        $this->db->where("id_mst_inv_barang_habispakai",$barang);
        $this->db->where("batch",$batch);
        $this->db->select("bhp_retur.*");
        $query = $this->db->get('bhp_retur');
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